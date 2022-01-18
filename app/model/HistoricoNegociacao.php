<?php

class HistoricoNegociacao extends TRecord
{
    const TABLENAME  = 'historico_negociacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $tipo_contato;
    private $negociacao;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('negociacao_id');
        parent::addAttribute('tipo_contato_id');
        parent::addAttribute('dt_contato');
        parent::addAttribute('descricao');
            
    }

    /**
     * Method set_tipo_contato
     * Sample of usage: $var->tipo_contato = $object;
     * @param $object Instance of TipoContato
     */
    public function set_tipo_contato(TipoContato $object)
    {
        $this->tipo_contato = $object;
        $this->tipo_contato_id = $object->id;
    }

    /**
     * Method get_tipo_contato
     * Sample of usage: $var->tipo_contato->attribute;
     * @returns TipoContato instance
     */
    public function get_tipo_contato()
    {
    
        // loads the associated object
        if (empty($this->tipo_contato))
            $this->tipo_contato = new TipoContato($this->tipo_contato_id);
    
        // returns the associated object
        return $this->tipo_contato;
    }
    /**
     * Method set_negociacao
     * Sample of usage: $var->negociacao = $object;
     * @param $object Instance of Negociacao
     */
    public function set_negociacao(Negociacao $object)
    {
        $this->negociacao = $object;
        $this->negociacao_id = $object->id;
    }

    /**
     * Method get_negociacao
     * Sample of usage: $var->negociacao->attribute;
     * @returns Negociacao instance
     */
    public function get_negociacao()
    {
    
        // loads the associated object
        if (empty($this->negociacao))
            $this->negociacao = new Negociacao($this->negociacao_id);
    
        // returns the associated object
        return $this->negociacao;
    }

    
}

