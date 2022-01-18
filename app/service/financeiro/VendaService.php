<?php

class VendaService
{
    //constantes de configuração
    const DATABASE              = 'vendas_base';
    const ACTIVE_RECORD         = 'Venda';
    const HABILITAR_EMISSAO     = true;//true = produção // false = teste
    
   public function handle($param)
    {
            $method = strtoupper($_SERVER['REQUEST_METHOD']);
            unset($param['class']);
            unset($param['method']);
            //seletor de redirecionamento de função
            switch($method)
        {
            case 'POST':
                return self::vendaWebhook($param);//recebe as vendas proveniente do webhook
                break;
            case 'PUT':
                return self::vendaManual($param);//permite salvar/emitir uma venda a partir do numero da venda.
                break;
            case 'GET':
                return self::obterVendaPdv($param);//permite obter o array de venda do respectivo PDV.
                break;
            default:
                return "metodos indisponiveis";
        }
    }
    
    public function vendaWebhook($param){
        //tipo webhook = array responsável por enviar a venda do webhook completa para a função salvar venda
        $id_woo                         = $param["number"];
        $id_venda                       = $param["id"];
        $promo                          = "PR"; //prefixo de vendas provenientes do pdv promoção
        $promocao                       = str_replace($promo,"",strtoupper($id_woo));
        $origem                         = "Webhook";
        $usuario                        = "PDV PRODUÇÃO";
        $is_promo                       = false;
        if($promocao != $id_woo){//valida se é uma venda promoção
            $is_promo                   = true;
            $usuario                    = "PDV PROMOÇÃO";
        }
        //cariaveis de configuração
        $configuracao['fiscal']         = 0;
        $configuracao['origem']         = $origem;
        $configuracao['usuario']        = $usuario;
        $configuracao['id_venda_pdv']   = $id_venda;
        $configuracao['is_promo']       = $is_promo;
        $retorno;
        try{
            //analisa se o webhook está completo, pois em alguns momentos o WOO entrega o webhook da venda sem as informações do Yith POS. 
            //sendo necessário realizar uma consulta no WOO  pelo ID e buscar essa venda completa.
            if(!isset($param['yith_pos_data']->store_name) || $param['yith_pos_data']->store_name == null ){
                if($is_promo){
                    $woocommerce            = ApiManager::getWooClient(1);
                    $param                  = $woocommerce->get('orders/'.$id_venda);
                }else{
                    $woocommerce            = ApiManager::getWooClient();
                    $param                  = $woocommerce->get('orders/'.$id_venda);
                }
            }
            $retorno = self::salvarVenda($param,$configuracao);
            return $retorno;
        }catch(Exception $e){
            //envia para fila Gsheets
            $fila['numero_venda'] = $id_woo;
            $fila['data']         = $param['date_created'];
            $fila['motivo']       = 'vendaWebhook / '.$e->getMessage();
            $fila['id_venda']     = $id_venda;
            ApiManager::sendMessage($fila['motivo']);
            self::filaVendas($fila);
            return 'numero de venda inv : '.$e->getMessage();
        }
    }
    
    public function vendaManual($param){
        //responsável por buscar a venda no woo e enviar para a funcão salvar no banco
        if(isset($param['id'])&& $param['id']== null) throw new Exception('numero de venda inv :');
        $fiscal                         = isset($param['fiscal'])?$param['fiscal']:0;
        $configuracao                   = array(); //array com as informações obtidas em tempo de execução
        $origem                         = isset($param['origem']) ? str_replace('Store: ','',$param['origem']) : "origem";
        $usuario                        = isset($param['usuario']) ? $param['usuario'] : "usuario";
        $id_venda_pdv                   = isset($param['id_venda_pdv']) ? $param['id_venda_pdv'] : false;
        $invalidos                      = array('#',",","."," ");
        $id_woo                         = strtoupper(str_replace($invalidos,"",$param['id']));
        $promo                          = "PR";
        $promocao                       = str_replace($promo,"",$id_woo);
        $is_promo                       = $promocao != $id_woo ? true : false;
        $configuracao['fiscal']         = $fiscal;
        $configuracao['origem']         = $origem;
        $configuracao['usuario']        = $usuario;
        $configuracao['id_venda_pdv']   = $id_venda_pdv;
        $configuracao['is_promo']       = $is_promo;
        $dados;
        $retorno;
       //ApiManager::sendMessage("$id_woo VENDA vendaManual");
        try{
            if(isset($id_woo)|| $id_woo != null || $id_woo != ''){
               if($is_promo){
                    $woocommerce            = ApiManager::getWooClient(1);
                    if($id_venda_pdv){
                        $dados              = $woocommerce->get("orders/$id_venda_pdv");//objecto
                    }else{
                        $dados              = $woocommerce->get("orders?search=$id_woo");//array
                    }
                }else{
                    $woocommerce            = ApiManager::getWooClient();
                    if($id_venda_pdv){
                        $dados              = $woocommerce->get("orders/$id_venda_pdv");//object
                    }else{
                        $dados              = $woocommerce->get("orders?search=$id_woo");//array
                    }
                } 
                //quando DADOS for um array, significa que foi realizado uma pesquisa com o number da venda em todo banco do WOO, 
                //sendo assim é necessário realizar um ajuste apra que retorne a venda correta.
                if(is_array($dados)){
                  foreach($dados as $dado_){//valida qual do array é a venda correta
                      if($dado_->number == $id_woo){
                          $param = $dado_;
                          break;
                      }
                  }
                }else{
                    $param = $dados;
                }
                if(!isset($param->number)){
                    throw new exception ("numero de venda inv : venda não encontrada"); //não alterar a resposta se não entra em loop
                    
                }
                $retorno = self::salvarVenda($param,$configuracao);
                return $retorno;
            }else{
                return 'numero de venda inv : venda não encontrada';
            }
        }catch(Exception $e){
            //envia para fila Gsheets
            $fila['numero_venda'] = $id_woo;
            $fila['data']         = is_array($param['date_created'])? $param['date_created']:$param->date_created;
            $fila['motivo']       = 'vendaManual / '.$e->getMessage();
            $fila['id_venda']     = is_array($param)?$param['id']:$param->id;
            self::enviarPipedream($param);
            ApiManager::sendMessage($fila['motivo']);
            self::filaVendas($fila);
            return 'numero de venda inv : '.$e->getMessage();
        }
    }
    public function limitarCaractere($string,$add_string,$limite_carac){
        //função responsável por concatenar uma string, respeitando o limite de caracteres
        if((strlen($string) + strlen(' // '.$add_string)) >= $limite_carac){
            return substr($string.' // '.$add_string, 0, ($limite_carac*-1));  
        }else{
            return $string.' // '.$add_string;
        }
        
    }
    
    public function salvarVenda($param,$configuracao){
        try{
            $duplicidade                    = self::antiduplicidade($param,$configuracao);
            $isDuplicidade                  = $duplicidade['isDuplicidade'];
            $isArray                        = is_array($param)?true:false;
            $loja                           = null;
            $link_nfce                      = null;
            $retorno;
            
            //ApiManager::sendMessage("$param->number SALVAR VENDA");
            if(!$isDuplicidade && !isset($duplicidade['venda_encontrada'])){
                $numero_venda               = $isArray ? $param['number'] : $param->number;
                if(isset($duplicidade['number_ajustado'])){
                    //$numero_venda           = "0".$numero_venda;
                
                }
                //variaveis da Venda
                $venda                      = isset($duplicidade['venda_encontrada']) ?$duplicidade['venda_encontrada'] : new Venda();
                $woo_loja                   = $isArray ? $param ['yith_pos_data'] : $param->yith_pos_data;
                $woo_cliente                = $isArray ? $param ['billing'] : $param->billing; 
                //localizar lojas
                TTransaction::open('base_banco');
                $lojas                      = Loja::where('nome_fantasia','=',$woo_loja->store_name)->load();
                TTransaction::close();
                if($lojas){
                    $loja                   = $lojas[0];
                    $venda->loja            = $loja->id;
                }else{
                    throw new Exception('numero de venda inv : loja não encontrada ou definida de forma incorreta.'.$woo_loja->store_name);
                }//fim lojas
                //atributos venda
                $venda->n_venda             = $numero_venda;
                $venda->variavel_duplicidade= $duplicidade['antiduplicidade'];
                $venda->id_interno          = "{$venda->n_venda}"."{$loja->abreviacao}";
                $venda->caixa               = $woo_loja->register_name;
                $venda->func_caixa          = $woo_loja->cashier_name;
                $venda->dt_venda            = $isArray ? $param['date_created'] : $param->date_created;
                $venda->obs                 = $isArray ? $param['customer_note'] : $param->customer_note;
                $venda->status              = $isArray ? $param['status'] : $param->status;
                $venda->fiscal              = "F";
                $venda->id_venda            = $isArray ? $param['id'] : $param->id;
                $taxas                      = $isArray ? $param['fee_lines'] : $param->fee_lines;
                $venda->valor_total         = $isArray ? $param['total'] : $param->total;
                $configuracao['loja']       = $loja;
                $produtos                   = $isArray ? $param['line_items'] : $param->line_items;
                $pagamentos                 = $isArray ? $param['multiple_payment_methods'] : $param->multiple_payment_methods;
                if( $produtos == null){
                    throw new Exception('numero de venda inv : venda recebida sem nenhum produto');
                }
                if($pagamentos == null){
                    throw new Exception('numero de venda inv : venda recebida sem nenhuma forma de pagamento');
                }
                $venda->total_desconto      = 0;
                //CALCULO DO DESCONTO --------------------------------------------------------------------------------
                //o woo envia uma seção chamada fee_lines onde provém de taxas na venda ou desconto, na nossa regra de negocio, não trabalhamos com taxa no PDV, sendo assim acusa um erro nesse caso.
                if(isset($taxas) || $taxas != null){
                    foreach( $taxas as $taxa){
                        $desconto                   = str_replace("Desconto - ","",$taxa->name);
                        $venda->obs                 = self::limitarCaractere($venda->obs,$desconto,400);
                        if($desconto != $taxa->name){
                            $venda->total_desconto  = $venda->total_desconto+ doubleval($taxa->total);
                        }else{
                    ApiManager::sendMessage("VENDA: $venda->id_interno as $venda->dt_venda
                    CAIXA: $venda->func_caixa
                    LOJA: $loja->nome_fantasia 
                    ERRO: foi lancada uma taxa na venda ",3);
                        } 
                    }
                }
                //VENDA CLIENTE --------------------------------------------------------------------------------
                if($woo_cliente && $woo_cliente->email != null ){
                    TTransaction::open('base_banco');
                    $clientes = Pessoa::where('email','=',$woo_cliente->email)->load();
                    TTransaction::close();
                    if($clientes){
                        $cliente                    = $clientes[0];
                        $venda->cliente_id          = $cliente->id;
                        $configuracao['cliente']    = $cliente;
                    }else{
                        $array_cliente              = ['email'=>$woo_cliente->email];
                        $cliente                    = ClienteService::novoClienteManual($array_cliente);
                        $venda->cliente_id          = $cliente->id;
                        $configuracao['cliente']    = $cliente;
                    }
                }
                TTransaction::open(static::DATABASE);
                $venda->store();
                //PRODUTOS -------------------------------------------------------------------------------------
                foreach($produtos as $produto){
                    $item_venda                  = new VendaItem();
                    $item_venda-> name           = $produto->name;
                    $item_venda-> venda_id       = $venda->id;
                    $item_venda-> quantidade     = $produto->quantity;
                    $item_venda-> valor_unitario = $produto->price;
                    $item_venda-> valor_desconto = "";//yith não vem com desconto, ele altera direto no valor unitário do produto
                    $item_venda-> valor_total    = $produto->total;
                    $item_venda-> deposito       = $loja->deposito;  
                    $item_venda-> dt_venda       = $venda->dt_venda;  
                    $item_venda-> loja_id        = $loja->id;
                    $produto_interno;
                    $is_sku                      = str_replace("100000","",strval($produto->sku));
                    $strlen                      = strlen($produto->sku);
                    $produto_interno;
                    
                    TTransaction::open('base_banco');
                    if($is_sku == $produto->sku){// o SKU do produto é um codigo de barras ou SKU
                        if($strlen == 13 ){
                        $produto_internos = Produto::where('cod_barras','=',$produto->sku)->load();
                            if($produto_internos){
                                $produto_interno = $produto_internos[0];
                                $item_venda-> SKU            = $produto_interno->SKU;
                                $item_venda-> produto_id     = $produto_interno->id; 
                            }
                        }else{
                        $produto_internos = Produto::where('SKU','=',$produto->sku)->load();
                            if($produto_internos){
                                $produto_interno = $produto_internos[0];
                                $item_venda-> SKU            = $produto_interno->SKU;
                                $item_venda-> produto_id     = $produto_interno->id;
                            }
                        }
                    }else{
                        $produto_internos = Produto::where('SKU','=',$produto->sku)->load();
                        if($produto_internos){
                            $produto_interno = $produto_internos[0];
                            $item_venda-> SKU            = $produto_interno->SKU;
                            $item_venda-> produto_id     = $produto_interno->id;
                        }else{
                            ApiManager::sendMessage("VENDA: $venda->id_interno as $venda->dt_venda
                            LOJA: $loja->nome_fantasia 
                            PRODUTO: $produto->sku
                            ERRO: Produto não cadastrado.");
                            throw new Exception ("produto não localizado sku: $produto->sku",2);
                        }
                    }
                    //validador de CEST e NCM
                    $toRemoveCest               = [',','.',' '];
                    if($produto_interno->cest == '' || $produto_interno->cest == null){
                        $ncms                   = Ncm::where('n_ncm','=',$produto_interno->ncm)->load();
                        if($ncms){
                            $ncm                    = $ncms[0];
                            $cest                   = new Cest($ncm->cest);
                            $produto_interno->cest  = str_replace($toRemoveCest,'',$cest->n_cest);
                        }else{
                            $categoria              = new CategoriaProduto($produto_interno->categoria_produto_id);
                            $ncms                   = Ncm::where('n_ncm','=',$categoria->ncm_padrao)->load();
                            if($ncms){
                                $ncm                    = $ncms[0];
                                $cest                   = new Cest($ncm->cest);
                                $produto_interno->cest  = str_replace($toRemoveCest,'',$cest->n_cest);
                            }else{
                                throw new Exception("numero de venda inv : Produto: $produto_interno->SKU com o NCM inválido e da categoria também");
                            }
                        }
                    }
                    TTransaction::close();
                    $item_venda-> cest                  = isset($produto_interno->cest)            ?str_replace($toRemoveCest,'',$produto_interno->cest)         :null;
                    $item_venda-> ncm                   = isset($produto_interno->ncm)             ?str_replace($toRemoveCest,'',$produto_interno->ncm)          :null;
                    $item_venda-> percentual            = 18;
                    $item_venda-> unidadeMedida         = 'UN';
                    if($produto_interno->sit_tribut == 500){
                        $item_venda->cfop               = 5405;
                        $item_venda->situacaoTributaria = 500;
                    }else{
                        $item_venda->cfop               = 5102;
                        $item_venda->situacaoTributaria = 102;
                    }
                    $item_venda-> origem                = isset($produto_interno->origem)               ?$produto_interno->origem               :null;
                    $item_venda-> categoria_produto     = isset($produto_interno->categoria_produto_id) ?$produto_interno->categoria_produto_id :null;
                    $item_venda-> fornecedor            = isset($produto_interno->fornecedor_id)        ?$produto_interno->fornecedor_id        :null;
                    $item_venda-> referencia            = isset($produto_interno->referencia)           ?$produto_interno->referencia           :null;
                    $item_venda-> marca                 = isset($produto_interno->marca)                ?$produto_interno->marca                :null;
                    $item_venda-> store();
                    $array_prod[]                       = $item_venda;
                    self::AjustarEstoque($item_venda);
                    $venda->total_produtos              = $venda->total_produtos + $item_venda->valor_total;
                }
                $configuracao['array_prod']             = $array_prod;
            //PAGAMENTO --------------------------------------------------------------------------------
                $total_pagamentos                       = 0;
                $indice                                 = 0;
                $array_pgto                             = array();//array onde salva os id's das parcelas de pagamento geradas nessa venda;
                //Pré calcular um foreach para obter qtd de parcelas e valor total se bate com o total da Venda, e então ajustar os centavos que vem errado do PDV.
                foreach($pagamentos as $pagamento){
                    $parcela                            = null;
                    if(floatval($pagamento->amount)>10000){//pagamento excecivo
                            throw new Exception('numero de venda inv : com pagamento muito alto');
                        }
                    
                    if(floatval($pagamento->amount)>0 && floatval($pagamento->amount) < 10000){ //fluxo normal
                        //uma parcela comun a ser tratada
                        $parcela                        = new VendaPagamento();
                        $parcela->valor_pgto            = $pagamento->amount;
                        $total_pagamentos               += $pagamento->amount;
                        switch($pagamento->paymentMethod){
                            case  'yith_pos_cash_gateway':
                                $parcela->metodo_pgto   = "Dinheiro";
                                $venda->forma_pagamento = "Dinheiro";
                                break;
                            case 'bacs':
                                $parcela->metodo_pgto   = "Cartão Débito";
                                $venda->forma_pagamento = "Cartão Débito";
                                break;
                            case 'cod':
                                $parcela->metodo_pgto   = "Cartão Credito à Vista";
                                $venda->forma_pagamento = "Cartão Credito à Vista";
                                break;
                            case 'cheque';
                                $parcela->metodo_pgto   = "Cartão Credito parcelado";
                                $venda->forma_pagamento = "Cartão Credito parcelado";
                                break;
                            case 'yith_pos_chip_pin_gateway';
                                $parcela->metodo_pgto   = "pix";
                                $venda->forma_pagamento = "pix";
                                break;
                            case 'carteiraDigital';
                                $parcela->metodo_pgto   = "carteiraDigital";
                                $venda->forma_pagamento = "carteiraDigital";
                                break;
                            case 'cashback';
                                $parcela->metodo_pgto   = "cashback";
                                $venda->forma_pagamento = "cashback";
                                break;
                            default :
                                ApiManager::sendMessage("VENDA: $venda->id_interno as $venda->dt_venda
                                CAIXA: $venda->func_caixa
                                LOJA: $loja->nome_fantasia 
                                ERRO: não informou uma forma de pagamento válida ",3);
                        }   
                        $venda->forma_pagamento         = isset($pagamentos[1])?'Pagamento misto':$venda->forma_pagamento;
                        $parcela->venda_id              = $venda->id;
                        $parcela->dt_venda              = $venda->dt_venda;
                        $parcela->id_loja               = $loja->id;
                        $parcela->store();
                        $array_pgto[$indice]            = $parcela;
                        $indice++;
                        
                    }else{
                        if(floatval($pagamento->amount) < 0 && $indice != 0){//fluxo para troco
                            //calcula se é um troco ou valor inválido de parcela
                            $parcela_anterior               = $array_pgto[$indice-1];
                            $parcela_anterior->valor_pgto   = $parcela_anterior->valor_pgto + $pagamento->amount; 
                            $total_pagamentos               += $pagamento->amount;
                            $parcela_anterior->store();
                            continue;
                        }else{
                            throw new Exception('numero de venda inv : pagamento com unico valor negativo');
                        }
                    }
                }
                //algum momento o PDV envia uma venda com 1 centavo a mais, e evita de emitir a nfce, então este pedaço de trecho ajusta esse bug do pdv
                $total_produtos_cd                      = floatval($venda->total_desconto != 0) ?floatval($venda->total_produtos) + floatval($venda->total_desconto) : floatval($venda->total_produtos);
                $is1cent                                = $total_pagamentos != $total_produtos_cd ? true : false;
                if($is1cent){
                    $diferenca                          = $total_produtos_cd - $total_pagamentos;
                    $ultimaParcela                      = $array_pgto[$indice-1];
                    $ultimaParcela->valor_pgto          += $diferenca;
                    $total_pagamentos                   += $diferenca;
                    $venda->valor_total                 = $total_pagamentos;
                    $ultimaParcela->store();
                }
                $configuracao['array_pgto']             = $array_pgto;
                
                $venda->total_pagamentos                = $total_pagamentos;
                $venda->store();
                $retorno = self::isFiscal($venda,$configuracao);
                TTransaction::close();
                return $retorno;
            }else{
                TTransaction::open(static::DATABASE);
                if(isset($duplicidade['venda_encontrada'])){
                    $retorno = self::isFiscal($duplicidade['venda_encontrada'],$configuracao);
                }else{
                    $number  = $isArray ? $param['number'] : $param->number;
                    $retorno = "venda : $number é uma duplicidade \n Antiduplicidade : ".$duplicidade['antiduplicidade'];
                }
                TTransaction::close();
                if($retorno == 'duplicidade'){ApiManager::sendMessage("venda : ".$param['number']." é uma duplicidade");}
                return $retorno;
            }
        }catch(Exception $e){
            //envia para fila Gsheets
            $duplicate_entry          = str_replace('Duplicate entry','',$e->getMessage());
            if($duplicate_entry == $e->getMessage()){
                $fila['numero_venda'] = is_array($param)?$param['number']:$param->number;
                $fila['data']         = is_array($param)?$param['date_created']:$param->date_created;
                $fila['motivo']       = 'SalvarVenda / '.$e->getMessage();
                $fila['id_venda']     = is_array($param)?$param['id']:$param->id;
                ApiManager::sendMessage("VENDA : ".$fila['numero_venda']."\n motivo : ".$fila['motivo']);
                self::filaVendas($fila);
                return 'numero de venda inv :.. '.$e->getMessage();
            }
        }
    }
    public function antiduplicidade($venda){
        //responsável por verificar se a venda que está entrando é uma duplicidade,
        try{
                $isArray                        = is_array($venda) ? true : false;
                $isArray                        = is_array($venda) ? true : false;
                $numero_venda                   = $isArray ? $venda['number'] : $venda->number;
                $variavel_antiduplicidade       = "";
                $pagamentos                     = $isArray ? $venda['multiple_payment_methods'] : $venda->multiple_payment_methods;
                $pagamento                      = 0;
                $lojas                          = $isArray ? $venda['yith_pos_data'] : $venda->yith_pos_data;
                $loja                           = strlen($lojas->store_name).strlen($lojas->register_name).strlen($lojas->cashier_name);
                $produtos                       = $isArray ? $venda['line_items'] : $venda->line_items;
                $produto                        = 0;
                $data                           = $isArray ? date('YmdHi',strtotime($venda['date_created'])) : date('YmdHi',strtotime($venda->date_created));
                $valor                          = $isArray ? $venda['total'] : $venda->total;
                //inicio da construção
                foreach($produtos as $produto_){
                    $produto                    += strlen($produto_->name);
                }
                foreach($pagamentos as $pagamento_){
                    $pagamento                  += strlen($pagamento_->paymentMethod);
                }
                
                $variavel_antiduplicidade       = $data.$pagamento.$produto.$loja.$valor;
                $retorno                        = array();
                $retorno['antiduplicidade']     = $variavel_antiduplicidade;
                TTransaction::open(static::DATABASE);
                $vendas                         = Venda::where("variavel_duplicidade","=",$variavel_antiduplicidade)->load();
                TTransaction::close();
                $isDuplicidade                  = false;
                if($vendas){
                    //a primeiro momento é considerado como duplicidade.
                    $isDuplicidade              = true; 
                    foreach($vendas as $venda_){
                        if($venda_->n_venda === $numero_venda){
                            //se houver uma numeração correspondente, se trata de uma reemissão
                            $isDuplicidade      = false;
                            $retorno['venda_encontrada'] = $venda_;
                        }elseif($venda_->n_venda === "0".$numero_venda){
                            //se houver uma numeração correspondente, se trata de uma reemissão
                            $isDuplicidade      = false;
                            $retorno['venda_encontrada'] = $venda_;
                        }
                    }
                }else{
                    TTransaction::open(static::DATABASE);
                    $vendas                         = Venda::where("n_venda","=",$numero_venda)->load();
                    TTransaction::close();
                    foreach($vendas as $venda_){
                        if($venda_->antiDuplicidade === $variavel_antiduplicidade){
                            $isDuplicidade          = true; 
                            $retorno['venda_encontrada']    = $venda_;
                            unset($retorno['number_ajustado']);   
                        }else{
                            $retorno['number_ajustado']     = true;
                        }
                    }
                }
                $retorno['isDuplicidade']       = $isDuplicidade;
                return $retorno;
        }catch(Exception $e){
            $fila['numero_venda'] = is_array($venda) ? $venda['number'] : $venda->number;
            $fila['data']         = is_array($venda) ? $venda['date_created'] : $venda->date_created;
            $fila['motivo']       = 'antiDuplicidade / '.$e->getMessage();
            $fila['id_venda']     = is_array($venda) ? $venda['id'] : $venda->id;
            ApiManager::sendMessage($fila['motivo']);
            self::filaVendas($fila);
            return 'numero de venda inv : ';
        }
    }
    
  // ------------------------------------------------------Gerenciador de fiscal -----------------------------------------------------------------------------------------  
    
public function isFiscal($venda, $configuracao){ 
    try{
            //o metodo que chamou precisa ter transações abertas
            //valida se está no mês vigente
            //calculo da vigencia da venda
            
            $fiscal                 = $configuracao['fiscal'];
            $origem                 = $configuracao['origem']; 
            $usuario                = $configuracao['usuario'];
            $configuracao['classe'] = 'isFiscal';
            $loja;
            if(isset($configuracao['loja'])){
                $loja               = $configuracao['loja']; 
            }else{
                TTransaction::open('base_banco');
                $loja               = new Loja($venda->loja);
                $configuracao['loja'] = $loja;
                TTransaction::close();
            }
            if($loja->tipo_emissao==2){//variavel de emissao da loja
                $fiscal = 1;
            }
            $retorno    = null;
            $vigencia   = false;
            $mes_venda  = date( 'm', strtotime( $venda->dt_venda ) );
            $ano_venda  = date( 'y', strtotime( $venda->dt_venda ) );
            $ano_venda  = '20'.$ano_venda;//Famoso bug do milénio
            $hoje       = getdate();
            $mes_atual  = '0'.$hoje['mon'];
            $ano_atual  = $hoje['year'];
            if($venda->cliente_id != null){
                TTransaction::open('base_banco');
                $cliente                 = new Pessoa($venda->cliente_id);
                $configuracao['cliente'] = $cliente;
                TTransaction::close();
            }
            if($mes_venda == $mes_atual && $ano_venda == $ano_atual){ 
                $vigencia= true;
            }else{
                $vigencia =false;
            }
            if(self::HABILITAR_EMISSAO){
                switch($fiscal){
                    case 0: //ciclo normal de emissão, se nao for dinheiro, e nao ter uma nfce, e for no mês vigente, emitir.
                          if($vigencia){
                            if($venda->forma_pagamento !="Dinheiro"){ 
                                    $nfces = Nfce::where('numVenda','=',$venda->id_interno)->load();
                                    if(!$nfces){
                                        $retorno                = self::emitirNfce($venda,$configuracao);
                                    }else{
                                        foreach($nfces as $nfce){//vendas antigas acabaram gerando várias requisições, ja corrigido, mas esse foreach evita isso
                                            if($nfce->link_cupom != null){
                                                $venda->status  = $nfce->status;
                                                $venda->fiscal  = 'T';
                                                $retorno        = $nfce->link_cupom;
                                                $venda->store();
                                                break;
                                            }else{
                                                $configuracao['nfce']   = $nfce;
                                                $retorno                = self::emitirNfce($venda,$configuracao);
                                            }
                                        }
                                    }
                                }
                            }
                        break;
                        
                    case 1:// emitir caso não tenha dentro da vigência, idependente da forma de pagamento.
                        if($vigencia){
                            $nfces = Nfce::where('numVenda','=',$venda->id_interno)->load();
                            if($nfces){
                                $nfce                   = $nfces[0];
                                $configuracao['nfce']   = $nfce;
                                if($nfce->link_cupom == null ){
                                   $configuracao['nfce']= $nfce;
                                   $retorno             = self::emitirNfce($venda,$configuracao);
                                }else{
                                    foreach($nfces as $nfce){//vendas antigas acabaram gerando várias requisições, ja corrigido, mas esse foreach corrige isso
                                        if($nfce->link_cupom != null){
                                            $venda->status  = $nfce->status;
                                            $venda->fiscal  = 'T';
                                            $retorno        = $nfce->link_cupom;
                                            $venda->store();
                                            break;
                                        }else{
                                                $configuracao['nfce']   = $nfce;
                                                $retorno                = self::emitirNfce($venda,$configuracao);
                                            }
                                    if($retorno == ""){
                                        $retorno = self::emitirNfce($venda,$configuracao);
                                    }
                                    }
                                }
                            }else{
                               $retorno                = self::emitirNfce($venda,$configuracao);
                            }
                        }
                        break;
                    case 2: //não emitir, idepentende da forma de pagamento. apenas salva a venda. para envio de vendas entigas ao sistema
                        $retorno = null;
                        break;
                    default :
                        $fila['numero_venda']    = $venda->n_venda;
                        $fila['data']            = $venda->dt_venda;
                        $fila['motivo']          = 'isFiscal em cases / '.$e->getMessage();
                        $fila['id_venda']        = $venda->id_venda;
                        $venda->status           = "Negada";
                        $venda->obs              = self::limitarCaractere($venda->obs,' -> isFiscal / '.$e->getMessage(),400);
                        $venda->store();
                        self::filaVendas($fila);
                        $retorno =  $e->getMessage();
                        break;
                    }
                } 
           return $retorno;
    }catch(Exception $e){
            //envia para fila Gsheets
            $fila['numero_venda']    = $venda->n_venda;
            $fila['data']            = $venda->dt_venda;
            $fila['motivo']          = 'isFiscal / '.$e->getMessage();
            $fila['id_venda']        = $venda->id_venda;
            $venda->status           = "Negada";
            $venda->obs              = self::limitarCaractere($venda->obs,' -> isFiscal / '.$e->getMessage(),400);
            $venda->store();
            self::filaVendas($fila);
            return 'numero de venda inv : '.$e->getMessage();
            }
    }     
    
    
//INICIO DA EMISSÃO AUTOMATICA NFCE----------------------------------------------------------------------------------------------INICIO DA EMISSÃO AUTOMATICA NFCE
    public function emitirNfce($venda, $configuracao){
        
    $link_nfce              = null;
    //obtendo variaveis de emissão
    //staticas
    $nfce_config            = ApiManager::getNfceConfig();
    $ambienteEmissao        = $nfce_config['ambiente_emissao'];
    $presencaConsumidor     = $nfce_config['presenca_consumidor'];
    $informacoesAdicionais  = $nfce_config['informacoes_adicionais'];
    $configuracao['classe'] = 'emitirNfce';
    //variaveis
    $loja;
    $origem;
    $usuario;
    $pagamentos;
    $itensVenda;
    $result;//para definir result como global
    $itemArray              = array();
    $formasPgto             = array();
    $nfce;
    $cliente                = false;
    //valida se ja recebe as informações pelo array de configurações
    //nfce
    
    if(isset($configuracao['nfce'])){
        $nfce = $configuracao['nfce'];
    }else{
        $nfces = Nfce::where('numVenda','=',$venda->id_interno)->load();
        if($nfces){
            $nfce           = $nfces[0];
            $configuracao['nfce'] = $nfce;
        }else{
            $nfce           = new Nfce();
        }
    }
    if(isset($configuracao['cliente'])){
        $cliente            = $configuracao['cliente'];
    }
    $nfce->dt_nfce          = $venda->dt_venda;
    //loja
    if(isset($configuracao['loja'])){
        $loja               = $configuracao['loja'];
    }else{
        TTransaction::open('base_banco');
        $loja               = new Loja($venda->loja);
        $configuracao['loja']= $loja;
        TTransaction::close();
    }
    //origem/usuario
    $origem                 = $configuracao['origem'];
    $usuario                = $configuracao['usuario'];
    //array de pagamento/itens
    if(isset($configuracao['array_pgto']) && isset($configuracao['array_prod'])){
        $pagamentos             = $configuracao['array_pgto'];
        $itensVenda             = $configuracao['array_prod'];
    }else{
        $pagamentos             = VendaPagamento::where ('venda_id','=',$venda->id)->load();
        $itensVenda             = VendaItem::where      ('venda_id','=',$venda->id)->load();
        $configuracao['array_pgto'] = $pagamentos;
        $configuracao['array_prod'] = $itensVenda;
    }
  try{
    if($nfce->status != "Autorizada" ){
       $nfce->ambienteEmissao               = $ambienteEmissao;
       $nfce->informacoesAdicionais         = $informacoesAdicionais;
       $nfce->presencaConsumidor            = $presencaConsumidor;
       $nfce->numVenda                      = $nfce->ambienteEmissao=='Homologacao'?$venda->id_interno.'H':$venda->id_interno;
       $nfce->venda_id                      = $venda->id;
       $nfce->dt_venda                      = $venda->dt_venda;
       $nfce->id_loja                       = $loja->id;
       $nfce->store();
       
//INICIO  PAGAMENTOS NFCE--------------------------------------------------------------------
        if($pagamentos){
        foreach($pagamentos as $pagamento){
            $pagamentoArray                                     = array();
            $credenciadoraCartao                                = array();
            //adiciona no array pagamentos
            $Fpagamento = $pagamento->metodo_pgto;
            switch($Fpagamento){
                case 'Cartão Débito':
                    $credenciadoraCartao['tipoIntegracaoPagamento'] = 'NaoIntegradoAoSistemaDeGestao';
                    $pagamento->metodo_pgto                     = "CartaoDeDebito";
                    $pagamentoArray['tipo']                     = $pagamento->metodo_pgto;
                    $pagamentoArray['valor']                    = doubleval($pagamento->valor_pgto); 
                    $pagamentoArray['credenciadoraCartao']      = $credenciadoraCartao;
                    break;
                case 'Cartão Credito à Vista':
                case 'Cartão Credito parcelado':
                    $credenciadoraCartao['tipoIntegracaoPagamento'] = 'NaoIntegradoAoSistemaDeGestao';
                    $pagamento->metodo_pgto                     = "CartaoDeCredito";
                    $pagamentoArray['tipo']                     = $pagamento->metodo_pgto;
                    $pagamentoArray['valor']                    = doubleval($pagamento->valor_pgto); 
                    $pagamentoArray['credenciadoraCartao']      = $credenciadoraCartao;
                    break;
                case 'Dinheiro':
                    $pagamento->metodo_pgto                     = "Dinheiro";
                    $pagamentoArray['tipo']                     = $pagamento->metodo_pgto;
                    $pagamentoArray['valor']                    = doubleval($pagamento->valor_pgto);
                    break;
                case 'pix':
                    $pagamento->metodo_pgto                     = "PagamentoInstantaneoPix";
                    $pagamentoArray['tipo']                     = $pagamento->metodo_pgto;
                    $pagamentoArray['valor']                    = doubleval($pagamento->valor_pgto);
                    break;
                case 'carteiraDigital':
                    $pagamento->metodo_pgto                     = "TransferenciaBancariaCarteiraDigital";
                    $pagamentoArray['tipo']                     = $pagamento->metodo_pgto;
                    $pagamentoArray['valor']                    = doubleval($pagamento->valor_pgto);
                    break;
                case 'cashback':
                    $pagamento->metodo_pgto                     = "ProgramaFidelidadeCashbackCarteiraVirtual";
                    $pagamentoArray['tipo']                     = $pagamento->metodo_pgto;
                    $pagamentoArray['valor']                    = doubleval($pagamento->valor_pgto);
                    break;
                default:
                    throw new Exception('numero de venda inv : venda com um pagamento de forma não definida');
                    break;
            }
            $formasPgto[]                                       = $pagamentoArray;
        }
        }else{
            throw new exception ("numero de venda inv : pagamento negativo"); //throw new Exception('venda sem pagamento realizado');
        }
//INICIO TRATAMENTO DOS ITENS DA Nfce --------------------------------------------------------------------
        if($itensVenda){
            //algoritmo para calculo do desconto. 
            $total_itens    = 0;
            $indice         = 0;
            $tota_desconto  = 0.00;
            $qtd_item       = count($itensVenda);
            $valor_desconto = $venda->total_desconto*-1;
            $dec_desconto   = $valor_desconto;
            
            if($valor_desconto != 0){
                foreach($itensVenda as $itemVenda){
                   $total_itens                     += $itemVenda->valor_unitario*$itemVenda->quantidade;
                }
            }
            foreach($itensVenda as $vendaItem){
                $itens                       = array();
                $toRemove                    = array(".","/","-"," ");
                $itens['cfop']               = strval($vendaItem->cfop);
                $itens['codigo']             = strval($vendaItem->produto_id);
                $itens['descricao']          = $vendaItem->name;
                $itens['ncm']                = str_replace($toRemove,"",$vendaItem->ncm);
                if($loja->id == 21){
                    TTransaction::open('base_banco');
                    $ncms = Ncm::where('n_ncm','=',$vendaItem->ncm)->load();
                    if($ncms){
                        $ncm                 = $ncms[0];
                        $cest                = new Cest($ncm->cest);
                        $itens['cest']       = str_replace($toRemove,"",$cest->n_cest);
                    }else{
                        throw new Exception ("numero de venda inv : Produto sem NCM cadastrado, SKU: $vendaItem->SKU");
                    }
                    TTransaction::close();
                }
                $itens['quantidade']         = intval($vendaItem->quantidade);
                $itens['unidadeMedida']      = $vendaItem->unidadeMedida;
                $itens['valorUnitario']      = doubleval($vendaItem->valor_unitario);
                if($venda->total_desconto != 0){
                    if(isset($itensVenda[1])){
                        $indice++;
                        //calcula a proporção deste item em relação a venda
                        $porcentagem                = $vendaItem->valor_unitario*$vendaItem->quantidade/$total_itens;
                        //obtem o desconto proporcional da venda
                        $descontos                  = round(($porcentagem*$valor_desconto),2,PHP_ROUND_HALF_UP);
                        //valida se é o ultimo item e adiciona o desconto + sobra no ultimo item
                        if($indice == $qtd_item){
                            $dec_desconto = round(($dec_desconto),2,PHP_ROUND_HALF_UP);
                            $itens['descontos']     = $dec_desconto;
                        }else{
                            $itens['descontos']     = $descontos;
                            $dec_desconto           = $dec_desconto - $descontos;
                        }
                        //desconto sem validador de ultimo e com sobra no ultimo item.
                        /*$menssagem = $menssagem."\n desconto: $descontos";
                        $itens['descontos']  = round( $descontos, 2, PHP_ROUND_HALF_UP);*/
                    }else{
                        $desconto = round((($vendaItem->valor_unitario * $vendaItem->quantidade) - $venda->valor_total),2);
                        $itens['descontos']         = $desconto;
                    }
                }
                $impostos = array();
                $percentualAproximadoTributos = array();
                $simplificado                 = array();
                $icms                         = array();
                $simplificado['percentual']   = intval($vendaItem->percentual);
                $icms['situacaoTributaria']   = strval($vendaItem->situacaoTributaria);
                $icms['origem']               = intval($vendaItem->origem);
                $percentualAproximadoTributos['simplificado'] = $simplificado;
                
                //nesta aprte é preciso implementar a aprte o IBPT
                $percentualAproximadoTributos['fonte']        = 'IBPT';
                $impostos ['percentualAproximadoTributos']    = $percentualAproximadoTributos;
                $impostos ['icms']                            = $icms;
                $itens['impostos']                            = $impostos;
                $itemArray[]                                  = $itens; 
            }
        }else{
            throw new Exception('numero de venda inv : venda sem produtos para emissão da NFCE');
        }
        //INICIO DO ENVIO DA NOTA PARA O E-NOTAS-------------------------------------------------------------------------------PRIMEIRO CICLO DE ENVIO DE NF PARA O ENOTAS
        $NFCE;
        //AJUSTE DE CLIENTE
        if($cliente || $cliente != null){
        $cliente_array                  = array();
        $cliente_array['tipoPessoa']    = strlen($cliente->documento)==14 ? "J" : "F";
        $cliente_array['nome']          = $cliente->nome;
        $cliente_array['telefone']      = $cliente->fone;
        $cliente_array['cpfCnpj']       = $cliente->documento;
        $endereco                       = array();
        TTransaction::open('base_banco');
        $cidade                         = new Cidade($cliente->cidade_id);
        $estado                         = new Estado($cliente->estado_id);
        TTransaction::close();
        $endereco['uf']                 = $estado->uf;
        $endereco['cidade']             = $cidade->nome;
        $endereco['logradouro']         = $cliente->endereco;
        $endereco['cep']                = $cliente->cep;
        //$cliente_array['endereco']      = $endereco;
        $NFCE = array(
          'id' => $nfce->numVenda,
          'ambienteEmissao' => $nfce->ambienteEmissao,//'Producao' ou 'Homologacao'
          'naturezaOperacao'=> 'Venda',
          'enviarPorEmail'=> true,
          'cliente'=> $cliente_array,
          'pedido' => array(
    	  'presencaConsumidor' => $nfce->presencaConsumidor,
    	  'pagamento' => array(
    		'tipo' => 'PagamentoAVista',
    		'formas' => $formasPgto
                )
            ),
         'itens' => $itemArray,
    	 'informacoesAdicionais' => $nfce ->informacoesAdicionais."numero da venda: ".$venda->id_interno
            );
        }else{
            $NFCE = array(
          'id' => $nfce->numVenda,
          'ambienteEmissao' => $nfce->ambienteEmissao,//'Producao' ou 'Homologacao'
          'naturezaOperacao'=> 'Venda',
          'enviarPorEmail'=> false,
          'pedido' => array(
    	  'presencaConsumidor' => $nfce->presencaConsumidor,
    	  'pagamento' => array(
    		'tipo' => 'PagamentoAVista',
    		'formas' => $formasPgto
                )
            ),
         'itens' => $itemArray,
    	 'informacoesAdicionais' => $nfce ->informacoesAdicionais."numero da venda: ".$venda->id_interno
            );
        }
        $result = ApiManager::sendNfce($loja->idEmpresa,$NFCE);
        $status = $result->status;
        
        
        
        
 //INICIO DO SEGUNDO CICLO DE REEMISSÃO  ------------------------------------------------------INICIO DO SEGUNDO CICLO DE REEMISSÃO  ** AJUSTE NOS ERROS**
    
    if($status != "Autorizada"){
        self::enviarPipedream($NFCE);
        $erros_ncm      = array('NCM','ncm');
        $erro_ncm       = str_replace($erros_ncm,"",$result->motivoStatus);
        $erros_cest     = array('cest','CEST');
        $erro_cest      = str_replace($erros_cest,"",$result->motivoStatus);
        $pillar         = 0;
        //erros relacionados a NCM
        if($erro_ncm != $result->motivoStatus ){
            $message = "";
            $message = ("VENDA: $venda->id_interno as $venda->dt_venda \nERRO: $result->motivoStatus \nPRODUTO(OS):\n");
            foreach($itemArray as $itens){
                TTransaction::open('base_banco');
                    $produto            = new Produto($itens['codigo']);
                    $message            = $message."\n|{$produto->SKU} - {$produto->descricao} NCM: {$produto->ncm}";
                    $produto->obs       = "PRODUTO COM DIVERGÊNCIA NO NCM";
                    $prod_categoria     = new CategoriaProduto($produto->categoria_produto_id);
                    $itens['ncm']       = $prod_categoria->ncm_padrao;
                    $itemArray2[]       = $itens;
                    $pillar             = 1;
                TTransaction::close();
            }
            $NFCE['itens']              = $itemArray2;
            $result                     = ApiManager::sendNfce($loja->idEmpresa,$NFCE);
            ApiManager::sendMessage($message,2);
        }
        //erros relacionados a NCM
        if($erro_cest != $result->motivoStatus){
            ApiManager::sendMessage("venda $venda->n_venda com produtos com CEST errado\n $result->motivoStatus",2);
        }
        //erros relacionados  a certificado digital
        if($result->status == 'Código: _Cert002'){
            $erro               = str_replace($certificado,'',$result->motivoStatus);
            if(strlen($erro) == strlen($result->motivoStatus)){
                $fila['numero_venda']    = $venda->n_venda;
                $fila['data']            = $venda->dt_venda;
                $fila['motivo']          = 'emitirNfce CERTI/ '.$result->motivoStatus;
                $fila['id_venda']        = $venda->id_venda;
                self::filaVendas($fila);
            }else{
                return 'numero de venda inv :. '.$result->motivoStatus;
            }
        }
        
        //envia o erro para o pipedream para debug
       
       
        $status = $result->status;
        
    }
    //se o resultado for Autorizada
    if($result == "Autorizada"){
        //trata do resultado
        if($ambienteEmissao == "Homologacao"){
            $loja->seq_nf_homologacao = (intval($result->numero)+1);
        }else{
            $loja->seq_nf_producao    = (intval($result->numero)+1);
        }
        TTransaction::open('base_banco');
        $loja->store();
        TTransaction::close();
    }
        $venda->status          = isset($result->status) ? $result->status : null;
        $venda->fiscal          = $venda->status == 'Autorizada' ? 'T' :  'F';
        $venda->obs             = self::limitarCaractere($venda->obs,$result->motivoStatus,400);
        $nfce->numVenda         = $venda->id_interno;
        $nfce->link_cupom       = isset($result->linkDanfe) ? $result->linkDanfe : null;
        $nfce->ambienteEmissao  = isset($result->ambienteEmissao) ? $result->ambienteEmissao : $ambienteEmissao;
        $nfce->status           = $result->status;
        $nfce->n_nfce           = $result->numero;
        $nfce->store();
        $venda->store();
        $link_nfce              = $nfce->link_cupom; 
    }else{
        $venda->fiscal = 'T';
        $venda->status = 'Autorizada';
        $venda->store();
        $link_nfce     = $nfce->link_cupom;
    }
    return $link_nfce;
    }catch(Exception $e){
        
        switch($e->getMessage()){
            case 'NFe0003 - Uma NFe com o status "Autorizada" não pode ser atualizada':
                TTransaction::open('base_banco');
                $loja = new Loja($venda->loja);
                TTransaction::close();
                $headers = array(
                    'Accept: application/json',
                    'Authorization: Basic  NWZiZGM0YWUtMDdmYi00NTEzLThjYjMtNTMwMjAxYmYwNjAw'
                    );
                    
                $url = ApiManager::ambienteServidor() ? "https://api.enotasgw.com.br/v2/empresas/{$loja->idEmpresa}/nfc-e/{$venda->id_interno}" : "https://api.enotasgw.com.br/v2/empresas/{$loja->idEmpresa}/nfc-e/{$venda->id_interno}H";
                $ch = curl_init($url);
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
                $result = json_decode(curl_exec($ch));
                curl_close($ch);
                
                
                //cria uma nova NFCE request    
                $nfce->ambienteEmissao               = $ambienteEmissao;
                $nfce->informacoesAdicionais         = $informacoesAdicionais;
                $nfce->presencaConsumidor            = $presencaConsumidor;
                $nfce->numVenda                      = $venda->id_interno;
                $nfce->id_loja                       = $venda->loja;
                $nfce->status                        = $result->status;
                $nfce->n_nfce                        = $result->numero;
                $nfce->link_cupom                    = $result->linkDanfe;
                $nfce->venda_id                      = $venda->id;
                $nfce->dt_nfce                       = $venda->dt_venda;
                $nfce->store();
                $venda->status                       = $result->status;
                //ApiManager::sendMessage("venda ja com NFCE: $nfce->numVenda");
                if($result->status == "Autorizada"){
                    $venda->fiscal          = 'T';
                    $venda->status          ='Autorizada';
                }else{
                    $venda->fiscal          = 'F';
                    $venda->status          = 'Negada';
                }
                $venda->store();
                return $nfce->link_cupom;
                break;
            default:
                self::enviarPipedream($NFCE);
                //envia para fila Gsheets
                $fila['numero_venda']    = $venda->n_venda;
                $fila['data']            = $venda->dt_venda;
                $fila['motivo']          = 'emitirNfce já emit/ '.$e->getMessage();
                $fila['id_venda']        = $venda->id_venda;
                self::filaVendas($fila);
                $venda->fiscal           = 'F';
                $venda->status           = 'Negada';
                $venda->obs              = self::limitarCaractere($venda->obs,$e->getMessage(),400);
                
                if($e->getMessage() == 'NFe0003 - Uma NFe com o status "SolicitandoAutorizacao" não pode ser atualizada'){
                    $venda->status       = 'SolicitandoAutorizacao';
                    $venda->store();
                    return 'numero de venda inv : NFCE em processo de autorização';
                }else{
                    $venda->store();
                    return 'numero de venda inv'.$e->getMessage();
                }
                
            }
            
        }
    }
    public function enviarPipedream($param){
        //ENVIO AO PIPEDREAM  PARA DEBUG
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://d4d27f0963b9a5ec5114863f71d581ec.m.pipedream.net',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($param),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }
        
// ---------------------- FUNÇÃO PARA AJUSTE DO ESTOQUE --------------------------------------------------      
    public function AjustarEstoque($vendaItem){
        TTransaction::open('base_banco');
        $prod_estoque = ProdEstoque::where('id_deposito','=',$vendaItem->deposito)
                                   ->where('id_produto','=',$vendaItem->produto_id)
                                   ->load();
     if($prod_estoque){
         $estoque = $prod_estoque[0];
         $estoque->quantidade = $estoque->quantidade - $vendaItem->quantidade;
         $estoque->store();
         //descomentar quando estoque estiver redondo
         /*if($estoque->quantidade<= 0){
             ApiManager::sendMessage("PRODUTO: $vendaItem->sku, com estoque baixo",2);
             }
         */
     }else{
         $estoque = new ProdEstoque();
         $estoque->quantidade       = ($vendaItem->quantidade*-1);
         $estoque->qtd_min          = "";
         $estoque->qtd_max          = "";
         $estoque->id_deposito      = $vendaItem->deposito;
         $estoque->id_produto       = $vendaItem->produto_id;
         $estoque->qtd_vendido      = "";
         $estoque->qtd_atual        = "";
         $estoque->produto_sku      = $vendaItem->SKU;
         $estoque->produto_nome     = $vendaItem->name;
         $estoque->curva            = null;
         $estoque->store();
          }
        TTransaction::close();
    }

// -------------------------------------------------- ALTERNATIVA DA FILA DO GSHEETS --------------------------------------------------  
public function filaVendas($fila){
        //configurando variaveis
        $numeros            = array('0','1','2','3','4','5','6','7','8','9','PR','H');
        //vemover tambéma s abreviações de loja.
        $numero_venda       = isset($fila['numero_venda'])  ?strval($fila['numero_venda'])      :'';
        $data               = isset($fila['data'])          ?$fila['data']                      :'';
        $motivo             = isset($fila['motivo'])        ?$fila['motivo']                    :'';
        $id_venda           = isset($fila['id_venda'])      ?strval($fila['id_venda'])          :'';//id do pdv
        $motivoContingencia;
        $tipo               = 'venda';
        $erros              = array('venda.id_interno','system_sql_log.PRIMARY', 'teste');
        $erros_             = str_replace($erros,'',$motivo);
        $vendaErro;
        //$venda->store();
        if(strlen($erros_) == strlen($motivo)){
            ApiManager::sendMessage("venda: $numero_venda as $data\n-Erro: $motivo");
        }
        $motivo                 = str_replace($numeros,'',$motivo);
        $abreviacao_loja        = array();
        //remover abreviacao da loja
        TTransaction::open('base_banco');
        $lojas                  = Loja::getObjects();
        TTransaction::close();
        foreach($lojas as $loja){
            $abreviacao_loja[]  = $loja->abreviacao;
        }
        $motivo                 = str_replace($abreviacao_loja,'',$motivo);
        //inicio do tratamento da contingencia
        TTransaction::open('contingencia');
        //motivo
        $motivosContingencia = MotivoContingencia::where('motivo','=',$motivo)->load();
        if(!$motivosContingencia){
            $motivoContingencia             = new MotivoContingencia();
            $motivoContingencia->motivo     = $motivo;
            $motivoContingencia->reemissao  = '0';
            $motivoContingencia->tipo       = $tipo;
            $motivoContingencia->store();
        }else{
            $motivoContingencia = $motivosContingencia[0];
        }   
        //salvar venda
        $vendasErro = Vendaerror::where('n_venda','=',$numero_venda)->load();
        if($vendasErro){
            $vendaErro              = $vendasErro[0];
            $vendaErro->status      = 'reemitido';
            $vendaErro->motivo      = $motivoContingencia->id;
            $vendaErro->tentativas  += 1;
            $vendaErro->store();
        }else{
            $vendaErro              = new Vendaerror();
            $vendaErro->id_venda    = $id_venda;
            $vendaErro->n_venda     = $numero_venda;
            $vendaErro->motivo      = $motivoContingencia->id;
            $vendaErro->data_venda  = $data;
            $vendaErro->status      = 'novo';
            $vendaErro->tentativas  = 0;
            $vendaErro->store();
                    }
        TTransaction::close();     
    }
    

 // ---------------------- FUNÇÃO PARA ENVIO DE ERROS PARA GSHEETS --------------------------------------------------      
 
    public function vendaErro($id_interno,$motivo,$data){
           $cliente = GsheetClient::getClient();
                $service = new Google_Service_Sheets($cliente);
                $objectsJson =[];
                $spreadsheetId = '1Vig4sp0F6CXxHXUqLaY-68xywbF0dlk737DvEsDVWKI';  
                $range = "Erros!A:c";
                    $arrayTemp      = [];                  
                    $arrayTemp[]    = $id_interno;
                    $arrayTemp[]    = $data;
                    $arrayTemp[]    = $motivo;
                    $objectsJson[]  = $arrayTemp;
                    $Sjson = $objectsJson;
                $body = new Google_Service_Sheets_ValueRange(['values' => $Sjson]);
                $params = ['valueInputOption' => 'RAW'];
                $insert = ["insertDataOption"=>"INSERT_ROWS"];
                $result = $service -> spreadsheets_values->append(
                    $spreadsheetId,
                    $range,
                    $body,
                    $params,
                    $insert
                    );
    }
    
    public static function IsCancelable($duplicidade)
    {
        $return = false;
        $dt_venda   = date_create($duplicidade->dt_venda);
        $dt         = date('Y-m-d\TH:i:sO');
        $dt_atual   = date_create($dt);
        $prazo      = $dt_atual->diff($dt_venda);
        if($prazo->h <1 && $prazo->i < 25){ //25min, devido a tempo de processamento da venda. finalizarVenda->ERP->E-notas->ERP->salvar.
            $return = true;
        }
        return $return;
    }
   
    
    
   public static function CancelarVenda($id_venda)
    {
       try{
            TTransaction::open(static::DATABASE);
            $venda  = new Venda($id_venda);
            $is_promo   = false;
            $promo      = "PR";
            $promocao   = str_replace($promo,"",$venda->n_venda);
            if($promocao != $venda->n_venda){
                $is_promo =true;
            }
            
            if($venda->fiscal == "T"){
                $is_cancelable = self::IsCancelable($venda);
                if($is_cancelable){
                    //cancelar no E-notas
                    //cancelar via curl
                    TTransaction::open('base_banco');
                    $loja = new Loja($venda->loja);//obter o id do e-E-notas
                    $curl = curl_init();
                    TTransaction::close();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://api.enotasgw.com.br/v2/empresas/$loja->idEmpresa/nfc-e/$venda->id_interno",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'DELETE',
                      CURLOPT_HTTPHEADER => array(
                        'Authorization: Basic NWZiZGM0YWUtMDdmYi00NTEzLThjYjMtNTMwMjAxYmYwNjAw'
                      ),
                    ));
                    $response = curl_exec($curl);
                    
                    //cancelar no PDV
                    if($venda->id_venda != "" || $venda->id_venda != null || isset($venda->id_venda) ){ //se possui ID do pdv salvo
                        if($is_promo){
                            $woocommerce = ApiManager::getWooClient(1);
                            $data = [
                                'status' => 'cancelled'
                            ];
                            $dados = $woocommerce->put("orders/$venda->id_venda",$data);
                        }else{
                            $woocommerce = ApiManager::getWooClient();
                            $data = [
                                'status' => 'cancelled'
                            ];
                            $dados = $woocommerce->put("orders/$venda->id_venda",$data);
                        }
                    }else{
                       throw new Exception ("não é possivel cancelar a venda pois o id da venda no PDV não foi salva."); 
                    }
                    //adicionar ao estoque a quantidade dos itens vendidos
                    $itens = VendaItem::where('venda_id','=',$venda->id)->load();
                    foreach($itens as $item){
                        //adiciona ao estoque
                        $prod_estoques = ProdEstoque::where('id_deposito','=',$item->deposito)
                                       ->where('id_produto','=',$item->produto_id)
                                       ->load();
                       if($prod_estoques){
                           $prod_estoque = $prod_estoques[0];
                           $prod_estoque->quantidade = $prod_estoque->quantidade + $item->quantidade;
                           $prod_estoque->store();
                       }
                       //$item->delete(); apenas cancelou a venda e não a deletou, mantem os itens no sistema
                    }
                    //alterar o status da requisição NFCE para "Cancelado";
                     $nfces = Nfce::where('numVenda','=',$venda->id_interno)->load();
                     if($nfces){
                         $nfce = $nfces[0];
                         $nfce->status = "Cancelado";
                         $nfce->store();
                     }
                    //alterar o status da venda para "Cancelado";
                    $venda->status = "Cancelado";
                    $venda->store();
                }else{
                    throw new Exception ("não é possível excluir uma venda com uma NFC-e emitida com mais de 30mim.");
                    //e nada é feito no banco.*/
                }
            }else{
                //deleta a venda do PDV
                if($venda->id_venda != "" || $venda->id_venda != null || isset($venda->id_venda) ){ //se possui ID do pdv salvo
                    if($is_promo){
                        $woocommerce = ApiManager::getWooClient(1);
                        $dados = $woocommerce->delete("orders/$venda->id_venda",['force' => true]);
                    }else{
                        $woocommerce = ApiManager::getWooClient();
                        $dados = $woocommerce->delete("orders/$venda->id_venda",['force' => true]);
                    }
                }else{
                   throw new Exception ("não é possivel cancelar a venda pois o id da venda no PDV não foi salva."); 
                }
                //adicionar ao estoque a quantidade dos itens vendidos e apaga os itens da venda
                 $itens = VendaItem::where('venda_id','=',$venda->id)->load();
                    foreach($itens as $item){
                        //adiciona ao estoque
                        $prod_estoques = ProdEstoque::where('id_deposito','=',$item->deposito)
                                       ->where('id_produto','=',$item->produto_id)
                                       ->load();
                       if($prod_estoques){
                           $prod_estoque = $prod_estoques[0];
                           $prod_estoque->quantidade = $prod_estoque->quantidade + $item->quantidade;
                           $prod_estoque->store();
                       }
                       $item->delete();
                }
                //apaga as formas de pagamento dessa venda
                $pagamentos = VendaPagamento::where('venda_id','=',$venda->id)->load();
                foreach($pagamentos as $pagamento){
                    $pagamento->delete();
                }
                //deleta a venda do ERP
                $venda->delete();
            }
        TTransaction::close();
       }catch (Exception $e) 
       {
        ApiManager::sendMessage($e->getMessage());
        new TMessage('error', $e->getMessage()); 
        TTransaction::rollback();  
       }
    }
    public function sincronizarVendas(){
        
        try{
            //função responsável por obter as vendas do dia e então enviar para salvar para salvar,
            ApiManager::sendMessage(
' --- Sincronização de vendas ---
iniciada em: '.date('d/m/Y H:i:s'));
            $configuracao                   = array();
            $configuracao['fiscal']         = 0;
            $configuracao['origem']         = 'System-Crontab';
            $configuracao['usuario']        = 'Job-Crontab';
            $vendas_pdv_producao = self::obterVendasPDV();
            $vendas_pdv_promocao = self::obtervendasPDV(1);
            $vendas              = array();
            foreach($vendas_pdv_producao as $vendaArray){
                $vendas[] = $vendaArray;
            }
            foreach($vendas_pdv_promocao as $vendaArray){
                $vendas[] = $vendaArray;
            }
            $status                                 = '';
            foreach($vendas as $venda){
                try{
                    $status = $status."\nvenda: $venda->number - ";
                    $promocao                       = str_replace("PR","",strtoupper($venda->number));
                    $is_promo                       = $promocao != $venda->number ? true : false;
                    $configuracao['id_venda_pdv']   = $venda->id;
                    $configuracao['is_promo']       = $is_promo;
                    self::salvarVenda($venda,$configuracao);
                    $status = $status."Status : Sucesso!";
                }catch(Exception $e){
                    $status = $status."Status : Falhou -> ".$e->getMessage();
                    continue;
                }
            }
            ApiManager::sendMessage(
' --- Sincronização de vendas ---
Finalizada em: '.date('d/m/Y H:i:s'));
            ApiManager::sendMessage("LOG:\n".$status);
        }
        catch (Exception $e){
            ApiManager::sendMessage('Sincronizador de vendas falhou :'.$e->getMessage());
       }
    }
    public function obterVendasPDV($pdv = false){
        try{
            $vendas_woo_array       = array();
            //2021-09-24T10:41:38
            $date                   = date('Y-m-d',strtotime('-1 day'));
            //2021-11-19T09:50:55
            $exit = true;
            $page = 0;
            $cont = 0;
            while($exit){
                $woocommerce        = $pdv ? ApiManager::getWooClient(1) : ApiManager::getWooClient();
                $page++;
                $filtros            = array(
                'status'            => 'completed',
                'page'              => $page,
                'per_page'          => 99,
                'order'             =>'desc'
                );
                $vendas             = $woocommerce->get('orders',$filtros);//array 
                if(isset($vendas[0])){
                    foreach($vendas as $venda){
                        $orderDate = date('Y-m-d',strtotime($venda->date_created));
                        if($orderDate == $date){
                            $vendas_woo_array[] = $venda;
                            $cont++;
                        }else{
                            $exit = false;
                        }
                    }
                }else{
                    $exit = false;
                }
                ApiManager::sendMessage('pagina: '.$page.' Nº vendas:'.$cont);
                sleep(15);
            }
            return $vendas_woo_array;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
}
