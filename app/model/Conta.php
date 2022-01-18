<?php

class Conta extends TRecord
{
    const TABLENAME  = 'conta';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $natureza;
    private $tipo_conta;
    private $fk_loja;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo_conta_id');
        parent::addAttribute('loja');
        parent::addAttribute('natureza_id');
        parent::addAttribute('fornecedor');
        parent::addAttribute('forma_pagamento');
        parent::addAttribute('dt_emissao');
        parent::addAttribute('dt_vencimento');
        parent::addAttribute('valor');
        parent::addAttribute('desconto');
        parent::addAttribute('juros');
        parent::addAttribute('multa');
        parent::addAttribute('obs');
        parent::addAttribute('quitada');
        parent::addAttribute('parcelas');
    
    }

    /**
     * Method set_natureza
     * Sample of usage: $var->natureza = $object;
     * @param $object Instance of Natureza
     */
    public function set_natureza(Natureza $object)
    {
        $this->natureza = $object;
        $this->natureza_id = $object->id;
    }

    /**
     * Method get_natureza
     * Sample of usage: $var->natureza->attribute;
     * @returns Natureza instance
     */
    public function get_natureza()
    {
    
        // loads the associated object
        if (empty($this->natureza))
            $this->natureza = new Natureza($this->natureza_id);
    
        // returns the associated object
        return $this->natureza;
    }
    /**
     * Method set_tipo_conta
     * Sample of usage: $var->tipo_conta = $object;
     * @param $object Instance of TipoConta
     */
    public function set_tipo_conta(TipoConta $object)
    {
        $this->tipo_conta = $object;
        $this->tipo_conta_id = $object->id;
    }

    /**
     * Method get_tipo_conta
     * Sample of usage: $var->tipo_conta->attribute;
     * @returns TipoConta instance
     */
    public function get_tipo_conta()
    {
    
        // loads the associated object
        if (empty($this->tipo_conta))
            $this->tipo_conta = new TipoConta($this->tipo_conta_id);
    
        // returns the associated object
        return $this->tipo_conta;
    }
    /**
     * Method set_loja
     * Sample of usage: $var->loja = $object;
     * @param $object Instance of Loja
     */
    public function set_fk_loja(Loja $object)
    {
        $this->fk_loja = $object;
        $this->loja = $object->id;
    }

    /**
     * Method get_fk_loja
     * Sample of usage: $var->fk_loja->attribute;
     * @returns Loja instance
     */
    public function get_fk_loja()
    {
    
        // loads the associated object
        if (empty($this->fk_loja))
            $this->fk_loja = new Loja($this->loja);
    
        // returns the associated object
        return $this->fk_loja;
    }

    /**
     * Method getParcelasContas
     */
    public function getParcelasContas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('conta_origem', '=', $this->id));
        return ParcelasConta::getObjects( $criteria );
    }
    /**
     * Method getSubparcelasParcelas
     */
    public function getSubparcelasParcelas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('conta_origem', '=', $this->id));
        return SubparcelasParcela::getObjects( $criteria );
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
    
        $values = ParcelasConta::where('conta_origem', '=', $this->id)->getIndexedArray('conta_origem','{fk_conta_origem->id}');
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
    
        $values = ParcelasConta::where('conta_origem', '=', $this->id)->getIndexedArray('loja_id','{loja->razao_social}');
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
    
        $values = ParcelasConta::where('conta_origem', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
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
    
        $values = ParcelasConta::where('conta_origem', '=', $this->id)->getIndexedArray('conta_bancaria_loja','{fk_conta_bancaria_loja->id}');
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
    
        $values = ParcelasConta::where('conta_origem', '=', $this->id)->getIndexedArray('conta_bancaria_fornecedor','{fk_conta_bancaria_fornecedor->id}');
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
    
        $values = SubparcelasParcela::where('conta_origem', '=', $this->id)->getIndexedArray('conta_origem','{fk_conta_origem->id}');
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
    
        $values = SubparcelasParcela::where('conta_origem', '=', $this->id)->getIndexedArray('loja_id','{loja->razao_social}');
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
    
        $values = SubparcelasParcela::where('conta_origem', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
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
    
        $values = SubparcelasParcela::where('conta_origem', '=', $this->id)->getIndexedArray('conta_bancaria_loja','{fk_conta_bancaria_loja->id}');
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
    
        $values = SubparcelasParcela::where('conta_origem', '=', $this->id)->getIndexedArray('conta_bancaria_fornecedor','{fk_conta_bancaria_fornecedor->id}');
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
    
        $values = SubparcelasParcela::where('conta_origem', '=', $this->id)->getIndexedArray('id_parcela_mestre','{parcela_mestre->id}');
        return implode(', ', $values);
    }

}

