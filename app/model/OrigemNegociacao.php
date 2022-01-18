<?php

class OrigemNegociacao extends TRecord
{
    const TABLENAME  = 'origem_negociacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
            
    }

    /**
     * Method getNegociacaos
     */
    public function getNegociacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('origem_negociacao_id', '=', $this->id));
        return Negociacao::getObjects( $criteria );
    }

    public function set_negociacao_cliente_to_string($negociacao_cliente_to_string)
    {
        if(is_array($negociacao_cliente_to_string))
        {
            $values = Pessoa::where('id', 'in', $negociacao_cliente_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_cliente_to_string = $negociacao_cliente_to_string;
        }

        $this->vdata['negociacao_cliente_to_string'] = $this->negociacao_cliente_to_string;
    }

    public function get_negociacao_cliente_to_string()
    {
        if(!empty($this->negociacao_cliente_to_string))
        {
            return $this->negociacao_cliente_to_string;
        }
    
        $values = Negociacao::where('origem_negociacao_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->nome}');
        return implode(', ', $values);
    }

    public function set_negociacao_vendedor_to_string($negociacao_vendedor_to_string)
    {
        if(is_array($negociacao_vendedor_to_string))
        {
            $values = Pessoa::where('id', 'in', $negociacao_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_vendedor_to_string = $negociacao_vendedor_to_string;
        }

        $this->vdata['negociacao_vendedor_to_string'] = $this->negociacao_vendedor_to_string;
    }

    public function get_negociacao_vendedor_to_string()
    {
        if(!empty($this->negociacao_vendedor_to_string))
        {
            return $this->negociacao_vendedor_to_string;
        }
    
        $values = Negociacao::where('origem_negociacao_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_negociacao_origem_negociacao_to_string($negociacao_origem_negociacao_to_string)
    {
        if(is_array($negociacao_origem_negociacao_to_string))
        {
            $values = OrigemNegociacao::where('id', 'in', $negociacao_origem_negociacao_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_origem_negociacao_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_origem_negociacao_to_string = $negociacao_origem_negociacao_to_string;
        }

        $this->vdata['negociacao_origem_negociacao_to_string'] = $this->negociacao_origem_negociacao_to_string;
    }

    public function get_negociacao_origem_negociacao_to_string()
    {
        if(!empty($this->negociacao_origem_negociacao_to_string))
        {
            return $this->negociacao_origem_negociacao_to_string;
        }
    
        $values = Negociacao::where('origem_negociacao_id', '=', $this->id)->getIndexedArray('origem_negociacao_id','{origem_negociacao->nome}');
        return implode(', ', $values);
    }

    public function set_negociacao_tipo_negociacao_to_string($negociacao_tipo_negociacao_to_string)
    {
        if(is_array($negociacao_tipo_negociacao_to_string))
        {
            $values = TipoNegociacao::where('id', 'in', $negociacao_tipo_negociacao_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_tipo_negociacao_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_tipo_negociacao_to_string = $negociacao_tipo_negociacao_to_string;
        }

        $this->vdata['negociacao_tipo_negociacao_to_string'] = $this->negociacao_tipo_negociacao_to_string;
    }

    public function get_negociacao_tipo_negociacao_to_string()
    {
        if(!empty($this->negociacao_tipo_negociacao_to_string))
        {
            return $this->negociacao_tipo_negociacao_to_string;
        }
    
        $values = Negociacao::where('origem_negociacao_id', '=', $this->id)->getIndexedArray('tipo_negociacao_id','{tipo_negociacao->nome}');
        return implode(', ', $values);
    }

    public function set_negociacao_estado_negociacao_to_string($negociacao_estado_negociacao_to_string)
    {
        if(is_array($negociacao_estado_negociacao_to_string))
        {
            $values = EstadoNegociacao::where('id', 'in', $negociacao_estado_negociacao_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_estado_negociacao_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_estado_negociacao_to_string = $negociacao_estado_negociacao_to_string;
        }

        $this->vdata['negociacao_estado_negociacao_to_string'] = $this->negociacao_estado_negociacao_to_string;
    }

    public function get_negociacao_estado_negociacao_to_string()
    {
        if(!empty($this->negociacao_estado_negociacao_to_string))
        {
            return $this->negociacao_estado_negociacao_to_string;
        }
    
        $values = Negociacao::where('origem_negociacao_id', '=', $this->id)->getIndexedArray('estado_negociacao_id','{estado_negociacao->nome}');
        return implode(', ', $values);
    }

    
}

