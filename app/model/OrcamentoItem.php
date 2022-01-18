<?php

class OrcamentoItem extends TRecord
{
    const TABLENAME  = 'orcamento_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $produto;
    private $orcamento;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('orcamento_id');
        parent::addAttribute('produto_id');
        parent::addAttribute('quantidade');
        parent::addAttribute('valor');
        parent::addAttribute('desconto');
            
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
    
        // loads the associated object
        if (empty($this->produto))
            $this->produto = new Produto($this->produto_id);
    
        // returns the associated object
        return $this->produto;
    }
    /**
     * Method set_orcamento
     * Sample of usage: $var->orcamento = $object;
     * @param $object Instance of Orcamento
     */
    public function set_orcamento(Orcamento $object)
    {
        $this->orcamento = $object;
        $this->orcamento_id = $object->id;
    }

    /**
     * Method get_orcamento
     * Sample of usage: $var->orcamento->attribute;
     * @returns Orcamento instance
     */
    public function get_orcamento()
    {
    
        // loads the associated object
        if (empty($this->orcamento))
            $this->orcamento = new Orcamento($this->orcamento_id);
    
        // returns the associated object
        return $this->orcamento;
    }

    
}

