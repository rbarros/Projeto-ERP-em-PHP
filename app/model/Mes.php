<?php

class Mes extends TRecord
{
    const TABLENAME  = 'mes';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('loja');
        parent::addAttribute('descricao');
        parent::addAttribute('mes');
        parent::addAttribute('ano');
        parent::addAttribute('qtd_dias_uteis');
        parent::addAttribute('cidade');
        parent::addAttribute('valor_passagem');
            
    }

    /**
     * Method getValeTransportes
     */
    public function getValeTransportes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('dias_uteis', '=', $this->id));
        return ValeTransporte::getObjects( $criteria );
    }

    public function set_vale_transporte_colaborador_to_string($vale_transporte_colaborador_to_string)
    {
        if(is_array($vale_transporte_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $vale_transporte_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->vale_transporte_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->vale_transporte_colaborador_to_string = $vale_transporte_colaborador_to_string;
        }

        $this->vdata['vale_transporte_colaborador_to_string'] = $this->vale_transporte_colaborador_to_string;
    }

    public function get_vale_transporte_colaborador_to_string()
    {
        if(!empty($this->vale_transporte_colaborador_to_string))
        {
            return $this->vale_transporte_colaborador_to_string;
        }
    
        $values = ValeTransporte::where('dias_uteis', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
        return implode(', ', $values);
    }

    public function set_vale_transporte_fk_dias_uteis_to_string($vale_transporte_fk_dias_uteis_to_string)
    {
        if(is_array($vale_transporte_fk_dias_uteis_to_string))
        {
            $values = Mes::where('id', 'in', $vale_transporte_fk_dias_uteis_to_string)->getIndexedArray('descricao', 'descricao');
            $this->vale_transporte_fk_dias_uteis_to_string = implode(', ', $values);
        }
        else
        {
            $this->vale_transporte_fk_dias_uteis_to_string = $vale_transporte_fk_dias_uteis_to_string;
        }

        $this->vdata['vale_transporte_fk_dias_uteis_to_string'] = $this->vale_transporte_fk_dias_uteis_to_string;
    }

    public function get_vale_transporte_fk_dias_uteis_to_string()
    {
        if(!empty($this->vale_transporte_fk_dias_uteis_to_string))
        {
            return $this->vale_transporte_fk_dias_uteis_to_string;
        }
    
        $values = ValeTransporte::where('dias_uteis', '=', $this->id)->getIndexedArray('dias_uteis','{fk_dias_uteis->descricao}');
        return implode(', ', $values);
    }

    
}

