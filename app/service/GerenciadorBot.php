<?php

class GerenciadorBot
{
    //classe em desuso
    public static function onSincronizar()
    {
        try{
            Chat::sendMessage("-----INICIANDO OS BOTS DE SINCRONIZAÇÃO-----");
            SincronizarVendas::onSincronizar();
            
            Chat::sendMessage("-----AGUARDANDO 5 MINUTOS PARA CHAMAR O BOT DE AUTO EMISSÃO");
            sleep(500);
            AutoGerarNfce::onAutogerar();
            
            Chat::sendMessage("-----AGUARDANDO 5 MINUTOS PARA CHAMAR O BOT DE SINCRONIZAÇÃO DE XML");
            sleep(500);
            SincronizarXml::onSincronizar();
            
            Chat::sendMessage("-----AGUARDANDO 5 MINUTOS PARA CHAMAR O BOT DE AUTO DOWNLOAD DE XML");
            sleep(500);
            BaixarXml::onBaixar();
            
        }catch(Exception $e){
            Chat::sendMessage("ERRO AO SINCRONIZAR BOTS:".$e->getMessage());
            return $e->getMessage();   
        }
    }
}
