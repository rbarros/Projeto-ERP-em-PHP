<?php

class TabelaPreco extends TRecord
{
    const TABLENAME  = 'tabela_preco';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_preco');
        parent::addAttribute('nome_tabela_preco');
            
    }

    /**
     * Method getPrecos
     */
    public function getPrecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id_tabela', '=', $this->id));
        return Preco::getObjects( $criteria );
    }

    public function set_Preco_produto_to_string($Preco_produto_to_string)
    {
        if(is_array($Preco_produto_to_string))
        {
            $values = Produto::where('id', 'in', $Preco_produto_to_string)->getIndexedArray('descricao', 'descricao');
            $this->Preco_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->Preco_produto_to_string = $Preco_produto_to_string;
        }

        $this->vdata['Preco_produto_to_string'] = $this->Preco_produto_to_string;
    }

    public function get_Preco_produto_to_string()
    {
        if(!empty($this->Preco_produto_to_string))
        {
            return $this->Preco_produto_to_string;
        }
    
        $values = Preco::where('id_tabela', '=', $this->id)->getIndexedArray('id_produto','{produto->descricao}');
        return implode(', ', $values);
    }

    public function set_Preco_tabela_to_string($Preco_tabela_to_string)
    {
        if(is_array($Preco_tabela_to_string))
        {
            $values = TabelaPreco::where('id', 'in', $Preco_tabela_to_string)->getIndexedArray('nome_tabela_preco', 'nome_tabela_preco');
            $this->Preco_tabela_to_string = implode(', ', $values);
        }
        else
        {
            $this->Preco_tabela_to_string = $Preco_tabela_to_string;
        }

        $this->vdata['Preco_tabela_to_string'] = $this->Preco_tabela_to_string;
    }

    public function get_Preco_tabela_to_string()
    {
        if(!empty($this->Preco_tabela_to_string))
        {
            return $this->Preco_tabela_to_string;
        }
    
        $values = Preco::where('id_tabela', '=', $this->id)->getIndexedArray('id_tabela','{tabela->nome_tabela_preco}');
        return implode(', ', $values);
    }

    
}

