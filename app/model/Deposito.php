<?php

class Deposito extends TRecord
{
    const TABLENAME  = 'deposito';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $fk_loja;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome_deposito');
        parent::addAttribute('loja');
        parent::addAttribute('prod_estoque');
            
    }

    /**
     * Method set_loja
     * Sample of usage: $var->loja = $object;
     * @param $object Instance of Loja
     */
    public function set_fk_loja(Loja $object)
    {
        $this->fk_loja = $object;
        $this->loja = $object->id;
    }

    /**
     * Method get_fk_loja
     * Sample of usage: $var->fk_loja->attribute;
     * @returns Loja instance
     */
    public function get_fk_loja()
    {
    
        // loads the associated object
        if (empty($this->fk_loja))
            $this->fk_loja = new Loja($this->loja);
    
        // returns the associated object
        return $this->fk_loja;
    }

    /**
     * Method getProdEstoques
     */
    public function getProdEstoques()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id_deposito', '=', $this->id));
        return ProdEstoque::getObjects( $criteria );
    }
    /**
     * Method getTransferenciaEtqs
     */
    public function getTransferenciaEtqs()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('deposito_env', '=', $this->id));
        return TransferenciaEtq::getObjects( $criteria );
    }
    /**
     * Method getProdutodiarios
     */
    public function getProdutodiarios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('deposito_id', '=', $this->id));
        return Produtodiario::getObjects( $criteria );
    }
    /**
     * Method getProdutomensals
     */
    public function getProdutomensals()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('deposito_id', '=', $this->id));
        return Produtomensal::getObjects( $criteria );
    }

    public function set_prod_estoque_deposito_to_string($prod_estoque_deposito_to_string)
    {
        if(is_array($prod_estoque_deposito_to_string))
        {
            $values = Deposito::where('id', 'in', $prod_estoque_deposito_to_string)->getIndexedArray('nome_deposito', 'nome_deposito');
            $this->prod_estoque_deposito_to_string = implode(', ', $values);
        }
        else
        {
            $this->prod_estoque_deposito_to_string = $prod_estoque_deposito_to_string;
        }

        $this->vdata['prod_estoque_deposito_to_string'] = $this->prod_estoque_deposito_to_string;
    }

    public function get_prod_estoque_deposito_to_string()
    {
        if(!empty($this->prod_estoque_deposito_to_string))
        {
            return $this->prod_estoque_deposito_to_string;
        }
    
        $values = ProdEstoque::where('id_deposito', '=', $this->id)->getIndexedArray('id_deposito','{deposito->nome_deposito}');
        return implode(', ', $values);
    }

    public function set_prod_estoque_produto_to_string($prod_estoque_produto_to_string)
    {
        if(is_array($prod_estoque_produto_to_string))
        {
            $values = Produto::where('id', 'in', $prod_estoque_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->prod_estoque_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->prod_estoque_produto_to_string = $prod_estoque_produto_to_string;
        }

        $this->vdata['prod_estoque_produto_to_string'] = $this->prod_estoque_produto_to_string;
    }

    public function get_prod_estoque_produto_to_string()
    {
        if(!empty($this->prod_estoque_produto_to_string))
        {
            return $this->prod_estoque_produto_to_string;
        }
    
        $values = ProdEstoque::where('id_deposito', '=', $this->id)->getIndexedArray('id_produto','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_prod_estoque_fk_produto_marca_to_string($prod_estoque_fk_produto_marca_to_string)
    {
        if(is_array($prod_estoque_fk_produto_marca_to_string))
        {
            $values = Marca::where('id', 'in', $prod_estoque_fk_produto_marca_to_string)->getIndexedArray('marca', 'marca');
            $this->prod_estoque_fk_produto_marca_to_string = implode(', ', $values);
        }
        else
        {
            $this->prod_estoque_fk_produto_marca_to_string = $prod_estoque_fk_produto_marca_to_string;
        }

        $this->vdata['prod_estoque_fk_produto_marca_to_string'] = $this->prod_estoque_fk_produto_marca_to_string;
    }

    public function get_prod_estoque_fk_produto_marca_to_string()
    {
        if(!empty($this->prod_estoque_fk_produto_marca_to_string))
        {
            return $this->prod_estoque_fk_produto_marca_to_string;
        }
    
        $values = ProdEstoque::where('id_deposito', '=', $this->id)->getIndexedArray('produto_marca','{fk_produto_marca->marca}');
        return implode(', ', $values);
    }

    public function set_prod_estoque_fk_produto_fornecedor_to_string($prod_estoque_fk_produto_fornecedor_to_string)
    {
        if(is_array($prod_estoque_fk_produto_fornecedor_to_string))
        {
            $values = Fornecedor::where('id', 'in', $prod_estoque_fk_produto_fornecedor_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->prod_estoque_fk_produto_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->prod_estoque_fk_produto_fornecedor_to_string = $prod_estoque_fk_produto_fornecedor_to_string;
        }

        $this->vdata['prod_estoque_fk_produto_fornecedor_to_string'] = $this->prod_estoque_fk_produto_fornecedor_to_string;
    }

    public function get_prod_estoque_fk_produto_fornecedor_to_string()
    {
        if(!empty($this->prod_estoque_fk_produto_fornecedor_to_string))
        {
            return $this->prod_estoque_fk_produto_fornecedor_to_string;
        }
    
        $values = ProdEstoque::where('id_deposito', '=', $this->id)->getIndexedArray('produto_fornecedor','{fk_produto_fornecedor->razao_social}');
        return implode(', ', $values);
    }

    public function set_prod_estoque_fk_produto_categoria_to_string($prod_estoque_fk_produto_categoria_to_string)
    {
        if(is_array($prod_estoque_fk_produto_categoria_to_string))
        {
            $values = CategoriaProduto::where('id', 'in', $prod_estoque_fk_produto_categoria_to_string)->getIndexedArray('nome', 'nome');
            $this->prod_estoque_fk_produto_categoria_to_string = implode(', ', $values);
        }
        else
        {
            $this->prod_estoque_fk_produto_categoria_to_string = $prod_estoque_fk_produto_categoria_to_string;
        }

        $this->vdata['prod_estoque_fk_produto_categoria_to_string'] = $this->prod_estoque_fk_produto_categoria_to_string;
    }

    public function get_prod_estoque_fk_produto_categoria_to_string()
    {
        if(!empty($this->prod_estoque_fk_produto_categoria_to_string))
        {
            return $this->prod_estoque_fk_produto_categoria_to_string;
        }
    
        $values = ProdEstoque::where('id_deposito', '=', $this->id)->getIndexedArray('produto_categoria','{fk_produto_categoria->nome}');
        return implode(', ', $values);
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
    
        $values = TransferenciaEtq::where('deposito_env', '=', $this->id)->getIndexedArray('deposito_env','{fk_deposito_env->nome_deposito}');
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
    
        $values = TransferenciaEtq::where('deposito_env', '=', $this->id)->getIndexedArray('estoque_id','{estoque->id}');
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
    
        $values = TransferenciaEtq::where('deposito_env', '=', $this->id)->getIndexedArray('id_produto','{produto->descricao}');
        return implode(', ', $values);
    }

    
}

