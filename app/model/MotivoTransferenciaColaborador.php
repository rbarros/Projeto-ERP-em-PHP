<?php

class MotivoTransferenciaColaborador extends TRecord
{
    const TABLENAME  = 'motivo_transferencia_colaborador';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('motivo');
            
    }

    /**
     * Method getTransferenciaColaboradors
     */
    public function getTransferenciaColaboradors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('motivo_transferencia', '=', $this->id));
        return TransferenciaColaborador::getObjects( $criteria );
    }

    public function set_transferencia_colaborador_colaborador_to_string($transferencia_colaborador_colaborador_to_string)
    {
        if(is_array($transferencia_colaborador_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $transferencia_colaborador_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->transferencia_colaborador_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->transferencia_colaborador_colaborador_to_string = $transferencia_colaborador_colaborador_to_string;
        }

        $this->vdata['transferencia_colaborador_colaborador_to_string'] = $this->transferencia_colaborador_colaborador_to_string;
    }

    public function get_transferencia_colaborador_colaborador_to_string()
    {
        if(!empty($this->transferencia_colaborador_colaborador_to_string))
        {
            return $this->transferencia_colaborador_colaborador_to_string;
        }
    
        $values = TransferenciaColaborador::where('motivo_transferencia', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
        return implode(', ', $values);
    }

    public function set_transferencia_colaborador_fk_loja_origem_to_string($transferencia_colaborador_fk_loja_origem_to_string)
    {
        if(is_array($transferencia_colaborador_fk_loja_origem_to_string))
        {
            $values = Loja::where('id', 'in', $transferencia_colaborador_fk_loja_origem_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->transferencia_colaborador_fk_loja_origem_to_string = implode(', ', $values);
        }
        else
        {
            $this->transferencia_colaborador_fk_loja_origem_to_string = $transferencia_colaborador_fk_loja_origem_to_string;
        }

        $this->vdata['transferencia_colaborador_fk_loja_origem_to_string'] = $this->transferencia_colaborador_fk_loja_origem_to_string;
    }

    public function get_transferencia_colaborador_fk_loja_origem_to_string()
    {
        if(!empty($this->transferencia_colaborador_fk_loja_origem_to_string))
        {
            return $this->transferencia_colaborador_fk_loja_origem_to_string;
        }
    
        $values = TransferenciaColaborador::where('motivo_transferencia', '=', $this->id)->getIndexedArray('loja_origem','{fk_loja_origem->razao_social}');
        return implode(', ', $values);
    }

    public function set_transferencia_colaborador_fk_loja_destino_to_string($transferencia_colaborador_fk_loja_destino_to_string)
    {
        if(is_array($transferencia_colaborador_fk_loja_destino_to_string))
        {
            $values = Loja::where('id', 'in', $transferencia_colaborador_fk_loja_destino_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->transferencia_colaborador_fk_loja_destino_to_string = implode(', ', $values);
        }
        else
        {
            $this->transferencia_colaborador_fk_loja_destino_to_string = $transferencia_colaborador_fk_loja_destino_to_string;
        }

        $this->vdata['transferencia_colaborador_fk_loja_destino_to_string'] = $this->transferencia_colaborador_fk_loja_destino_to_string;
    }

    public function get_transferencia_colaborador_fk_loja_destino_to_string()
    {
        if(!empty($this->transferencia_colaborador_fk_loja_destino_to_string))
        {
            return $this->transferencia_colaborador_fk_loja_destino_to_string;
        }
    
        $values = TransferenciaColaborador::where('motivo_transferencia', '=', $this->id)->getIndexedArray('loja_destino','{fk_loja_destino->razao_social}');
        return implode(', ', $values);
    }

    public function set_transferencia_colaborador_fk_motivo_transferencia_to_string($transferencia_colaborador_fk_motivo_transferencia_to_string)
    {
        if(is_array($transferencia_colaborador_fk_motivo_transferencia_to_string))
        {
            $values = MotivoTransferenciaColaborador::where('id', 'in', $transferencia_colaborador_fk_motivo_transferencia_to_string)->getIndexedArray('id', 'id');
            $this->transferencia_colaborador_fk_motivo_transferencia_to_string = implode(', ', $values);
        }
        else
        {
            $this->transferencia_colaborador_fk_motivo_transferencia_to_string = $transferencia_colaborador_fk_motivo_transferencia_to_string;
        }

        $this->vdata['transferencia_colaborador_fk_motivo_transferencia_to_string'] = $this->transferencia_colaborador_fk_motivo_transferencia_to_string;
    }

    public function get_transferencia_colaborador_fk_motivo_transferencia_to_string()
    {
        if(!empty($this->transferencia_colaborador_fk_motivo_transferencia_to_string))
        {
            return $this->transferencia_colaborador_fk_motivo_transferencia_to_string;
        }
    
        $values = TransferenciaColaborador::where('motivo_transferencia', '=', $this->id)->getIndexedArray('motivo_transferencia','{fk_motivo_transferencia->id}');
        return implode(', ', $values);
    }

    
}

