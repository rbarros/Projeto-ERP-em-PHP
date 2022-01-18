<?php

class TipoDocumento extends TRecord
{
    const TABLENAME  = 'tipo_documento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const cpf = '1';
    const cnh = '2';
    const ctps = '3';
    const CCasamento = '4';
    const TEleitor = '5';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo');
            
    }

    /**
     * Method getDocumentos
     */
    public function getDocumentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipo_documento', '=', $this->id));
        return Documento::getObjects( $criteria );
    }

    public function set_documento_colaborador_to_string($documento_colaborador_to_string)
    {
        if(is_array($documento_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $documento_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->documento_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->documento_colaborador_to_string = $documento_colaborador_to_string;
        }

        $this->vdata['documento_colaborador_to_string'] = $this->documento_colaborador_to_string;
    }

    public function get_documento_colaborador_to_string()
    {
        if(!empty($this->documento_colaborador_to_string))
        {
            return $this->documento_colaborador_to_string;
        }
    
        $values = Documento::where('tipo_documento', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
        return implode(', ', $values);
    }

    public function set_documento_fk_tipo_documento_to_string($documento_fk_tipo_documento_to_string)
    {
        if(is_array($documento_fk_tipo_documento_to_string))
        {
            $values = TipoDocumento::where('id', 'in', $documento_fk_tipo_documento_to_string)->getIndexedArray('id', 'id');
            $this->documento_fk_tipo_documento_to_string = implode(', ', $values);
        }
        else
        {
            $this->documento_fk_tipo_documento_to_string = $documento_fk_tipo_documento_to_string;
        }

        $this->vdata['documento_fk_tipo_documento_to_string'] = $this->documento_fk_tipo_documento_to_string;
    }

    public function get_documento_fk_tipo_documento_to_string()
    {
        if(!empty($this->documento_fk_tipo_documento_to_string))
        {
            return $this->documento_fk_tipo_documento_to_string;
        }
    
        $values = Documento::where('tipo_documento', '=', $this->id)->getIndexedArray('tipo_documento','{fk_tipo_documento->id}');
        return implode(', ', $values);
    }

    
}

