<?php

class CompraFuncionario extends TRecord
{
    const TABLENAME  = 'compra_funcionario';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $colaborador;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('colaborador_id');
        parent::addAttribute('venda_id');
        parent::addAttribute('dt_venda');
        parent::addAttribute('valor_venda');
        parent::addAttribute('loja_venda');
        parent::addAttribute('quitado');
            
    }

    /**
     * Method set_colaborador
     * Sample of usage: $var->colaborador = $object;
     * @param $object Instance of Colaborador
     */
    public function set_colaborador(Colaborador $object)
    {
        $this->colaborador = $object;
        $this->colaborador_id = $object->id;
    }

    /**
     * Method get_colaborador
     * Sample of usage: $var->colaborador->attribute;
     * @returns Colaborador instance
     */
    public function get_colaborador()
    {
    
        // loads the associated object
        if (empty($this->colaborador))
            $this->colaborador = new Colaborador($this->colaborador_id);
    
        // returns the associated object
        return $this->colaborador;
    }

    
}

