<?php

class SubparcelasParcela extends TRecord
{
    const TABLENAME  = 'subparcelas_parcela';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $fk_conta_origem;
    private $loja;
    private $fornecedor;
    private $fk_conta_bancaria_loja;
    private $parcela_mestre;
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
     * Method set_parcelas_conta
     * Sample of usage: $var->parcelas_conta = $object;
     * @param $object Instance of ParcelasConta
     */
    public function set_parcela_mestre(ParcelasConta $object)
    {
        $this->parcela_mestre = $object;
        $this->id_parcela_mestre = $object->id;
    }

    /**
     * Method get_parcela_mestre
     * Sample of usage: $var->parcela_mestre->attribute;
     * @returns ParcelasConta instance
     */
    public function get_parcela_mestre()
    {
    
        // loads the associated object
        if (empty($this->parcela_mestre))
            $this->parcela_mestre = new ParcelasConta($this->id_parcela_mestre);
    
        // returns the associated object
        return $this->parcela_mestre;
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

    
}

