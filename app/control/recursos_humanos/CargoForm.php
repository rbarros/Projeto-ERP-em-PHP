<?php

class CargoForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Cargo';
    private static $primaryKey = 'id';
    private static $formName = 'form_Cargo';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Cargos");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cargos");


        $id = new TEntry('id');
        $salario = new TNumeric('salario', '2', ',', '.' );
        $cargo = new TDBEntry('cargo', 'base_banco', 'Cargo', 'id','id asc'  );
        $escala = new TDBCombo('escala', 'base_banco', 'Escala', 'id', '{descricao}','id asc'  );
        $button__ = new TButton('button__');
        $descricao = new TText('descricao');

        $salario->addValidation("Salario", new TRequiredValidator()); 
        $cargo->addValidation("Cargo", new TRequiredValidator()); 

        $id->setEditable(false);
        $salario->setAllowNegative(false);
        $cargo->setDisplayMask('field');
        $button__->setAction(new TAction(['EscalaForm', 'onShow']), " ");
        $button__->addStyleClass('btn-default');
        $button__->setImage('fas:plus #FF0091');

        $id->setSize(100);
        $cargo->setSize('100%');
        $escala->setSize('100%');
        $salario->setSize('100%');
        $descricao->setSize('100%', 70);

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[]);
        $row1->layout = [' col-sm-6 col-lg-2','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Salario (R$):", null, '14px', null, '100%'),$salario],[new TLabel("Nome do cargo:", null, '14px', null, '100%'),$cargo],[new TLabel("Escala:", null, '14px', null, '100%'),$escala],[$button__]);
        $row2->layout = ['col-sm-6 col-lg-4','col-sm-6 col-lg-4',' col-sm-2 col-lg-3',' col-sm-2 col-lg-1'];

        $row3 = $this->form->addFields([new TLabel("Descrição do cargo:", null, '14px', null, '100%'),$descricao]);
        $row3->layout = [' col-sm-3 col-lg-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Cargo(); // create an empty object 

            $data = $this->form->getData(); // get form data as array

            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

                TWindow::closeWindow(parent::getId()); 

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

                $object = new Cargo($key); // instantiates the Active Record 

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

