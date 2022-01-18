<?php

class nfceWebHook
{
    
    const ACTIVE_RECORD = 'RetornoNfce';
    const ATTRIBUTES    = [ 'id_externo',
                            'tipo',
                            'status',
                            'motivoStatus',
                            'ambienteEmissao',
                            'enviadaPorEmail',
                            'dataCriacao',
                            'dataUltimaAlteracao',
                            'forcarEmissaoContingencia',
                            'numero',
                            'serie',
                            'dataEmissao',
                            'chaveAcesso',
                            'dataAutorizacao',
                            'linkDanfe',
                            'linkDownloadXml',
                            'lnkConsultaPorChaveAcesso',
                            'emitidaEmContingencia',
                            'empresaId',
                            'digestValue',
                            'numeroProtocolo',
                            'valorTotal',
                            'informacoesAdicionais',
                            'qrCode'];
    const DATABASE      = 'webhook';
               
   public function handle($param)
    {
        try{
            $method = strtoupper($_SERVER['REQUEST_METHOD']);
            unset($param['class']);
            unset($param['method']);
            $param['data'] = $param;
            $time = rand(1,10);
            switch( $method )
        {
            case 'POST':
                sleep($time);
                return self::obterRetorno($param);
                break;
            case 'PUT':
                return self::obterRetornoPorID($param);
                break;        
            default:
                return "metodos indisponiveis";
        }
    }catch(Exception $e){
            $error = "HY000";
            $lock_error   = str_replace($error,"",$e->getMessage());
            if($lock_error == $e->getMessage()){
            return $e->getMessage();    
            }
        }
    }
    
//----------------------------------------------------------------------------------------------- RETORNO POR WEBHOOOK -----------------------------------------------------------------------------------------------    
    
    public function obterRetorno($param){
        try{
            if(strlen($param['nfeId']) < 20){
                TTransaction::open(static::DATABASE);
                $retorno;
                $nfeId = str_replace('H','',$param['nfeId']);
                $retornos = NfceRetorno::where('id_externo','=',$param['nfeId'])->load();
                if($retornos){
                    $retorno = $retornos[0];
                }else{
                    $retorno = new NfceRetorno();
                }
                $toremove = array('z','Z');
                $retorno->id_externo                = $param['nfeId'];
                $retorno->tipo                      = $param['tipo'];
                $retorno->status                    = $param['nfeStatus'];
                $retorno->motivoStatus              = $param['nfeMotivoStatus'];
                $retorno->ambienteEmissao           = "";
                $retorno->enviadaPorEmail           = "";
                $retorno->dataCriacao               = "";//str_replace($toremove,"",$param['dataCriacao']);
                $retorno->dataUltimaAlteracao       = "";
                $retorno->forcarEmissaoContingencia = "";
                $retorno->numero                    = $param['nfeNumero'];
                $retorno->serie                     = $param['nfeSerie'];
                $retorno->dataEmissao               = str_replace($toremove,"",$param['nfeDataEmissao']);
                $retorno->chaveAcesso               = $param['nfeChaveAcesso'];
                $retorno->dataAutorizacao           = str_replace($toremove,"",$param['nfeDataAutorizacao']);
                $retorno->linkDanfe                 = $param['nfeLinkDanfe'];
                $retorno->linkDownloadXml           = $param['nfeLinkXml'];
                $retorno->lnkConsultaPorChaveAcesso = $param['nfeLinkConsultaPorChaveAcesso'];   
                $retorno->emitidaEmContingencia     = "";
                $retorno->empresaId                 = $param['empresaId'];
                $retorno->digestValue               = $param['nfeDigestValue'];
                $retorno->numeroProtocolo           = $param['nfeNumeroProtocolo'];
                $retorno->valorTotal                = "";
                $retorno->informacoesAdicionais     = "";
                $retorno->qrCode                    = $param['conteudoQRCode'];
                //atualiza uma venda e nfce quando a nota é emitida em contigencia e retorna negada
                $retorno->store();
                TTransaction::close();
                $salvar                             = self::baixarXml($retorno);
                $toUpdate                           = array();
                $atualizarVendaNfce                 = self::updateVendaNfce($retorno);
                $resultado                          = "Xml salvo em: $salvar \n$atualizarVendaNfce ---- $retorno->motivoStatus ";
                return $resultado;
            }
        }catch(Exception $e){
            $fila['data']                           = str_replace('Z',"",$param['nfeDataEmissao']);
            $fila['motivo']                         = 'retornoPorWebhook / '.$e->getMessage();
            $fila['id_retorno']                     = $param['nfeId'];
            $fila['empresaId']                      = $param['empresaId'];
            self::onContingencia($fila);
        }
    }
    
//----------------------------------------------------------------------------------------------- RETORNO MANUAL ----------------------------------------------------------------------------------------------- 
     public function obterRetornoPorID($param){
        $salvar = "";
        try{
        TTransaction::open(static::DATABASE);
        $retorno;
        if(isset($param['id'])&&strlen($param['id'])<25){
            $nfceId     = $param['id'];
            $numero     = array('0','1','2','3','4','5','6','7','8','9','TT','PR','H');
            $abr_loja   = str_replace($numero,"",$nfceId);
            //ApiManager::sendMessage("webhook recebido para reemissão - $abr_loja");
            $loja;
            TTransaction::open("base_banco");
            $lojas      = Loja::where('abreviacao','=',$abr_loja)->load();
            TTransaction::close();
            
        if($lojas){
            $loja = $lojas[0];
            $headers = array(
                    'Accept: application/json',
                    'Authorization: Basic  [token E-notas]'
                    );
            $url = "https://api.enotasgw.com.br/v2/empresas/{$loja->idEmpresa}/nfc-e/{$nfceId}";
            $ch = curl_init($url);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
            $result = json_decode(curl_exec($ch));
            curl_close($ch);
            $toremove = array('z','Z');
            $retornos = NfceRetorno::where('id_externo','=',$nfceId)->load();
            if(!$retornos){
                $retorno                            = new NfceRetorno();
                $retorno->id_externo                = $result->id;
                $retorno->tipo                      = $result->tipo;
                $retorno->status                    = $result->status;
                $retorno->motivoStatus              = $result->motivoStatus;
                $retorno->ambienteEmissao           = $result->ambienteEmissao;
                $retorno->enviadaPorEmail           = $result->enviadaPorEmail;
                $retorno->dataCriacao               = str_replace($toremove,"",$result->dataCriacao);
                $retorno->dataUltimaAlteracao       = str_replace($toremove,"",$result->dataUltimaAlteracao);
                $retorno->forcarEmissaoContingencia = $result->forcarEmissaoContingencia;
                $retorno->numero                    = $result->numero;
                $retorno->serie                     = $result->serie;
                $retorno->dataEmissao               = str_replace($toremove,"",$result->dataEmissao);
                $retorno->chaveAcesso               = $result->chaveAcesso;
                $retorno->dataAutorizacao           = null; //venda manual não possui este campo no retorno
                $retorno->linkDanfe                 = isset($result->linkDanfe)?$result->linkDanfe:null ;
                $retorno->linkDownloadXml           = isset($result->linkDownloadXml)?$result->linkDownloadXml:null ;
                //$retorno->lnkConsultaPorChaveAcesso = $result->lnkConsultaPorChaveAcesso;
                //$retorno->emitidaEmContingencia     = $result->emitidaEmContingencia;
                $retorno->empresaId                 = $loja->idEmpresa;
                //$retorno->digestValue               = $result->digestValue;
                //$retorno->numeroProtocolo           = $result->numeroProtocolo;
                $retorno->valorTotal                = $result->valorTotal;
                $retorno->informacoesAdicionais     = $result->informacoesAdicionais;
                $retorno->qrCode                    = isset($result->qrCode)?$result->qrCode:null;
                $retorno->store();
            }else{
                $retorno                            = $retornos[0];
                $retorno->id_externo                = $result->id;
                $retorno->tipo                      = $result->tipo;
                $retorno->status                    = $result->status; 
                $retorno->motivoStatus              = $result->motivoStatus;
                $retorno->ambienteEmissao           = $result->ambienteEmissao;
                $retorno->enviadaPorEmail           = $result->enviadaPorEmail;
                $retorno->dataCriacao               = str_replace($toremove,"",$result->dataCriacao);
                $retorno->dataUltimaAlteracao       = str_replace($toremove,"",$result->dataUltimaAlteracao);
                $retorno->forcarEmissaoContingencia = $result->forcarEmissaoContingencia;
                $retorno->numero                    = $result->numero;
                $retorno->serie                     = $result->serie;
                $retorno->dataEmissao               = str_replace($toremove,"",$result->dataEmissao);
                $retorno->chaveAcesso               = $result->chaveAcesso;
                $retorno->dataAutorizacao           = null; //venda manual não possui este campo no retorno
                $retorno->linkDanfe                 = isset($result->linkDanfe)?$result->linkDanfe:null ;
                $retorno->linkDownloadXml           = isset($result->linkDownloadXml)?$result->linkDownloadXml:null ;
                //$retorno->lnkConsultaPorChaveAcesso = $result->lnkConsultaPorChaveAcesso;
                //$retorno->emitidaEmContingencia     = $result->emitidaEmContingencia;
                $retorno->empresaId                 = $loja->idEmpresa;
                //$retorno->digestValue               = $result->digestValue;
                //$retorno->numeroProtocolo           = $result->numeroProtocolo;
                $retorno->valorTotal                = $result->valorTotal;
                $retorno->informacoesAdicionais     = $result->informacoesAdicionais;
                $retorno->qrCode                    = isset($result->qrCode)?$result->qrCode:null;
                $retorno->store();
            }
            TTransaction::close();
            $salvar                     = self::baixarXml($retorno);
            $atualizarVendaNfce         = self::updateVendaNfce($retorno);
            $resultado                  = "Xml salvo em: $salvar \n$atualizarVendaNfce ---- $retorno->motivoStatus";
            //var_dump($retorno->numero);
            return $resultado;
            }else{
                ApiManager::sendMessage("NFCE SEM LOJA DEFINIDA");
            }
        }//venda não é nossa
        }catch(Exception $e){
           // $fila['data']       = 
            $fila['motivo']     = 'retornoPorId / '.$e->getMessage();
            $fila['id_retorno'] = $param['id'];
           // $fila['empresaId']  = 
            ApiManager::sendMessage($e->getMessage());
           // self::onContingencia($fila);
        } 
    }
//-----------------------------------------------------------------------------------------------Atualizar VENDA / NFCE ----------------------------------------------------------------------------------------------- 
    public function updateVendaNfce($retorno){
        try{
            TTransaction::open('vendas_base');
            $vendas                     = Venda::where('id_interno','=',$retorno->id_externo)->load();
            $resultado                  = '';
            if($vendas){
                $venda                  = $vendas[0];
                $venda->status          = $retorno->status;
                $venda->fiscal          = $retorno->status=="Autorizada"?'T':'F';
                $venda->obs             = $retorno->motivoStatus;
                $venda->store();
                $resultado              = $resultado."\nvenda salva: $retorno->id_externo";
                $nfces                  = Nfce::where('Venda_id','=',$venda->id)->load();
                 if($nfces){
                    $nfce               = $nfces[0];
                    $nfce->status       = $retorno->status;
                    $nfce->obs          = $retorno->motivoStatus;
                    $nfce->n_nfce       = $retorno->numero;
                    $nfce->dt_nfce      = $retorno->dataEmissao;
                    $nfce->link_cupom   = $retorno->linkDanfe;
                    $nfce->retorno_nfce = $retorno->id;
                    $nfce->store();
                    $resultado          = $resultado."\nnfce salva: $retorno->id_externo";
                }else{
                    $nfce               = new Nfce();
                    $nfce->status       = $retorno->status;
                    $nfce->obs          = $retorno->motivoStatus;
                    $nfce->n_nfce       = $retorno->numero;
                    $nfce->dt_nfce      = $retorno->dataEmissao;
                    $nfce->link_cupom   = $retorno->linkDanfe;
                    $nfce->retorno_nfce = $retorno->id;
                    $nfce->store();
                    $resultado          = $resultado."\nnfce gerada: $retorno->id_externo";
                }
            }else{
                $resultado              = $resultado."\nvenda não salva: $retorno->id_externo";
            }
           
            TTransaction::close();
            return $resultado;
        }catch(Exception $e){
            //$fila['data']       = 
            $fila['motivo']         = 'updateVendaNfce / '.$e->getMessage();
            $fila['id_retorno']     = $retorno->id;
            //$fila['empresaId']  = 
            
            //self::onContingencia($fila);
        }
    }
    
//-----------------------------------------------------------------------------------------------BAIXAR NFCE -----------------------------------------------------------------------------------------------
    
    public function baixarXml ($retorno){
        try{
        $loja;
        if(isset($retorno->linkDownloadXml) && $retorno->linkDownloadXml != null){
            TTransaction::open('base_banco');
            $lojas = Loja::where('idEmpresa','=',$retorno->empresaId)->load();
            TTransaction::close();
            if($lojas){
                $loja       = $lojas[0];
                //tabela de emissão de NF, loja vendendo com CNPJ tal, abaixo serve apra os XML serem baixados na pasta correta do CNPJ que emitiu.
                $loja->nome_fantasia = $loja->nome_fantasia == 'Fashion Biju São Gonçalo Shopping br'   ? 'Fashion Biju Duque de Caxias'            : $loja->nome_fantasia;
                $loja->nome_fantasia = $loja->nome_fantasia == 'Fashion Biju Araruama Avenida 2'        ? 'Fashion Biju Loja Cabo Frio Joao Pessoa' : $loja->nome_fantasia;
                $loja->nome_fantasia = $loja->nome_fantasia == 'Fashion Biju Partage'                   ? 'Fashion Biju Madureira'                  : $loja->nome_fantasia;
                $loja->nome_fantasia = $loja->nome_fantasia == 'Fashion Biju Araruama Avenida 1'        ? 'Fashion Biju Loja Cabo Frio Joao Pessoa' : $loja->nome_fantasia;
                $loja->nome_fantasia = $loja->nome_fantasia == 'Fashion Biju Araruama Opmall'           ? 'Fashion Biju Loja Cabo Frio Joao Pessoa' : $loja->nome_fantasia;
                $loja->nome_fantasia = $loja->nome_fantasia == 'Fashion Rio das Ostras 2'               ? 'Fashion Biju Madureira'                  : $loja->nome_fantasia;
                //
                $dt         = $retorno->dataEmissao;
                $dt_nfce    = date_create($dt);
                $dt_nfce->modify('- 3 hours');
                $mes        = $dt_nfce->format('m');//mes
                $ano        = $dt_nfce->format('Y');//ano
                $pasta      = "xml/$ano/$mes/$loja->nome_fantasia";
                if(!is_dir($pasta)){
                    mkdir($pasta,0777,true);
                }
                $filename   = $retorno->numero.'-'.$retorno->id_externo.'.xml';
                $fp         = fopen ($filename, 'w+');
                $ch         = curl_init($retorno->linkDownloadXml);
                curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_exec($ch);
                curl_close($ch);
                fclose($fp);
                $copy       = "$pasta/$filename";
                rename($filename,$copy);
                return $pasta;
            }else{
                var_dump ($retorno);
                throw new Exception("venda: $retorno->id_externo \n não foi localizada uma loja associada.");
            }
        }else{
            throw new Exception ("Venda: $retorno->id_externo Retorno com status negado, e não possui um link para downlaod");
        }
        }catch(Exception $e){
            
            ApiManager::sendMessage($e->getMessage());
            /*$fila['data']       = $param->dataEmissao;
            $fila['motivo']     = 'baixarXml / '.$e->getMessage();
            $fila['id_retorno'] = $param->id_externo;
            $fila['empresaId']  = $param->empresaId;
            self::onContingencia($fila);*/
        }
    }
    
    // ----------------------------------------------------------------------------------------------- TRATAMENTO CONTINGENCIAS -----------------------------------------------------------------------------------------------
    
    public function onContingencia($fila){
       
            //configurando variaveis
            $idEmpresa;
            $numero                 = array('0','1','2','3','4','5','6','7','8','9','TT','PR','H');
            
            $data                   = isset($fila['data'])?$fila['data']:date('Y-m-d H:i:s');
            $motivo                 = $fila['motivo'];
            $id_retorno             = $fila['id_retorno'];
            $empresaId              = "";
         try{
            if(isset($fila['empresaId'])){
                $empresaId          = strval($fila['empresaId']);
            }else{
                $nfceId             = $fila['id_retorno'];
                $abr_loja           = str_replace($numero,"",$nfceId);
                $loja;
                TTransaction::open("base_banco");
                $lojas              = Loja::where('abreviacao','=',$abr_loja)->load();
                TTransaction::close();
                if($lojas){
                    $loja               = $lojas[0];
                    $empresaId          = $loja->empresaId;
                }else{
                    $motivo = $motivo.' -- id da nfce não é válido, não possui uma loja associada';
                    throw new Exception ("-- id da nfce não é válido, não possui uma loja associada -- $id_retorno");
                }
            }
            $motivoContingencia;
            $tipo                   = 'retorno';
            $erros                  = array('system_sql_log.PRIMARY');
            $erros_                 = str_replace($erros,'',$motivo);
            
            $abreviacao_loja        = array();
            TTransaction::open('base_banco');
            $lojas                  = Loja::getObjects();
            TTransaction::close();
            foreach($lojas as $loja){
                $abreviacao_loja[]  = $loja->abreviacao;
            }
             //envia erro caso seja diferente de registro duplicado
            if(strlen($erros_) == strlen($motivo)){
                ApiManager::sendMessage("retorno: $id_retorno as $data\n-Erro: $motivo");
            }
            //remove os numeros para que o motivo seje genérico.
            $motivo                 = str_replace($numero,'',$motivo);
            $motivo                 = str_replace($abreviacao_loja,'',$motivo);
            //verifica se existe id empresa e preenche caso não tenha
            
            
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
            $retornosErro = RetornoError::where('id_retorno','=',$id_retorno)->load();
            if($retornosErro){
                $retornoErro              = $retornosErro[0];
                $retornoErro->status      = 'reemitido';
                $retornoErro->motivo      = $motivoContingencia->id;
                $retornoErro->tentativas  += 1;
                $retornoErro->store();
            }else{
                $retornoErro              = new Retornoerror();
                $retornoErro->id_retorno  = $id_retorno;
                $retornoErro->id_empresa  = $empresaId;
                $retornoErro->motivo      = $motivoContingencia->id;
                $retornoErro->data_retorno= $data;
                $retornoErro->status      = 'novo';
                $retornoErro->tentativas  = 0;
                $retornoErro->store();
                        }
            TTransaction::close();  
        }catch(Exception $e){
            $motivo                 = $fila['motivo'];
            $erros                  = array('system_sql_log.PRIMARY');
            $erros_                 = str_replace($erros,'',$motivo);
                if(strlen($erros_) == strlen($motivo)){
                    $menssagem = "--- ERRO CRITICO CONTINGENCIA DO RETORNO NFCE ---\n RETORNO: ";
                foreach($fila as $variaveis){
                    $menssagem = $menssagem."$variaveis \n";
                }
                $menssagem = $menssagem."$id_retorno ERRO: ".$e->getMessage();
               // ApiManager::sendMessage($menssagem);
            }
            //ApiManager::sendMessage("$id_retorno ERRO: ".$e->getMessage());
        }
    }
}
