<?php

class GrupoLojas extends TRecord
{
    const TABLENAME  = 'grupo_lojas';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const fashionBiju = '1';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
            
    }

    /**
     * Method getLojas
     */
    public function getLojas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('grupo', '=', $this->id));
        return Loja::getObjects( $criteria );
    }

    public function set_loja_fk_grupo_to_string($loja_fk_grupo_to_string)
    {
        if(is_array($loja_fk_grupo_to_string))
        {
            $values = GrupoLojas::where('id', 'in', $loja_fk_grupo_to_string)->getIndexedArray('nome', 'nome');
            $this->loja_fk_grupo_to_string = implode(', ', $values);
        }
        else
        {
            $this->loja_fk_grupo_to_string = $loja_fk_grupo_to_string;
        }

        $this->vdata['loja_fk_grupo_to_string'] = $this->loja_fk_grupo_to_string;
    }

    public function get_loja_fk_grupo_to_string()
    {
        if(!empty($this->loja_fk_grupo_to_string))
        {
            return $this->loja_fk_grupo_to_string;
        }
    
        $values = Loja::where('grupo', '=', $this->id)->getIndexedArray('grupo','{fk_grupo->nome}');
        return implode(', ', $values);
    }

    public function set_loja_fk_cidade_to_string($loja_fk_cidade_to_string)
    {
        if(is_array($loja_fk_cidade_to_string))
        {
            $values = Cidade::where('id', 'in', $loja_fk_cidade_to_string)->getIndexedArray('nome', 'nome');
            $this->loja_fk_cidade_to_string = implode(', ', $values);
        }
        else
        {
            $this->loja_fk_cidade_to_string = $loja_fk_cidade_to_string;
        }

        $this->vdata['loja_fk_cidade_to_string'] = $this->loja_fk_cidade_to_string;
    }

    public function get_loja_fk_cidade_to_string()
    {
        if(!empty($this->loja_fk_cidade_to_string))
        {
            return $this->loja_fk_cidade_to_string;
        }
    
        $values = Loja::where('grupo', '=', $this->id)->getIndexedArray('cidade','{fk_cidade->nome}');
        return implode(', ', $values);
    }

    
}

