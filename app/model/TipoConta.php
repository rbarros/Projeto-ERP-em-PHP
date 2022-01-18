<?php

class TipoConta extends TRecord
{
    const TABLENAME  = 'tipo_conta';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const receber = '1';
    const pagar = '2';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
            
    }

    /**
     * Method getContas
     */
    public function getContas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipo_conta_id', '=', $this->id));
        return Conta::getObjects( $criteria );
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
    
        $values = Conta::where('tipo_conta_id', '=', $this->id)->getIndexedArray('tipo_conta_id','{tipo_conta->nome}');
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
    
        $values = Conta::where('tipo_conta_id', '=', $this->id)->getIndexedArray('loja','{fk_loja->razao_social}');
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
    
        $values = Conta::where('tipo_conta_id', '=', $this->id)->getIndexedArray('natureza_id','{natureza->nome}');
        return implode(', ', $values);
    }

    
}

