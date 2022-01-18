<?php

class MotivoContingencia extends TRecord
{
    const TABLENAME  = 'motivo_contingencia';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('motivo');
        parent::addAttribute('reemissao');
        parent::addAttribute('tipo');
        parent::addAttribute('tratamento');
            
    }

    /**
     * Method getRetornoErrors
     */
    public function getRetornoErrors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('motivo', '=', $this->id));
        return RetornoError::getObjects( $criteria );
    }
    /**
     * Method getVendaerrors
     */
    public function getVendaerrors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('motivo', '=', $this->id));
        return Vendaerror::getObjects( $criteria );
    }

    public function set_retorno_error_fk_motivo_to_string($retorno_error_fk_motivo_to_string)
    {
        if(is_array($retorno_error_fk_motivo_to_string))
        {
            $values = MotivoContingencia::where('id', 'in', $retorno_error_fk_motivo_to_string)->getIndexedArray('id', 'id');
            $this->retorno_error_fk_motivo_to_string = implode(', ', $values);
        }
        else
        {
            $this->retorno_error_fk_motivo_to_string = $retorno_error_fk_motivo_to_string;
        }

        $this->vdata['retorno_error_fk_motivo_to_string'] = $this->retorno_error_fk_motivo_to_string;
    }

    public function get_retorno_error_fk_motivo_to_string()
    {
        if(!empty($this->retorno_error_fk_motivo_to_string))
        {
            return $this->retorno_error_fk_motivo_to_string;
        }
    
        $values = RetornoError::where('motivo', '=', $this->id)->getIndexedArray('motivo','{fk_motivo->id}');
        return implode(', ', $values);
    }

    public function set_vendaError_fk_motivo_to_string($vendaError_fk_motivo_to_string)
    {
        if(is_array($vendaError_fk_motivo_to_string))
        {
            $values = MotivoContingencia::where('id', 'in', $vendaError_fk_motivo_to_string)->getIndexedArray('id', 'id');
            $this->vendaError_fk_motivo_to_string = implode(', ', $values);
        }
        else
        {
            $this->vendaError_fk_motivo_to_string = $vendaError_fk_motivo_to_string;
        }

        $this->vdata['vendaError_fk_motivo_to_string'] = $this->vendaError_fk_motivo_to_string;
    }

    public function get_vendaError_fk_motivo_to_string()
    {
        if(!empty($this->vendaError_fk_motivo_to_string))
        {
            return $this->vendaError_fk_motivo_to_string;
        }
    
        $values = Vendaerror::where('motivo', '=', $this->id)->getIndexedArray('motivo','{fk_motivo->id}');
        return implode(', ', $values);
    }

    
}

