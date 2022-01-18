<?php

class MotivoContingenciaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'contingencia';
    private static $activeRecord = 'MotivoContingencia';
    private static $primaryKey = 'id';
    private static $formName = 'form_MotivoContingencia';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Motivos da fila");


        $id = new TEntry('id');
        $reemissao = new TRadioGroup('reemissao');
        $tipo = new TRadioGroup('tipo');
        $motivo = new TText('motivo');
        $tratamento = new TText('tratamento');

        $reemissao->addValidation("Reemissao", new TRequiredValidator()); 
        $tipo->addValidation("Tipo", new TRequiredValidator()); 
        $motivo->addValidation("Motivo", new TRequiredValidator()); 

        $tipo->addItems(['venda'=>'venda','nfce'=>'nfce']);
        $reemissao->addItems(['venda'=>'venda','nfce'=>'nfce']);

        $id->setEditable(false);
        $motivo->setEditable(false);

        $tipo->setLayout('horizontal');
        $reemissao->setLayout('horizontal');

        $id->setSize(100);
        $tipo->setSize('100%');
        $reemissao->setSize(80);
        $motivo->setSize('100%', 70);
        $tratamento->setSize('100%', 70);

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[],[new TLabel("Reemissão:", null, '14px', null, '100%'),$reemissao],[new TLabel("Tipo:", null, '14px', null, '100%'),$tipo]);
        $row1->layout = [' col-sm-6 col-lg-2',' col-sm-2 col-lg-6',' col-sm-6 col-lg-2','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Motivo:", null, '14px', null, '100%'),$motivo],[new TLabel("Tratamento:", null, '14px', null, '100%'),$tratamento]);
        $row2->layout = [' col-sm-3 col-lg-6',' col-sm-2 col-lg-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Desenvolvedor","Motivos da fila"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new MotivoContingencia(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new MotivoContingencia($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    } 

}

