<?php


class Caixa extends TPage {
    protected $form;
    private $formFields             = [];
    private static $database        = '';
    private static $activeRecord    = '';
    private static $primaryKey      = '';
    private static $formName        = 'caixa';
    
    public function __construct( $param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle("Caixa");

        $iframe = new TElement('iframe');
        
        $iframe->width          = '100%';
        $iframe->height         = '900px';
        $iframe->src            = "[link do pdv aqui]";
        $iframe->frameborder    = '0';
        $iframe->allow          = 'cookies';
        $this->iframe           = $iframe;

        $row1 = $this->form->addFields([$iframe]);
        $row1->layout = [' col-sm-6 col-lg-12'];
    
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Loja","Caixa"]));
        }
        $container->add($this->form);

        parent::add($container);

    }
    

        
        

    
}
