<?php

class Negociacao extends TRecord
{
    const TABLENAME  = 'negociacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $tipo_negociacao;
    private $origem_negociacao;
    private $vendedor;
    private $cliente;
    private $estado_negociacao;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cliente_id');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('origem_negociacao_id');
        parent::addAttribute('tipo_negociacao_id');
        parent::addAttribute('estado_negociacao_id');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('dt_inicio_negociacao');
        parent::addAttribute('dt_fim_negociacao');
        parent::addAttribute('descricao');
        parent::addAttribute('obs');
            
    }

    /**
     * Method set_tipo_negociacao
     * Sample of usage: $var->tipo_negociacao = $object;
     * @param $object Instance of TipoNegociacao
     */
    public function set_tipo_negociacao(TipoNegociacao $object)
    {
        $this->tipo_negociacao = $object;
        $this->tipo_negociacao_id = $object->id;
    }

    /**
     * Method get_tipo_negociacao
     * Sample of usage: $var->tipo_negociacao->attribute;
     * @returns TipoNegociacao instance
     */
    public function get_tipo_negociacao()
    {
    
        // loads the associated object
        if (empty($this->tipo_negociacao))
            $this->tipo_negociacao = new TipoNegociacao($this->tipo_negociacao_id);
    
        // returns the associated object
        return $this->tipo_negociacao;
    }
    /**
     * Method set_origem_negociacao
     * Sample of usage: $var->origem_negociacao = $object;
     * @param $object Instance of OrigemNegociacao
     */
    public function set_origem_negociacao(OrigemNegociacao $object)
    {
        $this->origem_negociacao = $object;
        $this->origem_negociacao_id = $object->id;
    }

    /**
     * Method get_origem_negociacao
     * Sample of usage: $var->origem_negociacao->attribute;
     * @returns OrigemNegociacao instance
     */
    public function get_origem_negociacao()
    {
    
        // loads the associated object
        if (empty($this->origem_negociacao))
            $this->origem_negociacao = new OrigemNegociacao($this->origem_negociacao_id);
    
        // returns the associated object
        return $this->origem_negociacao;
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
     * Method set_estado_negociacao
     * Sample of usage: $var->estado_negociacao = $object;
     * @param $object Instance of EstadoNegociacao
     */
    public function set_estado_negociacao(EstadoNegociacao $object)
    {
        $this->estado_negociacao = $object;
        $this->estado_negociacao_id = $object->id;
    }

    /**
     * Method get_estado_negociacao
     * Sample of usage: $var->estado_negociacao->attribute;
     * @returns EstadoNegociacao instance
     */
    public function get_estado_negociacao()
    {
    
        // loads the associated object
        if (empty($this->estado_negociacao))
            $this->estado_negociacao = new EstadoNegociacao($this->estado_negociacao_id);
    
        // returns the associated object
        return $this->estado_negociacao;
    }

    /**
     * Method getHistoricoNegociacaos
     */
    public function getHistoricoNegociacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('negociacao_id', '=', $this->id));
        return HistoricoNegociacao::getObjects( $criteria );
    }
    /**
     * Method getNegociacaoProdutos
     */
    public function getNegociacaoProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('negociacao_id', '=', $this->id));
        return NegociacaoProduto::getObjects( $criteria );
    }

    public function set_historico_negociacao_negociacao_to_string($historico_negociacao_negociacao_to_string)
    {
        if(is_array($historico_negociacao_negociacao_to_string))
        {
            $values = Negociacao::where('id', 'in', $historico_negociacao_negociacao_to_string)->getIndexedArray('descricao', 'descricao');
            $this->historico_negociacao_negociacao_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_negociacao_negociacao_to_string = $historico_negociacao_negociacao_to_string;
        }

        $this->vdata['historico_negociacao_negociacao_to_string'] = $this->historico_negociacao_negociacao_to_string;
    }

    public function get_historico_negociacao_negociacao_to_string()
    {
        if(!empty($this->historico_negociacao_negociacao_to_string))
        {
            return $this->historico_negociacao_negociacao_to_string;
        }
    
        $values = HistoricoNegociacao::where('negociacao_id', '=', $this->id)->getIndexedArray('negociacao_id','{negociacao->descricao}');
        return implode(', ', $values);
    }

    public function set_historico_negociacao_tipo_contato_to_string($historico_negociacao_tipo_contato_to_string)
    {
        if(is_array($historico_negociacao_tipo_contato_to_string))
        {
            $values = TipoContato::where('id', 'in', $historico_negociacao_tipo_contato_to_string)->getIndexedArray('nome', 'nome');
            $this->historico_negociacao_tipo_contato_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_negociacao_tipo_contato_to_string = $historico_negociacao_tipo_contato_to_string;
        }

        $this->vdata['historico_negociacao_tipo_contato_to_string'] = $this->historico_negociacao_tipo_contato_to_string;
    }

    public function get_historico_negociacao_tipo_contato_to_string()
    {
        if(!empty($this->historico_negociacao_tipo_contato_to_string))
        {
            return $this->historico_negociacao_tipo_contato_to_string;
        }
    
        $values = HistoricoNegociacao::where('negociacao_id', '=', $this->id)->getIndexedArray('tipo_contato_id','{tipo_contato->nome}');
        return implode(', ', $values);
    }

    public function set_negociacao_produto_produto_to_string($negociacao_produto_produto_to_string)
    {
        if(is_array($negociacao_produto_produto_to_string))
        {
            $values = Produto::where('id', 'in', $negociacao_produto_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->negociacao_produto_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_produto_produto_to_string = $negociacao_produto_produto_to_string;
        }

        $this->vdata['negociacao_produto_produto_to_string'] = $this->negociacao_produto_produto_to_string;
    }

    public function get_negociacao_produto_produto_to_string()
    {
        if(!empty($this->negociacao_produto_produto_to_string))
        {
            return $this->negociacao_produto_produto_to_string;
        }
    
        $values = NegociacaoProduto::where('negociacao_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_negociacao_produto_negociacao_to_string($negociacao_produto_negociacao_to_string)
    {
        if(is_array($negociacao_produto_negociacao_to_string))
        {
            $values = Negociacao::where('id', 'in', $negociacao_produto_negociacao_to_string)->getIndexedArray('descricao', 'descricao');
            $this->negociacao_produto_negociacao_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_produto_negociacao_to_string = $negociacao_produto_negociacao_to_string;
        }

        $this->vdata['negociacao_produto_negociacao_to_string'] = $this->negociacao_produto_negociacao_to_string;
    }

    public function get_negociacao_produto_negociacao_to_string()
    {
        if(!empty($this->negociacao_produto_negociacao_to_string))
        {
            return $this->negociacao_produto_negociacao_to_string;
        }
    
        $values = NegociacaoProduto::where('negociacao_id', '=', $this->id)->getIndexedArray('negociacao_id','{negociacao->descricao}');
        return implode(', ', $values);
    }

    
}

