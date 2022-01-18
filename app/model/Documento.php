<?php

class Documento extends TRecord
{
    const TABLENAME  = 'documento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $colaborador;
    private $fk_tipo_documento;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('dt_registro');
        parent::addAttribute('colaborador_id');
        parent::addAttribute('tipo_documento');
        parent::addAttribute('link_scan_documento');
            
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
     * Method set_tipo_documento
     * Sample of usage: $var->tipo_documento = $object;
     * @param $object Instance of TipoDocumento
     */
    public function set_fk_tipo_documento(TipoDocumento $object)
    {
        $this->fk_tipo_documento = $object;
        $this->tipo_documento = $object->id;
    }

    /**
     * Method get_fk_tipo_documento
     * Sample of usage: $var->fk_tipo_documento->attribute;
     * @returns TipoDocumento instance
     */
    public function get_fk_tipo_documento()
    {
    
        // loads the associated object
        if (empty($this->fk_tipo_documento))
            $this->fk_tipo_documento = new TipoDocumento($this->tipo_documento);
    
        // returns the associated object
        return $this->fk_tipo_documento;
    }

    
}

