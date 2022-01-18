<?php

class JanelaCaixa extends TWindow
{
 public function __construct()
 {
     parent::__construct();
     parent::setSize(0.8, null);
     parent::removePadding();
     parent::removeTitleBar();
     parent::disableEscape();
     
     // with: 500, height: automatic
     parent::setSize(0.6, null); // use 0.6, 0.4 (for relative sizes 60%, 40%)
     
     // create the HTML Renderer
     $this->html = new THtmlRenderer('app/resources/modal.html');
     
     $replaces = [];
     $replaces['title'] = 'Panel title';
     $replaces['footer'] = 'Panel footer';
     $replaces['name'] = 'Someone famous';
     
     // replace the main section variables
     $this->html->enableSection('main', $replaces);
     
     parent::add($this->html); 
     }
}

