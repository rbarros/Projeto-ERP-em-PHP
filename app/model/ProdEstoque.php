<?php

class ProdEstoque extends TRecord
{
    const TABLENAME  = 'prod_estoque';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $deposito;
    private $fk_produto_marca;
    private $fk_produto_fornecedor;
    private $fk_produto_categoria;
    private $produto;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('quantidade');
        parent::addAttribute('qtd_min');
        parent::addAttribute('qtd_max');
        parent::addAttribute('id_deposito');
        parent::addAttribute('id_produto');
        parent::addAttribute('produto_marca');
        parent::addAttribute('produto_referencia');
        parent::addAttribute('produto_sku');
        parent::addAttribute('produto_nome');
        parent::addAttribute('produto_nome_variacao');
        parent::addAttribute('produto_fornecedor');
        parent::addAttribute('produto_categoria');
        parent::addAttribute('produto_cod_barras');
        parent::addAttribute('curva');
            
    }

    /**
     * Method set_deposito
     * Sample of usage: $var->deposito = $object;
     * @param $object Instance of Deposito
     */
    public function set_deposito(Deposito $object)
    {
        $this->deposito = $object;
        $this->id_deposito = $object->id;
    }

    /**
     * Method get_deposito
     * Sample of usage: $var->deposito->attribute;
     * @returns Deposito instance
     */
    public function get_deposito()
    {
    
        // loads the associated object
        if (empty($this->deposito))
            $this->deposito = new Deposito($this->id_deposito);
    
        // returns the associated object
        return $this->deposito;
    }
    /**
     * Method set_marca
     * Sample of usage: $var->marca = $object;
     * @param $object Instance of Marca
     */
    public function set_fk_produto_marca(Marca $object)
    {
        $this->fk_produto_marca = $object;
        $this->produto_marca = $object->id;
    }

    /**
     * Method get_fk_produto_marca
     * Sample of usage: $var->fk_produto_marca->attribute;
     * @returns Marca instance
     */
    public function get_fk_produto_marca()
    {
    
        // loads the associated object
        if (empty($this->fk_produto_marca))
            $this->fk_produto_marca = new Marca($this->produto_marca);
    
        // returns the associated object
        return $this->fk_produto_marca;
    }
    /**
     * Method set_fornecedor
     * Sample of usage: $var->fornecedor = $object;
     * @param $object Instance of Fornecedor
     */
    public function set_fk_produto_fornecedor(Fornecedor $object)
    {
        $this->fk_produto_fornecedor = $object;
        $this->produto_fornecedor = $object->id;
    }

    /**
     * Method get_fk_produto_fornecedor
     * Sample of usage: $var->fk_produto_fornecedor->attribute;
     * @returns Fornecedor instance
     */
    public function get_fk_produto_fornecedor()
    {
    
        // loads the associated object
        if (empty($this->fk_produto_fornecedor))
            $this->fk_produto_fornecedor = new Fornecedor($this->produto_fornecedor);
    
        // returns the associated object
        return $this->fk_produto_fornecedor;
    }
    /**
     * Method set_categoria_produto
     * Sample of usage: $var->categoria_produto = $object;
     * @param $object Instance of CategoriaProduto
     */
    public function set_fk_produto_categoria(CategoriaProduto $object)
    {
        $this->fk_produto_categoria = $object;
        $this->produto_categoria = $object->id;
    }

    /**
     * Method get_fk_produto_categoria
     * Sample of usage: $var->fk_produto_categoria->attribute;
     * @returns CategoriaProduto instance
     */
    public function get_fk_produto_categoria()
    {
    
        // loads the associated object
        if (empty($this->fk_produto_categoria))
            $this->fk_produto_categoria = new CategoriaProduto($this->produto_categoria);
    
        // returns the associated object
        return $this->fk_produto_categoria;
    }
    /**
     * Method set_produto
     * Sample of usage: $var->produto = $object;
     * @param $object Instance of Produto
     */
    public function set_produto(Produto $object)
    {
        $this->produto = $object;
        $this->id_produto = $object->id;
    }

    /**
     * Method get_produto
     * Sample of usage: $var->produto->attribute;
     * @returns Produto instance
     */
    public function get_produto()
    {
    
        // loads the associated object
        if (empty($this->produto))
            $this->produto = new Produto($this->id_produto);
    
        // returns the associated object
        return $this->produto;
    }

    /**
     * Method getTransferenciaEtqs
     */
    public function getTransferenciaEtqs()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estoque_id', '=', $this->id));
        return TransferenciaEtq::getObjects( $criteria );
    }
    /**
     * Method getProdutodiarios
     */
    public function getProdutodiarios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estoque_id', '=', $this->id));
        return Produtodiario::getObjects( $criteria );
    }
    /**
     * Method getProdutomensals
     */
    public function getProdutomensals()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estoque_id', '=', $this->id));
        return Produtomensal::getObjects( $criteria );
    }

    public function set_transferencia_etq_fk_deposito_env_to_string($transferencia_etq_fk_deposito_env_to_string)
    {
        if(is_array($transferencia_etq_fk_deposito_env_to_string))
        {
            $values = Deposito::where('id', 'in', $transferencia_etq_fk_deposito_env_to_string)->getIndexedArray('nome_deposito', 'nome_deposito');
            $this->transferencia_etq_fk_deposito_env_to_string = implode(', ', $values);
        }
        else
        {
            $this->transferencia_etq_fk_deposito_env_to_string = $transferencia_etq_fk_deposito_env_to_string;
        }

        $this->vdata['transferencia_etq_fk_deposito_env_to_string'] = $this->transferencia_etq_fk_deposito_env_to_string;
    }

    public function get_transferencia_etq_fk_deposito_env_to_string()
    {
        if(!empty($this->transferencia_etq_fk_deposito_env_to_string))
        {
            return $this->transferencia_etq_fk_deposito_env_to_string;
        }
    
        $values = TransferenciaEtq::where('estoque_id', '=', $this->id)->getIndexedArray('deposito_env','{fk_deposito_env->nome_deposito}');
        return implode(', ', $values);
    }

    public function set_transferencia_etq_estoque_to_string($transferencia_etq_estoque_to_string)
    {
        if(is_array($transferencia_etq_estoque_to_string))
        {
            $values = ProdEstoque::where('id', 'in', $transferencia_etq_estoque_to_string)->getIndexedArray('id', 'id');
            $this->transferencia_etq_estoque_to_string = implode(', ', $values);
        }
        else
        {
            $this->transferencia_etq_estoque_to_string = $transferencia_etq_estoque_to_string;
        }

        $this->vdata['transferencia_etq_estoque_to_string'] = $this->transferencia_etq_estoque_to_string;
    }

    public function get_transferencia_etq_estoque_to_string()
    {
        if(!empty($this->transferencia_etq_estoque_to_string))
        {
            return $this->transferencia_etq_estoque_to_string;
        }
    
        $values = TransferenciaEtq::where('estoque_id', '=', $this->id)->getIndexedArray('estoque_id','{estoque->id}');
        return implode(', ', $values);
    }

    public function set_transferencia_etq_produto_to_string($transferencia_etq_produto_to_string)
    {
        if(is_array($transferencia_etq_produto_to_string))
        {
            $values = Produto::where('id', 'in', $transferencia_etq_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->transferencia_etq_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->transferencia_etq_produto_to_string = $transferencia_etq_produto_to_string;
        }

        $this->vdata['transferencia_etq_produto_to_string'] = $this->transferencia_etq_produto_to_string;
    }

    public function get_transferencia_etq_produto_to_string()
    {
        if(!empty($this->transferencia_etq_produto_to_string))
        {
            return $this->transferencia_etq_produto_to_string;
        }
    
        $values = TransferenciaEtq::where('estoque_id', '=', $this->id)->getIndexedArray('id_produto','{produto->descricao}');
        return implode(', ', $values);
    }

    
}

