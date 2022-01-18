<?php

class NfceItem extends TRecord
{
    const TABLENAME  = 'nfce_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $nfce;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cfop');
        parent::addAttribute('codigo');
        parent::addAttribute('descricao');
        parent::addAttribute('ncm');
        parent::addAttribute('cest');
        parent::addAttribute('quantidade');
        parent::addAttribute('unidadeMedida');
        parent::addAttribute('valorUnitario');
        parent::addAttribute('percentual');
        parent::addAttribute('situacaoTributaria');
        parent::addAttribute('origem');
        parent::addAttribute('nfce_id');
            
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

