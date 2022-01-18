<?php

class Salario extends TRecord
{
    const TABLENAME  = 'salario';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('valor');
        parent::addAttribute('decimo_terceiro');
        parent::addAttribute('bonificacao_valor');
            
    }

    /**
     * Method getCargos
     */
    public function getCargos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('salario', '=', $this->id));
        return Cargo::getObjects( $criteria );
    }
    /**
     * Method getColaboradors
     */
    public function getColaboradors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('salario', '=', $this->id));
        return Colaborador::getObjects( $criteria );
    }

    public function set_cargo_fk_salario_to_string($cargo_fk_salario_to_string)
    {
        if(is_array($cargo_fk_salario_to_string))
        {
            $values = Salario::where('id', 'in', $cargo_fk_salario_to_string)->getIndexedArray('id', 'id');
            $this->cargo_fk_salario_to_string = implode(', ', $values);
        }
        else
        {
            $this->cargo_fk_salario_to_string = $cargo_fk_salario_to_string;
        }

        $this->vdata['cargo_fk_salario_to_string'] = $this->cargo_fk_salario_to_string;
    }

    public function get_cargo_fk_salario_to_string()
    {
        if(!empty($this->cargo_fk_salario_to_string))
        {
            return $this->cargo_fk_salario_to_string;
        }
    
        $values = Cargo::where('salario', '=', $this->id)->getIndexedArray('salario','{fk_salario->id}');
        return implode(', ', $values);
    }

    public function set_cargo_fk_escala_to_string($cargo_fk_escala_to_string)
    {
        if(is_array($cargo_fk_escala_to_string))
        {
            $values = Escala::where('id', 'in', $cargo_fk_escala_to_string)->getIndexedArray('id', 'id');
            $this->cargo_fk_escala_to_string = implode(', ', $values);
        }
        else
        {
            $this->cargo_fk_escala_to_string = $cargo_fk_escala_to_string;
        }

        $this->vdata['cargo_fk_escala_to_string'] = $this->cargo_fk_escala_to_string;
    }

    public function get_cargo_fk_escala_to_string()
    {
        if(!empty($this->cargo_fk_escala_to_string))
        {
            return $this->cargo_fk_escala_to_string;
        }
    
        $values = Cargo::where('salario', '=', $this->id)->getIndexedArray('escala','{fk_escala->id}');
        return implode(', ', $values);
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
    
        $values = Colaborador::where('salario', '=', $this->id)->getIndexedArray('pessoa_id','{pessoa->nome}');
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
    
        $values = Colaborador::where('salario', '=', $this->id)->getIndexedArray('loja_registro','{fk_loja_registro->razao_social}');
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
    
        $values = Colaborador::where('salario', '=', $this->id)->getIndexedArray('cargo','{fk_cargo->id}');
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
    
        $values = Colaborador::where('salario', '=', $this->id)->getIndexedArray('salario','{fk_salario->id}');
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
    
        $values = Colaborador::where('salario', '=', $this->id)->getIndexedArray('carga_horaria','{fk_carga_horaria->id}');
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
    
        $values = Colaborador::where('salario', '=', $this->id)->getIndexedArray('escala','{fk_escala->id}');
        return implode(', ', $values);
    }

    
}

