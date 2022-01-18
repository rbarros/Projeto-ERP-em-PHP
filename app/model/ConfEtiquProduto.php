<?php

class ConfEtiquProduto extends TRecord
{
    const TABLENAME  = 'conf_etiqu_produto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('leftMargin');
        parent::addAttribute('topMargin');
        parent::addAttribute('labelWidth');
        parent::addAttribute('labelHeight');
        parent::addAttribute('spaceBetween');
        parent::addAttribute('rowsPerPage');
        parent::addAttribute('colsPerPage');
        parent::addAttribute('fontSize');
        parent::addAttribute('barcodeHeight');
        parent::addAttribute('imageMargin');
        parent::addAttribute('barcodeMethod');
        parent::addAttribute('nome');
            
    }

    
}

