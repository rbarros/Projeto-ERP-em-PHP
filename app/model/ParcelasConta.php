<?php

class ParcelasConta extends TRecord
{
    const TABLENAME  = 'parcelas_conta';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $loja;
    private $fk_conta_bancaria_loja;
    private $fk_conta_origem;
    private $fornecedor;
    private $fk_conta_bancaria_fornecedor;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('conta_origem');
        parent::addAttribute('loja_id');
        parent::addAttribute('fornecedor_id');
        parent::addAttribute('valor');
        parent::addAttribute('forma_pagamento');
        parent::addAttribute('conta_bancaria_loja');
        parent::addAttribute('conta_bancaria_fornecedor');
        parent::addAttribute('vencimento');
        parent::addAttribute('quitada');
        parent::addAttribute('id_parcela_mestre');
        parent::addAttribute('tipo_parcela');
        parent::addAttribute('link_comprovante');
        parent::addAttribute('obs');
        parent::addAttribute('vazio1');
        parent::addAttribute('vazio2');
        parent::addAttribute('vazio3');
            
    }

    /**
     * Method set_loja
     * Sample of usage: $var->loja = $object;
     * @param $object Instance of Loja
     */
    public function set_loja(Loja $object)
    {
        $this->loja = $object;
        $this->loja_id = $object->id;
    }

    /**
     * Method get_loja
     * Sample of usage: $var->loja->attribute;
     * @returns Loja instance
     */
    public function get_loja()
    {
    
        // loads the associated object
        if (empty($this->loja))
            $this->loja = new Loja($this->loja_id);
    
        // returns the associated object
        return $this->loja;
    }
    /**
     * Method set_conta_bancaria
     * Sample of usage: $var->conta_bancaria = $object;
     * @param $object Instance of ContaBancaria
     */
    public function set_fk_conta_bancaria_loja(ContaBancaria $object)
    {
        $this->fk_conta_bancaria_loja = $object;
        $this->conta_bancaria_loja = $object->id;
    }

    /**
     * Method get_fk_conta_bancaria_loja
     * Sample of usage: $var->fk_conta_bancaria_loja->attribute;
     * @returns ContaBancaria instance
     */
    public function get_fk_conta_bancaria_loja()
    {
    
        // loads the associated object
        if (empty($this->fk_conta_bancaria_loja))
            $this->fk_conta_bancaria_loja = new ContaBancaria($this->conta_bancaria_loja);
    
        // returns the associated object
        return $this->fk_conta_bancaria_loja;
    }
    /**
     * Method set_conta
     * Sample of usage: $var->conta = $object;
     * @param $object Instance of Conta
     */
    public function set_fk_conta_origem(Conta $object)
    {
        $this->fk_conta_origem = $object;
        $this->conta_origem = $object->id;
    }

    /**
     * Method get_fk_conta_origem
     * Sample of usage: $var->fk_conta_origem->attribute;
     * @returns Conta instance
     */
    public function get_fk_conta_origem()
    {
    
        // loads the associated object
        if (empty($this->fk_conta_origem))
            $this->fk_conta_origem = new Conta($this->conta_origem);
    
        // returns the associated object
        return $this->fk_conta_origem;
    }
    /**
     * Method set_fornecedor
     * Sample of usage: $var->fornecedor = $object;
     * @param $object Instance of Fornecedor
     */
    public function set_fornecedor(Fornecedor $object)
    {
        $this->fornecedor = $object;
        $this->fornecedor_id = $object->id;
    }

    /**
     * Method get_fornecedor
     * Sample of usage: $var->fornecedor->attribute;
     * @returns Fornecedor instance
     */
    public function get_fornecedor()
    {
    
        // loads the associated object
        if (empty($this->fornecedor))
            $this->fornecedor = new Fornecedor($this->fornecedor_id);
    
        // returns the associated object
        return $this->fornecedor;
    }
    /**
     * Method set_conta_bancaria
     * Sample of usage: $var->conta_bancaria = $object;
     * @param $object Instance of ContaBancaria
     */
    public function set_fk_conta_bancaria_fornecedor(ContaBancaria $object)
    {
        $this->fk_conta_bancaria_fornecedor = $object;
        $this->conta_bancaria_fornecedor = $object->id;
    }

    /**
     * Method get_fk_conta_bancaria_fornecedor
     * Sample of usage: $var->fk_conta_bancaria_fornecedor->attribute;
     * @returns ContaBancaria instance
     */
    public function get_fk_conta_bancaria_fornecedor()
    {
    
        // loads the associated object
        if (empty($this->fk_conta_bancaria_fornecedor))
            $this->fk_conta_bancaria_fornecedor = new ContaBancaria($this->conta_bancaria_fornecedor);
    
        // returns the associated object
        return $this->fk_conta_bancaria_fornecedor;
    }

    /**
     * Method getSubparcelasParcelas
     */
    public function getSubparcelasParcelas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id_parcela_mestre', '=', $this->id));
        return SubparcelasParcela::getObjects( $criteria );
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
    
        $values = SubparcelasParcela::where('id_parcela_mestre', '=', $this->id)->getIndexedArray('conta_origem','{fk_conta_origem->id}');
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
    
        $values = SubparcelasParcela::where('id_parcela_mestre', '=', $this->id)->getIndexedArray('loja_id','{loja->razao_social}');
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
    
        $values = SubparcelasParcela::where('id_parcela_mestre', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
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
    
        $values = SubparcelasParcela::where('id_parcela_mestre', '=', $this->id)->getIndexedArray('conta_bancaria_loja','{fk_conta_bancaria_loja->id}');
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
    
        $values = SubparcelasParcela::where('id_parcela_mestre', '=', $this->id)->getIndexedArray('conta_bancaria_fornecedor','{fk_conta_bancaria_fornecedor->id}');
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
    
        $values = SubparcelasParcela::where('id_parcela_mestre', '=', $this->id)->getIndexedArray('id_parcela_mestre','{parcela_mestre->id}');
        return implode(', ', $values);
    }

    
}

