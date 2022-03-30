<?php

class Grupo extends TRecord
{
    const TABLENAME  = 'grupo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const clientes = '2';
    const vendedores = '4';

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
    }

    /**
     * Method getPessoas
     */
    public function getPessoas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipo_pessoa', '=', $this->id));
        return Pessoa::getObjects( $criteria );
    }
    /**
     * Method getPessoaGrupos
     */
    public function getPessoaGrupos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('grupo_id', '=', $this->id));
        return PessoaGrupo::getObjects( $criteria );
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

        $values = Pessoa::where('tipo_pessoa', '=', $this->id)->getIndexedArray('tipo_pessoa','{fk_tipo_pessoa->nome}');
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

        $values = Pessoa::where('tipo_pessoa', '=', $this->id)->getIndexedArray('cidade_id','{cidade->nome}');
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

        $values = Pessoa::where('tipo_pessoa', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
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

        $values = PessoaGrupo::where('grupo_id', '=', $this->id)->getIndexedArray('grupo_id','{grupo->nome}');
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

        $values = PessoaGrupo::where('grupo_id', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
        return implode(', ', $values);
    }

}
