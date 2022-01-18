<?php

class Preco extends TRecord
{
    const TABLENAME  = 'Preco';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $tabela;
    private $produto;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('preco_venda');
        parent::addAttribute('preco_custo');
        parent::addAttribute('id_produto');
        parent::addAttribute('id_tabela');
            
    }

    /**
     * Method set_tabela_preco
     * Sample of usage: $var->tabela_preco = $object;
     * @param $object Instance of TabelaPreco
     */
    public function set_tabela(TabelaPreco $object)
    {
        $this->tabela = $object;
        $this->id_tabela = $object->id;
    }

    /**
     * Method get_tabela
     * Sample of usage: $var->tabela->attribute;
     * @returns TabelaPreco instance
     */
    public function get_tabela()
    {
    
        // loads the associated object
        if (empty($this->tabela))
            $this->tabela = new TabelaPreco($this->id_tabela);
    
        // returns the associated object
        return $this->tabela;
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

