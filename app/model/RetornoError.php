<?php

class RetornoError extends TRecord
{
    const TABLENAME  = 'retorno_error';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $fk_motivo;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_retorno');
        parent::addAttribute('id_empresa');
        parent::addAttribute('motivo');
        parent::addAttribute('data_retorno');
        parent::addAttribute('status');
        parent::addAttribute('tentativas');
            
    }

    /**
     * Method set_motivo_contingencia
     * Sample of usage: $var->motivo_contingencia = $object;
     * @param $object Instance of MotivoContingencia
     */
    public function set_fk_motivo(MotivoContingencia $object)
    {
        $this->fk_motivo = $object;
        $this->motivo = $object->id;
    }

    /**
     * Method get_fk_motivo
     * Sample of usage: $var->fk_motivo->attribute;
     * @returns MotivoContingencia instance
     */
    public function get_fk_motivo()
    {
    
        // loads the associated object
        if (empty($this->fk_motivo))
            $this->fk_motivo = new MotivoContingencia($this->motivo);
    
        // returns the associated object
        return $this->fk_motivo;
    }

    
}

