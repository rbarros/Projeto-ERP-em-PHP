<?php

class VendaAlt extends TRecord
{
    const TABLENAME  = 'venda_alt';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('n_venda');
        parent::addAttribute('id_interno');
        parent::addAttribute('cliente_id');
        parent::addAttribute('status');
        parent::addAttribute('vendedor_id');
        parent::addAttribute('estado_venda_id');
        parent::addAttribute('system_unit_id');
        parent::addAttribute('dt_venda');
        parent::addAttribute('obs');
        parent::addAttribute('valor_total');
        parent::addAttribute('total_desconto');
        parent::addAttribute('loja');
        parent::addAttribute('id_venda');
        parent::addAttribute('variavel_duplicidade');
        parent::addAttribute('forma_pagamento');
        parent::addAttribute('caixa');
        parent::addAttribute('func_caixa');
        parent::addAttribute('fiscal');
        parent::addAttribute('total_produtos');
        parent::addAttribute('total_pagamentos');
            
    }

    /**
     * Method getVendaItemAlts
     */
    public function getVendaItemAlts()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('venda_id', '=', $this->id));
        return VendaItemAlt::getObjects( $criteria );
    }

    public function set_venda_item_alt_produto_to_string($venda_item_alt_produto_to_string)
    {
        if(is_array($venda_item_alt_produto_to_string))
        {
            $values = Produto::where('id', 'in', $venda_item_alt_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->venda_item_alt_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->venda_item_alt_produto_to_string = $venda_item_alt_produto_to_string;
        }

        $this->vdata['venda_item_alt_produto_to_string'] = $this->venda_item_alt_produto_to_string;
    }

    public function get_venda_item_alt_produto_to_string()
    {
        if(!empty($this->venda_item_alt_produto_to_string))
        {
            return $this->venda_item_alt_produto_to_string;
        }
    
        $values = VendaItemAlt::where('venda_id', '=', $this->id)->getIndexedArray('produto_id','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_venda_item_alt_venda_to_string($venda_item_alt_venda_to_string)
    {
        if(is_array($venda_item_alt_venda_to_string))
        {
            $values = VendaAlt::where('id', 'in', $venda_item_alt_venda_to_string)->getIndexedArray('id', 'id');
            $this->venda_item_alt_venda_to_string = implode(', ', $values);
        }
        else
        {
            $this->venda_item_alt_venda_to_string = $venda_item_alt_venda_to_string;
        }

        $this->vdata['venda_item_alt_venda_to_string'] = $this->venda_item_alt_venda_to_string;
    }

    public function get_venda_item_alt_venda_to_string()
    {
        if(!empty($this->venda_item_alt_venda_to_string))
        {
            return $this->venda_item_alt_venda_to_string;
        }
    
        $values = VendaItemAlt::where('venda_id', '=', $this->id)->getIndexedArray('venda_id','{venda->id}');
        return implode(', ', $values);
    }

    
}

