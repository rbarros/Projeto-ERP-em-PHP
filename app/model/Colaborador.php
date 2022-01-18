<?php

class Colaborador extends TRecord
{
    const TABLENAME  = 'colaborador';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $fk_loja_registro;
    private $fk_cargo;
    private $fk_salario;
    private $fk_carga_horaria;
    private $pessoa;
    private $fk_escala;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('rg');
        parent::addAttribute('ctps');
        parent::addAttribute('cnh');
        parent::addAttribute('dt_registro');
        parent::addAttribute('dt_desligamento');
        parent::addAttribute('status_colaborador');
        parent::addAttribute('dt_nascimento');
        parent::addAttribute('contrato1');
        parent::addAttribute('contrato2');
        parent::addAttribute('salario_familia');
        parent::addAttribute('salario_familia_qtd');
        parent::addAttribute('bonificacao');
        parent::addAttribute('status_ferias');
        parent::addAttribute('loja_registro');
        parent::addAttribute('loja_atual');
        parent::addAttribute('cargo');
        parent::addAttribute('salario');
        parent::addAttribute('carga_horaria');
        parent::addAttribute('escala');
    
    }

    /**
     * Method set_loja
     * Sample of usage: $var->loja = $object;
     * @param $object Instance of Loja
     */
    public function set_fk_loja_registro(Loja $object)
    {
        $this->fk_loja_registro = $object;
        $this->loja_registro = $object->id;
    }

    /**
     * Method get_fk_loja_registro
     * Sample of usage: $var->fk_loja_registro->attribute;
     * @returns Loja instance
     */
    public function get_fk_loja_registro()
    {
    
        // loads the associated object
        if (empty($this->fk_loja_registro))
            $this->fk_loja_registro = new Loja($this->loja_registro);
    
        // returns the associated object
        return $this->fk_loja_registro;
    }
    /**
     * Method set_cargo
     * Sample of usage: $var->cargo = $object;
     * @param $object Instance of Cargo
     */
    public function set_fk_cargo(Cargo $object)
    {
        $this->fk_cargo = $object;
        $this->cargo = $object->id;
    }

    /**
     * Method get_fk_cargo
     * Sample of usage: $var->fk_cargo->attribute;
     * @returns Cargo instance
     */
    public function get_fk_cargo()
    {
    
        // loads the associated object
        if (empty($this->fk_cargo))
            $this->fk_cargo = new Cargo($this->cargo);
    
        // returns the associated object
        return $this->fk_cargo;
    }
    /**
     * Method set_salario
     * Sample of usage: $var->salario = $object;
     * @param $object Instance of Salario
     */
    public function set_fk_salario(Salario $object)
    {
        $this->fk_salario = $object;
        $this->salario = $object->id;
    }

    /**
     * Method get_fk_salario
     * Sample of usage: $var->fk_salario->attribute;
     * @returns Salario instance
     */
    public function get_fk_salario()
    {
    
        // loads the associated object
        if (empty($this->fk_salario))
            $this->fk_salario = new Salario($this->salario);
    
        // returns the associated object
        return $this->fk_salario;
    }
    /**
     * Method set_escala
     * Sample of usage: $var->escala = $object;
     * @param $object Instance of Escala
     */
    public function set_fk_carga_horaria(Escala $object)
    {
        $this->fk_carga_horaria = $object;
        $this->carga_horaria = $object->id;
    }

    /**
     * Method get_fk_carga_horaria
     * Sample of usage: $var->fk_carga_horaria->attribute;
     * @returns Escala instance
     */
    public function get_fk_carga_horaria()
    {
    
        // loads the associated object
        if (empty($this->fk_carga_horaria))
            $this->fk_carga_horaria = new Escala($this->carga_horaria);
    
        // returns the associated object
        return $this->fk_carga_horaria;
    }
    /**
     * Method set_pessoa
     * Sample of usage: $var->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_pessoa(Pessoa $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }

    /**
     * Method get_pessoa
     * Sample of usage: $var->pessoa->attribute;
     * @returns Pessoa instance
     */
    public function get_pessoa()
    {
    
        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoa($this->pessoa_id);
    
        // returns the associated object
        return $this->pessoa;
    }
    /**
     * Method set_escala
     * Sample of usage: $var->escala = $object;
     * @param $object Instance of Escala
     */
    public function set_fk_escala(Escala $object)
    {
        $this->fk_escala = $object;
        $this->escala = $object->id;
    }

    /**
     * Method get_fk_escala
     * Sample of usage: $var->fk_escala->attribute;
     * @returns Escala instance
     */
    public function get_fk_escala()
    {
    
        // loads the associated object
        if (empty($this->fk_escala))
            $this->fk_escala = new Escala($this->escala);
    
        // returns the associated object
        return $this->fk_escala;
    }

    /**
     * Method getAdvertencias
     */
    public function getAdvertencias()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('colaborador_id', '=', $this->id));
        return Advertencia::getObjects( $criteria );
    }
    /**
     * Method getAsos
     */
    public function getAsos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('colaborador_id', '=', $this->id));
        return Aso::getObjects( $criteria );
    }
    /**
     * Method getAtestados
     */
    public function getAtestados()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('colaborador_id', '=', $this->id));
        return Atestado::getObjects( $criteria );
    }
    /**
     * Method getCompraFuncionarios
     */
    public function getCompraFuncionarios()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('colaborador_id', '=', $this->id));
        return CompraFuncionario::getObjects( $criteria );
    }
    /**
     * Method getContaBancarias
     */
    public function getContaBancarias()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('colaborador', '=', $this->id));
        return ContaBancaria::getObjects( $criteria );
    }
    /**
     * Method getDocumentos
     */
    public function getDocumentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('colaborador_id', '=', $this->id));
        return Documento::getObjects( $criteria );
    }
    /**
     * Method getFeriass
     */
    public function getFeriass()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('colaborador_id', '=', $this->id));
        return Ferias::getObjects( $criteria );
    }
    /**
     * Method getPessoas
     */
    public function getPessoas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('colaborador_id', '=', $this->id));
        return Pessoa::getObjects( $criteria );
    }
    /**
     * Method getTransferenciaColaboradors
     */
    public function getTransferenciaColaboradors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('colaborador_id', '=', $this->id));
        return TransferenciaColaborador::getObjects( $criteria );
    }
    /**
     * Method getValeTransportes
     */
    public function getValeTransportes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('colaborador_id', '=', $this->id));
        return ValeTransporte::getObjects( $criteria );
    }

    public function set_advertencia_colaborador_to_string($advertencia_colaborador_to_string)
    {
        if(is_array($advertencia_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $advertencia_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->advertencia_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->advertencia_colaborador_to_string = $advertencia_colaborador_to_string;
        }

        $this->vdata['advertencia_colaborador_to_string'] = $this->advertencia_colaborador_to_string;
    }

    public function get_advertencia_colaborador_to_string()
    {
        if(!empty($this->advertencia_colaborador_to_string))
        {
            return $this->advertencia_colaborador_to_string;
        }
    
        $values = Advertencia::where('colaborador_id', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
        return implode(', ', $values);
    }

    public function set_aso_colaborador_to_string($aso_colaborador_to_string)
    {
        if(is_array($aso_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $aso_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->aso_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->aso_colaborador_to_string = $aso_colaborador_to_string;
        }

        $this->vdata['aso_colaborador_to_string'] = $this->aso_colaborador_to_string;
    }

    public function get_aso_colaborador_to_string()
    {
        if(!empty($this->aso_colaborador_to_string))
        {
            return $this->aso_colaborador_to_string;
        }
    
        $values = Aso::where('colaborador_id', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
        return implode(', ', $values);
    }

    public function set_atestado_colaborador_to_string($atestado_colaborador_to_string)
    {
        if(is_array($atestado_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $atestado_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->atestado_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->atestado_colaborador_to_string = $atestado_colaborador_to_string;
        }

        $this->vdata['atestado_colaborador_to_string'] = $this->atestado_colaborador_to_string;
    }

    public function get_atestado_colaborador_to_string()
    {
        if(!empty($this->atestado_colaborador_to_string))
        {
            return $this->atestado_colaborador_to_string;
        }
    
        $values = Atestado::where('colaborador_id', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
        return implode(', ', $values);
    }

    public function set_compra_funcionario_colaborador_to_string($compra_funcionario_colaborador_to_string)
    {
        if(is_array($compra_funcionario_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $compra_funcionario_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->compra_funcionario_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->compra_funcionario_colaborador_to_string = $compra_funcionario_colaborador_to_string;
        }

        $this->vdata['compra_funcionario_colaborador_to_string'] = $this->compra_funcionario_colaborador_to_string;
    }

    public function get_compra_funcionario_colaborador_to_string()
    {
        if(!empty($this->compra_funcionario_colaborador_to_string))
        {
            return $this->compra_funcionario_colaborador_to_string;
        }
    
        $values = CompraFuncionario::where('colaborador_id', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
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
    
        $values = ContaBancaria::where('colaborador', '=', $this->id)->getIndexedArray('loja','{fk_loja->razao_social}');
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
    
        $values = ContaBancaria::where('colaborador', '=', $this->id)->getIndexedArray('fornecedor','{fk_fornecedor->razao_social}');
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
    
        $values = ContaBancaria::where('colaborador', '=', $this->id)->getIndexedArray('colaborador','{fk_colaborador->id}');
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
    
        $values = ContaBancaria::where('colaborador', '=', $this->id)->getIndexedArray('id_referencia_tipo','{referencia_tipo->conta_bancaria}');
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
    
        $values = ContaBancaria::where('colaborador', '=', $this->id)->getIndexedArray('banco','{fk_banco->id}');
        return implode(', ', $values);
    }

    public function set_documento_colaborador_to_string($documento_colaborador_to_string)
    {
        if(is_array($documento_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $documento_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->documento_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->documento_colaborador_to_string = $documento_colaborador_to_string;
        }

        $this->vdata['documento_colaborador_to_string'] = $this->documento_colaborador_to_string;
    }

    public function get_documento_colaborador_to_string()
    {
        if(!empty($this->documento_colaborador_to_string))
        {
            return $this->documento_colaborador_to_string;
        }
    
        $values = Documento::where('colaborador_id', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
        return implode(', ', $values);
    }

    public function set_documento_fk_tipo_documento_to_string($documento_fk_tipo_documento_to_string)
    {
        if(is_array($documento_fk_tipo_documento_to_string))
        {
            $values = TipoDocumento::where('id', 'in', $documento_fk_tipo_documento_to_string)->getIndexedArray('id', 'id');
            $this->documento_fk_tipo_documento_to_string = implode(', ', $values);
        }
        else
        {
            $this->documento_fk_tipo_documento_to_string = $documento_fk_tipo_documento_to_string;
        }

        $this->vdata['documento_fk_tipo_documento_to_string'] = $this->documento_fk_tipo_documento_to_string;
    }

    public function get_documento_fk_tipo_documento_to_string()
    {
        if(!empty($this->documento_fk_tipo_documento_to_string))
        {
            return $this->documento_fk_tipo_documento_to_string;
        }
    
        $values = Documento::where('colaborador_id', '=', $this->id)->getIndexedArray('tipo_documento','{fk_tipo_documento->id}');
        return implode(', ', $values);
    }

    public function set_ferias_colaborador_to_string($ferias_colaborador_to_string)
    {
        if(is_array($ferias_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $ferias_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->ferias_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->ferias_colaborador_to_string = $ferias_colaborador_to_string;
        }

        $this->vdata['ferias_colaborador_to_string'] = $this->ferias_colaborador_to_string;
    }

    public function get_ferias_colaborador_to_string()
    {
        if(!empty($this->ferias_colaborador_to_string))
        {
            return $this->ferias_colaborador_to_string;
        }
    
        $values = Ferias::where('colaborador_id', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
        return implode(', ', $values);
    }

    public function set_pessoa_fk_tipo_pessoa_to_string($pessoa_fk_tipo_pessoa_to_string)
    {
        if(is_array($pessoa_fk_tipo_pessoa_to_string))
        {
            $values = Grupo::where('id', 'in', $pessoa_fk_tipo_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->pessoa_fk_tipo_pessoa_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_fk_tipo_pessoa_to_string = $pessoa_fk_tipo_pessoa_to_string;
        }

        $this->vdata['pessoa_fk_tipo_pessoa_to_string'] = $this->pessoa_fk_tipo_pessoa_to_string;
    }

    public function get_pessoa_fk_tipo_pessoa_to_string()
    {
        if(!empty($this->pessoa_fk_tipo_pessoa_to_string))
        {
            return $this->pessoa_fk_tipo_pessoa_to_string;
        }
    
        $values = Pessoa::where('colaborador_id', '=', $this->id)->getIndexedArray('tipo_pessoa','{fk_tipo_pessoa->nome}');
        return implode(', ', $values);
    }

    public function set_pessoa_cidade_to_string($pessoa_cidade_to_string)
    {
        if(is_array($pessoa_cidade_to_string))
        {
            $values = Cidade::where('id', 'in', $pessoa_cidade_to_string)->getIndexedArray('nome', 'nome');
            $this->pessoa_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_cidade_to_string = $pessoa_cidade_to_string;
        }

        $this->vdata['pessoa_cidade_to_string'] = $this->pessoa_cidade_to_string;
    }

    public function get_pessoa_cidade_to_string()
    {
        if(!empty($this->pessoa_cidade_to_string))
        {
            return $this->pessoa_cidade_to_string;
        }
    
        $values = Pessoa::where('colaborador_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->nome}');
        return implode(', ', $values);
    }

    public function set_pessoa_colaborador_to_string($pessoa_colaborador_to_string)
    {
        if(is_array($pessoa_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $pessoa_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->pessoa_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_colaborador_to_string = $pessoa_colaborador_to_string;
        }

        $this->vdata['pessoa_colaborador_to_string'] = $this->pessoa_colaborador_to_string;
    }

    public function get_pessoa_colaborador_to_string()
    {
        if(!empty($this->pessoa_colaborador_to_string))
        {
            return $this->pessoa_colaborador_to_string;
        }
    
        $values = Pessoa::where('colaborador_id', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
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
    
        $values = TransferenciaColaborador::where('colaborador_id', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
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
    
        $values = TransferenciaColaborador::where('colaborador_id', '=', $this->id)->getIndexedArray('loja_origem','{fk_loja_origem->razao_social}');
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
    
        $values = TransferenciaColaborador::where('colaborador_id', '=', $this->id)->getIndexedArray('loja_destino','{fk_loja_destino->razao_social}');
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
    
        $values = TransferenciaColaborador::where('colaborador_id', '=', $this->id)->getIndexedArray('motivo_transferencia','{fk_motivo_transferencia->id}');
        return implode(', ', $values);
    }

    public function set_vale_transporte_colaborador_to_string($vale_transporte_colaborador_to_string)
    {
        if(is_array($vale_transporte_colaborador_to_string))
        {
            $values = Colaborador::where('id', 'in', $vale_transporte_colaborador_to_string)->getIndexedArray('id', 'id');
            $this->vale_transporte_colaborador_to_string = implode(', ', $values);
        }
        else
        {
            $this->vale_transporte_colaborador_to_string = $vale_transporte_colaborador_to_string;
        }

        $this->vdata['vale_transporte_colaborador_to_string'] = $this->vale_transporte_colaborador_to_string;
    }

    public function get_vale_transporte_colaborador_to_string()
    {
        if(!empty($this->vale_transporte_colaborador_to_string))
        {
            return $this->vale_transporte_colaborador_to_string;
        }
    
        $values = ValeTransporte::where('colaborador_id', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
        return implode(', ', $values);
    }

    public function set_vale_transporte_fk_dias_uteis_to_string($vale_transporte_fk_dias_uteis_to_string)
    {
        if(is_array($vale_transporte_fk_dias_uteis_to_string))
        {
            $values = Mes::where('id', 'in', $vale_transporte_fk_dias_uteis_to_string)->getIndexedArray('descricao', 'descricao');
            $this->vale_transporte_fk_dias_uteis_to_string = implode(', ', $values);
        }
        else
        {
            $this->vale_transporte_fk_dias_uteis_to_string = $vale_transporte_fk_dias_uteis_to_string;
        }

        $this->vdata['vale_transporte_fk_dias_uteis_to_string'] = $this->vale_transporte_fk_dias_uteis_to_string;
    }

    public function get_vale_transporte_fk_dias_uteis_to_string()
    {
        if(!empty($this->vale_transporte_fk_dias_uteis_to_string))
        {
            return $this->vale_transporte_fk_dias_uteis_to_string;
        }
    
        $values = ValeTransporte::where('colaborador_id', '=', $this->id)->getIndexedArray('dias_uteis','{fk_dias_uteis->descricao}');
        return implode(', ', $values);
    }

}

