<?php

class TransferenciaColaborador extends TRecord
{
    const TABLENAME  = 'transferencia_colaborador';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $colaborador;
    private $fk_motivo_transferencia;
    private $fk_loja_origem;
    private $fk_loja_destino;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('colaborador_id');
        parent::addAttribute('loja_origem');
        parent::addAttribute('loja_destino');
        parent::addAttribute('dt_transferencia');
        parent::addAttribute('motivo_transferencia');
            
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
     * Method set_motivo_transferencia_colaborador
     * Sample of usage: $var->motivo_transferencia_colaborador = $object;
     * @param $object Instance of MotivoTransferenciaColaborador
     */
    public function set_fk_motivo_transferencia(MotivoTransferenciaColaborador $object)
    {
        $this->fk_motivo_transferencia = $object;
        $this->motivo_transferencia = $object->id;
    }

    /**
     * Method get_fk_motivo_transferencia
     * Sample of usage: $var->fk_motivo_transferencia->attribute;
     * @returns MotivoTransferenciaColaborador instance
     */
    public function get_fk_motivo_transferencia()
    {
    
        // loads the associated object
        if (empty($this->fk_motivo_transferencia))
            $this->fk_motivo_transferencia = new MotivoTransferenciaColaborador($this->motivo_transferencia);
    
        // returns the associated object
        return $this->fk_motivo_transferencia;
    }
    /**
     * Method set_loja
     * Sample of usage: $var->loja = $object;
     * @param $object Instance of Loja
     */
    public function set_fk_loja_origem(Loja $object)
    {
        $this->fk_loja_origem = $object;
        $this->loja_origem = $object->id;
    }

    /**
     * Method get_fk_loja_origem
     * Sample of usage: $var->fk_loja_origem->attribute;
     * @returns Loja instance
     */
    public function get_fk_loja_origem()
    {
    
        // loads the associated object
        if (empty($this->fk_loja_origem))
            $this->fk_loja_origem = new Loja($this->loja_origem);
    
        // returns the associated object
        return $this->fk_loja_origem;
    }
    /**
     * Method set_loja
     * Sample of usage: $var->loja = $object;
     * @param $object Instance of Loja
     */
    public function set_fk_loja_destino(Loja $object)
    {
        $this->fk_loja_destino = $object;
        $this->loja_destino = $object->id;
    }

    /**
     * Method get_fk_loja_destino
     * Sample of usage: $var->fk_loja_destino->attribute;
     * @returns Loja instance
     */
    public function get_fk_loja_destino()
    {
    
        // loads the associated object
        if (empty($this->fk_loja_destino))
            $this->fk_loja_destino = new Loja($this->loja_destino);
    
        // returns the associated object
        return $this->fk_loja_destino;
    }

    
}

