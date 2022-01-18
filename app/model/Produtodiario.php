<?php

class Produtodiario extends TRecord
{
    const TABLENAME  = 'produtoDiario';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $produto;
    private $loja;
    private $estoque;
    private $deposito;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome_produto');
        parent::addAttribute('produto_id');
        parent::addAttribute('semanal');
        parent::addAttribute('valor_semanal');
        parent::addAttribute('quinzenal');
        parent::addAttribute('valor_quinzenal');
        parent::addAttribute('mensal');
        parent::addAttribute('valor_mensal');
        parent::addAttribute('dtAtualizacao');
        parent::addAttribute('loja_id');
        parent::addAttribute('estoque_id');
        parent::addAttribute('deposito_id');
    
    }

    /**
     * Method set_produto
     * Sample of usage: $var->produto = $object;
     * @param $object Instance of Produto
     */
    public function set_produto(Produto $object)
    {
        $this->produto = $object;
        $this->produto_id = $object->id;
    }

    /**
     * Method get_produto
     * Sample of usage: $var->produto->attribute;
     * @returns Produto instance
     */
    public function get_produto()
    {
        TTransaction::open('base_banco');
        // loads the associated object
        if (empty($this->produto))
            $this->produto = new Produto($this->produto_id);
        TTransaction::close();
        // returns the associated object
        return $this->produto;
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
        TTransaction::open('base_banco');
        // loads the associated object
        if (empty($this->loja))
            $this->loja = new Loja($this->loja_id);
        TTransaction::close();
        // returns the associated object
        return $this->loja;
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
        TTransaction::open('base_banco');
        // loads the associated object
        if (empty($this->estoque))
            $this->estoque = new ProdEstoque($this->estoque_id);
        TTransaction::close();
        // returns the associated object
        return $this->estoque;
    }
    /**
     * Method set_deposito
     * Sample of usage: $var->deposito = $object;
     * @param $object Instance of Deposito
     */
    public function set_deposito(Deposito $object)
    {
        $this->deposito = $object;
        $this->deposito_id = $object->id;
    }

    /**
     * Method get_deposito
     * Sample of usage: $var->deposito->attribute;
     * @returns Deposito instance
     */
    public function get_deposito()
    {
        TTransaction::open('base_banco');
        // loads the associated object
        if (empty($this->deposito))
            $this->deposito = new Deposito($this->deposito_id);
        TTransaction::close();
        // returns the associated object
        return $this->deposito;
    }

}

