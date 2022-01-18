<?php

class AutoGerarNfce
{
    const DATABASE              = 'base_banco';
    const AMBIENTE_EMISSAO      = 'Producao';
    const PRESENCA_CONSUMIDOR   = 'OperacaoPresencial';
    const INFORMACOES_ADICIONAIS= 'Documento emitido por ME ou EPP optante pelo Simples Nacional.';
    
    public static function onAutogerar($param=null)
    {
        try{
        TTransaction::open(static::DATABASE);
        $vendas = VEnda::getObjects();
        Chat::sendMessage("----INICIO DO BOT DE AUTO EMISSÃO DE NFCE----");
        foreach($vendas as $venda){
            //tratamento do mÊstrtotime
            $mes_venda = date( 'm', strtotime( $venda->dt_venda ) );
            $mes_venda = str_replace(0,"",$mes_venda);
            $ano_venda = date( 'y', strtotime( $venda->dt_venda ) );
            $ano_venda = '20'.$ano_venda;
            $hoje = getdate();
            $mes_atual  = $hoje['mon'];
            $ano_atual  = $hoje['year'];
            if($mes_venda == $mes_atual && $ano_venda == $ano_atual){
                if($venda->forma_pagamento != "Dinheiro"){
                    $nfces = NfceRequest::where('id_venda','=',$venda->id_interno)->load();
                    if(!$nfces){
                        $woocommerce =  WooConect::getClient();
                        $dados = $woocommerce->get("orders?search=$id_woo");
                        $dado = $dados[0];
                        $id_venda = $dado->id;
                        if($dado->number == $venda->id_externo){
                            Chat::sendMessage("|BOT EMISSÃO NFCE|emitindo a Nfc-e da venda: {$venda->id_interno}\n realizada no dia: {$venda->dt_venda} \n em :{$venda->forma_pagamento}");
                            VendaService::emitirNfce($venda,$id_venda);
                        }else{
                            Chat::sendMessage("|BOT EMISSÃO NFCE|venda: {$venda->id_interno}\n NÃO LOCALIZADA NO PDV DATA DA VENDA:$venda->dt_venda"); 
                        }
                    }else{
                        $nfce = $nfces[0];
                        if($nfce->status == "Negada"){
                            $woocommerce =  WooConect::getClient();
                            $dados = $woocommerce->get("orders?search=$id_woo");
                            $dado = $dados[0];
                            $id_venda = $dado->id;
                            if($dado->number == $venda->id_externo){
                                Chat::sendMessage("|BOT EMISSÃO NFCE|emitindo a Nfc-e da venda: {$venda->id_interno}\n realizada no dia: {$venda->dt_venda} \n em :{$venda->forma_pagamento}");
                                VendaService::emitirNfce($venda,$id_venda);
                            }else{
                                Chat::sendMessage("|BOT EMISSÃO NFCE|venda: {$venda->id_interno}\n NÃO LOCALIZADA NO PDV DATA DA VENDA:$venda->dt_venda"); 
                            }
                                VendaService::emitirNfce($venda);
                            }
                    }
                        //if comparativo se não houver NFCE
                }//if se dinheiro
            }//if comparativo de mes de emissão
        }//foreachvendas
        TTransaction::close();
        }catch(Exception $e){
            Chat::sendMessage("Classe: VendaService.php(produção)Metodo: EmitirNfce() - venda {$venda->id_interno} apresentou erro ao gerar NFC-e: ".$e->getMessage());
            return $e->getMessage();    
        }
    }
}
