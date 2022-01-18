<?php

class Estado extends TRecord
{
    const TABLENAME  = 'estado';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('uf');
            
    }

    /**
     * Method getCidades
     */
    public function getCidades()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estado_id', '=', $this->id));
        return Cidade::getObjects( $criteria );
    }

    public function set_cidade_estado_to_string($cidade_estado_to_string)
    {
        if(is_array($cidade_estado_to_string))
        {
            $values = Estado::where('id', 'in', $cidade_estado_to_string)->getIndexedArray('nome', 'nome');
            $this->cidade_estado_to_string = implode(', ', $values);
        }
        else
        {
            $this->cidade_estado_to_string = $cidade_estado_to_string;
        }

        $this->vdata['cidade_estado_to_string'] = $this->cidade_estado_to_string;
    }

    public function get_cidade_estado_to_string()
    {
        if(!empty($this->cidade_estado_to_string))
        {
            return $this->cidade_estado_to_string;
        }
    
        $values = Cidade::where('estado_id', '=', $this->id)->getIndexedArray('estado_id','{estado->nome}');
        return implode(', ', $values);
    }

    
}

