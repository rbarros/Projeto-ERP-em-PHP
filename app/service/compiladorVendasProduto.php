<?php

class compiladorVendasProduto
{
    public static function compilarProdutosDiario(){
        //algoritmo roda todo dia as 00:00 e tem por objetivo criar objetos com o resumo das vendas do dia anterior seja, qtd de produtos vendidos na semana->mes e por loja
        try{
        ini_set('max_execution_time', 0);
        $apartir                = date('Y-m-d', strtotime('-1 month'));
        $hoje                   = date('Y-m-d');
        $semana                 = date('Y-m-d', strtotime('-1 week'));
        $quinzena               = date('Y-m-d', strtotime('-15 days'));
        
        $hoje                   = new DateTime($hoje);
        $semana                 = new DateTime($semana);
        $quinzena               = new DateTime($quinzena);
            
        TTransaction::open("vendas_base");
        $itens_vendidos         = VendaItem::where('dt_venda','>=',$apartir)->load();
        TTransaction::close();
        $produtos_compilados    = array();
        
        if($itens_vendidos){
            $produtoCompiladoArray = array();
            foreach($itens_vendidos as $item_vendido){
                $data_item                      = new DateTime($item_vendido->dt_venda);
                $array_prod                     = array();
                $array_prod['produto_id']       = $item_vendido->produto_id;
                $array_prod['nome_produto']     = $item_vendido->name;
                $array_prod['loja_id']          = $item_vendido->loja_id;
                $array_prod['deposito_id']      = $item_vendido->deposito;
                //separa o grupo da venda
                //semanal
                if($data_item >= $semana && $data_item <= $hoje){
                    $semanal                  = 0;
                    $semanal_valor            = 0;
                    if(isset($produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['semanal'])){
                        $semanal              = $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['semanal'];
                        $semanal_valor        = $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['semanal_valor'];
                        $semanal              += intval($item_vendido->quantidade);
                        $semanal_valor        += doubleval($item_vendido->valor_total); 
                    }else{
                        $semanal              += intval($item_vendido->quantidade);
                        $semanal_valor        += doubleval($item_vendido->valor_total); 
                    }
                    $array_prod['semanal']      = $semanal;
                    $array_prod['semanal_valor']= $semanal_valor;
                }
                //quinzenal
                if($data_item >= $quinzena && $data_item <= $hoje){
                    $quinzenal                  = 0;
                    $quinzenal_valor            = 0;
                    if(isset($produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['quinzenal'])){
                        $quinzenal              = $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['quinzenal'];
                        $quinzenal_valor        = $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['quinzenal_valor'];
                        $quinzenal              += intval($item_vendido->quantidade);
                        $quinzenal_valor        += doubleval($item_vendido->valor_total); 
                    }else{
                    $quinzenal                  += intval($item_vendido->quantidade);
                    $mensal_valor               += doubleval($item_vendido->valor_total); 
                    }
                $array_prod['quinzenal']        = $quinzenal;
                $array_prod['quinzenal_valor']  = $mensal_valor;
                }
                //mensal
                $mensal                         = 0;
                $mensal_valor                   = 0;
                    if(isset($produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['mensal'])){
                        $mensal                 = $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['mensal'];
                        $mensal_valor           = $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['mensal_valor'];
                        $mensal                 += intval($item_vendido->quantidade);
                        $mensal_valor           += doubleval($item_vendido->valor_total);
                    }else{
                        $mensal                 += intval($item_vendido->quantidade);
                        $mensal_valor           += doubleval($item_vendido->valor_total);
                    }
                $array_prod['mensal']           = $mensal;
                $array_prod['mensal_valor']     = $mensal_valor;
                
                $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito] = $array_prod;
            }
        
        TTransaction::open('compilador');
        foreach($produtos_compilados as $deposito_produto){
           foreach($deposito_produto as $produto_compilado){
                $id_produto     = $produto_compilado['produto_id'];
                $id_deposito    = $produto_compilado['deposito_id'];
                $produtodiario  ;
                $produtosdiario = Produtodiario::where('produto_id','=',$id_produto)
                                              ->where('deposito_id','=',$id_deposito)
                                              ->load();
                if($produtosdiario){
                    $produtodiario = $produtosdiario[0];
                }else{
                    $produtodiario = new Produtodiario();
                }
                $produtodiario->nome_produto    = $produto_compilado['nome_produto'];
                $produtodiario->produto_id      = $produto_compilado['produto_id'];
                $produtodiario->semanal         = isset($produto_compilado['semanal'])          ?$produto_compilado['semanal']                      :null;
                $produtodiario->valor_semanal   = isset($produto_compilado['semanal_valor'])    ?doubleval($produto_compilado['semanal_valor'])     :null;
                $produtodiario->quinzenal       = isset($produto_compilado['quinzenal'])        ?$produto_compilado['quinzenal']                    :null;
                $produtodiario->valor_quinzenal = isset($produto_compilado['quinzenal_valor'])  ?doubleval($produto_compilado['quinzenal_valor'])   :null;
                $produtodiario->mensal          = isset($produto_compilado['mensal'])           ?$produto_compilado['mensal']                       :null;
                $produtodiario->valor_mensal    = isset($produto_compilado['mensal_valor'])     ?doubleval($produto_compilado['mensal_valor'])      :null;
                $produtodiario->dtAtualizacao   = date('Y-m-d');
                $produtodiario->loja_id         = $produto_compilado['loja_id'];
                $produtodiario->mensal          = $produto_compilado['mensal'];
                $produtodiario->deposito_id     = $produto_compilado['deposito_id'];
                $produtodiario->store();
            }
        }
        TTransaction::close();
        }
            
        }catch(Exception $e){
            ApiManager::sendMessage("compilador diario de produto falhou:".$e->getMessage());
            return $e->getMessage();    
        }
    }
    
    public static function compilarProdutosMensal(){//roda todo dia 01 do mês 
        ini_set('max_execution_time', 0);
        $apartir                = date('Y-m-1', strtotime('-1 month'));
        $ate                    = date('Y-m-1');
        try{
        TTransaction::open("vendas_base");
        $itens_vendidos         = VendaItem::where('dt_venda','>=',$apartir)
                                           ->where('dt_venda','<',$ate)
                                           ->load();
        TTransaction::close();
        $produtos_compilados    = array();
        foreach($itens_vendidos as $item_vendido){
                $data_item                      = new DateTime($item_vendido->dt_venda);
                $array_prod                     = array();
                $array_prod['produto_id']       = $item_vendido->produto_id;
                $array_prod['nome_produto']     = $item_vendido->name;
                $array_prod['loja_id']          = $item_vendido->loja_id;
                $array_prod['deposito_id']      = $item_vendido->deposito;
                $data_item                      = date_create($item_vendido->dt_venda);
                $array_prod['mes']              = $data_item->format('m');
                $array_prod['ano']              = $data_item->format('Y');
                //separa o grupo da venda
                //mensal
                $quantidade                     = 0;
                $valor                          = 0;
                    if(isset($produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['quantidade'])){
                        $quantidade             = $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['quantidade'];
                        $quantidade             += intval($item_vendido->quantidade);
                        $valor                  = $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['valor'];
                        $valor                  += doubleval($item_vendido->valor_total);
                    }else{
                        $quantidade             += intval($item_vendido->quantidade);
                        $valor                  += doubleval($item_vendido->valor_total);
                    }
                $array_prod['quantidade']       = $quantidade;
                $array_prod['valor']            = $valor;
                $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito] = $array_prod;
            }
        
        TTransaction::open('compilador');
        foreach($produtos_compilados as $deposito_produto){
           foreach($deposito_produto as $produto_compilado){
                $id_produto     = $produto_compilado['produto_id'];
                $id_deposito    = $produto_compilado['deposito_id'];
                $mes            = $produto_compilado['mes'];
                $ano            = $produto_compilado['ano'];
                $produtodiario  ;
                $produtosdiario = Produtomensal::where('produto_id','=',$id_produto)
                                              ->where('deposito_id','=',$id_deposito)
                                              ->where('mes','=',$mes)
                                              ->where('ano','=',$ano)
                                              ->load();
                if($produtosdiario){
                    $produtomensal = $produtosdiario[0];
                }else{
                    $produtomensal = new Produtomensal();
                }
                $produtomensal->nome_produto        = $produto_compilado['nome_produto'];            
                $produtomensal->quantidade          = isset($produto_compilado['quantidade'])  ?$produto_compilado['quantidade']        :null;             
                $produtomensal->valor               = isset($produto_compilado['valor'])       ?doubleval($produto_compilado['valor'])  :null;         
                $produtomensal->mes                 = isset($produto_compilado['mes'])         ?$produto_compilado['mes']               :null;                     
                $produtomensal->ano                 = isset($produto_compilado['ano'])         ?$produto_compilado['ano']               :null;                                  
                $produtomensal->dtAtualizacao       = date('Y-m-d');           
                $produtomensal->loja_id             = $produto_compilado['loja_id'];          
                $produtomensal->produto_id          = $produto_compilado['produto_id'];            
                $produtomensal->estoque_id          = null;             
                $produtomensal->deposito_id         = $produto_compilado['deposito_id'];     
                $produtomensal->store();
            }
        }
        TTransaction::close();
        }catch(Exception $e){
            ApiManager::sendMessage("compilador mensal de produto falhou:".$e->getMessage());
            return $e->getMessage();    
        }
    }
    
    public static function compilarProdutos(){//roda apenas uma vez no sistema para calcular valores retroativos
        ini_set('max_execution_time', 0);
        try{
            $data_inicio            = date('Y-m-01');
            $continue               = true;
            $contador               = 1;
            while($continue){
                sleep(1);
                $data_fim;
                $data_inicio    = date("Y-m-01",strtotime("-$contador month"));
                $contador_fim   = $contador -1;  
                $data_fim       = date("Y-m-01",strtotime("-$contador_fim month"));
                TTransaction::open("vendas_base");
                $itens_vendidos         = VendaItem::where('dt_venda','>=',$data_inicio)
                                                   ->where('dt_venda','<',$data_fim)
                                                   ->load();
                TTransaction::close();
                if($itens_vendidos){
                    $produtos_compilados    = array();
                    foreach($itens_vendidos as $item_vendido){
                            $data_item                      = new DateTime($item_vendido->dt_venda);
                            $array_prod                     = array();
                            $array_prod['produto_id']       = $item_vendido->produto_id;
                            $array_prod['nome_produto']     = $item_vendido->name;
                            $array_prod['loja_id']          = $item_vendido->loja_id;
                            $array_prod['deposito_id']      = $item_vendido->deposito;
                            $data_item                      = date_create($item_vendido->dt_venda);
                            $array_prod['mes']              = $data_item->format('m');
                            $array_prod['ano']              = $data_item->format('Y');
                            $mes                            = $data_item->format('m');
                            $ano                            = $data_item->format('Y');
                            //separa o grupo da venda
                            //mensal
                            $quantidade                     = 0;
                            $valor                          = 0;
                                if(isset($produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['quantidade'])){
                                    $quantidade             = $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['quantidade'];
                                    $quantidade             += intval($item_vendido->quantidade);
                                    $valor                  = $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito]['valor'];
                                    $valor                  += intval($item_vendido->valor_total);
                                }else{
                                    $quantidade             += intval($item_vendido->quantidade);
                                    $valor                  += intval($item_vendido->valor_total);
                                }
                            $array_prod['quantidade']       = $quantidade;
                            $array_prod['valor']            = $valor;
                            
                            $produtos_compilados[$item_vendido->produto_id][$item_vendido->deposito] = $array_prod;
                        } 
                    
                    TTransaction::open('compilador');
                    foreach($produtos_compilados as $data){
                       foreach($data as $produto_compilado){
                                echo'<pre>';
                                var_dump($produto_compilado);
                                echo'</pre>';
                                $id_produto     = $produto_compilado['produto_id'];
                                $id_deposito    = $produto_compilado['deposito_id'];
                                $mes            = $produto_compilado['mes'];
                                $ano            = $produto_compilado['ano'];
                                $produtodiario  ;
                                $produtosdiario = Produtomensal::where('produto_id','=',$id_produto)
                                                              ->where('deposito_id','=',$id_deposito)
                                                              ->where('mes','=',$mes)
                                                              ->where('ano','=',$ano)
                                                              ->load();
                                if($produtosdiario){
                                    $produtomensal = $produtosdiario[0];
                                }else{
                                    $produtomensal = new Produtomensal();
                                }
                                $produtomensal->nome_produto        = $produto_compilado['nome_produto'];            
                                $produtomensal->quantidade          = isset($produto_compilado['quantidade'])  ?$produto_compilado['quantidade']   :null;             
                                $produtomensal->valor               = isset($produto_compilado['valor'])       ?$produto_compilado['valor']        :null;         
                                $produtomensal->mes                 = isset($produto_compilado['mes'])         ?$produto_compilado['mes']          :null;                     
                                $produtomensal->ano                 = isset($produto_compilado['ano'])         ?$produto_compilado['ano']          :null;                                  
                                $produtomensal->dtAtualizacao       = date('Y-m-d');           
                                $produtomensal->loja_id             = $produto_compilado['loja_id'];          
                                $produtomensal->produto_id          = $produto_compilado['produto_id'];            
                                $produtomensal->estoque_id          = null;             
                                $produtomensal->deposito_id         = $produto_compilado['deposito_id'];     
                                $produtomensal->store();
                        }
                    }
                    TTransaction::close();
                }else{
                    $continue = false;
                }
                $contador++;
            }
            
        }catch(Exception $e){
            ApiManager::sendMessage("compilador geral de produto falhou:".$e->getMessage());
            return $e->getMessage();    
        }    
    }
    
    public function calcularCurvaABC(){
        ini_set('max_execution_time', 0);
        $curvaA                 = 20;
        $curvaB                 = 30;
        $curva_compilada        = array();
        $data_inicio            = date("Y-m-01",strtotime("-1 month"));
        
        //PASSO 1 - OBTER OS ITENS VENDIDOS E ADICIONAR AO ARRAY E OBTER O SOMATORIO TOTAL DE SKU.
        TTransaction::open("vendas_base");
        //$itens_vendidos       = VendaItem::where('dt_venda','>=',$data_inicio)->load();
        $itens_vendidos         = VendaItem::getObjects();
        TTransaction::close();
        if($itens_vendidos){
            foreach($itens_vendidos as $item_vendido){//obtem e preenche os dados
                $array_produto                  = array();
                $total_sku                      = isset($curva_compilada[$item_vendido->deposito]['total_sku'])?$curva_compilada[$item_vendido->deposito]['total_sku']:0;
                $total_sku                      += $item_vendido->valor_total;
                $valor                          = isset($curva_compilada[$item_vendido->deposito][$item_vendido->produto_id])?$curva_compilada[$item_vendido->deposito][$item_vendido->produto_id]['valor']:0;
                $valor                          += $item_vendido->valor_total;
                $array_produto['produto_id']    = $item_vendido->produto_id;
                $array_produto['sku']           = $item_vendido->SKU;
                $array_produto['nome_produto']  = $item_vendido->name;
                $array_produto['deposito_id']   = $item_vendido->deposito;
                $array_produto['valor']         = $valor;
                
                $curva_compilada[$item_vendido->deposito]['total_sku']      = $total_sku;
                $curva_compilada[$item_vendido->deposito][$item_vendido->produto_id] = $array_produto;
            }
        //PASSO 2 - CALCULAR A REPRESENTAÇÃO DE CADA SKU DE ACORDO COM O TOTAL E ORDENAR
            foreach($curva_compilada as $curva_deposito){
                $total_sku              = $curva_deposito['total_sku'];
                $porcentagem_sku        = 0;
                unset($curva_deposito['total_sku']);
                $produto                = array();
                //calcula a porcentagem
                foreach($curva_deposito as $curva_produto){
                    $produto_aux        = array();
                    $id_produto         = $curva_produto['produto_id'];
                    $valor              = $curva_produto['valor'];
                    $valor_sku          = floatval($valor) / floatval($total_sku);
                    $produto_aux["id"]  = $id_produto;
                    $produto_aux["valor"]= $valor_sku;
                    $produto []         = $produto_aux; 
                }
                //ordena o array
                usort($produto, function ($a, $b){
                	return $a['valor'] < $b['valor'];
                });
                //ajusta o array de produtos deste deposito em ordem decrescente
                $curva_deposito_ordenada = array();
                $pos                     = 0;
                foreach($produto as $indice){
                    $porcentagem_sku              += $indice['valor']*100;
                    $ordem                        = $indice['id'];
                    $curva_deposito[$ordem]['porcentagem'] = $indice['valor']*100;
                    $curva_deposito[$ordem]['posicao']  =  $pos;
                    $pos++;
                //calcula a curva
                    if($porcentagem_sku <= $curvaA)
                        $curva_deposito[$ordem]['curva']= "A";
                    else{
                        if($porcentagem_sku > $curvaA && $porcentagem_sku <= $curvaB){
                            $curva_deposito[$ordem]['curva']= "B";
                        }else{
                            $curva_deposito[$ordem]['curva']= "C";
                        }
                    }
                    $curva_deposito_ordenada[]  = $curva_deposito[$ordem];
                }
                //salva o objeco curva no banco
                foreach($curva_deposito_ordenada as $produto_curva){
                    
                    $curva_deposito_ordenada[]  = $curva_deposito[$ordem];
                    $id_produto                 = $produto_curva['produto_id'];
                    $id_deposito                = $produto_curva´['deposito_id'];
                    TTransaction::open('compilador');
                    $curvaABC;
                    $curvasABC = Curvaabc::where('produto_id','=',$id_produto)
                                         ->where('deposito_id','=',$id_deposito)
                                         ->load();
                    if($curvasABC){
                        $curvaABC = $curvasABC[0];
                    }else{
                        $curvaABC = new Curvaabc();
                    }
                    $curvaABC->nome_produto     = $produto_curva['nome_produto'];
                    $curvaABC->sku              = $produto_curva['sku'];
                    $curvaABC->produto_id       = $produto_curva['produto_id'];
                    $curvaABC->deposito_id      = $produto_curva['deposito_id'];
                    $curvaABC->curva            = $produto_curva['curva'];
                    $curvaABC->dtAtualizacao    = date('Y-m-d');
                    $curvaABC->porcentagem      = round($produto_curva['porcentagem'],2,PHP_ROUND_HALF_UP);
                    $curvaABC->valor            = round($produto_curva['valor'],2,PHP_ROUND_HALF_UP);
                    $curvaABC->store();
                    TTransaction::close();
                    TTransaction::open('base_banco');
                    $estoques = ProdEstoque::where('id_produto','=',$produtomensal->produto_id)
                                           ->where('id_deposito','=',$produtomensal->deposito_id)
                                           ->load();
                    if($estoques){
                        $estoque                = $estoques[0];
                        $estoque->curva         = $curvaABC->curva;
                        $estoque->store();
                    }
                    TTransaction::close();
                }
            }
        }
    }
}
