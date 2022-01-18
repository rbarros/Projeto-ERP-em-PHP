<?php

use Google\client;
use Google\service;

class FornecedorForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Fornecedor';
    private static $primaryKey = 'id';
    private static $formName = 'form_Fornecedor';

    use BuilderMasterDetailTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.70, null);
        parent::setTitle("Cadastro Fornecedor");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro Fornecedor");


        $id = new TEntry('id');
        $dt_ativacao = new TDate('dt_ativacao');
        $razao_social = new TEntry('razao_social');
        $nome_fantasia = new TEntry('nome_fantasia');
        $cnpj = new TEntry('cnpj');
        $observacao = new TText('observacao');
        $regime_tributario = new TCombo('regime_tributario');
        $inscr_estadual = new TEntry('inscr_estadual');
        $possui_ie = new TRadioGroup('possui_ie');
        $inscr_municipal = new TEntry('inscr_municipal');
        $icms = new TCombo('icms');
        $fk_cidade_estado_id = new TDBCombo('fk_cidade_estado_id', 'base_banco', 'Estado', 'id', '{nome}','nome asc'  );
        $rua = new TEntry('rua');
        $bairro = new TEntry('bairro');
        $cidade = new TDBCombo('cidade', 'base_banco', 'Cidade', 'id', '{nome}','nome asc'  );
        $numero = new TEntry('numero');
        $complemento = new TEntry('complemento');
        $fone = new TEntry('fone');
        $email = new TEntry('email');
        $contato_fornecedor_pessoa_id = new TEntry('contato_fornecedor_pessoa_id');
        $contato_fornecedor_id = new THidden('contato_fornecedor_id');
        $contato_fornecedor_nome = new TEntry('contato_fornecedor_nome');
        $contato_fornecedor_email = new TEntry('contato_fornecedor_email');
        $contato_fornecedor_telefone = new TEntry('contato_fornecedor_telefone');
        $contato_fornecedor_obs = new TText('contato_fornecedor_obs');
        $button_adicionar_contato_fornecedor = new TButton('button_adicionar_contato_fornecedor');
        $conta_bancaria_fk_fornecedor_id_referencia_tipo = new TDBCombo('conta_bancaria_fk_fornecedor_id_referencia_tipo', 'base_banco', 'TipoContaBancaria', 'id', '{conta_bancaria}','conta_bancaria asc'  );
        $conta_bancaria_fk_fornecedor_id = new THidden('conta_bancaria_fk_fornecedor_id');
        $conta_bancaria_fk_fornecedor_nome = new TEntry('conta_bancaria_fk_fornecedor_nome');
        $conta_bancaria_fk_fornecedor_agencia = new TEntry('conta_bancaria_fk_fornecedor_agencia');
        $conta_bancaria_fk_fornecedor_numero_conta = new TEntry('conta_bancaria_fk_fornecedor_numero_conta');
        $conta_bancaria_fk_fornecedor_banco = new TDBCombo('conta_bancaria_fk_fornecedor_banco', 'base_banco', 'Banco', 'id', '{cod}  {nome} ','nome asc'  );
        $button_adicionar_conta_bancaria_fk_fornecedor = new TButton('button_adicionar_conta_bancaria_fk_fornecedor');

        $cnpj->setExitAction(new TAction([$this,'validarCNPJ']));
        $inscr_estadual->setExitAction(new TAction([$this,'definirOpcaoIsento']));

        $dt_ativacao->addValidation("Dt ativacao", new TRequiredValidator()); 
        $razao_social->addValidation("Razao social", new TRequiredValidator()); 
        $cnpj->addValidation("Cnpj", new TRequiredValidator()); 
        $possui_ie->addValidation("Marcar a opção de  I.E.", new TRequiredValidator()); 
        $cidade->addValidation("Cidade", new TRequiredValidator()); 

        $dt_ativacao->setDatabaseMask('yyyy-mm-dd');
        $possui_ie->setLayout('horizontal');
        $button_adicionar_contato_fornecedor->setAction(new TAction([$this, 'onAddDetailContatoFornecedor'],['static' => 1]), "Adicionar");
        $button_adicionar_conta_bancaria_fk_fornecedor->setAction(new TAction([$this, 'onAddDetailContaBancariaFkFornecedor'],['static' => 1]), "Adicionar");

        $button_adicionar_contato_fornecedor->addStyleClass('btn-success');
        $button_adicionar_conta_bancaria_fk_fornecedor->addStyleClass('btn-success');

        $button_adicionar_contato_fornecedor->setImage('fas:plus #2ecc71');
        $button_adicionar_conta_bancaria_fk_fornecedor->setImage('fas:plus #2ecc71');

        $fone->setMask('(99)9-9999-9999');
        $dt_ativacao->setMask('dd/mm/yyyy');
        $cnpj->setMask('99.999.999/9999-99');

        $possui_ie->setValue('2');
        $dt_ativacao->setValue(date('d/m/Y'));
        $conta_bancaria_fk_fornecedor_id_referencia_tipo->setValue('2');

        $possui_ie->addItems(['1'=>'Sim','2'=>'Não']);
        $regime_tributario->addItems(['1'=>' Não definido','2'=>' Simples nacional','3'=>' Simples nacional - Excesso de sublimite de receita bruta','4'=>' Regime normal']);
        $icms->addItems(['1'=>' 1- Contribuinte ICMS','2'=>' 2- Contribuinte isento de inscrição no cadastro de Contribuintes','3'=>' 9- Não Contribuinte, que pode ou não possuir inscrição estadual no cadastro de contribuinte']);

        $icms->setDefaultOption(false);
        $regime_tributario->setDefaultOption(false);
        $conta_bancaria_fk_fornecedor_id_referencia_tipo->setDefaultOption(false);

        $button_adicionar_contato_fornecedor->id = '60d39e878bec1';
        $button_adicionar_conta_bancaria_fk_fornecedor->id = '60d39f304a987';

        $id->setEditable(false);
        $dt_ativacao->setEditable(false);
        $contato_fornecedor_pessoa_id->setEditable(false);
        $conta_bancaria_fk_fornecedor_id_referencia_tipo->setEditable(false);

        $inscr_estadual->setMaxLength(12);
        $inscr_municipal->setMaxLength(12);
        $contato_fornecedor_nome->setMaxLength(30);
        $contato_fornecedor_email->setMaxLength(30);
        $contato_fornecedor_telefone->setMaxLength(20);
        $conta_bancaria_fk_fornecedor_nome->setMaxLength(50);

        $rua->forceUpperCase();
        $bairro->forceUpperCase();
        $numero->forceUpperCase();
        $complemento->forceUpperCase();
        $razao_social->forceUpperCase();
        $nome_fantasia->forceUpperCase();
        $inscr_estadual->forceUpperCase();

        $id->setSize(100);
        $rua->setSize('100%');
        $icms->setSize('100%');
        $fone->setSize('100%');
        $cnpj->setSize('100%');
        $email->setSize('100%');
        $cidade->setSize('100%');
        $bairro->setSize('100%');
        $numero->setSize('100%');
        $possui_ie->setSize('100%');
        $dt_ativacao->setSize('100%');
        $complemento->setSize('100%');
        $razao_social->setSize('100%');
        $nome_fantasia->setSize('100%');
        $inscr_estadual->setSize('100%');
        $observacao->setSize('100%', 70);
        $inscr_municipal->setSize('100%');
        $regime_tributario->setSize('100%');
        $contato_fornecedor_id->setSize(200);
        $fk_cidade_estado_id->setSize('100%');
        $contato_fornecedor_nome->setSize('100%');
        $contato_fornecedor_email->setSize('100%');
        $contato_fornecedor_obs->setSize('100%', 70);
        $contato_fornecedor_telefone->setSize('100%');
        $contato_fornecedor_pessoa_id->setSize('100%');
        $conta_bancaria_fk_fornecedor_id->setSize(200);
        $conta_bancaria_fk_fornecedor_nome->setSize('100%');
        $conta_bancaria_fk_fornecedor_banco->setSize('100%');
        $conta_bancaria_fk_fornecedor_agencia->setSize('100%');
        $conta_bancaria_fk_fornecedor_numero_conta->setSize('100%');
        $conta_bancaria_fk_fornecedor_id_referencia_tipo->setSize('100%');

        $this->form->appendPage("Dados Cadastrais");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TFormSeparator("Dados Cadastrais", '#000000', '20', '#ff0091')]);
        $row1->layout = [' col-sm-12'];

        $row2 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Data de ativação:", '#ff0000', '14px', null, '100%'),$dt_ativacao]);
        $row2->layout = [' col-sm-10',' col-sm-2'];

        $row3 = $this->form->addFields([new TLabel("Razao social:", '#ff0000', '14px', null, '100%'),$razao_social],[new TLabel("Nome fantasia:", null, '14px', null, '100%'),$nome_fantasia]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("Cnpj:", '#ff0000', '14px', null, '100%'),$cnpj],[new TLabel("Observacao:", null, '14px', null, '100%'),$observacao]);
        $row4->layout = [' col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([new TLabel("Cód. Regime Tributário:", null, '14px', null, '100%'),$regime_tributario],[new TLabel("Inscrição Estadual:", null, '14px', null, '100%'),$inscr_estadual],[new TLabel(" possui I.E ?", null, '14px', null, '100%'),$possui_ie],[new TLabel("Inscrição Municipal:", null, '14px', null, '100%'),$inscr_municipal]);
        $row5->layout = [' col-sm-4 col-lg-3',' col-sm-3',' col-sm-2 col-lg-3',' col-sm-3'];

        $row6 = $this->form->addFields([new TLabel("Contribuinte ICMS:", null, '14px', null, '100%'),$icms],[],[]);
        $row6->layout = ['col-sm-3','col-sm-3','col-sm-6'];

        $this->form->appendPage("Endereço");
        $row7 = $this->form->addFields([new TFormSeparator("Endereço", '#000000', '20', '#ff0091')]);
        $row7->layout = [' col-sm-12'];

        $row8 = $this->form->addFields([new TLabel("UF:", '#ff0000', '14px', null, '100%'),$fk_cidade_estado_id,new TLabel("Rua:", null, '14px', null, '100%'),$rua,new TLabel("Bairro:", null, '14px', null, '100%'),$bairro],[new TLabel("Cidade:", '#ff0000', '14px', null, '100%'),$cidade,new TLabel("Numero:", null, '14px', null, '100%'),$numero,new TLabel("Complemento:", null, '14px', null, '100%'),$complemento]);
        $row8->layout = [' col-sm-6',' col-sm-6'];

        $row9 = $this->form->addFields([new TLabel("Fone:", null, '14px', null, '100%'),$fone],[new TLabel("Email:", null, '14px', null, '100%'),$email]);
        $row9->layout = ['col-sm-6','col-sm-6'];

        $this->form->appendPage("Contato");

        $this->detailFormContatoFornecedor = new BootstrapFormBuilder('detailFormContatoFornecedor');
        $this->detailFormContatoFornecedor->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormContatoFornecedor->setProperty('class', 'form-horizontal builder-detail-form');

        $row10 = $this->detailFormContatoFornecedor->addFields([new TFormSeparator("Contato", '#333333', '18', '#ff0091')]);
        $row10->layout = [' col-sm-12'];

        $row11 = $this->detailFormContatoFornecedor->addFields([new TLabel("id:", null, '14px', null, '100%'),$contato_fornecedor_pessoa_id,$contato_fornecedor_id],[new TLabel("Nome do contato:", null, '14px', null, '100%'),$contato_fornecedor_nome],[new TLabel("Email:", null, '14px', null, '100%'),$contato_fornecedor_email],[new TLabel("Tefone:", null, '14px', null, '100%'),$contato_fornecedor_telefone]);
        $row11->layout = [' col-sm-6 col-lg-1',' col-sm-2 col-lg-4',' col-sm-6 col-lg-4',' col-sm-2 col-lg-3'];

        $row12 = $this->detailFormContatoFornecedor->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$contato_fornecedor_obs]);
        $row12->layout = [' col-sm-12'];

        $row13 = $this->detailFormContatoFornecedor->addFields([],[$button_adicionar_contato_fornecedor],[]);
        $row13->layout = [' col-sm-12 col-lg-4',' col-sm-2 col-lg-4',' col-sm-2 col-lg-4'];

        $row14 = $this->detailFormContatoFornecedor->addFields([new THidden('contato_fornecedor__row__id')]);
        $this->contato_fornecedor_criteria = new TCriteria();

        $this->contato_fornecedor_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->contato_fornecedor_list->disableHtmlConversion();;
        $this->contato_fornecedor_list->generateHiddenFields();
        $this->contato_fornecedor_list->setId('contato_fornecedor_list');

        $this->contato_fornecedor_list->style = 'width:100%';
        $this->contato_fornecedor_list->class .= ' table-bordered';

        $column_contato_fornecedor_pessoa_nome = new TDataGridColumn('pessoa->nome', "Pessoa", 'left');
        $column_contato_fornecedor_email = new TDataGridColumn('email', "Email", 'left');
        $column_contato_fornecedor_nome = new TDataGridColumn('nome', "Nome", 'left');
        $column_contato_fornecedor_telefone = new TDataGridColumn('telefone', "Tefone", 'left');
        $column_contato_fornecedor_obs = new TDataGridColumn('obs', "Observação", 'left');

        $column_contato_fornecedor__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_contato_fornecedor__row__data->setVisibility(false);

        $action_onEditDetailContato = new TDataGridAction(array('FornecedorForm', 'onEditDetailContato'));
        $action_onEditDetailContato->setUseButton(false);
        $action_onEditDetailContato->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailContato->setLabel("Editar");
        $action_onEditDetailContato->setImage('far:edit #478fca');
        $action_onEditDetailContato->setFields(['__row__id', '__row__data']);

        $this->contato_fornecedor_list->addAction($action_onEditDetailContato);
        $action_onDeleteDetailContato = new TDataGridAction(array('FornecedorForm', 'onDeleteDetailContato'));
        $action_onDeleteDetailContato->setUseButton(false);
        $action_onDeleteDetailContato->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailContato->setLabel("Excluir");
        $action_onDeleteDetailContato->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailContato->setFields(['__row__id', '__row__data']);

        $this->contato_fornecedor_list->addAction($action_onDeleteDetailContato);

        $this->contato_fornecedor_list->addColumn($column_contato_fornecedor_pessoa_nome);
        $this->contato_fornecedor_list->addColumn($column_contato_fornecedor_email);
        $this->contato_fornecedor_list->addColumn($column_contato_fornecedor_nome);
        $this->contato_fornecedor_list->addColumn($column_contato_fornecedor_telefone);
        $this->contato_fornecedor_list->addColumn($column_contato_fornecedor_obs);

        $this->contato_fornecedor_list->addColumn($column_contato_fornecedor__row__data);

        $this->contato_fornecedor_list->createModel();
        $this->detailFormContatoFornecedor->addContent([$this->contato_fornecedor_list]);

        $row15 = $this->form->addFields([$this->detailFormContatoFornecedor],[]);
        $row15->layout = [' col-sm-12 col-lg-12'];

        $this->form->appendPage("Contas Bancárias");

        $this->detailFormContaBancariaFkFornecedor = new BootstrapFormBuilder('detailFormContaBancariaFkFornecedor');
        $this->detailFormContaBancariaFkFornecedor->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormContaBancariaFkFornecedor->setProperty('class', 'form-horizontal builder-detail-form');

        $row16 = $this->detailFormContaBancariaFkFornecedor->addFields([new TFormSeparator("Conta Bancária", '#333333', '18', '#ff0091')]);
        $row16->layout = [' col-sm-12'];

        $row17 = $this->detailFormContaBancariaFkFornecedor->addFields([new TLabel("Tipo de conta:", '#000000', '14px', null, '100%'),$conta_bancaria_fk_fornecedor_id_referencia_tipo,$conta_bancaria_fk_fornecedor_id],[new TLabel("Nome:", '#000000', '14px', null, '100%'),$conta_bancaria_fk_fornecedor_nome],[new TLabel("Agencia:", '#000000', '14px', null, '100%'),$conta_bancaria_fk_fornecedor_agencia],[new TLabel("Numero conta:", null, '14px', null, '100%'),$conta_bancaria_fk_fornecedor_numero_conta],[new TLabel("Banco:", '#000000', '14px', null, '100%'),$conta_bancaria_fk_fornecedor_banco]);
        $row17->layout = [' col-sm-6 col-lg-2',' col-sm-6 col-lg-4','col-sm-2','col-sm-2',' col-sm-2 col-lg-2'];

        $row18 = $this->detailFormContaBancariaFkFornecedor->addFields([],[$button_adicionar_conta_bancaria_fk_fornecedor],[]);
        $row18->layout = [' col-sm-12 col-lg-4',' col-sm-2 col-lg-4',' col-sm-2 col-lg-4'];

        $row19 = $this->detailFormContaBancariaFkFornecedor->addFields([new THidden('conta_bancaria_fk_fornecedor__row__id')]);
        $this->conta_bancaria_fk_fornecedor_criteria = new TCriteria();

        $this->conta_bancaria_fk_fornecedor_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->conta_bancaria_fk_fornecedor_list->disableHtmlConversion();;
        $this->conta_bancaria_fk_fornecedor_list->generateHiddenFields();
        $this->conta_bancaria_fk_fornecedor_list->setId('conta_bancaria_fk_fornecedor_list');

        $this->conta_bancaria_fk_fornecedor_list->style = 'width:100%';
        $this->conta_bancaria_fk_fornecedor_list->class .= ' table-bordered';

        $column_conta_bancaria_fk_fornecedor_nome = new TDataGridColumn('nome', "Nome", 'left');
        $column_conta_bancaria_fk_fornecedor_referencia_tipo_conta_bancaria = new TDataGridColumn('referencia_tipo->conta_bancaria', "tipo conta", 'left');
        $column_conta_bancaria_fk_fornecedor_fk_fornecedor_razao_social = new TDataGridColumn('fk_fornecedor->razao_social', "Fornecedor", 'left');
        $column_conta_bancaria_fk_fornecedor_agencia = new TDataGridColumn('agencia', "Agencia", 'left');
        $column_conta_bancaria_fk_fornecedor_numero_conta = new TDataGridColumn('numero_conta', "Numero conta", 'left');
        $column_conta_bancaria_fk_fornecedor_banco = new TDataGridColumn('banco', "Banco", 'left');

        $column_conta_bancaria_fk_fornecedor__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_conta_bancaria_fk_fornecedor__row__data->setVisibility(false);

        $action_onEditDetailContaBancaria = new TDataGridAction(array('FornecedorForm', 'onEditDetailContaBancaria'));
        $action_onEditDetailContaBancaria->setUseButton(false);
        $action_onEditDetailContaBancaria->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailContaBancaria->setLabel("Editar");
        $action_onEditDetailContaBancaria->setImage('far:edit #478fca');
        $action_onEditDetailContaBancaria->setFields(['__row__id', '__row__data']);

        $this->conta_bancaria_fk_fornecedor_list->addAction($action_onEditDetailContaBancaria);
        $action_onDeleteDetailContaBancaria = new TDataGridAction(array('FornecedorForm', 'onDeleteDetailContaBancaria'));
        $action_onDeleteDetailContaBancaria->setUseButton(false);
        $action_onDeleteDetailContaBancaria->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailContaBancaria->setLabel("Excluir");
        $action_onDeleteDetailContaBancaria->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailContaBancaria->setFields(['__row__id', '__row__data']);

        $this->conta_bancaria_fk_fornecedor_list->addAction($action_onDeleteDetailContaBancaria);

        $this->conta_bancaria_fk_fornecedor_list->addColumn($column_conta_bancaria_fk_fornecedor_nome);
        $this->conta_bancaria_fk_fornecedor_list->addColumn($column_conta_bancaria_fk_fornecedor_referencia_tipo_conta_bancaria);
        $this->conta_bancaria_fk_fornecedor_list->addColumn($column_conta_bancaria_fk_fornecedor_fk_fornecedor_razao_social);
        $this->conta_bancaria_fk_fornecedor_list->addColumn($column_conta_bancaria_fk_fornecedor_agencia);
        $this->conta_bancaria_fk_fornecedor_list->addColumn($column_conta_bancaria_fk_fornecedor_numero_conta);
        $this->conta_bancaria_fk_fornecedor_list->addColumn($column_conta_bancaria_fk_fornecedor_banco);

        $this->conta_bancaria_fk_fornecedor_list->addColumn($column_conta_bancaria_fk_fornecedor__row__data);

        $this->conta_bancaria_fk_fornecedor_list->createModel();
        $this->detailFormContaBancariaFkFornecedor->addContent([$this->conta_bancaria_fk_fornecedor_list]);

        $row20 = $this->form->addFields([$this->detailFormContaBancariaFkFornecedor]);
        $row20->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        parent::add($this->form);

    }

    public static function validarCNPJ($param = null) 
    {
        try 
        {

            //code here            
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function definirOpcaoIsento($param = null) 
    {
        try 
        {
           //$inscr_estadual->setEditable(false);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailContatoFornecedor($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            foreach($requiredFields as $keyFieldName => $labelRequired)
            {
                try
                {
                    (new TRequiredValidator)->validate($labelRequired, $data->{$keyFieldName});
                }
                catch(Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
             }
             if(count($errors) > 0)
             {
                 throw new Exception(implode('<br>', $errors));
             }

            $__row__id = !empty($data->contato_fornecedor__row__id) ? $data->contato_fornecedor__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new Contato();
            $grid_data->__row__id = $__row__id;
            $grid_data->pessoa_id = $data->contato_fornecedor_pessoa_id;
            $grid_data->id = $data->contato_fornecedor_id;
            $grid_data->nome = $data->contato_fornecedor_nome;
            $grid_data->email = $data->contato_fornecedor_email;
            $grid_data->telefone = $data->contato_fornecedor_telefone;
            $grid_data->obs = $data->contato_fornecedor_obs;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['pessoa_id'] =  $param['contato_fornecedor_pessoa_id'] ?? null;
            $__row__data['__display__']['id'] =  $param['contato_fornecedor_id'] ?? null;
            $__row__data['__display__']['nome'] =  $param['contato_fornecedor_nome'] ?? null;
            $__row__data['__display__']['email'] =  $param['contato_fornecedor_email'] ?? null;
            $__row__data['__display__']['telefone'] =  $param['contato_fornecedor_telefone'] ?? null;
            $__row__data['__display__']['obs'] =  $param['contato_fornecedor_obs'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->contato_fornecedor_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('contato_fornecedor_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->contato_fornecedor_pessoa_id = '';
            $data->contato_fornecedor_id = '';
            $data->contato_fornecedor_nome = '';
            $data->contato_fornecedor_email = '';
            $data->contato_fornecedor_telefone = '';
            $data->contato_fornecedor_obs = '';
            $data->contato_fornecedor__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#60d39e878bec1');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public  function onAddDetailContaBancariaFkFornecedor($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields["conta_bancaria_fk_fornecedor_id_referencia_tipo"] = "identifica a qual tabela pertence o id referencia";
            $requiredFields["conta_bancaria_fk_fornecedor_nome"] = "Nome";
            $requiredFields["conta_bancaria_fk_fornecedor_banco"] = "Banco";
            foreach($requiredFields as $keyFieldName => $labelRequired)
            {
                try
                {
                    (new TRequiredValidator)->validate($labelRequired, $data->{$keyFieldName});
                }
                catch(Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
             }
             if(count($errors) > 0)
             {
                 throw new Exception(implode('<br>', $errors));
             }

            $__row__id = !empty($data->conta_bancaria_fk_fornecedor__row__id) ? $data->conta_bancaria_fk_fornecedor__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new ContaBancaria();
            $grid_data->__row__id = $__row__id;
            $grid_data->id_referencia_tipo = $data->conta_bancaria_fk_fornecedor_id_referencia_tipo;
            $grid_data->id = $data->conta_bancaria_fk_fornecedor_id;
            $grid_data->nome = $data->conta_bancaria_fk_fornecedor_nome;
            $grid_data->agencia = $data->conta_bancaria_fk_fornecedor_agencia;
            $grid_data->numero_conta = $data->conta_bancaria_fk_fornecedor_numero_conta;
            $grid_data->banco = $data->conta_bancaria_fk_fornecedor_banco;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['id_referencia_tipo'] =  $param['conta_bancaria_fk_fornecedor_id_referencia_tipo'] ?? null;
            $__row__data['__display__']['id'] =  $param['conta_bancaria_fk_fornecedor_id'] ?? null;
            $__row__data['__display__']['nome'] =  $param['conta_bancaria_fk_fornecedor_nome'] ?? null;
            $__row__data['__display__']['agencia'] =  $param['conta_bancaria_fk_fornecedor_agencia'] ?? null;
            $__row__data['__display__']['numero_conta'] =  $param['conta_bancaria_fk_fornecedor_numero_conta'] ?? null;
            $__row__data['__display__']['banco'] =  $param['conta_bancaria_fk_fornecedor_banco'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->conta_bancaria_fk_fornecedor_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('conta_bancaria_fk_fornecedor_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->conta_bancaria_fk_fornecedor_id_referencia_tipo = '2';
            $data->conta_bancaria_fk_fornecedor_id = '';
            $data->conta_bancaria_fk_fornecedor_nome = '';
            $data->conta_bancaria_fk_fornecedor_agencia = '';
            $data->conta_bancaria_fk_fornecedor_numero_conta = '';
            $data->conta_bancaria_fk_fornecedor_banco = '';
            $data->conta_bancaria_fk_fornecedor__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#60d39f304a987');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public static function onEditDetailContato($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->contato_fornecedor_pessoa_id = $__row__data->__display__->pessoa_id ?? null;
            $data->contato_fornecedor_id = $__row__data->__display__->id ?? null;
            $data->contato_fornecedor_nome = $__row__data->__display__->nome ?? null;
            $data->contato_fornecedor_email = $__row__data->__display__->email ?? null;
            $data->contato_fornecedor_telefone = $__row__data->__display__->telefone ?? null;
            $data->contato_fornecedor_obs = $__row__data->__display__->obs ?? null;
            $data->contato_fornecedor__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#60d39e878bec1');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='far fa-edit' style='color:#478fca;padding-right:4px;'></i>Editar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailContato($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->contato_fornecedor_pessoa_id = '';
            $data->contato_fornecedor_id = '';
            $data->contato_fornecedor_nome = '';
            $data->contato_fornecedor_email = '';
            $data->contato_fornecedor_telefone = '';
            $data->contato_fornecedor_obs = '';
            $data->contato_fornecedor__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('contato_fornecedor_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onEditDetailContaBancaria($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->conta_bancaria_fk_fornecedor_id_referencia_tipo = $__row__data->__display__->id_referencia_tipo ?? null;
            $data->conta_bancaria_fk_fornecedor_id = $__row__data->__display__->id ?? null;
            $data->conta_bancaria_fk_fornecedor_nome = $__row__data->__display__->nome ?? null;
            $data->conta_bancaria_fk_fornecedor_agencia = $__row__data->__display__->agencia ?? null;
            $data->conta_bancaria_fk_fornecedor_numero_conta = $__row__data->__display__->numero_conta ?? null;
            $data->conta_bancaria_fk_fornecedor_banco = $__row__data->__display__->banco ?? null;
            $data->conta_bancaria_fk_fornecedor__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#60d39f304a987');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='far fa-edit' style='color:#478fca;padding-right:4px;'></i>Editar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailContaBancaria($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->conta_bancaria_fk_fornecedor_id_referencia_tipo = '';
            $data->conta_bancaria_fk_fornecedor_id = '';
            $data->conta_bancaria_fk_fornecedor_nome = '';
            $data->conta_bancaria_fk_fornecedor_agencia = '';
            $data->conta_bancaria_fk_fornecedor_numero_conta = '';
            $data->conta_bancaria_fk_fornecedor_banco = '';
            $data->conta_bancaria_fk_fornecedor__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('conta_bancaria_fk_fornecedor_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
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

            $object = new Fornecedor(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            $conta_bancaria_fk_fornecedor_items = $this->storeMasterDetailItems('ContaBancaria', 'fornecedor', 'conta_bancaria_fk_fornecedor', $object, $param['conta_bancaria_fk_fornecedor_list___row__data'] ?? [], $this->form, $this->conta_bancaria_fk_fornecedor_list, function($masterObject, $detailObject){ 

                //code here

            }); 

            $contato_fornecedor_items = $this->storeMasterDetailItems('Contato', 'fornecedor_id', 'contato_fornecedor', $object, $param['contato_fornecedor_list___row__data'] ?? [], $this->form, $this->contato_fornecedor_list, function($masterObject, $detailObject){ 

                //code here

            }); 

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

                $object = new Fornecedor($key); // instantiates the Active Record 

                                $object->fk_cidade_estado_id = $object->fk_cidade->estado->id;

                $conta_bancaria_fk_fornecedor_items = $this->loadMasterDetailItems('ContaBancaria', 'fornecedor', 'conta_bancaria_fk_fornecedor', $object, $this->form, $this->conta_bancaria_fk_fornecedor_list, $this->conta_bancaria_fk_fornecedor_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $contato_fornecedor_items = $this->loadMasterDetailItems('Contato', 'fornecedor_id', 'contato_fornecedor', $object, $this->form, $this->contato_fornecedor_list, $this->contato_fornecedor_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $object = new StdClass();
                $object->dt_ativacao = date('Y-m-d');
                $object->possui_ie = 2;
                $this->form->setData($object);
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

