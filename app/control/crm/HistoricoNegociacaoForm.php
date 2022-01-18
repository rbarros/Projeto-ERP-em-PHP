<?php

class HistoricoNegociacaoForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'HistoricoNegociacao';
    private static $primaryKey = 'id';
    private static $formName = 'form_HistoricoNegociacao';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Cadastro de histórico de conversação");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de histórico de conversação");


        $id = new TEntry('id');
        $negociacao_id = new THidden('negociacao_id');
        $descricao = new TText('descricao');
        $tipo_contato_id = new TDBCombo('tipo_contato_id', 'base_banco', 'TipoContato', 'id', '{nome}','nome asc'  );
        $dt_contato = new TDateTime('dt_contato');

        $tipo_contato_id->addValidation("Tipo do contato", new TRequiredValidator()); 
        $dt_contato->addValidation("Data do contato", new TRequiredValidator()); 

        $id->setEditable(false);
        $dt_contato->setMask('dd/mm/yyyy hh:ii');
        $dt_contato->setValue(date('d/m/Y H:i'));
        $dt_contato->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setSize(100);
        $dt_contato->setSize(150);
        $negociacao_id->setSize(200);
        $descricao->setSize('100%', 90);
        $tipo_contato_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id,$negociacao_id]);
        $row2 = $this->form->addFields([new TLabel("Descrição:", null, '14px', null)],[$descricao]);
        $row3 = $this->form->addFields([new TLabel("Tipo do contato:", '#ff0000', '14px', null)],[$tipo_contato_id],[new TLabel("Data do contato:", '#ff0000', '14px', null)],[$dt_contato]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'far:save #ffffff');
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

            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new HistoricoNegociacao(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $messageAction);
        }
        catch (Exception $e) // in case of exception
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
            if (isset($param['negociacao_id']))
            {
                $object = new stdClass();
                $object->negociacao_id = $param['negociacao_id'];

                $this->form->setData($object);
            }
            elseif (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new HistoricoNegociacao($key); // instantiates the Active Record 

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

