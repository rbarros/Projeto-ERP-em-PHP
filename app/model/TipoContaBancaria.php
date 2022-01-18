<?php

class TipoContaBancaria extends TRecord
{
    const TABLENAME  = 'tipo_conta_bancaria';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const Loja = '1';
    const Fornecedor = '2';
    const Colaborador = '3';
    const Cliente = '4';
    const Parceiro = '5';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('conta_bancaria');
            
    }

    /**
     * Method getContaBancarias
     */
    public function getContaBancarias()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id_referencia_tipo', '=', $this->id));
        return ContaBancaria::getObjects( $criteria );
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
    
        $values = ContaBancaria::where('id_referencia_tipo', '=', $this->id)->getIndexedArray('loja','{fk_loja->razao_social}');
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
    
        $values = ContaBancaria::where('id_referencia_tipo', '=', $this->id)->getIndexedArray('fornecedor','{fk_fornecedor->razao_social}');
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
    
        $values = ContaBancaria::where('id_referencia_tipo', '=', $this->id)->getIndexedArray('colaborador','{fk_colaborador->id}');
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
    
        $values = ContaBancaria::where('id_referencia_tipo', '=', $this->id)->getIndexedArray('id_referencia_tipo','{referencia_tipo->conta_bancaria}');
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
    
        $values = ContaBancaria::where('id_referencia_tipo', '=', $this->id)->getIndexedArray('banco','{fk_banco->id}');
        return implode(', ', $values);
    }

    
}

