<?php

class Cargo extends TRecord
{
    const TABLENAME  = 'cargo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const vendedor = '1';
    const administrativo = '2';

    private $fk_salario;
    private $fk_escala;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cargo');
        parent::addAttribute('salario');
        parent::addAttribute('descricao');
        parent::addAttribute('escala');
            
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
     * Method getColaboradors
     */
    public function getColaboradors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cargo', '=', $this->id));
        return Colaborador::getObjects( $criteria );
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
    
        $values = Colaborador::where('cargo', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
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
    
        $values = Colaborador::where('cargo', '=', $this->id)->getIndexedArray('loja_registro','{fk_loja_registro->razao_social}');
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
    
        $values = Colaborador::where('cargo', '=', $this->id)->getIndexedArray('cargo','{fk_cargo->id}');
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
    
        $values = Colaborador::where('cargo', '=', $this->id)->getIndexedArray('salario','{fk_salario->id}');
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
    
        $values = Colaborador::where('cargo', '=', $this->id)->getIndexedArray('carga_horaria','{fk_carga_horaria->id}');
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
    
        $values = Colaborador::where('cargo', '=', $this->id)->getIndexedArray('escala','{fk_escala->id}');
        return implode(', ', $values);
    }

    
}

