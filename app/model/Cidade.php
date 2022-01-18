<?php

class Cidade extends TRecord
{
    const TABLENAME  = 'cidade';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $estado;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('estado_id');
            
    }

    /**
     * Method set_estado
     * Sample of usage: $var->estado = $object;
     * @param $object Instance of Estado
     */
    public function set_estado(Estado $object)
    {
        $this->estado = $object;
        $this->estado_id = $object->id;
    }

    /**
     * Method get_estado
     * Sample of usage: $var->estado->attribute;
     * @returns Estado instance
     */
    public function get_estado()
    {
    
        // loads the associated object
        if (empty($this->estado))
            $this->estado = new Estado($this->estado_id);
    
        // returns the associated object
        return $this->estado;
    }

    /**
     * Method getFornecedors
     */
    public function getFornecedors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidade', '=', $this->id));
        return Fornecedor::getObjects( $criteria );
    }
    /**
     * Method getLojas
     */
    public function getLojas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidade', '=', $this->id));
        return Loja::getObjects( $criteria );
    }
    /**
     * Method getPessoas
     */
    public function getPessoas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cidade_id', '=', $this->id));
        return Pessoa::getObjects( $criteria );
    }

    public function set_fornecedor_fk_cidade_to_string($fornecedor_fk_cidade_to_string)
    {
        if(is_array($fornecedor_fk_cidade_to_string))
        {
            $values = Cidade::where('id', 'in', $fornecedor_fk_cidade_to_string)->getIndexedArray('nome', 'nome');
            $this->fornecedor_fk_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->fornecedor_fk_cidade_to_string = $fornecedor_fk_cidade_to_string;
        }

        $this->vdata['fornecedor_fk_cidade_to_string'] = $this->fornecedor_fk_cidade_to_string;
    }

    public function get_fornecedor_fk_cidade_to_string()
    {
        if(!empty($this->fornecedor_fk_cidade_to_string))
        {
            return $this->fornecedor_fk_cidade_to_string;
        }
    
        $values = Fornecedor::where('cidade', '=', $this->id)->getIndexedArray('cidade','{fk_cidade->nome}');
        return implode(', ', $values);
    }

    public function set_loja_fk_grupo_to_string($loja_fk_grupo_to_string)
    {
        if(is_array($loja_fk_grupo_to_string))
        {
            $values = GrupoLojas::where('id', 'in', $loja_fk_grupo_to_string)->getIndexedArray('nome', 'nome');
            $this->loja_fk_grupo_to_string = implode(', ', $values);
        }
        else
        {
            $this->loja_fk_grupo_to_string = $loja_fk_grupo_to_string;
        }

        $this->vdata['loja_fk_grupo_to_string'] = $this->loja_fk_grupo_to_string;
    }

    public function get_loja_fk_grupo_to_string()
    {
        if(!empty($this->loja_fk_grupo_to_string))
        {
            return $this->loja_fk_grupo_to_string;
        }
    
        $values = Loja::where('cidade', '=', $this->id)->getIndexedArray('grupo','{fk_grupo->nome}');
        return implode(', ', $values);
    }

    public function set_loja_fk_cidade_to_string($loja_fk_cidade_to_string)
    {
        if(is_array($loja_fk_cidade_to_string))
        {
            $values = Cidade::where('id', 'in', $loja_fk_cidade_to_string)->getIndexedArray('nome', 'nome');
            $this->loja_fk_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->loja_fk_cidade_to_string = $loja_fk_cidade_to_string;
        }

        $this->vdata['loja_fk_cidade_to_string'] = $this->loja_fk_cidade_to_string;
    }

    public function get_loja_fk_cidade_to_string()
    {
        if(!empty($this->loja_fk_cidade_to_string))
        {
            return $this->loja_fk_cidade_to_string;
        }
    
        $values = Loja::where('cidade', '=', $this->id)->getIndexedArray('cidade','{fk_cidade->nome}');
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
    
        $values = Pessoa::where('cidade_id', '=', $this->id)->getIndexedArray('tipo_pessoa','{fk_tipo_pessoa->nome}');
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
    
        $values = Pessoa::where('cidade_id', '=', $this->id)->getIndexedArray('cidade_id','{cidade->nome}');
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
    
        $values = Pessoa::where('cidade_id', '=', $this->id)->getIndexedArray('colaborador_id','{colaborador->id}');
        return implode(', ', $values);
    }

    
}

