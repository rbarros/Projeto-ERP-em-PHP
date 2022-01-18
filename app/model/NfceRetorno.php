<?php

class NfceRetorno extends TRecord
{
    const TABLENAME  = 'nfce_retorno';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_externo');
        parent::addAttribute('tipo');
        parent::addAttribute('status');
        parent::addAttribute('motivoStatus');
        parent::addAttribute('ambienteEmissao');
        parent::addAttribute('enviadaPorEmail');
        parent::addAttribute('dataCriacao');
        parent::addAttribute('dataUltimaAlteracao');
        parent::addAttribute('forcarEmissaoContingencia');
        parent::addAttribute('numero');
        parent::addAttribute('serie');
        parent::addAttribute('dataEmissao');
        parent::addAttribute('chaveAcesso');
        parent::addAttribute('dataAutorizacao');
        parent::addAttribute('linkDanfe');
        parent::addAttribute('linkDownloadXml');
        parent::addAttribute('lnkConsultaPorChaveAcesso');
        parent::addAttribute('emitidaEmContingencia');
        parent::addAttribute('empresaId');
        parent::addAttribute('numeroProtocolo');
        parent::addAttribute('digestValue');
        parent::addAttribute('valorTotal');
        parent::addAttribute('informacoesAdicionais');
        parent::addAttribute('qrCode');
            
    }

    
}

