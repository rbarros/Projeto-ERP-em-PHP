<?php

class VendaItem extends TRecord
{
    const TABLENAME  = 'venda_item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $venda;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('produto_id');
        parent::addAttribute('deposito');
        parent::addAttribute('name');
        parent::addAttribute('venda_id');
        parent::addAttribute('quantidade');
        parent::addAttribute('valor_unitario');
        parent::addAttribute('valor_desconto');
        parent::addAttribute('valor_total');
        parent::addAttribute('SKU');
        parent::addAttribute('loja_id');
        parent::addAttribute('dt_venda');
        parent::addAttribute('cest');
        parent::addAttribute('ncm');
        parent::addAttribute('cfop');
        parent::addAttribute('percentual');
        parent::addAttribute('unidadeMedida');
        parent::addAttribute('situacaoTributaria');
        parent::addAttribute('origem');
        parent::addAttribute('categoria_produto');
        parent::addAttribute('fornecedor');
        parent::addAttribute('referencia');
        parent::addAttribute('marca');
    
    }

    /**
     * Method set_venda
     * Sample of usage: $var->venda = $object;
     * @param $object Instance of Venda
     */
    public function set_venda(Venda $object)
    {
        $this->venda = $object;
        $this->venda_id = $object->id;
    }

    /**
     * Method get_venda
     * Sample of usage: $var->venda->attribute;
     * @returns Venda instance
     */
    public function get_venda()
    {
    
        // loads the associated object
        if (empty($this->venda))
            $this->venda = new Venda($this->venda_id);
    
        // returns the associated object
        return $this->venda;
    }

}

