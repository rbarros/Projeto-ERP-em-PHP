<?php

class Nfce extends TRecord
{
    const TABLENAME  = 'nfce';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('ambienteEmissao');
        parent::addAttribute('informacoesAdicionais');
        parent::addAttribute('presencaConsumidor');
        parent::addAttribute('numVenda');
        parent::addAttribute('status');
        parent::addAttribute('n_nfce');
        parent::addAttribute('link_cupom');
        parent::addAttribute('id_loja');
        parent::addAttribute('retorno_nfce');
        parent::addAttribute('venda_id');
        parent::addAttribute('dt_nfce');
    
    }

    /**
     * Method getNfceItems
     */
    public function getNfceItems()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('nfce_id', '=', $this->id));
        return NfceItem::getObjects( $criteria );
    }
    /**
     * Method getNfcePagamentos
     */
    public function getNfcePagamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('nfce_id', '=', $this->id));
        return NfcePagamento::getObjects( $criteria );
    }

    public function set_nfce_item_nfce_to_string($nfce_item_nfce_to_string)
    {
        if(is_array($nfce_item_nfce_to_string))
        {
            $values = Nfce::where('id', 'in', $nfce_item_nfce_to_string)->getIndexedArray('id', 'id');
            $this->nfce_item_nfce_to_string = implode(', ', $values);
        }
        else
        {
            $this->nfce_item_nfce_to_string = $nfce_item_nfce_to_string;
        }

        $this->vdata['nfce_item_nfce_to_string'] = $this->nfce_item_nfce_to_string;
    }

    public function get_nfce_item_nfce_to_string()
    {
        if(!empty($this->nfce_item_nfce_to_string))
        {
            return $this->nfce_item_nfce_to_string;
        }
    
        $values = NfceItem::where('nfce_id', '=', $this->id)->getIndexedArray('nfce_id','{nfce->id}');
        return implode(', ', $values);
    }

    public function set_nfce_pagamento_nfce_to_string($nfce_pagamento_nfce_to_string)
    {
        if(is_array($nfce_pagamento_nfce_to_string))
        {
            $values = Nfce::where('id', 'in', $nfce_pagamento_nfce_to_string)->getIndexedArray('id', 'id');
            $this->nfce_pagamento_nfce_to_string = implode(', ', $values);
        }
        else
        {
            $this->nfce_pagamento_nfce_to_string = $nfce_pagamento_nfce_to_string;
        }

        $this->vdata['nfce_pagamento_nfce_to_string'] = $this->nfce_pagamento_nfce_to_string;
    }

    public function get_nfce_pagamento_nfce_to_string()
    {
        if(!empty($this->nfce_pagamento_nfce_to_string))
        {
            return $this->nfce_pagamento_nfce_to_string;
        }
    
        $values = NfcePagamento::where('nfce_id', '=', $this->id)->getIndexedArray('nfce_id','{nfce->id}');
        return implode(', ', $values);
    }

}

