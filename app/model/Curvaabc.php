<?php

class Curvaabc extends TRecord
{
    const TABLENAME  = 'curvaABC';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome_produto');
        parent::addAttribute('sku');
        parent::addAttribute('produto_id');
        parent::addAttribute('deposito_id');
        parent::addAttribute('curva');
        parent::addAttribute('dtAtualizacao');
        parent::addAttribute('porcentagem');
        parent::addAttribute('valor');
        parent::addAttribute('posicao');
    
    }

}

