<?php

class NfceRequestAlt extends TRecord
{
    const TABLENAME  = 'nfce_request_alt';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('ambienteEmissao');
        parent::addAttribute('informacoesAdicionais');
        parent::addAttribute('presencaConsumidor');
        parent::addAttribute('numVenda');
        parent::addAttribute('status');
        parent::addAttribute('n_nfce');
        parent::addAttribute('link_cupom');
        parent::addAttribute('id_loja');
        parent::addAttribute('retorno_nfce');
        parent::addAttribute('venda_id');
        parent::addAttribute('dt_nfce');
    
    }

}

