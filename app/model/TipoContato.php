<?php

class TipoContato extends TRecord
{
    const TABLENAME  = 'tipo_contato';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
            
    }

    /**
     * Method getHistoricoNegociacaos
     */
    public function getHistoricoNegociacaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tipo_contato_id', '=', $this->id));
        return HistoricoNegociacao::getObjects( $criteria );
    }

    public function set_historico_negociacao_negociacao_to_string($historico_negociacao_negociacao_to_string)
    {
        if(is_array($historico_negociacao_negociacao_to_string))
        {
            $values = Negociacao::where('id', 'in', $historico_negociacao_negociacao_to_string)->getIndexedArray('descricao', 'descricao');
            $this->historico_negociacao_negociacao_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_negociacao_negociacao_to_string = $historico_negociacao_negociacao_to_string;
        }

        $this->vdata['historico_negociacao_negociacao_to_string'] = $this->historico_negociacao_negociacao_to_string;
    }

    public function get_historico_negociacao_negociacao_to_string()
    {
        if(!empty($this->historico_negociacao_negociacao_to_string))
        {
            return $this->historico_negociacao_negociacao_to_string;
        }
    
        $values = HistoricoNegociacao::where('tipo_contato_id', '=', $this->id)->getIndexedArray('negociacao_id','{negociacao->descricao}');
        return implode(', ', $values);
    }

    public function set_historico_negociacao_tipo_contato_to_string($historico_negociacao_tipo_contato_to_string)
    {
        if(is_array($historico_negociacao_tipo_contato_to_string))
        {
            $values = TipoContato::where('id', 'in', $historico_negociacao_tipo_contato_to_string)->getIndexedArray('nome', 'nome');
            $this->historico_negociacao_tipo_contato_to_string = implode(', ', $values);
        }
        else
        {
            $this->historico_negociacao_tipo_contato_to_string = $historico_negociacao_tipo_contato_to_string;
        }

        $this->vdata['historico_negociacao_tipo_contato_to_string'] = $this->historico_negociacao_tipo_contato_to_string;
    }

    public function get_historico_negociacao_tipo_contato_to_string()
    {
        if(!empty($this->historico_negociacao_tipo_contato_to_string))
        {
            return $this->historico_negociacao_tipo_contato_to_string;
        }
    
        $values = HistoricoNegociacao::where('tipo_contato_id', '=', $this->id)->getIndexedArray('tipo_contato_id','{tipo_contato->nome}');
        return implode(', ', $values);
    }

    
}

