<?php

class Import extends TRecord
{
    const TABLENAME  = 'Import';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('link_sheet');
        parent::addAttribute('nome_tabela');
            
    }

    
}

