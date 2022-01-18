<?php

class ValeTransporte extends TRecord
{
    const TABLENAME  = 'vale_transporte';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $colaborador;
    private $fk_dias_uteis;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('valor');
        parent::addAttribute('quantidade');
        parent::addAttribute('colaborador_id');
        parent::addAttribute('dias_uteis');
            
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
    /**
     * Method set_mes
     * Sample of usage: $var->mes = $object;
     * @param $object Instance of Mes
     */
    public function set_fk_dias_uteis(Mes $object)
    {
        $this->fk_dias_uteis = $object;
        $this->dias_uteis = $object->id;
    }

    /**
     * Method get_fk_dias_uteis
     * Sample of usage: $var->fk_dias_uteis->attribute;
     * @returns Mes instance
     */
    public function get_fk_dias_uteis()
    {
    
        // loads the associated object
        if (empty($this->fk_dias_uteis))
            $this->fk_dias_uteis = new Mes($this->dias_uteis);
    
        // returns the associated object
        return $this->fk_dias_uteis;
    }

    
}

