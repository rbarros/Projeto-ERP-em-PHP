<?php

class Pessoa extends TRecord
{
    const TABLENAME  = 'pessoa';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $cidade;
    private $colaborador;
    private $fk_tipo_pessoa;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('tipo_pessoa');
        parent::addAttribute('nome');
        parent::addAttribute('documento');
        parent::addAttribute('obs');
        parent::addAttribute('fone');
        parent::addAttribute('email');
        parent::addAttribute('cidade_id');
        parent::addAttribute('endereco');
        parent::addAttribute('estado_id');
        parent::addAttribute('cep');
        parent::addAttribute('dt_ativacao');
        parent::addAttribute('dt_desativacao');
        parent::addAttribute('id_cliente_pdv2');
        parent::addAttribute('id_cliente_pdv1');
        parent::addAttribute('colaborador_id');
    
    }

    /**
     * Method set_cidade
     * Sample of usage: $var->cidade = $object;
     * @param $object Instance of Cidade
     */
    public function set_cidade(Cidade $object)
    {
        $this->cidade = $object;
        $this->cidade_id = $object->id;
    }

    /**
     * Method get_cidade
     * Sample of usage: $var->cidade->attribute;
     * @returns Cidade instance
     */
    public function get_cidade()
    {
    
        // loads the associated object
        if (empty($this->cidade))
            $this->cidade = new Cidade($this->cidade_id);
    
        // returns the associated object
        return $this->cidade;
    }
    /**
     * Method set_colaborador
     * Sample of usage: $var->colaborador = $object;
     * @param $object Instance of Colaborador
     */
    public function set_colaborador(Colaborador $object)
    {
        $this->colaborador = $object;
        $this->colaborador_id = $object->id;
    }

    /**
     * Method get_colaborador
     * Sample of usage: $var->colaborador->attribute;
     * @returns Colaborador instance
     */
    public function get_colaborador()
    {
    
        // loads the associated object
        if (empty($this->colaborador))
            $this->colaborador = new Colaborador($this->colaborador_id);
    
        // returns the associated object
        return $this->colaborador;
    }
    /**
     * Method set_grupo
     * Sample of usage: $var->grupo = $object;
     * @param $object Instance of Grupo
     */
    public function set_fk_tipo_pessoa(Grupo $object)
    {
        $this->fk_tipo_pessoa = $object;
        $this->tipo_pessoa = $object->id;
    }

    /**
     * Method get_fk_tipo_pessoa
     * Sample of usage: $var->fk_tipo_pessoa->attribute;
     * @returns Grupo instance
     */
    public function get_fk_tipo_pessoa()
    {
    
        // loads the associated object
        if (empty($this->fk_tipo_pessoa))
            $this->fk_tipo_pessoa = new Grupo($this->tipo_pessoa);
    
        // returns the associated object
        return $this->fk_tipo_pessoa;
    }

    /**
     * Method getColaboradors
     */
    public function getColaboradors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return Colaborador::getObjects( $criteria );
    }
    /**
     * Method getContatos
     */
    public function getContatos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return Contato::getObjects( $criteria );
    }
    /**
     * Method getNegociacaos
     */
    public function getNegociacaosByVendedors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return Negociacao::getObjects( $criteria );
    }
    /**
     * Method getNegociacaos
     */
    public function getNegociacaosByClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return Negociacao::getObjects( $criteria );
    }
    /**
     * Method getOrcamentos
     */
    public function getOrcamentosByClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return Orcamento::getObjects( $criteria );
    }
    /**
     * Method getOrcamentos
     */
    public function getOrcamentosByVendedors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return Orcamento::getObjects( $criteria );
    }
    /**
     * Method getPessoaGrupos
     */
    public function getPessoaGrupos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pessoa_id', '=', $this->id));
        return PessoaGrupo::getObjects( $criteria );
    }
    /**
     * Method getProspeccaos
     */
    public function getProspeccaosByVendedors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vendedor_id', '=', $this->id));
        return Prospeccao::getObjects( $criteria );
    }
    /**
     * Method getProspeccaos
     */
    public function getProspeccaosByClientes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return Prospeccao::getObjects( $criteria );
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
    
        $values = Colaborador::where('pessoa_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
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
    
        $values = Colaborador::where('pessoa_id', '=', $this->id)->getIndexedArray('loja_registro','{fk_loja_registro->razao_social}');
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
    
        $values = Colaborador::where('pessoa_id', '=', $this->id)->getIndexedArray('cargo','{fk_cargo->id}');
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
    
        $values = Colaborador::where('pessoa_id', '=', $this->id)->getIndexedArray('salario','{fk_salario->id}');
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
    
        $values = Colaborador::where('pessoa_id', '=', $this->id)->getIndexedArray('carga_horaria','{fk_carga_horaria->id}');
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
    
        $values = Colaborador::where('pessoa_id', '=', $this->id)->getIndexedArray('escala','{fk_escala->id}');
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
    
        $values = Contato::where('pessoa_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
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
    
        $values = Contato::where('pessoa_id', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
        return implode(', ', $values);
    }

    public function set_negociacao_cliente_to_string($negociacao_cliente_to_string)
    {
        if(is_array($negociacao_cliente_to_string))
        {
            $values = Pessoa::where('id', 'in', $negociacao_cliente_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_cliente_to_string = $negociacao_cliente_to_string;
        }

        $this->vdata['negociacao_cliente_to_string'] = $this->negociacao_cliente_to_string;
    }

    public function get_negociacao_cliente_to_string()
    {
        if(!empty($this->negociacao_cliente_to_string))
        {
            return $this->negociacao_cliente_to_string;
        }
    
        $values = Negociacao::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->nome}');
        return implode(', ', $values);
    }

    public function set_negociacao_vendedor_to_string($negociacao_vendedor_to_string)
    {
        if(is_array($negociacao_vendedor_to_string))
        {
            $values = Pessoa::where('id', 'in', $negociacao_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_vendedor_to_string = $negociacao_vendedor_to_string;
        }

        $this->vdata['negociacao_vendedor_to_string'] = $this->negociacao_vendedor_to_string;
    }

    public function get_negociacao_vendedor_to_string()
    {
        if(!empty($this->negociacao_vendedor_to_string))
        {
            return $this->negociacao_vendedor_to_string;
        }
    
        $values = Negociacao::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_negociacao_origem_negociacao_to_string($negociacao_origem_negociacao_to_string)
    {
        if(is_array($negociacao_origem_negociacao_to_string))
        {
            $values = OrigemNegociacao::where('id', 'in', $negociacao_origem_negociacao_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_origem_negociacao_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_origem_negociacao_to_string = $negociacao_origem_negociacao_to_string;
        }

        $this->vdata['negociacao_origem_negociacao_to_string'] = $this->negociacao_origem_negociacao_to_string;
    }

    public function get_negociacao_origem_negociacao_to_string()
    {
        if(!empty($this->negociacao_origem_negociacao_to_string))
        {
            return $this->negociacao_origem_negociacao_to_string;
        }
    
        $values = Negociacao::where('cliente_id', '=', $this->id)->getIndexedArray('origem_negociacao_id','{origem_negociacao->nome}');
        return implode(', ', $values);
    }

    public function set_negociacao_tipo_negociacao_to_string($negociacao_tipo_negociacao_to_string)
    {
        if(is_array($negociacao_tipo_negociacao_to_string))
        {
            $values = TipoNegociacao::where('id', 'in', $negociacao_tipo_negociacao_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_tipo_negociacao_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_tipo_negociacao_to_string = $negociacao_tipo_negociacao_to_string;
        }

        $this->vdata['negociacao_tipo_negociacao_to_string'] = $this->negociacao_tipo_negociacao_to_string;
    }

    public function get_negociacao_tipo_negociacao_to_string()
    {
        if(!empty($this->negociacao_tipo_negociacao_to_string))
        {
            return $this->negociacao_tipo_negociacao_to_string;
        }
    
        $values = Negociacao::where('cliente_id', '=', $this->id)->getIndexedArray('tipo_negociacao_id','{tipo_negociacao->nome}');
        return implode(', ', $values);
    }

    public function set_negociacao_estado_negociacao_to_string($negociacao_estado_negociacao_to_string)
    {
        if(is_array($negociacao_estado_negociacao_to_string))
        {
            $values = EstadoNegociacao::where('id', 'in', $negociacao_estado_negociacao_to_string)->getIndexedArray('nome', 'nome');
            $this->negociacao_estado_negociacao_to_string = implode(', ', $values);
        }
        else
        {
            $this->negociacao_estado_negociacao_to_string = $negociacao_estado_negociacao_to_string;
        }

        $this->vdata['negociacao_estado_negociacao_to_string'] = $this->negociacao_estado_negociacao_to_string;
    }

    public function get_negociacao_estado_negociacao_to_string()
    {
        if(!empty($this->negociacao_estado_negociacao_to_string))
        {
            return $this->negociacao_estado_negociacao_to_string;
        }
    
        $values = Negociacao::where('cliente_id', '=', $this->id)->getIndexedArray('estado_negociacao_id','{estado_negociacao->nome}');
        return implode(', ', $values);
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
    
        $values = Orcamento::where('vendedor_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->nome}');
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
    
        $values = Orcamento::where('vendedor_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
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
    
        $values = Orcamento::where('vendedor_id', '=', $this->id)->getIndexedArray('estado_orcamento_id','{estado_orcamento->nome}');
        return implode(', ', $values);
    }

    public function set_pessoa_grupo_grupo_to_string($pessoa_grupo_grupo_to_string)
    {
        if(is_array($pessoa_grupo_grupo_to_string))
        {
            $values = Grupo::where('id', 'in', $pessoa_grupo_grupo_to_string)->getIndexedArray('nome', 'nome');
            $this->pessoa_grupo_grupo_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_grupo_grupo_to_string = $pessoa_grupo_grupo_to_string;
        }

        $this->vdata['pessoa_grupo_grupo_to_string'] = $this->pessoa_grupo_grupo_to_string;
    }

    public function get_pessoa_grupo_grupo_to_string()
    {
        if(!empty($this->pessoa_grupo_grupo_to_string))
        {
            return $this->pessoa_grupo_grupo_to_string;
        }
    
        $values = PessoaGrupo::where('pessoa_id', '=', $this->id)->getIndexedArray('grupo_id','{grupo->nome}');
        return implode(', ', $values);
    }

    public function set_pessoa_grupo_pessoa_to_string($pessoa_grupo_pessoa_to_string)
    {
        if(is_array($pessoa_grupo_pessoa_to_string))
        {
            $values = Pessoa::where('id', 'in', $pessoa_grupo_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->pessoa_grupo_pessoa_to_string = implode(', ', $values);
        }
        else
        {
            $this->pessoa_grupo_pessoa_to_string = $pessoa_grupo_pessoa_to_string;
        }

        $this->vdata['pessoa_grupo_pessoa_to_string'] = $this->pessoa_grupo_pessoa_to_string;
    }

    public function get_pessoa_grupo_pessoa_to_string()
    {
        if(!empty($this->pessoa_grupo_pessoa_to_string))
        {
            return $this->pessoa_grupo_pessoa_to_string;
        }
    
        $values = PessoaGrupo::where('pessoa_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
        return implode(', ', $values);
    }

    public function set_prospeccao_vendedor_to_string($prospeccao_vendedor_to_string)
    {
        if(is_array($prospeccao_vendedor_to_string))
        {
            $values = Pessoa::where('id', 'in', $prospeccao_vendedor_to_string)->getIndexedArray('nome', 'nome');
            $this->prospeccao_vendedor_to_string = implode(', ', $values);
        }
        else
        {
            $this->prospeccao_vendedor_to_string = $prospeccao_vendedor_to_string;
        }

        $this->vdata['prospeccao_vendedor_to_string'] = $this->prospeccao_vendedor_to_string;
    }

    public function get_prospeccao_vendedor_to_string()
    {
        if(!empty($this->prospeccao_vendedor_to_string))
        {
            return $this->prospeccao_vendedor_to_string;
        }
    
        $values = Prospeccao::where('cliente_id', '=', $this->id)->getIndexedArray('vendedor_id','{vendedor->nome}');
        return implode(', ', $values);
    }

    public function set_prospeccao_cliente_to_string($prospeccao_cliente_to_string)
    {
        if(is_array($prospeccao_cliente_to_string))
        {
            $values = Pessoa::where('id', 'in', $prospeccao_cliente_to_string)->getIndexedArray('nome', 'nome');
            $this->prospeccao_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->prospeccao_cliente_to_string = $prospeccao_cliente_to_string;
        }

        $this->vdata['prospeccao_cliente_to_string'] = $this->prospeccao_cliente_to_string;
    }

    public function get_prospeccao_cliente_to_string()
    {
        if(!empty($this->prospeccao_cliente_to_string))
        {
            return $this->prospeccao_cliente_to_string;
        }
    
        $values = Prospeccao::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->nome}');
        return implode(', ', $values);
    }

}

