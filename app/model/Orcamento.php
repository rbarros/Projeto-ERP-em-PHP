<?php

class Orcamento extends TRecord
{
    const TABLENAME  = 'orcamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $estado_orcamento;
    private $cliente;
    private $vendedor;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_id');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('estado_orcamento_id');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('dt_orcamento');
        parent::addAttribute('obs');
        parent::addAttribute('frete');
        parent::addAttribute('valor_total');
            
    }

    /**
     * Method set_estado_orcamento
     * Sample of usage: $var->estado_orcamento = $object;
     * @param $object Instance of EstadoOrcamento
     */
    public function set_estado_orcamento(EstadoOrcamento $object)
    {
        $this->estado_orcamento = $object;
        $this->estado_orcamento_id = $object->id;
    }

    /**
     * Method get_estado_orcamento
     * Sample of usage: $var->estado_orcamento->attribute;
     * @returns EstadoOrcamento instance
     */
    public function get_estado_orcamento()
    {
    
        // loads the associated object
        if (empty($this->estado_orcamento))
            $this->estado_orcamento = new EstadoOrcamento($this->estado_orcamento_id);
    
        // returns the associated object
        return $this->estado_orcamento;
    }
    /**
     * Method set_pessoa
     * Sample of usage: $var->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_cliente(Pessoa $object)
    {
        $this->cliente = $object;
        $this->cliente_id = $object->id;
    }

    /**
     * Method get_cliente
     * Sample of usage: $var->cliente->attribute;
     * @returns Pessoa instance
     */
    public function get_cliente()
    {
    
        // loads the associated object
        if (empty($this->cliente))
            $this->cliente = new Pessoa($this->cliente_id);
    
        // returns the associated object
        return $this->cliente;
    }
    /**
     * Method set_pessoa
     * Sample of usage: $var->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_vendedor(Pessoa $object)
    {
        $this->vendedor = $object;
        $this->vendedor_id = $object->id;
    }

    /**
     * Method get_vendedor
     * Sample of usage: $var->vendedor->attribute;
     * @returns Pessoa instance
     */
    public function get_vendedor()
    {
    
        // loads the associated object
        if (empty($this->vendedor))
            $this->vendedor = new Pessoa($this->vendedor_id);
    
        // returns the associated object
        return $this->vendedor;
    }

    /**
     * Method getOrcamentoItems
     */
    public function getOrcamentoItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('orcamento_id', '=', $this->id));
        return OrcamentoItem::getObjects( $criteria );
    }

    public function set_orcamento_item_orcamento_to_string($orcamento_item_orcamento_to_string)
    {
        if(is_array($orcamento_item_orcamento_to_string))
        {
            $values = Orcamento::where('id', 'in', $orcamento_item_orcamento_to_string)->getIndexedArray('id', 'id');
            $this->orcamento_item_orcamento_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_item_orcamento_to_string = $orcamento_item_orcamento_to_string;
        }

        $this->vdata['orcamento_item_orcamento_to_string'] = $this->orcamento_item_orcamento_to_string;
    }

    public function get_orcamento_item_orcamento_to_string()
    {
        if(!empty($this->orcamento_item_orcamento_to_string))
        {
            return $this->orcamento_item_orcamento_to_string;
        }
    
        $values = OrcamentoItem::where('orcamento_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->id}');
        return implode(', ', $values);
    }

    public function set_orcamento_item_produto_to_string($orcamento_item_produto_to_string)
    {
        if(is_array($orcamento_item_produto_to_string))
        {
            $values = Produto::where('id', 'in', $orcamento_item_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->orcamento_item_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->orcamento_item_produto_to_string = $orcamento_item_produto_to_string;
        }

        $this->vdata['orcamento_item_produto_to_string'] = $this->orcamento_item_produto_to_string;
    }

    public function get_orcamento_item_produto_to_string()
    {
        if(!empty($this->orcamento_item_produto_to_string))
        {
            return $this->orcamento_item_produto_to_string;
        }
    
        $values = OrcamentoItem::where('orcamento_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    
}

