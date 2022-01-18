<?php

class VendaPagamento extends TRecord
{
    const TABLENAME  = 'venda_pagamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $venda;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('metodo_pgto');
        parent::addAttribute('valor_pgto');
        parent::addAttribute('venda_id');
        parent::addAttribute('dt_venda');
        parent::addAttribute('id_loja');
            
    }

    /**
     * Method set_venda
     * Sample of usage: $var->venda = $object;
     * @param $object Instance of Venda
     */
    public function set_venda(Venda $object)
    {
        $this->venda = $object;
        $this->venda_id = $object->id;
    }

    /**
     * Method get_venda
     * Sample of usage: $var->venda->attribute;
     * @returns Venda instance
     */
    public function get_venda()
    {
    
        // loads the associated object
        if (empty($this->venda))
            $this->venda = new Venda($this->venda_id);
    
        // returns the associated object
        return $this->venda;
    }

    
}

