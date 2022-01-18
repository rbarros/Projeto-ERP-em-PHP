<?php

class ContaBancariaForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'ContaBancaria';
    private static $primaryKey = 'id';
    private static $formName = 'form_ContaBancaria';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Conta Bancária");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Conta Bancária");


        $id = new TEntry('id');
        $agencia = new TEntry('agencia');
        $numero_conta = new TEntry('numero_conta');
        $banco = new TDBCombo('banco', 'base_banco', 'Banco', 'id', '{cod} - {nome} ','nome asc'  );
        $nome = new TEntry('nome');
        $id_referencia_tipo = new TDBCombo('id_referencia_tipo', 'base_banco', 'TipoContaBancaria', 'id', '{conta_bancaria}','conta_bancaria asc'  );
        $loja = new TDBCombo('loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $fornecedor = new TDBCombo('fornecedor', 'base_banco', 'Fornecedor', 'id', '{razao_social}  {nome_fantasia} ','razao_social asc'  );
        $colaborador = new TDBCombo('colaborador', 'base_banco', 'Colaborador', 'id', '{pessoa_id}','id asc'  );
        $cliente = new TDBCombo('cliente', 'base_banco', 'Pessoa', 'id', '{nome}','nome asc'  );
        $parceiro = new TDBCombo('parceiro', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc'  );

        $id_referencia_tipo->setChangeAction(new TAction([$this,'onTipoConta']));

        $banco->addValidation("Banco", new TRequiredValidator()); 
        $nome->addValidation("Nome", new TRequiredValidator()); 
        $id_referencia_tipo->addValidation("identifica a qual tabela pertence o id referencia", new TRequiredValidator()); 

        $nome->setMaxLength(50);

        $id->setEditable(false);
        $loja->setEditable(false);
        $cliente->setEditable(false);
        $parceiro->setEditable(false);
        $fornecedor->setEditable(false);
        $colaborador->setEditable(false);

        $id->setSize(100);
        $nome->setSize('100%');
        $loja->setSize('100%');
        $banco->setSize('100%');
        $agencia->setSize('100%');
        $cliente->setSize('100%');
        $parceiro->setSize('100%');
        $fornecedor->setSize('100%');
        $colaborador->setSize('100%');
        $numero_conta->setSize('100%');
        $id_referencia_tipo->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[],[new TLabel("Agência:", null, '14px', null, '100%'),$agencia],[new TLabel("Nº da conta:", null, '14px', null, '100%'),$numero_conta],[new TLabel("Banco:", null, '14px', null, '100%'),$banco]);
        $row1->layout = [' col-sm-6 col-lg-1',' col-sm-2 col-lg-5',' col-sm-6 col-lg-2','col-sm-2','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Descrição da conta:", null, '14px', null, '100%'),$nome],[],[new TLabel("Tipo Conta:", null, '14px', null, '100%'),$id_referencia_tipo]);
        $row2->layout = [' col-sm-3 col-lg-6',' col-sm-2 col-lg-4',' col-sm-6 col-lg-2'];

        $row3 = $this->form->addFields([new TLabel("Conta da loja:", null, '14px', null)],[$loja]);
        $row3->layout = [' col-sm-3 col-lg-2',' col-sm-2 col-lg-10'];

        $row4 = $this->form->addFields([new TLabel("Conta do Fornecedor:", null, '14px', null, '100%')],[$fornecedor]);
        $row4->layout = [' col-sm-3 col-lg-2',' col-sm-6 col-lg-10'];

        $row5 = $this->form->addFields([new TLabel("Conta do funcionário:", null, '14px', null, '100%')],[$colaborador]);
        $row5->layout = [' col-sm-6 col-lg-2',' col-sm-6 col-lg-10'];

        $row6 = $this->form->addFields([new TLabel("Conta do cliente:", null, '14px', null, '100%')],[$cliente]);
        $row6->layout = [' col-sm-6 col-lg-2',' col-sm-6 col-lg-10'];

        $row7 = $this->form->addFields([new TLabel("Conta do parceiro:", null, '14px', null, '100%')],[$parceiro]);
        $row7->layout = [' col-sm-6 col-lg-2',' col-sm-6 col-lg-10'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onaction = $this->form->addAction("Voltar", new TAction([$this, 'onAction'],['static' => 1]), 'fas:arrow-left #ffffff');
        $this->btn_onaction = $btn_onaction;
        $btn_onaction->addStyleClass('btn-success'); 

        parent::add($this->form);

    }

    public static function onTipoConta($param = null) 
    {
        try 
        {

           if(isset($param['id_referencia_tipo'])){
               $tipo_conta = $param['id_referencia_tipo'];
               switch($tipo_conta){
                   case '1':
                       //enable
                       TDBCombo::enableField(self::$formName, 'loja');
                       //disable
                       TDBCombo::disableField(self::$formName, 'colaborador');
                       TDBCombo::disableField(self::$formName, 'fornecedor');
                       TDBCombo::disableField(self::$formName, 'cliente');
                       TDBCombo::disableField(self::$formName, 'parceiro');
                       break;
                   case '2':
                       //enable
                       TDBCombo::enableField(self::$formName, 'fornecedor');
                       //disable
                       TDBCombo::disableField(self::$formName, 'cliente');
                       TDBCombo::disableField(self::$formName, 'colaborador');
                       TDBCombo::disableField(self::$formName, 'loja');
                       TDBCombo::disableField(self::$formName, 'parceiro');
                       break;
                   case '3':
                       //enable
                       TDBCombo::enableField(self::$formName, 'colaborador');
                       //disable
                       TDBCombo::disableField(self::$formName, 'cliente');
                       TDBCombo::disableField(self::$formName, 'fornecedor');
                       TDBCombo::disableField(self::$formName, 'loja');
                       TDBCombo::disableField(self::$formName, 'parceiro');
                       break;
                   case '4':
                       //enable
                       TDBCombo::enableField(self::$formName, 'cliente');
                       //disable
                       TDBCombo::disableField(self::$formName, 'loja');
                       TDBCombo::disableField(self::$formName, 'colaborador');
                       TDBCombo::disableField(self::$formName, 'fornecedor');
                       TDBCombo::disableField(self::$formName, 'parceiro');
                       break;
                   case '5':
                       //enable
                       TDBCombo::enableField(self::$formName, 'parceiro');
                       //disable
                       TDBCombo::disableField(self::$formName, 'cliente');
                       TDBCombo::disableField(self::$formName, 'colaborador');
                       TDBCombo::disableField(self::$formName, 'fornecedor');
                       TDBCombo::disableField(self::$formName, 'loja');
                       break;
                   default:

                       break;
               }
           }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new ContaBancaria(); // create an empty object 

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
    public function onAction($param = null) 
    {
        try 
        {

    TApplication::loadPage('ContaBancariaList', 'onShow');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']) || isset($param['id']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new ContaBancaria($key); // instantiates the Active Record 

                if(isset($param['tipo_conta']) && $param['tipo_conta'] != null){
                    $object->id_referencia_tipo = $param['tipo_conta'];
                    $object->loja = $param['loja'];
                }

                switch($object->id_referencia_tipo){
                    case '1':
                       //enable
                       TDBCombo::enableField(self::$formName, 'loja');
                       //disable
                       TDBCombo::disableField(self::$formName, 'colaborador');
                       TDBCombo::disableField(self::$formName, 'fornecedor');
                       TDBCombo::disableField(self::$formName, 'cliente');
                       TDBCombo::disableField(self::$formName, 'parceiro');
                       break;
                   case '2':
                       //enable
                       TDBCombo::enableField(self::$formName, 'fornecedor');
                       //disable
                       TDBCombo::disableField(self::$formName, 'cliente');
                       TDBCombo::disableField(self::$formName, 'colaborador');
                       TDBCombo::disableField(self::$formName, 'loja');
                       TDBCombo::disableField(self::$formName, 'parceiro');
                       break;
                   case '3':
                       //enable
                       TDBCombo::enableField(self::$formName, 'colaborador');
                       //disable
                       TDBCombo::disableField(self::$formName, 'cliente');
                       TDBCombo::disableField(self::$formName, 'fornecedor');
                       TDBCombo::disableField(self::$formName, 'loja');
                       TDBCombo::disableField(self::$formName, 'parceiro');
                       break;
                   case '4':
                       //enable
                       TDBCombo::enableField(self::$formName, 'cliente');
                       //disable
                       TDBCombo::disableField(self::$formName, 'loja');
                       TDBCombo::disableField(self::$formName, 'colaborador');
                       TDBCombo::disableField(self::$formName, 'fornecedor');
                       TDBCombo::disableField(self::$formName, 'parceiro');
                       break;
                   case '5':
                       //enable
                       TDBCombo::enableField(self::$formName, 'parceiro');
                       //disable
                       TDBCombo::disableField(self::$formName, 'cliente');
                       TDBCombo::disableField(self::$formName, 'colaborador');
                       TDBCombo::disableField(self::$formName, 'fornecedor');
                       TDBCombo::disableField(self::$formName, 'loja');
                       break;
                   default:

                       break;
               }
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

