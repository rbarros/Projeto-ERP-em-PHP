<?php

class Produto extends TRecord
{
    const TABLENAME  = 'produto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $categoria_produto;
    private $unidade;
    private $fk_tipo_cadastro;
    private $fk_situacao_prod;
    private $fk_marca;
    private $fornecedor;
    private $fk_mestre_variavel;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('unidade_id');
        parent::addAttribute('categoria_produto_id');
        parent::addAttribute('fornecedor_id');
        parent::addAttribute('descricao');
        parent::addAttribute('desc_variacao');
        parent::addAttribute('dt_cadastro');
        parent::addAttribute('SKU');
        parent::addAttribute('cod_barras');
        parent::addAttribute('obs');
        parent::addAttribute('id_externo');
        parent::addAttribute('estoque');
        parent::addAttribute('tabela_preco');
        parent::addAttribute('preco');
        parent::addAttribute('qtd_max');
        parent::addAttribute('marca');
        parent::addAttribute('situacao_prod');
        parent::addAttribute('referencia');
        parent::addAttribute('tipo_cadastro');
        parent::addAttribute('deposito');
        parent::addAttribute('id_familia');
        parent::addAttribute('mestre_variavel');
        parent::addAttribute('qtd_min');
        parent::addAttribute('valor_custo');
        parent::addAttribute('valor_venda');
        parent::addAttribute('sit_tribut');
        parent::addAttribute('qtd');
        parent::addAttribute('ncm');
        parent::addAttribute('cest');
        parent::addAttribute('id_externo_promocao');
        parent::addAttribute('origem');
        parent::addAttribute('link_site');
        parent::addAttribute('status');
        parent::addAttribute('vazio1');
        parent::addAttribute('vazio2');
        parent::addAttribute('vazio3');
            
    }

    /**
     * Method set_categoria_produto
     * Sample of usage: $var->categoria_produto = $object;
     * @param $object Instance of CategoriaProduto
     */
    public function set_categoria_produto(CategoriaProduto $object)
    {
        $this->categoria_produto = $object;
        $this->categoria_produto_id = $object->id;
    }

    /**
     * Method get_categoria_produto
     * Sample of usage: $var->categoria_produto->attribute;
     * @returns CategoriaProduto instance
     */
    public function get_categoria_produto()
    {
    
        // loads the associated object
        if (empty($this->categoria_produto))
            $this->categoria_produto = new CategoriaProduto($this->categoria_produto_id);
    
        // returns the associated object
        return $this->categoria_produto;
    }
    /**
     * Method set_unidade
     * Sample of usage: $var->unidade = $object;
     * @param $object Instance of Unidade
     */
    public function set_unidade(Unidade $object)
    {
        $this->unidade = $object;
        $this->unidade_id = $object->id;
    }

    /**
     * Method get_unidade
     * Sample of usage: $var->unidade->attribute;
     * @returns Unidade instance
     */
    public function get_unidade()
    {
    
        // loads the associated object
        if (empty($this->unidade))
            $this->unidade = new Unidade($this->unidade_id);
    
        // returns the associated object
        return $this->unidade;
    }
    /**
     * Method set_tipo_cadastro_prod
     * Sample of usage: $var->tipo_cadastro_prod = $object;
     * @param $object Instance of TipoCadastroProd
     */
    public function set_fk_tipo_cadastro(TipoCadastroProd $object)
    {
        $this->fk_tipo_cadastro = $object;
        $this->tipo_cadastro = $object->id;
    }

    /**
     * Method get_fk_tipo_cadastro
     * Sample of usage: $var->fk_tipo_cadastro->attribute;
     * @returns TipoCadastroProd instance
     */
    public function get_fk_tipo_cadastro()
    {
    
        // loads the associated object
        if (empty($this->fk_tipo_cadastro))
            $this->fk_tipo_cadastro = new TipoCadastroProd($this->tipo_cadastro);
    
        // returns the associated object
        return $this->fk_tipo_cadastro;
    }
    /**
     * Method set_situacao_prod
     * Sample of usage: $var->situacao_prod = $object;
     * @param $object Instance of SituacaoProd
     */
    public function set_fk_situacao_prod(SituacaoProd $object)
    {
        $this->fk_situacao_prod = $object;
        $this->situacao_prod = $object->id;
    }

    /**
     * Method get_fk_situacao_prod
     * Sample of usage: $var->fk_situacao_prod->attribute;
     * @returns SituacaoProd instance
     */
    public function get_fk_situacao_prod()
    {
    
        // loads the associated object
        if (empty($this->fk_situacao_prod))
            $this->fk_situacao_prod = new SituacaoProd($this->situacao_prod);
    
        // returns the associated object
        return $this->fk_situacao_prod;
    }
    /**
     * Method set_marca
     * Sample of usage: $var->marca = $object;
     * @param $object Instance of Marca
     */
    public function set_fk_marca(Marca $object)
    {
        $this->fk_marca = $object;
        $this->marca = $object->id;
    }

    /**
     * Method get_fk_marca
     * Sample of usage: $var->fk_marca->attribute;
     * @returns Marca instance
     */
    public function get_fk_marca()
    {
    
        // loads the associated object
        if (empty($this->fk_marca))
            $this->fk_marca = new Marca($this->marca);
    
        // returns the associated object
        return $this->fk_marca;
    }
    /**
     * Method set_fornecedor
     * Sample of usage: $var->fornecedor = $object;
     * @param $object Instance of Fornecedor
     */
    public function set_fornecedor(Fornecedor $object)
    {
        $this->fornecedor = $object;
        $this->fornecedor_id = $object->id;
    }

    /**
     * Method get_fornecedor
     * Sample of usage: $var->fornecedor->attribute;
     * @returns Fornecedor instance
     */
    public function get_fornecedor()
    {
    
        // loads the associated object
        if (empty($this->fornecedor))
            $this->fornecedor = new Fornecedor($this->fornecedor_id);
    
        // returns the associated object
        return $this->fornecedor;
    }
    /**
     * Method set_mestre_variavel
     * Sample of usage: $var->mestre_variavel = $object;
     * @param $object Instance of MestreVariavel
     */
    public function set_fk_mestre_variavel(MestreVariavel $object)
    {
        $this->fk_mestre_variavel = $object;
        $this->mestre_variavel = $object->id;
    }

    /**
     * Method get_fk_mestre_variavel
     * Sample of usage: $var->fk_mestre_variavel->attribute;
     * @returns MestreVariavel instance
     */
    public function get_fk_mestre_variavel()
    {
    
        // loads the associated object
        if (empty($this->fk_mestre_variavel))
            $this->fk_mestre_variavel = new MestreVariavel($this->mestre_variavel);
    
        // returns the associated object
        return $this->fk_mestre_variavel;
    }

    /**
     * Method getNegociacaoProdutos
     */
    public function getNegociacaoProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return NegociacaoProduto::getObjects( $criteria );
    }
    /**
     * Method getOrcamentoItems
     */
    public function getOrcamentoItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return OrcamentoItem::getObjects( $criteria );
    }
    /**
     * Method getPrecos
     */
    public function getPrecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id_produto', '=', $this->id));
        return Preco::getObjects( $criteria );
    }
    /**
     * Method getProdEstoques
     */
    public function getProdEstoques()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id_produto', '=', $this->id));
        return ProdEstoque::getObjects( $criteria );
    }
    /**
     * Method getTransferenciaEtqs
     */
    public function getTransferenciaEtqs()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id_produto', '=', $this->id));
        return TransferenciaEtq::getObjects( $criteria );
    }
    /**
     * Method getVendaItemAlts
     */
    public function getVendaItemAlts()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return VendaItemAlt::getObjects( $criteria );
    }
    /**
     * Method getProdutodiarios
     */
    public function getProdutodiarios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return Produtodiario::getObjects( $criteria );
    }
    /**
     * Method getProdutomensals
     */
    public function getProdutomensals()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return Produtomensal::getObjects( $criteria );
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
    
        $values = NegociacaoProduto::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
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
    
        $values = NegociacaoProduto::where('produto_id', '=', $this->id)->getIndexedArray('negociacao_id','{negociacao->descricao}');
        return implode(', ', $values);
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
    
        $values = OrcamentoItem::where('produto_id', '=', $this->id)->getIndexedArray('orcamento_id','{orcamento->id}');
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
    
        $values = OrcamentoItem::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_Preco_produto_to_string($Preco_produto_to_string)
    {
        if(is_array($Preco_produto_to_string))
        {
            $values = Produto::where('id', 'in', $Preco_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->Preco_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->Preco_produto_to_string = $Preco_produto_to_string;
        }

        $this->vdata['Preco_produto_to_string'] = $this->Preco_produto_to_string;
    }

    public function get_Preco_produto_to_string()
    {
        if(!empty($this->Preco_produto_to_string))
        {
            return $this->Preco_produto_to_string;
        }
    
        $values = Preco::where('id_produto', '=', $this->id)->getIndexedArray('id_produto','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_Preco_tabela_to_string($Preco_tabela_to_string)
    {
        if(is_array($Preco_tabela_to_string))
        {
            $values = TabelaPreco::where('id', 'in', $Preco_tabela_to_string)->getIndexedArray('nome_tabela_preco', 'nome_tabela_preco');
            $this->Preco_tabela_to_string = implode(', ', $values);
        }
        else
        {
            $this->Preco_tabela_to_string = $Preco_tabela_to_string;
        }

        $this->vdata['Preco_tabela_to_string'] = $this->Preco_tabela_to_string;
    }

    public function get_Preco_tabela_to_string()
    {
        if(!empty($this->Preco_tabela_to_string))
        {
            return $this->Preco_tabela_to_string;
        }
    
        $values = Preco::where('id_produto', '=', $this->id)->getIndexedArray('id_tabela','{tabela->nome_tabela_preco}');
        return implode(', ', $values);
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
    
        $values = ProdEstoque::where('id_produto', '=', $this->id)->getIndexedArray('id_deposito','{deposito->nome_deposito}');
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
    
        $values = ProdEstoque::where('id_produto', '=', $this->id)->getIndexedArray('id_produto','{produto->descricao}');
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
    
        $values = ProdEstoque::where('id_produto', '=', $this->id)->getIndexedArray('produto_marca','{fk_produto_marca->marca}');
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
    
        $values = ProdEstoque::where('id_produto', '=', $this->id)->getIndexedArray('produto_fornecedor','{fk_produto_fornecedor->razao_social}');
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
    
        $values = ProdEstoque::where('id_produto', '=', $this->id)->getIndexedArray('produto_categoria','{fk_produto_categoria->nome}');
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
    
        $values = TransferenciaEtq::where('id_produto', '=', $this->id)->getIndexedArray('deposito_env','{fk_deposito_env->nome_deposito}');
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
    
        $values = TransferenciaEtq::where('id_produto', '=', $this->id)->getIndexedArray('estoque_id','{estoque->id}');
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
    
        $values = TransferenciaEtq::where('id_produto', '=', $this->id)->getIndexedArray('id_produto','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_venda_item_alt_produto_to_string($venda_item_alt_produto_to_string)
    {
        if(is_array($venda_item_alt_produto_to_string))
        {
            $values = Produto::where('id', 'in', $venda_item_alt_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->venda_item_alt_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->venda_item_alt_produto_to_string = $venda_item_alt_produto_to_string;
        }

        $this->vdata['venda_item_alt_produto_to_string'] = $this->venda_item_alt_produto_to_string;
    }

    public function get_venda_item_alt_produto_to_string()
    {
        if(!empty($this->venda_item_alt_produto_to_string))
        {
            return $this->venda_item_alt_produto_to_string;
        }
    
        $values = VendaItemAlt::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_venda_item_alt_venda_to_string($venda_item_alt_venda_to_string)
    {
        if(is_array($venda_item_alt_venda_to_string))
        {
            $values = VendaAlt::where('id', 'in', $venda_item_alt_venda_to_string)->getIndexedArray('id', 'id');
            $this->venda_item_alt_venda_to_string = implode(', ', $values);
        }
        else
        {
            $this->venda_item_alt_venda_to_string = $venda_item_alt_venda_to_string;
        }

        $this->vdata['venda_item_alt_venda_to_string'] = $this->venda_item_alt_venda_to_string;
    }

    public function get_venda_item_alt_venda_to_string()
    {
        if(!empty($this->venda_item_alt_venda_to_string))
        {
            return $this->venda_item_alt_venda_to_string;
        }
    
        $values = VendaItemAlt::where('produto_id', '=', $this->id)->getIndexedArray('venda_id','{venda->id}');
        return implode(', ', $values);
    }

    
}

