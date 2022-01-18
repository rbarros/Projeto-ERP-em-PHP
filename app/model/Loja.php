<?php

class Loja extends TRecord
{
    const TABLENAME  = 'loja';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $fk_cidade;
    private $fk_grupo;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('razao_social');
        parent::addAttribute('abreviacao');
        parent::addAttribute('nome_fantasia');
        parent::addAttribute('cnpj');
        parent::addAttribute('grupo');
        parent::addAttribute('observacao');
        parent::addAttribute('fone');
        parent::addAttribute('email');
        parent::addAttribute('cidade');
        parent::addAttribute('cep');
        parent::addAttribute('rua');
        parent::addAttribute('numero');
        parent::addAttribute('bairro');
        parent::addAttribute('complemento');
        parent::addAttribute('dt_ativacao');
        parent::addAttribute('inscr_estadual');
        parent::addAttribute('icms');
        parent::addAttribute('inscr_municipal');
        parent::addAttribute('regime_tribut');
        parent::addAttribute('deposito');
        parent::addAttribute('tipo_emissao');
        parent::addAttribute('lat');
        parent::addAttribute('lon');
        parent::addAttribute('unidade');
        parent::addAttribute('idEmpresa');
        parent::addAttribute('csc_producao');
        parent::addAttribute('id_csc_producao');
        parent::addAttribute('serie_nf_producao');
        parent::addAttribute('seq_nf_producao');
        parent::addAttribute('csc_homologacao');
        parent::addAttribute('id_csc_homologacao');
        parent::addAttribute('serie_nf_homologacao');
        parent::addAttribute('seq_nf_homologacao');
        parent::addAttribute('senha_certificado');
        parent::addAttribute('funcionario');
            
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
     * Method set_grupo_lojas
     * Sample of usage: $var->grupo_lojas = $object;
     * @param $object Instance of GrupoLojas
     */
    public function set_fk_grupo(GrupoLojas $object)
    {
        $this->fk_grupo = $object;
        $this->grupo = $object->id;
    }

    /**
     * Method get_fk_grupo
     * Sample of usage: $var->fk_grupo->attribute;
     * @returns GrupoLojas instance
     */
    public function get_fk_grupo()
    {
    
        // loads the associated object
        if (empty($this->fk_grupo))
            $this->fk_grupo = new GrupoLojas($this->grupo);
    
        // returns the associated object
        return $this->fk_grupo;
    }

    /**
     * Method getColaboradors
     */
    public function getColaboradors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('loja_registro', '=', $this->id));
        return Colaborador::getObjects( $criteria );
    }
    /**
     * Method getContas
     */
    public function getContas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('loja', '=', $this->id));
        return Conta::getObjects( $criteria );
    }
    /**
     * Method getContaBancarias
     */
    public function getContaBancarias()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('loja', '=', $this->id));
        return ContaBancaria::getObjects( $criteria );
    }
    /**
     * Method getDepositos
     */
    public function getDepositos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('loja', '=', $this->id));
        return Deposito::getObjects( $criteria );
    }
    /**
     * Method getParcelasContas
     */
    public function getParcelasContas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('loja_id', '=', $this->id));
        return ParcelasConta::getObjects( $criteria );
    }
    /**
     * Method getSubparcelasParcelas
     */
    public function getSubparcelasParcelas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('loja_id', '=', $this->id));
        return SubparcelasParcela::getObjects( $criteria );
    }
    /**
     * Method getTransferenciaColaboradors
     */
    public function getTransferenciaColaboradorsByFkLojaOrigems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('loja_origem', '=', $this->id));
        return TransferenciaColaborador::getObjects( $criteria );
    }
    /**
     * Method getTransferenciaColaboradors
     */
    public function getTransferenciaColaboradorsByFkLojaDestinos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('loja_destino', '=', $this->id));
        return TransferenciaColaborador::getObjects( $criteria );
    }
    /**
     * Method getProdutodiarios
     */
    public function getProdutodiarios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('loja_id', '=', $this->id));
        return Produtodiario::getObjects( $criteria );
    }
    /**
     * Method getProdutomensals
     */
    public function getProdutomensals()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('loja_id', '=', $this->id));
        return Produtomensal::getObjects( $criteria );
    }

    public function set_colaborador_pessoa_to_string($colaborador_pessoa_to_string)
    {
        if(is_array($colaborador_pessoa_to_string))
        {
            $values = Pessoa::where('id', 'in', $colaborador_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->colaborador_pessoa_to_string = implode(', ', $values);
        }
        else
        {
            $this->colaborador_pessoa_to_string = $colaborador_pessoa_to_string;
        }

        $this->vdata['colaborador_pessoa_to_string'] = $this->colaborador_pessoa_to_string;
    }

    public function get_colaborador_pessoa_to_string()
    {
        if(!empty($this->colaborador_pessoa_to_string))
        {
            return $this->colaborador_pessoa_to_string;
        }
    
        $values = Colaborador::where('loja_registro', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
        return implode(', ', $values);
    }

    public function set_colaborador_fk_loja_registro_to_string($colaborador_fk_loja_registro_to_string)
    {
        if(is_array($colaborador_fk_loja_registro_to_string))
        {
            $values = Loja::where('id', 'in', $colaborador_fk_loja_registro_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->colaborador_fk_loja_registro_to_string = implode(', ', $values);
        }
        else
        {
            $this->colaborador_fk_loja_registro_to_string = $colaborador_fk_loja_registro_to_string;
        }

        $this->vdata['colaborador_fk_loja_registro_to_string'] = $this->colaborador_fk_loja_registro_to_string;
    }

    public function get_colaborador_fk_loja_registro_to_string()
    {
        if(!empty($this->colaborador_fk_loja_registro_to_string))
        {
            return $this->colaborador_fk_loja_registro_to_string;
        }
    
        $values = Colaborador::where('loja_registro', '=', $this->id)->getIndexedArray('loja_registro','{fk_loja_registro->razao_social}');
        return implode(', ', $values);
    }

    public function set_colaborador_fk_cargo_to_string($colaborador_fk_cargo_to_string)
    {
        if(is_array($colaborador_fk_cargo_to_string))
        {
            $values = Cargo::where('id', 'in', $colaborador_fk_cargo_to_string)->getIndexedArray('id', 'id');
            $this->colaborador_fk_cargo_to_string = implode(', ', $values);
        }
        else
        {
            $this->colaborador_fk_cargo_to_string = $colaborador_fk_cargo_to_string;
        }

        $this->vdata['colaborador_fk_cargo_to_string'] = $this->colaborador_fk_cargo_to_string;
    }

    public function get_colaborador_fk_cargo_to_string()
    {
        if(!empty($this->colaborador_fk_cargo_to_string))
        {
            return $this->colaborador_fk_cargo_to_string;
        }
    
        $values = Colaborador::where('loja_registro', '=', $this->id)->getIndexedArray('cargo','{fk_cargo->id}');
        return implode(', ', $values);
    }

    public function set_colaborador_fk_salario_to_string($colaborador_fk_salario_to_string)
    {
        if(is_array($colaborador_fk_salario_to_string))
        {
            $values = Salario::where('id', 'in', $colaborador_fk_salario_to_string)->getIndexedArray('id', 'id');
            $this->colaborador_fk_salario_to_string = implode(', ', $values);
        }
        else
        {
            $this->colaborador_fk_salario_to_string = $colaborador_fk_salario_to_string;
        }

        $this->vdata['colaborador_fk_salario_to_string'] = $this->colaborador_fk_salario_to_string;
    }

    public function get_colaborador_fk_salario_to_string()
    {
        if(!empty($this->colaborador_fk_salario_to_string))
        {
            return $this->colaborador_fk_salario_to_string;
        }
    
        $values = Colaborador::where('loja_registro', '=', $this->id)->getIndexedArray('salario','{fk_salario->id}');
        return implode(', ', $values);
    }

    public function set_colaborador_fk_carga_horaria_to_string($colaborador_fk_carga_horaria_to_string)
    {
        if(is_array($colaborador_fk_carga_horaria_to_string))
        {
            $values = Escala::where('id', 'in', $colaborador_fk_carga_horaria_to_string)->getIndexedArray('id', 'id');
            $this->colaborador_fk_carga_horaria_to_string = implode(', ', $values);
        }
        else
        {
            $this->colaborador_fk_carga_horaria_to_string = $colaborador_fk_carga_horaria_to_string;
        }

        $this->vdata['colaborador_fk_carga_horaria_to_string'] = $this->colaborador_fk_carga_horaria_to_string;
    }

    public function get_colaborador_fk_carga_horaria_to_string()
    {
        if(!empty($this->colaborador_fk_carga_horaria_to_string))
        {
            return $this->colaborador_fk_carga_horaria_to_string;
        }
    
        $values = Colaborador::where('loja_registro', '=', $this->id)->getIndexedArray('carga_horaria','{fk_carga_horaria->id}');
        return implode(', ', $values);
    }

    public function set_colaborador_fk_escala_to_string($colaborador_fk_escala_to_string)
    {
        if(is_array($colaborador_fk_escala_to_string))
        {
            $values = Escala::where('id', 'in', $colaborador_fk_escala_to_string)->getIndexedArray('id', 'id');
            $this->colaborador_fk_escala_to_string = implode(', ', $values);
        }
        else
        {
            $this->colaborador_fk_escala_to_string = $colaborador_fk_escala_to_string;
        }

        $this->vdata['colaborador_fk_escala_to_string'] = $this->colaborador_fk_escala_to_string;
    }

    public function get_colaborador_fk_escala_to_string()
    {
        if(!empty($this->colaborador_fk_escala_to_string))
        {
            return $this->colaborador_fk_escala_to_string;
        }
    
        $values = Colaborador::where('loja_registro', '=', $this->id)->getIndexedArray('escala','{fk_escala->id}');
        return implode(', ', $values);
    }

    public function set_conta_tipo_conta_to_string($conta_tipo_conta_to_string)
    {
        if(is_array($conta_tipo_conta_to_string))
        {
            $values = TipoConta::where('id', 'in', $conta_tipo_conta_to_string)->getIndexedArray('nome', 'nome');
            $this->conta_tipo_conta_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_tipo_conta_to_string = $conta_tipo_conta_to_string;
        }

        $this->vdata['conta_tipo_conta_to_string'] = $this->conta_tipo_conta_to_string;
    }

    public function get_conta_tipo_conta_to_string()
    {
        if(!empty($this->conta_tipo_conta_to_string))
        {
            return $this->conta_tipo_conta_to_string;
        }
    
        $values = Conta::where('loja', '=', $this->id)->getIndexedArray('tipo_conta_id','{tipo_conta->nome}');
        return implode(', ', $values);
    }

    public function set_conta_fk_loja_to_string($conta_fk_loja_to_string)
    {
        if(is_array($conta_fk_loja_to_string))
        {
            $values = Loja::where('id', 'in', $conta_fk_loja_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->conta_fk_loja_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_fk_loja_to_string = $conta_fk_loja_to_string;
        }

        $this->vdata['conta_fk_loja_to_string'] = $this->conta_fk_loja_to_string;
    }

    public function get_conta_fk_loja_to_string()
    {
        if(!empty($this->conta_fk_loja_to_string))
        {
            return $this->conta_fk_loja_to_string;
        }
    
        $values = Conta::where('loja', '=', $this->id)->getIndexedArray('loja','{fk_loja->razao_social}');
        return implode(', ', $values);
    }

    public function set_conta_natureza_to_string($conta_natureza_to_string)
    {
        if(is_array($conta_natureza_to_string))
        {
            $values = Natureza::where('id', 'in', $conta_natureza_to_string)->getIndexedArray('nome', 'nome');
            $this->conta_natureza_to_string = implode(', ', $values);
        }
        else
        {
            $this->conta_natureza_to_string = $conta_natureza_to_string;
        }

        $this->vdata['conta_natureza_to_string'] = $this->conta_natureza_to_string;
    }

    public function get_conta_natureza_to_string()
    {
        if(!empty($this->conta_natureza_to_string))
        {
            return $this->conta_natureza_to_string;
        }
    
        $values = Conta::where('loja', '=', $this->id)->getIndexedArray('natureza_id','{natureza->nome}');
        return implode(', ', $values);
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
    
        $values = ContaBancaria::where('loja', '=', $this->id)->getIndexedArray('loja','{fk_loja->razao_social}');
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
    
        $values = ContaBancaria::where('loja', '=', $this->id)->getIndexedArray('fornecedor','{fk_fornecedor->razao_social}');
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
    
        $values = ContaBancaria::where('loja', '=', $this->id)->getIndexedArray('colaborador','{fk_colaborador->id}');
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
    
        $values = ContaBancaria::where('loja', '=', $this->id)->getIndexedArray('id_referencia_tipo','{referencia_tipo->conta_bancaria}');
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
    
        $values = ContaBancaria::where('loja', '=', $this->id)->getIndexedArray('banco','{fk_banco->id}');
        return implode(', ', $values);
    }

    public function set_deposito_fk_loja_to_string($deposito_fk_loja_to_string)
    {
        if(is_array($deposito_fk_loja_to_string))
        {
            $values = Loja::where('id', 'in', $deposito_fk_loja_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->deposito_fk_loja_to_string = implode(', ', $values);
        }
        else
        {
            $this->deposito_fk_loja_to_string = $deposito_fk_loja_to_string;
        }

        $this->vdata['deposito_fk_loja_to_string'] = $this->deposito_fk_loja_to_string;
    }

    public function get_deposito_fk_loja_to_string()
    {
        if(!empty($this->deposito_fk_loja_to_string))
        {
            return $this->deposito_fk_loja_to_string;
        }
    
        $values = Deposito::where('loja', '=', $this->id)->getIndexedArray('loja','{fk_loja->razao_social}');
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
    
        $values = ParcelasConta::where('loja_id', '=', $this->id)->getIndexedArray('conta_origem','{fk_conta_origem->id}');
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
    
        $values = ParcelasConta::where('loja_id', '=', $this->id)->getIndexedArray('loja_id','{loja->razao_social}');
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
    
        $values = ParcelasConta::where('loja_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
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
    
        $values = ParcelasConta::where('loja_id', '=', $this->id)->getIndexedArray('conta_bancaria_loja','{fk_conta_bancaria_loja->id}');
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
    
        $values = ParcelasConta::where('loja_id', '=', $this->id)->getIndexedArray('conta_bancaria_fornecedor','{fk_conta_bancaria_fornecedor->id}');
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
    
        $values = SubparcelasParcela::where('loja_id', '=', $this->id)->getIndexedArray('conta_origem','{fk_conta_origem->id}');
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
    
        $values = SubparcelasParcela::where('loja_id', '=', $this->id)->getIndexedArray('loja_id','{loja->razao_social}');
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
    
        $values = SubparcelasParcela::where('loja_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
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
    
        $values = SubparcelasParcela::where('loja_id', '=', $this->id)->getIndexedArray('conta_bancaria_loja','{fk_conta_bancaria_loja->id}');
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
    
        $values = SubparcelasParcela::where('loja_id', '=', $this->id)->getIndexedArray('conta_bancaria_fornecedor','{fk_conta_bancaria_fornecedor->id}');
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
    
        $values = SubparcelasParcela::where('loja_id', '=', $this->id)->getIndexedArray('id_parcela_mestre','{parcela_mestre->id}');
        return implode(', ', $values);
    }

    public function set_transferencia_colaborador_colaborador_to_string($transferencia_colaborador_colaborador_to_string)
    {
        if(is_array($transferencia_colaborador_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $transferencia_colaborador_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->transferencia_colaborador_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->transferencia_colaborador_colaborador_to_string = $transferencia_colaborador_colaborador_to_string;
        }

        $this->vdata['transferencia_colaborador_colaborador_to_string'] = $this->transferencia_colaborador_colaborador_to_string;
    }

    public function get_transferencia_colaborador_colaborador_to_string()
    {
        if(!empty($this->transferencia_colaborador_colaborador_to_string))
        {
            return $this->transferencia_colaborador_colaborador_to_string;
        }
    
        $values = TransferenciaColaborador::where('loja_destino', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
        return implode(', ', $values);
    }

    public function set_transferencia_colaborador_fk_loja_origem_to_string($transferencia_colaborador_fk_loja_origem_to_string)
    {
        if(is_array($transferencia_colaborador_fk_loja_origem_to_string))
        {
            $values = Loja::where('id', 'in', $transferencia_colaborador_fk_loja_origem_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->transferencia_colaborador_fk_loja_origem_to_string = implode(', ', $values);
        }
        else
        {
            $this->transferencia_colaborador_fk_loja_origem_to_string = $transferencia_colaborador_fk_loja_origem_to_string;
        }

        $this->vdata['transferencia_colaborador_fk_loja_origem_to_string'] = $this->transferencia_colaborador_fk_loja_origem_to_string;
    }

    public function get_transferencia_colaborador_fk_loja_origem_to_string()
    {
        if(!empty($this->transferencia_colaborador_fk_loja_origem_to_string))
        {
            return $this->transferencia_colaborador_fk_loja_origem_to_string;
        }
    
        $values = TransferenciaColaborador::where('loja_destino', '=', $this->id)->getIndexedArray('loja_origem','{fk_loja_origem->razao_social}');
        return implode(', ', $values);
    }

    public function set_transferencia_colaborador_fk_loja_destino_to_string($transferencia_colaborador_fk_loja_destino_to_string)
    {
        if(is_array($transferencia_colaborador_fk_loja_destino_to_string))
        {
            $values = Loja::where('id', 'in', $transferencia_colaborador_fk_loja_destino_to_string)->getIndexedArray('razao_social', 'razao_social');
            $this->transferencia_colaborador_fk_loja_destino_to_string = implode(', ', $values);
        }
        else
        {
            $this->transferencia_colaborador_fk_loja_destino_to_string = $transferencia_colaborador_fk_loja_destino_to_string;
        }

        $this->vdata['transferencia_colaborador_fk_loja_destino_to_string'] = $this->transferencia_colaborador_fk_loja_destino_to_string;
    }

    public function get_transferencia_colaborador_fk_loja_destino_to_string()
    {
        if(!empty($this->transferencia_colaborador_fk_loja_destino_to_string))
        {
            return $this->transferencia_colaborador_fk_loja_destino_to_string;
        }
    
        $values = TransferenciaColaborador::where('loja_destino', '=', $this->id)->getIndexedArray('loja_destino','{fk_loja_destino->razao_social}');
        return implode(', ', $values);
    }

    public function set_transferencia_colaborador_fk_motivo_transferencia_to_string($transferencia_colaborador_fk_motivo_transferencia_to_string)
    {
        if(is_array($transferencia_colaborador_fk_motivo_transferencia_to_string))
        {
            $values = MotivoTransferenciaColaborador::where('id', 'in', $transferencia_colaborador_fk_motivo_transferencia_to_string)->getIndexedArray('id', 'id');
            $this->transferencia_colaborador_fk_motivo_transferencia_to_string = implode(', ', $values);
        }
        else
        {
            $this->transferencia_colaborador_fk_motivo_transferencia_to_string = $transferencia_colaborador_fk_motivo_transferencia_to_string;
        }

        $this->vdata['transferencia_colaborador_fk_motivo_transferencia_to_string'] = $this->transferencia_colaborador_fk_motivo_transferencia_to_string;
    }

    public function get_transferencia_colaborador_fk_motivo_transferencia_to_string()
    {
        if(!empty($this->transferencia_colaborador_fk_motivo_transferencia_to_string))
        {
            return $this->transferencia_colaborador_fk_motivo_transferencia_to_string;
        }
    
        $values = TransferenciaColaborador::where('loja_destino', '=', $this->id)->getIndexedArray('motivo_transferencia','{fk_motivo_transferencia->id}');
        return implode(', ', $values);
    }

    
}

