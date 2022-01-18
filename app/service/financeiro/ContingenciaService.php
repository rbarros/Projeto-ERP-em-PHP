<?php

class ContingenciaService
{
    const DATABASE              = 'contingencia';
    // classe responsável por analisar os erros gerados, tratar e então reemitir.
    // seguindo essa ordem  --- verificador->seletorDeErros->Tratadores->reemissão ---
    // verificador -> puxa os erros gerados da fila com status NOVO, REEMITIDO(até 3x), dependendo do motivo ele já reenvia direto ou envia apra seletor de erros;
    // Seletor de erros -> um grande switch que redireciona a logica de acordo com o tratamento de cada erro;
    // tratadores de erro -> classes especificas apra cadas tipo de tratamento de erro, permite ser mais customizável e permitindo melhor tratativa;
    // REEMISSAO em motivos funciona assim 0-> não há tratativa implementada, 1-> há uma tratativa implementada;
    
    
    //VERIFICADOR
    public static function verificador(){
        try{
            $retorno = null;
            //iniciar tratando as vendas que falharam
            
            TTransaction::open(static::DATABASE);
            $vendasErro = Vendaerror::where('status','!=','tratado')->load();
            foreach($vendasErro as $vendaErro){
                $motivo = new MotivoContingencia ($vendaErro->motivo);
                if($motivo->reemissao){
                    $retorno = self::seletorVenda($vendaErro,$motivo);
                }else{
                    if($vendaErro->tentativa >3){
                        ApiManager::sendMessage("Reemissão da venda: $vendaErro->n_venda \n erro: implementação para o erro nao criada");
                    }
                }
            }
            TTransaction::close();
            return $retorno;
        }catch(Exception $e){
            ApiManager::sendMessage('Verificador de erros falhou: '.$e->getMessage());
            }
    }
    //SELETOR
    public function seletorVenda($vendaErro,$motivo){
        $retorno = null;
        switch($motivo->id){
            case 625: //reemissão
            case 626:
            case 627:
            case 628:
            case 629:
            case 630:
            case 631:
            case 748:
            case 756:
            case 757:
            case 758:
            case 681:
            case 711:
            case 730:
            case 759:
            case 760:
            case 634:
            case 633:
            case 632:
            case 1042:
            case 1035:
                $retorno = self::tratadorA($vendaErro);
                break;
            case 747: //pagamento menor que o total de produtos
            case 806:
                $retorno = self::tratadorB($vendaErro);
                break;
            default:
                $motivo->reemissao = 0;
                $motivo->store();
                break;
        }
        return $retorno;
    }
    public function tratadorA($vendaErro){
        $retorno = null;
        TTransaction::open('vendas_base');
        $vendas = Venda::where('n_venda','=',$vendaErro->n_venda)->load();
        TTransaction::close();
            if($vendas){
                foreach($vendas as $venda){
                    $is_fiscal = $venda->forma_pagamento != 'Dinheiro' && $venda->fiscal == 'T'?true:false;
                    if(!$is_fiscal){
                        $envio                  = array();
                        $envio['usuario']       = 'botCron - tratadorA';
                        $envio['origem']        = 'TarefaCrontabServidor';
                        $envio['id_venda_pdv']  = $vendaErro->id_venda;
                        $envio['id']            = $vendaErro->n_venda;
                        $nvio['fiscal']         = 0;
                        $retorno                = VendaService::vendaManual($envio);
                    }
                }
                $vendaErro->status      = 'tratado';
                $vendaErro->tentativa   += 1;
                $vendaErro->store();
            }else{
                $envio                  = array();
                $envio['usuario']       = 'botCron - tratadorA';
                $envio['origem']        = 'TarefaCrontabServidor';
                $envio['id_venda_pdv']  = $vendaErro->id_venda;
                $envio['id']            = $vendaErro->n_venda;
                $nvio['fiscal']         = 0;
                $retorno                = VendaService::vendaManual($envio); 
                $vendaErro->status      = 'tratado';
                $vendaErro->tentativa   += 1;
                $vendaErro->store();
            }
        return $retorno;
    }
    public function TratadorB($vendaErro){
        try{
            TTransaction::open('vendas_base');
            $vendas = Venda::where('n_venda','=',$vendaErro->n_venda)->load();
            if($vendas){
                $venda = $vendas[0];
                $vendaPagamentos = VendaPagamento::where('venda_id','=',$venda->id)->load();
                if($vendaPagamentos){
                    $vendaPagamento             = $vendaPagamentos[0];
                    $vendaPagamento->valor_pgto += 0.08;
                    $DEBUG = $DEBUG."\n".$vendaPagamento->valor_pgto;
                    $vendaPagamento->store();
                    sleep(3);
                    $configuracao           =array();
                    $configuracao['fiscal'] = 0;
                    $configuracao['origem'] = 'botCrontab - tratadorB';
                    $configuracao['usuario']= TSession::getValue('username');
                    $configuracao['classe'] = 'Contingenciaservice';
                    $retorno                = VendaService::isFiscal($venda,$configuracao);
                    $vendaErro->status      = 'tratado';
                    $vendaErro->tentativa   += 1;
                }
            }
            TTransaction::close();
            $vendaErro->store();
        }catch(Exception $e){
            ApiManager::sendMessage('TratadorB falhou em contingência: '.$e->getMessage());
        }
    }
    
    
    public function EnviarGsheets($fila){
        //configurando variaveis
        $numero_venda       = isset($fila['numero_venda'])  ?strval($fila['numero_venda'])      :'';
        $data               = isset($fila['data'])          ?$fila['data']                      :'';
        $motivo             = isset($fila['motivo'])        ?$fila['motivo']                    :'';
        $id_venda           = isset($fila['id_venda'])      ?strval($fila['id_venda'])          :'';
        $cliente            = ApiManager::getGoogleClient();
        $service            = new Google_Service_Sheets($cliente);
        $objectsJson        =[];
        $spreadsheetId      = '1YZ04FxrPA9Zl_bUwwMlPp_oM4Cxn5OMf3wjJTERbDpM';  // -> link sincronizador de teste
        $range              = "Vendas!A:c";
        $arrayTemp          = [];                  
        $arrayTemp[]        = $numero_venda;
        $arrayTemp[]        = $id_venda;
        $arrayTemp[]        = $data;
        $arrayTemp[]        = $motivo;
        $objectsJson[]      = $arrayTemp;
        $Sjson              = $objectsJson;
        $body               = new Google_Service_Sheets_ValueRange(['values' => $Sjson]);
        $params             = ['valueInputOption' => 'RAW'];
        $insert             = ["insertDataOption"=>"INSERT_ROWS"];
        $result             = $service -> spreadsheets_values->append(
            $spreadsheetId,
            $range,
            $body,
            $params,
            $insert
            );
    }
}
