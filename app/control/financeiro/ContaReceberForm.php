<?php

class ContaReceberForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Conta';
    private static $primaryKey = 'id';
    private static $formName = 'form_ContaReceberForm';

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
        $this->form->setFormTitle("Contas a Receber");


        $id = new TEntry('id');
        $tipo_conta_id = new TDBRadioGroup('tipo_conta_id', 'base_banco', 'TipoConta', 'id', '{nome}','nome asc'  );
        $natureza_id = new TDBCombo('natureza_id', 'base_banco', 'Natureza', 'id', '{nome}','nome asc'  );
        $dt_emissao = new TDate('dt_emissao');
        $dt_vencimento = new TDate('dt_vencimento');
        $loja = new TDBCombo('loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $fornecedor = new TDBCombo('fornecedor', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc'  );
        $forma_pagamento = new TEntry('forma_pagamento');
        $valor = new TNumeric('valor', '2', ',', '.' );
        $desconto = new TNumeric('desconto', '2', ',', '.' );
        $juros = new TNumeric('juros', '2', ',', '.' );
        $multa = new TNumeric('multa', '2', ',', '.' );
        $quitada = new TRadioGroup('quitada');
        $obs = new TText('obs');

        $tipo_conta_id->addValidation("Tipo da conta", new TRequiredValidator()); 
        $natureza_id->addValidation("Natureza", new TRequiredValidator()); 
        $dt_emissao->addValidation("Data de emissão", new TRequiredValidator()); 
        $dt_vencimento->addValidation("Data de vencimento", new TRequiredValidator()); 
        $loja->addValidation("Loja", new TRequiredValidator()); 
        $valor->addValidation("Valor", new TRequiredValidator()); 
        $quitada->addValidation("Quitada", new TRequiredValidator()); 

        $forma_pagamento->setMaxLength(50);
        $valor->setAllowNegative(false);
        $quitada->addItems(['t'=>'SIM','f'=>'NÃO']);

        $id->setEditable(false);
        $tipo_conta_id->setEditable(false);

        $quitada->setLayout('horizontal');
        $tipo_conta_id->setLayout('horizontal');

        $quitada->setValue('f');
        $tipo_conta_id->setValue('1');

        $dt_emissao->setMask('dd/mm/yyyy');
        $dt_vencimento->setMask('dd/mm/yyyy');

        $dt_emissao->setDatabaseMask('yyyy-mm-dd');
        $dt_vencimento->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(100);
        $loja->setSize('100%');
        $valor->setSize('100%');
        $juros->setSize('100%');
        $multa->setSize('100%');
        $quitada->setSize('100%');
        $obs->setSize('100%', 70);
        $desconto->setSize('100%');
        $dt_emissao->setSize('100%');
        $fornecedor->setSize('100%');
        $natureza_id->setSize('100%');
        $tipo_conta_id->setSize('100%');
        $dt_vencimento->setSize('100%');
        $forma_pagamento->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[]);
        $row1->layout = [' col-sm-6 col-lg-2',' col-sm-2 col-lg-10'];

        $row2 = $this->form->addFields([new TLabel("Tipo da conta:", null, '14px', null, '100%'),$tipo_conta_id],[new TLabel("Natureza:", null, '14px', null, '100%'),$natureza_id],[new TLabel("Data de emissão:", null, '14px', null, '100%'),$dt_emissao],[new TLabel("Data de vencimento:", null, '14px', null, '100%'),$dt_vencimento]);
        $row2->layout = [' col-sm-3 col-lg-3',' col-sm-3 col-lg-3',' col-sm-6 col-lg-3',' col-sm-2 col-lg-3'];

        $row3 = $this->form->addFields([new TLabel("Loja:", null, '14px', null, '100%'),$loja]);
        $row3->layout = [' col-sm-6 col-lg-12'];

        $row4 = $this->form->addFields([new TLabel("Fornecedor:", null, '14px', null, '100%'),$fornecedor],[new TLabel("Forma pagamento:", null, '14px', null, '100%'),$forma_pagamento]);
        $row4->layout = ['col-sm-6',' col-sm-6 col-lg-4'];

        $row5 = $this->form->addFields([new TLabel("Valor(R$):", null, '14px', null, '100%'),$valor],[new TLabel("Desconto:", null, '14px', null, '100%'),$desconto],[new TLabel("Juros:", null, '14px', null, '100%'),$juros],[new TLabel("Multa:", null, '14px', null, '100%'),$multa],[new TLabel("Quitada:", null, '14px', null, '100%'),$quitada]);
        $row5->layout = [' col-sm-6 col-lg-2',' col-sm-6 col-lg-2','col-sm-2','col-sm-2','col-sm-2'];

        $row6 = $this->form->addFields([new TLabel("Obs:", null, '14px', null, '100%'),$obs]);
        $row6->layout = [' col-sm-6 col-lg-12'];

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
            $container->add(TBreadCrumb::create(["Financeiro","Contas a Receber"]));
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

            $object = new Conta(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data
            $object->valor = $object->valor * -1;

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

                $object = new Conta($key); // instantiates the Active Record 

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

