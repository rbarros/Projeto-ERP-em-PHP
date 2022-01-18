<?php

class Ncm extends TRecord
{
    const TABLENAME  = 'Ncm';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('n_ncm');
        parent::addAttribute('cest');
        parent::addAttribute('id_woo_ncm');
            
    }

    
}

