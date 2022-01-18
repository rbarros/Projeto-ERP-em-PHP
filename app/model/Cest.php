<?php

class Cest extends TRecord
{
    const TABLENAME  = 'Cest';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('n_cest');
        parent::addAttribute('descricao');
        parent::addAttribute('id_woo_cst');
            
    }

    
}

