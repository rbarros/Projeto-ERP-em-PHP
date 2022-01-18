<?php

class Fornecedor extends TRecord
{
    const TABLENAME  = 'fornecedor';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $fk_cidade;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('razao_social');
        parent::addAttribute('nome_fantasia');
        parent::addAttribute('cnpj');
        parent::addAttribute('observacao');
        parent::addAttribute('fone');
        parent::addAttribute('email');
        parent::addAttribute('cidade');
        parent::addAttribute('rua');
        parent::addAttribute('numero');
        parent::addAttribute('bairro');
        parent::addAttribute('complemento');
        parent::addAttribute('dt_ativacao');
        parent::addAttribute('inscr_estadual');
        parent::addAttribute('possui_ie');
        parent::addAttribute('icms');
        parent::addAttribute('inscr_municipal');
        parent::addAttribute('regime_tributario');
        parent::addAttribute('contato');
        parent::addAttribute('marca');
        parent::addAttribute('vazio1');
        parent::addAttribute('vazio2');
        parent::addAttribute('vazio3');
            
    }

    /**
     * Method set_cidade
     * Sample of usage: $var->cidade = $object;
     * @param $object Instance of Cidade
     */
    public function set_fk_cidade(Cidade $object)
    {
        $this->fk_cidade = $object;
        $this->cidade = $object->id;
    }

    /**
     * Method get_fk_cidade
     * Sample of usage: $var->fk_cidade->attribute;
     * @returns Cidade instance
     */
    public function get_fk_cidade()
    {
    
        // loads the associated object
        if (empty($this->fk_cidade))
            $this->fk_cidade = new Cidade($this->cidade);
    
        // returns the associated object
        return $this->fk_cidade;
    }

    /**
     * Method getContaBancarias
     */
    public function getContaBancarias()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('fornecedor', '=', $this->id));
        return ContaBancaria::getObjects( $criteria );
    }
    /**
     * Method getContatos
     */
    public function getContatos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('fornecedor_id', '=', $this->id));
        return Contato::getObjects( $criteria );
    }
    /**
     * Method getMarcas
     */
    public function getMarcas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('fornecedor_id', '=', $this->id));
        return Marca::getObjects( $criteria );
    }
    /**
     * Method getParcelasContas
     */
    public function getParcelasContas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('fornecedor_id', '=', $this->id));
        return ParcelasConta::getObjects( $criteria );
    }
    /**
     * Method getProdEstoques
     */
    public function getProdEstoques()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_fornecedor', '=', $this->id));
        return ProdEstoque::getObjects( $criteria );
    }
    /**
     * Method getProdutos
     */
    public function getProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('fornecedor_id', '=', $this->id));
        return Produto::getObjects( $criteria );
    }
    /**
     * Method getSubparcelasParcelas
     */
    public function getSubparcelasParcelas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('fornecedor_id', '=', $this->id));
        return SubparcelasParcela::getObjects( $criteria );
    }

    public function set_conta_bancaria_fk_loja_to_string($conta_bancaria_fk_loja_to_string)
    {
        if(is_array($conta_bancaria_fk_loja_to_string))
        {
            $values = Loja::where('id', 'in', $conta_bancaria_fk_loja_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->conta_bancaria_fk_loja_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_bancaria_fk_loja_to_string = $conta_bancaria_fk_loja_to_string;
        }

        $this->vdata['conta_bancaria_fk_loja_to_string'] = $this->conta_bancaria_fk_loja_to_string;
    }

    public function get_conta_bancaria_fk_loja_to_string()
    {
        if(!empty($this->conta_bancaria_fk_loja_to_string))
        {
            return $this->conta_bancaria_fk_loja_to_string;
        }
    
        $values = ContaBancaria::where('fornecedor', '=', $this->id)->getIndexedArray('loja','{fk_loja->razao_social}');
        return implode(', ', $values);
    }

    public function set_conta_bancaria_fk_fornecedor_to_string($conta_bancaria_fk_fornecedor_to_string)
    {
        if(is_array($conta_bancaria_fk_fornecedor_to_string))
        {
            $values = Fornecedor::where('id', 'in', $conta_bancaria_fk_fornecedor_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->conta_bancaria_fk_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_bancaria_fk_fornecedor_to_string = $conta_bancaria_fk_fornecedor_to_string;
        }

        $this->vdata['conta_bancaria_fk_fornecedor_to_string'] = $this->conta_bancaria_fk_fornecedor_to_string;
    }

    public function get_conta_bancaria_fk_fornecedor_to_string()
    {
        if(!empty($this->conta_bancaria_fk_fornecedor_to_string))
        {
            return $this->conta_bancaria_fk_fornecedor_to_string;
        }
    
        $values = ContaBancaria::where('fornecedor', '=', $this->id)->getIndexedArray('fornecedor','{fk_fornecedor->razao_social}');
        return implode(', ', $values);
    }

    public function set_conta_bancaria_fk_colaborador_to_string($conta_bancaria_fk_colaborador_to_string)
    {
        if(is_array($conta_bancaria_fk_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $conta_bancaria_fk_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->conta_bancaria_fk_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_bancaria_fk_colaborador_to_string = $conta_bancaria_fk_colaborador_to_string;
        }

        $this->vdata['conta_bancaria_fk_colaborador_to_string'] = $this->conta_bancaria_fk_colaborador_to_string;
    }

    public function get_conta_bancaria_fk_colaborador_to_string()
    {
        if(!empty($this->conta_bancaria_fk_colaborador_to_string))
        {
            return $this->conta_bancaria_fk_colaborador_to_string;
        }
    
        $values = ContaBancaria::where('fornecedor', '=', $this->id)->getIndexedArray('colaborador','{fk_colaborador->id}');
        return implode(', ', $values);
    }

    public function set_conta_bancaria_referencia_tipo_to_string($conta_bancaria_referencia_tipo_to_string)
    {
        if(is_array($conta_bancaria_referencia_tipo_to_string))
        {
            $values = TipoContaBancaria::where('id', 'in', $conta_bancaria_referencia_tipo_to_string)->getIndexedArray('conta_bancaria', 'conta_bancaria');
            $this->conta_bancaria_referencia_tipo_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_bancaria_referencia_tipo_to_string = $conta_bancaria_referencia_tipo_to_string;
        }

        $this->vdata['conta_bancaria_referencia_tipo_to_string'] = $this->conta_bancaria_referencia_tipo_to_string;
    }

    public function get_conta_bancaria_referencia_tipo_to_string()
    {
        if(!empty($this->conta_bancaria_referencia_tipo_to_string))
        {
            return $this->conta_bancaria_referencia_tipo_to_string;
        }
    
        $values = ContaBancaria::where('fornecedor', '=', $this->id)->getIndexedArray('id_referencia_tipo','{referencia_tipo->conta_bancaria}');
        return implode(', ', $values);
    }

    public function set_conta_bancaria_fk_banco_to_string($conta_bancaria_fk_banco_to_string)
    {
        if(is_array($conta_bancaria_fk_banco_to_string))
        {
            $values = Banco::where('id', 'in', $conta_bancaria_fk_banco_to_string)->getIndexedArray('id', 'id');
            $this->conta_bancaria_fk_banco_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_bancaria_fk_banco_to_string = $conta_bancaria_fk_banco_to_string;
        }

        $this->vdata['conta_bancaria_fk_banco_to_string'] = $this->conta_bancaria_fk_banco_to_string;
    }

    public function get_conta_bancaria_fk_banco_to_string()
    {
        if(!empty($this->conta_bancaria_fk_banco_to_string))
        {
            return $this->conta_bancaria_fk_banco_to_string;
        }
    
        $values = ContaBancaria::where('fornecedor', '=', $this->id)->getIndexedArray('banco','{fk_banco->id}');
        return implode(', ', $values);
    }

    public function set_contato_pessoa_to_string($contato_pessoa_to_string)
    {
        if(is_array($contato_pessoa_to_string))
        {
            $values = Pessoa::where('id', 'in', $contato_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->contato_pessoa_to_string = implode(', ', $values);
        }
        else
        {
            $this->contato_pessoa_to_string = $contato_pessoa_to_string;
        }

        $this->vdata['contato_pessoa_to_string'] = $this->contato_pessoa_to_string;
    }

    public function get_contato_pessoa_to_string()
    {
        if(!empty($this->contato_pessoa_to_string))
        {
            return $this->contato_pessoa_to_string;
        }
    
        $values = Contato::where('fornecedor_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
        return implode(', ', $values);
    }

    public function set_contato_fornecedor_to_string($contato_fornecedor_to_string)
    {
        if(is_array($contato_fornecedor_to_string))
        {
            $values = Fornecedor::where('id', 'in', $contato_fornecedor_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->contato_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->contato_fornecedor_to_string = $contato_fornecedor_to_string;
        }

        $this->vdata['contato_fornecedor_to_string'] = $this->contato_fornecedor_to_string;
    }

    public function get_contato_fornecedor_to_string()
    {
        if(!empty($this->contato_fornecedor_to_string))
        {
            return $this->contato_fornecedor_to_string;
        }
    
        $values = Contato::where('fornecedor_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
        return implode(', ', $values);
    }

    public function set_marca_fornecedor_to_string($marca_fornecedor_to_string)
    {
        if(is_array($marca_fornecedor_to_string))
        {
            $values = Fornecedor::where('id', 'in', $marca_fornecedor_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->marca_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->marca_fornecedor_to_string = $marca_fornecedor_to_string;
        }

        $this->vdata['marca_fornecedor_to_string'] = $this->marca_fornecedor_to_string;
    }

    public function get_marca_fornecedor_to_string()
    {
        if(!empty($this->marca_fornecedor_to_string))
        {
            return $this->marca_fornecedor_to_string;
        }
    
        $values = Marca::where('fornecedor_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
        return implode(', ', $values);
    }

    public function set_parcelas_conta_fk_conta_origem_to_string($parcelas_conta_fk_conta_origem_to_string)
    {
        if(is_array($parcelas_conta_fk_conta_origem_to_string))
        {
            $values = Conta::where('id', 'in', $parcelas_conta_fk_conta_origem_to_string)->getIndexedArray('id', 'id');
            $this->parcelas_conta_fk_conta_origem_to_string = implode(', ', $values);
        }
        else
        {
            $this->parcelas_conta_fk_conta_origem_to_string = $parcelas_conta_fk_conta_origem_to_string;
        }

        $this->vdata['parcelas_conta_fk_conta_origem_to_string'] = $this->parcelas_conta_fk_conta_origem_to_string;
    }

    public function get_parcelas_conta_fk_conta_origem_to_string()
    {
        if(!empty($this->parcelas_conta_fk_conta_origem_to_string))
        {
            return $this->parcelas_conta_fk_conta_origem_to_string;
        }
    
        $values = ParcelasConta::where('fornecedor_id', '=', $this->id)->getIndexedArray('conta_origem','{fk_conta_origem->id}');
        return implode(', ', $values);
    }

    public function set_parcelas_conta_loja_to_string($parcelas_conta_loja_to_string)
    {
        if(is_array($parcelas_conta_loja_to_string))
        {
            $values = Loja::where('id', 'in', $parcelas_conta_loja_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->parcelas_conta_loja_to_string = implode(', ', $values);
        }
        else
        {
            $this->parcelas_conta_loja_to_string = $parcelas_conta_loja_to_string;
        }

        $this->vdata['parcelas_conta_loja_to_string'] = $this->parcelas_conta_loja_to_string;
    }

    public function get_parcelas_conta_loja_to_string()
    {
        if(!empty($this->parcelas_conta_loja_to_string))
        {
            return $this->parcelas_conta_loja_to_string;
        }
    
        $values = ParcelasConta::where('fornecedor_id', '=', $this->id)->getIndexedArray('loja_id','{loja->razao_social}');
        return implode(', ', $values);
    }

    public function set_parcelas_conta_fornecedor_to_string($parcelas_conta_fornecedor_to_string)
    {
        if(is_array($parcelas_conta_fornecedor_to_string))
        {
            $values = Fornecedor::where('id', 'in', $parcelas_conta_fornecedor_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->parcelas_conta_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->parcelas_conta_fornecedor_to_string = $parcelas_conta_fornecedor_to_string;
        }

        $this->vdata['parcelas_conta_fornecedor_to_string'] = $this->parcelas_conta_fornecedor_to_string;
    }

    public function get_parcelas_conta_fornecedor_to_string()
    {
        if(!empty($this->parcelas_conta_fornecedor_to_string))
        {
            return $this->parcelas_conta_fornecedor_to_string;
        }
    
        $values = ParcelasConta::where('fornecedor_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
        return implode(', ', $values);
    }

    public function set_parcelas_conta_fk_conta_bancaria_loja_to_string($parcelas_conta_fk_conta_bancaria_loja_to_string)
    {
        if(is_array($parcelas_conta_fk_conta_bancaria_loja_to_string))
        {
            $values = ContaBancaria::where('id', 'in', $parcelas_conta_fk_conta_bancaria_loja_to_string)->getIndexedArray('id', 'id');
            $this->parcelas_conta_fk_conta_bancaria_loja_to_string = implode(', ', $values);
        }
        else
        {
            $this->parcelas_conta_fk_conta_bancaria_loja_to_string = $parcelas_conta_fk_conta_bancaria_loja_to_string;
        }

        $this->vdata['parcelas_conta_fk_conta_bancaria_loja_to_string'] = $this->parcelas_conta_fk_conta_bancaria_loja_to_string;
    }

    public function get_parcelas_conta_fk_conta_bancaria_loja_to_string()
    {
        if(!empty($this->parcelas_conta_fk_conta_bancaria_loja_to_string))
        {
            return $this->parcelas_conta_fk_conta_bancaria_loja_to_string;
        }
    
        $values = ParcelasConta::where('fornecedor_id', '=', $this->id)->getIndexedArray('conta_bancaria_loja','{fk_conta_bancaria_loja->id}');
        return implode(', ', $values);
    }

    public function set_parcelas_conta_fk_conta_bancaria_fornecedor_to_string($parcelas_conta_fk_conta_bancaria_fornecedor_to_string)
    {
        if(is_array($parcelas_conta_fk_conta_bancaria_fornecedor_to_string))
        {
            $values = ContaBancaria::where('id', 'in', $parcelas_conta_fk_conta_bancaria_fornecedor_to_string)->getIndexedArray('id', 'id');
            $this->parcelas_conta_fk_conta_bancaria_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->parcelas_conta_fk_conta_bancaria_fornecedor_to_string = $parcelas_conta_fk_conta_bancaria_fornecedor_to_string;
        }

        $this->vdata['parcelas_conta_fk_conta_bancaria_fornecedor_to_string'] = $this->parcelas_conta_fk_conta_bancaria_fornecedor_to_string;
    }

    public function get_parcelas_conta_fk_conta_bancaria_fornecedor_to_string()
    {
        if(!empty($this->parcelas_conta_fk_conta_bancaria_fornecedor_to_string))
        {
            return $this->parcelas_conta_fk_conta_bancaria_fornecedor_to_string;
        }
    
        $values = ParcelasConta::where('fornecedor_id', '=', $this->id)->getIndexedArray('conta_bancaria_fornecedor','{fk_conta_bancaria_fornecedor->id}');
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
    
        $values = ProdEstoque::where('produto_fornecedor', '=', $this->id)->getIndexedArray('id_deposito','{deposito->nome_deposito}');
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
    
        $values = ProdEstoque::where('produto_fornecedor', '=', $this->id)->getIndexedArray('id_produto','{produto->descricao}');
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
    
        $values = ProdEstoque::where('produto_fornecedor', '=', $this->id)->getIndexedArray('produto_marca','{fk_produto_marca->marca}');
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
    
        $values = ProdEstoque::where('produto_fornecedor', '=', $this->id)->getIndexedArray('produto_fornecedor','{fk_produto_fornecedor->razao_social}');
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
    
        $values = ProdEstoque::where('produto_fornecedor', '=', $this->id)->getIndexedArray('produto_categoria','{fk_produto_categoria->nome}');
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
    
        $values = Produto::where('fornecedor_id', '=', $this->id)->getIndexedArray('unidade_id','{unidade->nome}');
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
    
        $values = Produto::where('fornecedor_id', '=', $this->id)->getIndexedArray('categoria_produto_id','{categoria_produto->nome}');
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
    
        $values = Produto::where('fornecedor_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
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
    
        $values = Produto::where('fornecedor_id', '=', $this->id)->getIndexedArray('marca','{fk_marca->marca}');
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
    
        $values = Produto::where('fornecedor_id', '=', $this->id)->getIndexedArray('situacao_prod','{fk_situacao_prod->situacao_prod}');
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
    
        $values = Produto::where('fornecedor_id', '=', $this->id)->getIndexedArray('tipo_cadastro','{fk_tipo_cadastro->descricao}');
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
    
        $values = Produto::where('fornecedor_id', '=', $this->id)->getIndexedArray('mestre_variavel','{fk_mestre_variavel->id}');
        return implode(', ', $values);
    }

    public function set_subparcelas_parcela_fk_conta_origem_to_string($subparcelas_parcela_fk_conta_origem_to_string)
    {
        if(is_array($subparcelas_parcela_fk_conta_origem_to_string))
        {
            $values = Conta::where('id', 'in', $subparcelas_parcela_fk_conta_origem_to_string)->getIndexedArray('id', 'id');
            $this->subparcelas_parcela_fk_conta_origem_to_string = implode(', ', $values);
        }
        else
        {
            $this->subparcelas_parcela_fk_conta_origem_to_string = $subparcelas_parcela_fk_conta_origem_to_string;
        }

        $this->vdata['subparcelas_parcela_fk_conta_origem_to_string'] = $this->subparcelas_parcela_fk_conta_origem_to_string;
    }

    public function get_subparcelas_parcela_fk_conta_origem_to_string()
    {
        if(!empty($this->subparcelas_parcela_fk_conta_origem_to_string))
        {
            return $this->subparcelas_parcela_fk_conta_origem_to_string;
        }
    
        $values = SubparcelasParcela::where('fornecedor_id', '=', $this->id)->getIndexedArray('conta_origem','{fk_conta_origem->id}');
        return implode(', ', $values);
    }

    public function set_subparcelas_parcela_loja_to_string($subparcelas_parcela_loja_to_string)
    {
        if(is_array($subparcelas_parcela_loja_to_string))
        {
            $values = Loja::where('id', 'in', $subparcelas_parcela_loja_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->subparcelas_parcela_loja_to_string = implode(', ', $values);
        }
        else
        {
            $this->subparcelas_parcela_loja_to_string = $subparcelas_parcela_loja_to_string;
        }

        $this->vdata['subparcelas_parcela_loja_to_string'] = $this->subparcelas_parcela_loja_to_string;
    }

    public function get_subparcelas_parcela_loja_to_string()
    {
        if(!empty($this->subparcelas_parcela_loja_to_string))
        {
            return $this->subparcelas_parcela_loja_to_string;
        }
    
        $values = SubparcelasParcela::where('fornecedor_id', '=', $this->id)->getIndexedArray('loja_id','{loja->razao_social}');
        return implode(', ', $values);
    }

    public function set_subparcelas_parcela_fornecedor_to_string($subparcelas_parcela_fornecedor_to_string)
    {
        if(is_array($subparcelas_parcela_fornecedor_to_string))
        {
            $values = Fornecedor::where('id', 'in', $subparcelas_parcela_fornecedor_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->subparcelas_parcela_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->subparcelas_parcela_fornecedor_to_string = $subparcelas_parcela_fornecedor_to_string;
        }

        $this->vdata['subparcelas_parcela_fornecedor_to_string'] = $this->subparcelas_parcela_fornecedor_to_string;
    }

    public function get_subparcelas_parcela_fornecedor_to_string()
    {
        if(!empty($this->subparcelas_parcela_fornecedor_to_string))
        {
            return $this->subparcelas_parcela_fornecedor_to_string;
        }
    
        $values = SubparcelasParcela::where('fornecedor_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
        return implode(', ', $values);
    }

    public function set_subparcelas_parcela_fk_conta_bancaria_loja_to_string($subparcelas_parcela_fk_conta_bancaria_loja_to_string)
    {
        if(is_array($subparcelas_parcela_fk_conta_bancaria_loja_to_string))
        {
            $values = ContaBancaria::where('id', 'in', $subparcelas_parcela_fk_conta_bancaria_loja_to_string)->getIndexedArray('id', 'id');
            $this->subparcelas_parcela_fk_conta_bancaria_loja_to_string = implode(', ', $values);
        }
        else
        {
            $this->subparcelas_parcela_fk_conta_bancaria_loja_to_string = $subparcelas_parcela_fk_conta_bancaria_loja_to_string;
        }

        $this->vdata['subparcelas_parcela_fk_conta_bancaria_loja_to_string'] = $this->subparcelas_parcela_fk_conta_bancaria_loja_to_string;
    }

    public function get_subparcelas_parcela_fk_conta_bancaria_loja_to_string()
    {
        if(!empty($this->subparcelas_parcela_fk_conta_bancaria_loja_to_string))
        {
            return $this->subparcelas_parcela_fk_conta_bancaria_loja_to_string;
        }
    
        $values = SubparcelasParcela::where('fornecedor_id', '=', $this->id)->getIndexedArray('conta_bancaria_loja','{fk_conta_bancaria_loja->id}');
        return implode(', ', $values);
    }

    public function set_subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string($subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string)
    {
        if(is_array($subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string))
        {
            $values = ContaBancaria::where('id', 'in', $subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string)->getIndexedArray('id', 'id');
            $this->subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string = $subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string;
        }

        $this->vdata['subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string'] = $this->subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string;
    }

    public function get_subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string()
    {
        if(!empty($this->subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string))
        {
            return $this->subparcelas_parcela_fk_conta_bancaria_fornecedor_to_string;
        }
    
        $values = SubparcelasParcela::where('fornecedor_id', '=', $this->id)->getIndexedArray('conta_bancaria_fornecedor','{fk_conta_bancaria_fornecedor->id}');
        return implode(', ', $values);
    }

    public function set_subparcelas_parcela_parcela_mestre_to_string($subparcelas_parcela_parcela_mestre_to_string)
    {
        if(is_array($subparcelas_parcela_parcela_mestre_to_string))
        {
            $values = ParcelasConta::where('id', 'in', $subparcelas_parcela_parcela_mestre_to_string)->getIndexedArray('id', 'id');
            $this->subparcelas_parcela_parcela_mestre_to_string = implode(', ', $values);
        }
        else
        {
            $this->subparcelas_parcela_parcela_mestre_to_string = $subparcelas_parcela_parcela_mestre_to_string;
        }

        $this->vdata['subparcelas_parcela_parcela_mestre_to_string'] = $this->subparcelas_parcela_parcela_mestre_to_string;
    }

    public function get_subparcelas_parcela_parcela_mestre_to_string()
    {
        if(!empty($this->subparcelas_parcela_parcela_mestre_to_string))
        {
            return $this->subparcelas_parcela_parcela_mestre_to_string;
        }
    
        $values = SubparcelasParcela::where('fornecedor_id', '=', $this->id)->getIndexedArray('id_parcela_mestre','{parcela_mestre->id}');
        return implode(', ', $values);
    }

    
}

