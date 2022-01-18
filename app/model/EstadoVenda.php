<?php

class EstadoVenda extends TRecord
{
    const TABLENAME  = 'estado_venda';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const nova = '1';
    const finalizada = '2';
    const cancelada = '3';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
            
    }

    
}

