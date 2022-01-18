<?php

class EstadoOrcamento extends TRecord
{
    const TABLENAME  = 'estado_orcamento';
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
     * Method getOrcamentos
     */
    public function getOrcamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estado_orcamento_id', '=', $this->id));
        return Orcamento::getObjects( $criteria );
    }

    public function set_orcamento_cliente_to_string($orcamento_cliente_to_string)
    {
        if(is_array($orcamento_cliente_to_string))
        {
            $values = Pessoa::where('id', 'in', $orcamento_cliente_to_string)->getIndexedArray('nome', 'nome');
            $this->orcamento_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_cliente_to_string = $orcamento_cliente_to_string;
        }

        $this->vdata['orcamento_cliente_to_string'] = $this->orcamento_cliente_to_string;
    }

    public function get_orcamento_cliente_to_string()
    {
        if(!empty($this->orcamento_cliente_to_string))
        {
            return $this->orcamento_cliente_to_string;
        }
    
        $values = Orcamento::where('estado_orcamento_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->nome}');
        return implode(', ', $values);
    }

    public function set_orcamento_vendedor_to_string($orcamento_vendedor_to_string)
    {
        if(is_array($orcamento_vendedor_to_string))
        {
            $values = Pessoa::where('id', 'in', $orcamento_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->orcamento_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_vendedor_to_string = $orcamento_vendedor_to_string;
        }

        $this->vdata['orcamento_vendedor_to_string'] = $this->orcamento_vendedor_to_string;
    }

    public function get_orcamento_vendedor_to_string()
    {
        if(!empty($this->orcamento_vendedor_to_string))
        {
            return $this->orcamento_vendedor_to_string;
        }
    
        $values = Orcamento::where('estado_orcamento_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_orcamento_estado_orcamento_to_string($orcamento_estado_orcamento_to_string)
    {
        if(is_array($orcamento_estado_orcamento_to_string))
        {
            $values = EstadoOrcamento::where('id', 'in', $orcamento_estado_orcamento_to_string)->getIndexedArray('nome', 'nome');
            $this->orcamento_estado_orcamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_estado_orcamento_to_string = $orcamento_estado_orcamento_to_string;
        }

        $this->vdata['orcamento_estado_orcamento_to_string'] = $this->orcamento_estado_orcamento_to_string;
    }

    public function get_orcamento_estado_orcamento_to_string()
    {
        if(!empty($this->orcamento_estado_orcamento_to_string))
        {
            return $this->orcamento_estado_orcamento_to_string;
        }
    
        $values = Orcamento::where('estado_orcamento_id', '=', $this->id)->getIndexedArray('estado_orcamento_id','{estado_orcamento->nome}');
        return implode(', ', $values);
    }

    
}

