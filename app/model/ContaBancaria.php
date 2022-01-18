<?php

class ContaBancaria extends TRecord
{
    const TABLENAME  = 'conta_bancaria';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $referencia_tipo;
    private $fk_loja;
    private $fk_fornecedor;
    private $fk_colaborador;
    private $fk_banco;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('loja');
        parent::addAttribute('fornecedor');
        parent::addAttribute('colaborador');
        parent::addAttribute('cliente');
        parent::addAttribute('parceiro');
        parent::addAttribute('id_referencia_tipo');
        parent::addAttribute('nome');
        parent::addAttribute('agencia');
        parent::addAttribute('numero_conta');
        parent::addAttribute('banco');
            
    }

    /**
     * Method set_tipo_conta_bancaria
     * Sample of usage: $var->tipo_conta_bancaria = $object;
     * @param $object Instance of TipoContaBancaria
     */
    public function set_referencia_tipo(TipoContaBancaria $object)
    {
        $this->referencia_tipo = $object;
        $this->id_referencia_tipo = $object->id;
    }

    /**
     * Method get_referencia_tipo
     * Sample of usage: $var->referencia_tipo->attribute;
     * @returns TipoContaBancaria instance
     */
    public function get_referencia_tipo()
    {
    
        // loads the associated object
        if (empty($this->referencia_tipo))
            $this->referencia_tipo = new TipoContaBancaria($this->id_referencia_tipo);
    
        // returns the associated object
        return $this->referencia_tipo;
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
     * Method set_fornecedor
     * Sample of usage: $var->fornecedor = $object;
     * @param $object Instance of Fornecedor
     */
    public function set_fk_fornecedor(Fornecedor $object)
    {
        $this->fk_fornecedor = $object;
        $this->fornecedor = $object->id;
    }

    /**
     * Method get_fk_fornecedor
     * Sample of usage: $var->fk_fornecedor->attribute;
     * @returns Fornecedor instance
     */
    public function get_fk_fornecedor()
    {
    
        // loads the associated object
        if (empty($this->fk_fornecedor))
            $this->fk_fornecedor = new Fornecedor($this->fornecedor);
    
        // returns the associated object
        return $this->fk_fornecedor;
    }
    /**
     * Method set_colaborador
     * Sample of usage: $var->colaborador = $object;
     * @param $object Instance of Colaborador
     */
    public function set_fk_colaborador(Colaborador $object)
    {
        $this->fk_colaborador = $object;
        $this->colaborador = $object->id;
    }

    /**
     * Method get_fk_colaborador
     * Sample of usage: $var->fk_colaborador->attribute;
     * @returns Colaborador instance
     */
    public function get_fk_colaborador()
    {
    
        // loads the associated object
        if (empty($this->fk_colaborador))
            $this->fk_colaborador = new Colaborador($this->colaborador);
    
        // returns the associated object
        return $this->fk_colaborador;
    }
    /**
     * Method set_banco
     * Sample of usage: $var->banco = $object;
     * @param $object Instance of Banco
     */
    public function set_fk_banco(Banco $object)
    {
        $this->fk_banco = $object;
        $this->banco = $object->id;
    }

    /**
     * Method get_fk_banco
     * Sample of usage: $var->fk_banco->attribute;
     * @returns Banco instance
     */
    public function get_fk_banco()
    {
    
        // loads the associated object
        if (empty($this->fk_banco))
            $this->fk_banco = new Banco($this->banco);
    
        // returns the associated object
        return $this->fk_banco;
    }

    /**
     * Method getParcelasContas
     */
    public function getParcelasContasByFkContaBancariaLojas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('conta_bancaria_loja', '=', $this->id));
        return ParcelasConta::getObjects( $criteria );
    }
    /**
     * Method getParcelasContas
     */
    public function getParcelasContasByFkContaBancariaFornecedors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('conta_bancaria_fornecedor', '=', $this->id));
        return ParcelasConta::getObjects( $criteria );
    }
    /**
     * Method getSubparcelasParcelas
     */
    public function getSubparcelasParcelasByFkContaBancariaLojas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('conta_bancaria_loja', '=', $this->id));
        return SubparcelasParcela::getObjects( $criteria );
    }
    /**
     * Method getSubparcelasParcelas
     */
    public function getSubparcelasParcelasByFkContaBancariaFornecedors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('conta_bancaria_fornecedor', '=', $this->id));
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
    
        $values = ParcelasConta::where('conta_bancaria_fornecedor', '=', $this->id)->getIndexedArray('conta_origem','{fk_conta_origem->id}');
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
    
        $values = ParcelasConta::where('conta_bancaria_fornecedor', '=', $this->id)->getIndexedArray('loja_id','{loja->razao_social}');
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
    
        $values = ParcelasConta::where('conta_bancaria_fornecedor', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
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
    
        $values = ParcelasConta::where('conta_bancaria_fornecedor', '=', $this->id)->getIndexedArray('conta_bancaria_loja','{fk_conta_bancaria_loja->id}');
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
    
        $values = ParcelasConta::where('conta_bancaria_fornecedor', '=', $this->id)->getIndexedArray('conta_bancaria_fornecedor','{fk_conta_bancaria_fornecedor->id}');
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
    
        $values = SubparcelasParcela::where('conta_bancaria_fornecedor', '=', $this->id)->getIndexedArray('conta_origem','{fk_conta_origem->id}');
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
    
        $values = SubparcelasParcela::where('conta_bancaria_fornecedor', '=', $this->id)->getIndexedArray('loja_id','{loja->razao_social}');
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
    
        $values = SubparcelasParcela::where('conta_bancaria_fornecedor', '=', $this->id)->getIndexedArray('fornecedor_id','{fornecedor->razao_social}');
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
    
        $values = SubparcelasParcela::where('conta_bancaria_fornecedor', '=', $this->id)->getIndexedArray('conta_bancaria_loja','{fk_conta_bancaria_loja->id}');
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
    
        $values = SubparcelasParcela::where('conta_bancaria_fornecedor', '=', $this->id)->getIndexedArray('conta_bancaria_fornecedor','{fk_conta_bancaria_fornecedor->id}');
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
    
        $values = SubparcelasParcela::where('conta_bancaria_fornecedor', '=', $this->id)->getIndexedArray('id_parcela_mestre','{parcela_mestre->id}');
        return implode(', ', $values);
    }

    
}

