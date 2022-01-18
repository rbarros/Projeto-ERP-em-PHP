<?php

class NfcePagamento extends TRecord
{
    const TABLENAME  = 'nfce_pagamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $nfce;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('prazo');
        parent::addAttribute('pedido_id');
        parent::addAttribute('nfce_id');
        parent::addAttribute('tipo');
        parent::addAttribute('valor');
            
    }

    /**
     * Method set_nfce
     * Sample of usage: $var->nfce = $object;
     * @param $object Instance of Nfce
     */
    public function set_nfce(Nfce $object)
    {
        $this->nfce = $object;
        $this->nfce_id = $object->id;
    }

    /**
     * Method get_nfce
     * Sample of usage: $var->nfce->attribute;
     * @returns Nfce instance
     */
    public function get_nfce()
    {
    
        // loads the associated object
        if (empty($this->nfce))
            $this->nfce = new Nfce($this->nfce_id);
    
        // returns the associated object
        return $this->nfce;
    }

    
}

