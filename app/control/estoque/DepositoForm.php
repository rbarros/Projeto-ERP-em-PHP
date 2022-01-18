<?php

class DepositoForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Deposito';
    private static $primaryKey = 'id';
    private static $formName = 'form_Deposito';

    //use Adianti\Base\AdiantiStandardListTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("Cadastro Depósito");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro Depósito");


        $id = new TEntry('id');
        $nome_deposito = new TDBEntry('nome_deposito', 'base_banco', 'Deposito', 'nome_deposito','nome_deposito asc'  );
        $loja = new TDBCombo('loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}','id asc'  );

        $nome_deposito->addValidation("Nome deposito", new TRequiredValidator()); 

        $nome_deposito->setDisplayMask('{nome_deposito}');
        $id->setEditable(false);

        $id->setSize('100%');
        $loja->setSize('100%');
        $nome_deposito->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Nome deposito:", null, '14px', null, '100%'),$nome_deposito]);
        $row1->layout = ['col-sm-2',' col-sm-6 col-lg-10'];

        $row2 = $this->form->addFields([new TLabel("Loja:", null, '14px', null, '100%'),$loja]);
        $row2->layout = [' col-sm-3 col-lg-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 


        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try 
        {
         TTransaction::open(self::$database); // open a transaction

            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Deposito(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 
            if($object->nomeLoja){
                $lojas = Loja::where('nome_fantasia','=',$object->nomeLoja)->load();
                if($lojas){
                    $loja = $lojas[0];
                    $loja->deposito = $object->id;
                    $loja->store();
                }
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', "Registro salvo", $messageAction); 

                TWindow::closeWindow(parent::getId()); 

        }
        catch (Exception $e) 
        {
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

                $object = new Deposito($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                    $var = [$key];
                    $valor = TSession::setValue('deposito', $var);

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

            //</autoCode>

}

