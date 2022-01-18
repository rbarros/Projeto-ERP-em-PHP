<?php

class CategoriaProduto extends TRecord
{
    const TABLENAME  = 'categoria_produto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('id_externo');
        parent::addAttribute('ncm_padrao');
        parent::addAttribute('iconeCategoria');
    
    }

    /**
     * Method getProdEstoques
     */
    public function getProdEstoques()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_categoria', '=', $this->id));
        return ProdEstoque::getObjects( $criteria );
    }
    /**
     * Method getProdutos
     */
    public function getProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('categoria_produto_id', '=', $this->id));
        return Produto::getObjects( $criteria );
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
    
        $values = ProdEstoque::where('produto_categoria', '=', $this->id)->getIndexedArray('id_deposito','{deposito->nome_deposito}');
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
    
        $values = ProdEstoque::where('produto_categoria', '=', $this->id)->getIndexedArray('id_produto','{produto->descricao}');
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
    
        $values = ProdEstoque::where('produto_categoria', '=', $this->id)->getIndexedArray('produto_marca','{fk_produto_marca->marca}');
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
    
        $values = ProdEstoque::where('produto_categoria', '=', $this->id)->getIndexedArray('produto_fornecedor','{fk_produto_fornecedor->razao_social}');
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
    
        $values = ProdEstoque::where('produto_categoria', '=', $this->id)->getIndexedArray('produto_categoria','{fk_produto_categoria->nome}');
        return implode(', ', $values);
    }

    public function set_produto_unidade_to_string($produto_unidade_to_string)
    {
        if(is_array($produto_unidade_to_string))
        {
            $values = Unidade::where('id', 'in', $produto_unidade_to_string)->getIndexedArray('nome', 'nome');
            $this->produto_unidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_unidade_to_string = $produto_unidade_to_string;
        }

        $this->vdata['produto_unidade_to_string'] = $this->produto_unidade_to_string;
    }

    public function get_produto_unidade_to_string()
    {
        if(!empty($this->produto_unidade_to_string))
        {
            return $this->produto_unidade_to_string;
        }
    
        $values = Produto::where('categoria_produto_id', '=', $this->id)->getIndexedArray('unidade_id','{unidade->nome}');
        return implode(', ', $values);
    }

    public function set_produto_categoria_produto_to_string($produto_categoria_produto_to_string)
    {
        if(is_array($produto_categoria_produto_to_string))
        {
            $values = CategoriaProduto::where('id', 'in', $produto_categoria_produto_to_string)->getIndexedArray('nome', 'nome');
            $this->produto_categoria_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_categoria_produto_to_string = $produto_categoria_produto_to_string;
        }

        $this->vdata['produto_categoria_produto_to_string'] = $this->produto_categoria_produto_to_string;
    }

    public function get_produto_categoria_produto_to_string()
    {
        if(!empty($this->produto_categoria_produto_to_string))
        {
            return $this->produto_categoria_produto_to_string;
        }
    
        $values = Produto::where('categoria_produto_id', '=', $this->id)->getIndexedArray('categoria_produto_id','{categoria_produto->nome}');
        return implode(', ', $values);
    }

    public function set_produto_fornecedor_to_string($produto_fornecedor_to_string)
    {
        if(is_array($produto_fornecedor_to_string))
        {
            $values = Fornecedor::where('id', 'in', $produto_fornecedor_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->produto_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_fornecedor_to_string = $produto_fornecedor_to_string;
        }

        $this->vdata['produto_fornecedor_to_string'] = $this->produto_fornecedor_to_string;
    }

    public function get_produto_fornecedor_to_string()
    {
        if(!empty($this->produto_fornecedor_to_string))
        {
            return $this->produto_fornecedor_to_string;
        }
    
        $values = Produto::where('categoria_produto_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
        return implode(', ', $values);
    }

    public function set_produto_fk_marca_to_string($produto_fk_marca_to_string)
    {
        if(is_array($produto_fk_marca_to_string))
        {
            $values = Marca::where('id', 'in', $produto_fk_marca_to_string)->getIndexedArray('marca', 'marca');
            $this->produto_fk_marca_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_fk_marca_to_string = $produto_fk_marca_to_string;
        }

        $this->vdata['produto_fk_marca_to_string'] = $this->produto_fk_marca_to_string;
    }

    public function get_produto_fk_marca_to_string()
    {
        if(!empty($this->produto_fk_marca_to_string))
        {
            return $this->produto_fk_marca_to_string;
        }
    
        $values = Produto::where('categoria_produto_id', '=', $this->id)->getIndexedArray('marca','{fk_marca->marca}');
        return implode(', ', $values);
    }

    public function set_produto_fk_situacao_prod_to_string($produto_fk_situacao_prod_to_string)
    {
        if(is_array($produto_fk_situacao_prod_to_string))
        {
            $values = SituacaoProd::where('id', 'in', $produto_fk_situacao_prod_to_string)->getIndexedArray('situacao_prod', 'situacao_prod');
            $this->produto_fk_situacao_prod_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_fk_situacao_prod_to_string = $produto_fk_situacao_prod_to_string;
        }

        $this->vdata['produto_fk_situacao_prod_to_string'] = $this->produto_fk_situacao_prod_to_string;
    }

    public function get_produto_fk_situacao_prod_to_string()
    {
        if(!empty($this->produto_fk_situacao_prod_to_string))
        {
            return $this->produto_fk_situacao_prod_to_string;
        }
    
        $values = Produto::where('categoria_produto_id', '=', $this->id)->getIndexedArray('situacao_prod','{fk_situacao_prod->situacao_prod}');
        return implode(', ', $values);
    }

    public function set_produto_fk_tipo_cadastro_to_string($produto_fk_tipo_cadastro_to_string)
    {
        if(is_array($produto_fk_tipo_cadastro_to_string))
        {
            $values = TipoCadastroProd::where('id', 'in', $produto_fk_tipo_cadastro_to_string)->getIndexedArray('descricao', 'descricao');
            $this->produto_fk_tipo_cadastro_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_fk_tipo_cadastro_to_string = $produto_fk_tipo_cadastro_to_string;
        }

        $this->vdata['produto_fk_tipo_cadastro_to_string'] = $this->produto_fk_tipo_cadastro_to_string;
    }

    public function get_produto_fk_tipo_cadastro_to_string()
    {
        if(!empty($this->produto_fk_tipo_cadastro_to_string))
        {
            return $this->produto_fk_tipo_cadastro_to_string;
        }
    
        $values = Produto::where('categoria_produto_id', '=', $this->id)->getIndexedArray('tipo_cadastro','{fk_tipo_cadastro->descricao}');
        return implode(', ', $values);
    }

    public function set_produto_fk_mestre_variavel_to_string($produto_fk_mestre_variavel_to_string)
    {
        if(is_array($produto_fk_mestre_variavel_to_string))
        {
            $values = MestreVariavel::where('id', 'in', $produto_fk_mestre_variavel_to_string)->getIndexedArray('id', 'id');
            $this->produto_fk_mestre_variavel_to_string = implode(', ', $values);
        }
        else
        {
            $this->produto_fk_mestre_variavel_to_string = $produto_fk_mestre_variavel_to_string;
        }

        $this->vdata['produto_fk_mestre_variavel_to_string'] = $this->produto_fk_mestre_variavel_to_string;
    }

    public function get_produto_fk_mestre_variavel_to_string()
    {
        if(!empty($this->produto_fk_mestre_variavel_to_string))
        {
            return $this->produto_fk_mestre_variavel_to_string;
        }
    
        $values = Produto::where('categoria_produto_id', '=', $this->id)->getIndexedArray('mestre_variavel','{fk_mestre_variavel->id}');
        return implode(', ', $values);
    }

}

