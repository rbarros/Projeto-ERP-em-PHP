<?php

class ClienteForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Pessoa';
    private static $primaryKey = 'id';
    private static $formName = 'form_ClienteForm';

    use BuilderMasterDetailTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Cadastro Cliente");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro Cliente");


        $id = new TEntry('id');
        $dt_ativacao = new TDate('dt_ativacao');
        $dt_desativacao = new TDate('dt_desativacao');
        $id_cliente_pdv1 = new TEntry('id_cliente_pdv1');
        $id_cliente_pdv2 = new TEntry('id_cliente_pdv2');
        $system_user_id = new TDBCombo('system_user_id', 'permission', 'SystemUsers', 'id', '{name}','name asc'  );
        $tipo_pessoa = new TDBCombo('tipo_pessoa', 'base_banco', 'Grupo', 'id', '{nome}','nome asc'  );
        $colaborador_id = new TDBCombo('colaborador_id', 'base_banco', 'Colaborador', 'id', '{id}','id asc'  );
        $nome = new TEntry('nome');
        $documento = new TEntry('documento');
        $obs = new TEntry('obs');
        $fone = new TEntry('fone');
        $email = new TEntry('email');
        $cidade_id = new TDBCombo('cidade_id', 'base_banco', 'Cidade', 'id', '{nome}','nome asc'  );
        $estado_id = new TDBCombo('estado_id', 'base_banco', 'Estado', 'id', '{nome}','nome asc'  );
        $endereco = new TEntry('endereco');
        $cep = new TEntry('cep');

        $dt_ativacao->addValidation("Data de ativação", new TRequiredValidator()); 
        $tipo_pessoa->addValidation("Tipo pessoa", new TRequiredValidator()); 
        $nome->addValidation("Nome", new TRequiredValidator()); 
        $email->addValidation("Email", new TRequiredValidator()); 
        $cidade_id->addValidation("Cidade", new TRequiredValidator()); 

        $email->setTip("Email");

        $dt_ativacao->setMask('dd/mm/yyyy');
        $dt_desativacao->setMask('dd/mm/yyyy');

        $dt_ativacao->setDatabaseMask('yyyy-mm-dd');
        $dt_desativacao->setDatabaseMask('yyyy-mm-dd');

        $tipo_pessoa->setValue('1');
        $endereco->setValue('null');

        $id->setEditable(false);
        $dt_desativacao->setEditable(false);
        $id_cliente_pdv1->setEditable(false);
        $id_cliente_pdv2->setEditable(false);

        $cep->setMaxLength(20);
        $nome->setMaxLength(50);
        $obs->setMaxLength(200);
        $fone->setMaxLength(20);
        $email->setMaxLength(50);
        $endereco->setMaxLength(400);
        $documento->setMaxLength(100);

        $id->setSize(100);
        $obs->setSize('100%');
        $cep->setSize('100%');
        $fone->setSize('100%');
        $nome->setSize('100%');
        $email->setSize('100%');
        $dt_ativacao->setSize(110);
        $endereco->setSize('100%');
        $documento->setSize('100%');
        $cidade_id->setSize('100%');
        $estado_id->setSize('100%');
        $tipo_pessoa->setSize('100%');
        $dt_desativacao->setSize(110);
        $system_user_id->setSize('100%');
        $colaborador_id->setSize('100%');
        $id_cliente_pdv1->setSize('100%');
        $id_cliente_pdv2->setSize('100%');

        $this->form->appendPage("Cadastro");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[],[new TLabel("Data de ativação:", null, '14px', null, '100%'),$dt_ativacao],[new TLabel("Data de desativação:", null, '14px', null, '100%'),$dt_desativacao],[new TLabel("Id cliente pdv1:", null, '14px', null, '100%'),$id_cliente_pdv1],[new TLabel("Id cliente pdv2:", null, '14px', null, '100%'),$id_cliente_pdv2]);
        $row1->layout = [' col-sm-6 col-lg-2','col-sm-2',' col-sm-6 col-lg-2','col-sm-2','col-sm-2','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Usuário:", null, '14px', null, '100%'),$system_user_id],[new TLabel("Tipo pessoa:", null, '14px', null, '100%'),$tipo_pessoa],[],[new TLabel("Colaborador id:", null, '14px', null, '100%'),$colaborador_id]);
        $row2->layout = ['col-sm-3','col-sm-3',' col-sm-2 col-lg-4',' col-sm-6 col-lg-2'];

        $row3 = $this->form->addFields([new TLabel("Nome:", null, '14px', null, '100%'),$nome],[new TLabel("CPF:", null, '14px', null, '100%'),$documento]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$obs],[],[new TLabel("Telefone:", null, '14px', null, '100%'),$fone],[new TLabel("Email:", null, '14px', null, '100%'),$email]);
        $row4->layout = ['col-sm-6','col-sm-2',' col-sm-6 col-lg-2','col-sm-2'];

        $row5 = $this->form->addFields([new TLabel("Cidade:", null, '14px', null, '100%'),$cidade_id],[new TLabel("Estado id:", null, '14px', null, '100%'),$estado_id],[new TLabel("Endereco:", null, '14px', null, '100%'),$endereco],[new TLabel("CEP:", null, '14px', null, '100%'),$cep]);
        $row5->layout = [' col-sm-6 col-lg-3',' col-sm-2 col-lg-3',' col-sm-6 col-lg-3',' col-sm-2 col-lg-3'];

        $this->form->appendPage("Vendas");

        $this->detailFormFk = new BootstrapFormBuilder('detailFormFk');
        $this->detailFormFk->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormFk->setProperty('class', 'form-horizontal builder-detail-form');

        $row6 = $this->detailFormFk->addFields([new THidden('_fk___row__id')]);
        $this->_fk__criteria = new TCriteria();

        $this->_fk__list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->_fk__list->disableHtmlConversion();;
        $this->_fk__list->generateHiddenFields();
        $this->_fk__list->setId('_fk__list');

        $this->_fk__list->style = 'width:100%';
        $this->_fk__list->class .= ' table-bordered';

        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Venda", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Data", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Loja", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Forma pagamento", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Valor total", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Status", 'left');

        $column__fk___row__data = new TDataGridColumn('__row__data', '', 'center');
        $column__fk___row__data->setVisibility(false);

        $action_onEdit = new TDataGridAction(array('VendaForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("");
        $action_onEdit->setImage('fas:shopping-cart #2196F3');
        $action_onEdit->setFields(['__row__id', '__row__data']);

        $action_onEdit->setParameter('key', '{entity_column_id:4140051}');
        $this->_fk__list->addAction($action_onEdit);

        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);

        $this->_fk__list->addColumn($column__fk___row__data);

        $this->_fk__list->createModel();
        $this->detailFormFk->addContent([$this->_fk__list]);

        $row7 = $this->form->addFields([$this->detailFormFk]);
        $row7->layout = [' col-sm-3 col-lg-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
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

            $object = new Pessoa(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            TTransaction::open('vendas_base');
            $_fk__items = $this->storeMasterDetailItems('', '', '_fk_', $object, $param['_fk__list___row__data'] ?? [], $this->form, $this->_fk__list, function($masterObject, $detailObject){ 

                //code here

            }); 
            TTransaction::close();
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

                $object = new Pessoa($key); // instantiates the Active Record 

                TTransaction::open('vendas_base');
                $_fk__items = $this->loadMasterDetailItems('', '', '_fk_', $object, $this->form, $this->_fk__list, $this->_fk__criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 
                TTransaction::close();
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

