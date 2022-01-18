<?php

class TransferenciaEtq extends TRecord
{
    const TABLENAME  = 'transferencia_etq';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $fk_deposito_env;
    private $estoque;
    private $produto;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('deposito_rec');
        parent::addAttribute('deposito_env');
        parent::addAttribute('estoque_id');
        parent::addAttribute('quantidade');
        parent::addAttribute('dt_registro');
        parent::addAttribute('usuario');
        parent::addAttribute('id_transferencia');
        parent::addAttribute('id_produto');
        parent::addAttribute('tipo_transferencia');
        parent::addAttribute('saldo_posterior');
            
    }

    /**
     * Method set_deposito
     * Sample of usage: $var->deposito = $object;
     * @param $object Instance of Deposito
     */
    public function set_fk_deposito_env(Deposito $object)
    {
        $this->fk_deposito_env = $object;
        $this->deposito_env = $object->id;
    }

    /**
     * Method get_fk_deposito_env
     * Sample of usage: $var->fk_deposito_env->attribute;
     * @returns Deposito instance
     */
    public function get_fk_deposito_env()
    {
    
        // loads the associated object
        if (empty($this->fk_deposito_env))
            $this->fk_deposito_env = new Deposito($this->deposito_env);
    
        // returns the associated object
        return $this->fk_deposito_env;
    }
    /**
     * Method set_prod_estoque
     * Sample of usage: $var->prod_estoque = $object;
     * @param $object Instance of ProdEstoque
     */
    public function set_estoque(ProdEstoque $object)
    {
        $this->estoque = $object;
        $this->estoque_id = $object->id;
    }

    /**
     * Method get_estoque
     * Sample of usage: $var->estoque->attribute;
     * @returns ProdEstoque instance
     */
    public function get_estoque()
    {
    
        // loads the associated object
        if (empty($this->estoque))
            $this->estoque = new ProdEstoque($this->estoque_id);
    
        // returns the associated object
        return $this->estoque;
    }
    /**
     * Method set_produto
     * Sample of usage: $var->produto = $object;
     * @param $object Instance of Produto
     */
    public function set_produto(Produto $object)
    {
        $this->produto = $object;
        $this->id_produto = $object->id;
    }

    /**
     * Method get_produto
     * Sample of usage: $var->produto->attribute;
     * @returns Produto instance
     */
    public function get_produto()
    {
    
        // loads the associated object
        if (empty($this->produto))
            $this->produto = new Produto($this->id_produto);
    
        // returns the associated object
        return $this->produto;
    }

    
}

