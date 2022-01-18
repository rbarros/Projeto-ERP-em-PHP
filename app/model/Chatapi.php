<?php

class Chatapi extends TRecord
{
    const TABLENAME  = 'ChatAPi';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('bot_nome');
        parent::addAttribute('bot_token');
        parent::addAttribute('chat_id');
        parent::addAttribute('loja');
        parent::addAttribute('grupo_id');
            
    }

    
}

