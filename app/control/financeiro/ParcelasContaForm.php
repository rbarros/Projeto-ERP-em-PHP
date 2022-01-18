<?php

class ParcelasContaForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'ParcelasConta';
    private static $primaryKey = 'id';
    private static $formName = 'form_ParcelasConta';

    use BuilderMasterDetailTrait;
    use Adianti\Base\AdiantiFileSaveTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Gerenciar parcelas e subparcelas");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Gerenciar parcelas e subparcelas");

        $criteria_conta_bancaria_loja = new TCriteria();
        $criteria_subparcelas_parcela_parcela_mestre_conta_bancaria_loja = new TCriteria();

        $filterVar = TipoContaBancaria::Loja;
        $criteria_subparcelas_parcela_parcela_mestre_conta_bancaria_loja->add(new TFilter('id_referencia_tipo', '=', $filterVar)); 

        $id = new TEntry('id');
        $conta_origem = new TEntry('conta_origem');
        $id_parcela_mestre = new TEntry('id_parcela_mestre');
        $loja_id = new TDBCombo('loja_id', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $fornecedor_id = new TDBCombo('fornecedor_id', 'base_banco', 'Fornecedor', 'id', '{nome_fantasia}','nome_fantasia asc'  );
        $tipo_parcela = new TEntry('tipo_parcela');
        $vencimento = new TDate('vencimento');
        $forma_pagamento = new TCombo('forma_pagamento');
        $conta_bancaria_loja = new TDBCombo('conta_bancaria_loja', 'base_banco', 'ContaBancaria', 'id', '{nome} - ag: {agencia}  c:{numero_conta} b: {fk_banco->nome} ','id asc' , $criteria_conta_bancaria_loja );
        $conta_bancaria_fornecedor = new TCombo('conta_bancaria_fornecedor');
        $valor = new TNumeric('valor', '2', ',', '.' );
        $quitada = new TRadioGroup('quitada');
        $obs = new TText('obs');
        $caminho = new TFile('caminho');
        $atdParcelas = new TNumeric('atdParcelas', '2', ',', '.' );
        $valorTotal = new TNumeric('valorTotal', '2', ',', '.' );
        $totalPago = new TNumeric('totalPago', '2', ',', '.' );
        $valorProg = new TNumeric('valorProg', '2', ',', '.' );
        $valorRestante = new TNumeric('valorRestante', '2', ',', '.' );
        $subparcelas_parcela_parcela_mestre_id = new THidden('subparcelas_parcela_parcela_mestre_id');
        $subparcelas_parcela_parcela_mestre_conta_origem = new TDBCombo('subparcelas_parcela_parcela_mestre_conta_origem', 'base_banco', 'Conta', 'id', '{id}','id asc'  );
        $subparcelas_parcela_parcela_mestre_id_parcela_mestre = new TEntry('subparcelas_parcela_parcela_mestre_id_parcela_mestre');
        $subparcelas_parcela_parcela_mestre_loja_id = new TDBCombo('subparcelas_parcela_parcela_mestre_loja_id', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $subparcelas_parcela_parcela_mestre_fornecedor_id = new TDBCombo('subparcelas_parcela_parcela_mestre_fornecedor_id', 'base_banco', 'Fornecedor', 'id', '{nome_fantasia}','nome_fantasia asc'  );
        $subparcelas_parcela_parcela_mestre_tipo_parcela = new TEntry('subparcelas_parcela_parcela_mestre_tipo_parcela');
        $subparcelas_parcela_parcela_mestre_vencimento = new TDate('subparcelas_parcela_parcela_mestre_vencimento');
        $subparcelas_parcela_parcela_mestre_forma_pagamento = new TCombo('subparcelas_parcela_parcela_mestre_forma_pagamento');
        $subparcelas_parcela_parcela_mestre_conta_bancaria_loja = new TDBCombo('subparcelas_parcela_parcela_mestre_conta_bancaria_loja', 'base_banco', 'ContaBancaria', 'id', '{nome} - ag: {agencia}  c:{numero_conta} b: {fk_banco->nome} ','id asc' , $criteria_subparcelas_parcela_parcela_mestre_conta_bancaria_loja );
        $subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor = new TCombo('subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor');
        $subparcelas_parcela_parcela_mestre_valor = new TNumeric('subparcelas_parcela_parcela_mestre_valor', '2', ',', '.' );
        $subparcelas_parcela_parcela_mestre_quitada = new TRadioGroup('subparcelas_parcela_parcela_mestre_quitada');
        $subparcelas_parcela_parcela_mestre_obs = new TText('subparcelas_parcela_parcela_mestre_obs');
        $button_adicionar_subparcela_subparcelas_parcela_parcela_mestre = new TButton('button_adicionar_subparcela_subparcelas_parcela_parcela_mestre');

        $fornecedor_id->setChangeAction(new TAction([$this,'onforncedor']));
        $forma_pagamento->setChangeAction(new TAction([$this,'onPagamentoP']));
        $subparcelas_parcela_parcela_mestre_loja_id->setChangeAction(new TAction([$this,'onChangeLoja']));

        $conta_origem->addValidation("Conta origem", new TRequiredValidator()); 
        $loja_id->addValidation("quem pagou", new TRequiredValidator()); 
        $tipo_parcela->addValidation("Tipo parcela", new TRequiredValidator()); 
        $vencimento->addValidation("Vencimento", new TRequiredValidator()); 
        $forma_pagamento->addValidation("Forma pagamento", new TRequiredValidator()); 
        $valor->addValidation("Valor", new TRequiredValidator()); 
        $quitada->addValidation("Quitada", new TRequiredValidator()); 

        $button_adicionar_subparcela_subparcelas_parcela_parcela_mestre->setAction(new TAction([$this, 'onAddDetailSubparcelasParcelaParcelaMestre'],['static' => 1]), "Adicionar subparcela");
        $caminho->enableFileHandling();
        $button_adicionar_subparcela_subparcelas_parcela_parcela_mestre->addStyleClass('btn-success');
        $button_adicionar_subparcela_subparcelas_parcela_parcela_mestre->setImage('fas:plus #54b917');
        $tipo_parcela->setMaxLength(30);
        $subparcelas_parcela_parcela_mestre_tipo_parcela->setMaxLength(30);

        $vencimento->setMask('dd/mm/yyyy');
        $subparcelas_parcela_parcela_mestre_vencimento->setMask('dd/mm/yyyy');

        $vencimento->setDatabaseMask('yyyy-mm-dd');
        $subparcelas_parcela_parcela_mestre_vencimento->setDatabaseMask('yyyy-mm-dd');

        $quitada->setLayout('horizontal');
        $subparcelas_parcela_parcela_mestre_quitada->setLayout('horizontal');

        $button_adicionar_subparcela_subparcelas_parcela_parcela_mestre->id = '60c0f7c478b15';

        $quitada->setValue('f');
        $subparcelas_parcela_parcela_mestre_quitada->setValue('t');
        $subparcelas_parcela_parcela_mestre_forma_pagamento->setValue('deposito');

        $quitada->addItems(['t'=>'SIM','f'=>'NÃO']);
        $subparcelas_parcela_parcela_mestre_quitada->addItems(['t'=>'SIM','f'=>'NÃO']);
        $forma_pagamento->addItems(['deposito'=>' Depósito','transferencia'=>' Transferência','cheque'=>' Cheque','dinheiro'=>'Dinheiro','boleto'=>' Boleto']);
        $subparcelas_parcela_parcela_mestre_forma_pagamento->addItems(['deposito'=>' Depósito','transferencia'=>' Transferência','cheque'=>' Cheque','dinheiro'=>'Dinheiro','boleto'=>' Boleto']);

        $id->setEditable(false);
        $loja_id->setEditable(false);
        $valorProg->setEditable(false);
        $totalPago->setEditable(false);
        $valorTotal->setEditable(false);
        $atdParcelas->setEditable(false);
        $tipo_parcela->setEditable(false);
        $conta_origem->setEditable(false);
        $fornecedor_id->setEditable(false);
        $valorRestante->setEditable(false);
        $id_parcela_mestre->setEditable(false);
        $conta_bancaria_loja->setEditable(false);
        $conta_bancaria_fornecedor->setEditable(false);
        $subparcelas_parcela_parcela_mestre_vencimento->setEditable(false);
        $subparcelas_parcela_parcela_mestre_conta_origem->setEditable(false);
        $subparcelas_parcela_parcela_mestre_tipo_parcela->setEditable(false);
        $subparcelas_parcela_parcela_mestre_fornecedor_id->setEditable(false);
        $subparcelas_parcela_parcela_mestre_forma_pagamento->setEditable(false);
        $subparcelas_parcela_parcela_mestre_id_parcela_mestre->setEditable(false);
        $subparcelas_parcela_parcela_mestre_conta_bancaria_loja->setEditable(false);

        $id->setSize(100);
        $valor->setSize('100%');
        $loja_id->setSize('100%');
        $caminho->setSize('100%');
        $obs->setSize('100%', 70);
        $quitada->setSize('100%');
        $valorProg->setSize('100%');
        $totalPago->setSize('100%');
        $vencimento->setSize('100%');
        $valorTotal->setSize('100%');
        $atdParcelas->setSize('100%');
        $tipo_parcela->setSize('100%');
        $conta_origem->setSize('100%');
        $fornecedor_id->setSize('100%');
        $valorRestante->setSize('100%');
        $forma_pagamento->setSize('100%');
        $id_parcela_mestre->setSize('100%');
        $conta_bancaria_loja->setSize('100%');
        $conta_bancaria_fornecedor->setSize('100%');
        $subparcelas_parcela_parcela_mestre_id->setSize(200);
        $subparcelas_parcela_parcela_mestre_valor->setSize('100%');
        $subparcelas_parcela_parcela_mestre_quitada->setSize('100%');
        $subparcelas_parcela_parcela_mestre_obs->setSize('100%', 70);
        $subparcelas_parcela_parcela_mestre_loja_id->setSize('100%');
        $subparcelas_parcela_parcela_mestre_vencimento->setSize('100%');
        $subparcelas_parcela_parcela_mestre_tipo_parcela->setSize('100%');
        $subparcelas_parcela_parcela_mestre_conta_origem->setSize('100%');
        $subparcelas_parcela_parcela_mestre_fornecedor_id->setSize('100%');
        $subparcelas_parcela_parcela_mestre_forma_pagamento->setSize('100%');
        $subparcelas_parcela_parcela_mestre_id_parcela_mestre->setSize('100%');
        $subparcelas_parcela_parcela_mestre_conta_bancaria_loja->setSize('100%');
        $subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor->setSize('100%');

        $this->form->appendPage("Parcela");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addContent([new TFormSeparator("Parcela", '#333333', '18', '#ff0091')]);
        $row2 = $this->form->addFields([new TLabel("ID parcela:", null, '14px', null, '100%'),$id],[],[new TLabel("Conta origem:", null, '14px', null, '100%'),$conta_origem],[new TLabel("ID parcela principal", null, '14px', null, '100%'),$id_parcela_mestre]);
        $row2->layout = [' col-sm-1',' col-sm-7',' col-sm-2','col-sm-2'];

        $row3 = $this->form->addFields([new TLabel("Loja:", null, '14px', null, '100%'),$loja_id],[new TLabel("Fornecedor:", null, '14px', null, '100%'),$fornecedor_id],[new TLabel("Tipo parcela:", null, '14px', null, '100%'),$tipo_parcela],[new TLabel("Vencimento:", null, '14px', null, '100%'),$vencimento]);
        $row3->layout = [' col-sm-4',' col-sm-4','col-sm-2','col-sm-2'];

        $row4 = $this->form->addFields([new TLabel("Forma pagamento:", null, '14px', null, '100%'),$forma_pagamento],[new TLabel("Conta bancaria da loja:", null, '14px', null, '100%'),$conta_bancaria_loja],[new TLabel("Conta bancaria fornecedor:", null, '14px', null),$conta_bancaria_fornecedor],[new TLabel("Valor (R$):", null, '14px', null, '100%'),$valor],[new TLabel("Quitada:", null, '14px', null, '100%'),$quitada]);
        $row4->layout = [' col-sm-4',' col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row5 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$obs]);
        $row5->layout = [' col-sm-12'];

        $row6 = $this->form->addFields([$caminho]);
        $row6->layout = [' col-sm-3 col-lg-12'];

        $this->form->appendPage("Subparcela");
        $row7 = $this->form->addFields([new TLabel("Quantidade de subparcelas:", null, '14px', null),$atdParcelas],[new TLabel("Valor Total:", null, '14px', null),$valorTotal],[new TLabel("Total pago:", null, '14px', null),$totalPago],[new TLabel("Valor programado:", null, '14px', null),$valorProg],[],[new TLabel("Valor restante:", null, '15px', 'B'),$valorRestante]);
        $row7->layout = [' col-sm-2',' col-sm-2',' col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $this->detailFormSubparcelasParcelaParcelaMestre = new BootstrapFormBuilder('detailFormSubparcelasParcelaParcelaMestre');
        $this->detailFormSubparcelasParcelaParcelaMestre->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormSubparcelasParcelaParcelaMestre->setProperty('class', 'form-horizontal builder-detail-form');

        $row8 = $this->detailFormSubparcelasParcelaParcelaMestre->addFields([new TFormSeparator("Subparcelas", '#333333', '18', '#ff0091')]);
        $row8->layout = [' col-sm-12'];

        $row9 = $this->detailFormSubparcelasParcelaParcelaMestre->addFields([new TLabel("Conta origem:", null, '14px', null),$subparcelas_parcela_parcela_mestre_id,$subparcelas_parcela_parcela_mestre_conta_origem],[],[new TLabel("ID parcela principal:", null, '14px', null, '100%'),$subparcelas_parcela_parcela_mestre_id_parcela_mestre]);
        $row9->layout = [' col-sm-1',' col-sm-9',' col-sm-2'];

        $row10 = $this->detailFormSubparcelasParcelaParcelaMestre->addFields([new TLabel("Loja id:", null, '14px', null, '100%'),$subparcelas_parcela_parcela_mestre_loja_id],[new TLabel("Fornecedor id:", null, '14px', null, '100%'),$subparcelas_parcela_parcela_mestre_fornecedor_id],[new TLabel("Tipo parcela:", null, '14px', null, '100%'),$subparcelas_parcela_parcela_mestre_tipo_parcela],[new TLabel("Vencimento:", null, '14px', null, '100%'),$subparcelas_parcela_parcela_mestre_vencimento]);
        $row10->layout = [' col-sm-4',' col-sm-4','col-sm-2',' col-sm-2'];

        $row11 = $this->detailFormSubparcelasParcelaParcelaMestre->addFields([new TLabel("Forma pagamento:", null, '14px', null, '100%'),$subparcelas_parcela_parcela_mestre_forma_pagamento],[new TLabel("Conta bancaria loja:", null, '14px', null, '100%'),$subparcelas_parcela_parcela_mestre_conta_bancaria_loja],[new TLabel("Conta bancária fornecedor:", null, '14px', null),$subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor],[new TLabel("Valor (R$):", null, '14px', null, '100%'),$subparcelas_parcela_parcela_mestre_valor],[new TLabel("Quitada:", null, '14px', null, '100%'),$subparcelas_parcela_parcela_mestre_quitada]);
        $row11->layout = ['col-sm-4',' col-sm-4 col-lg-2','col-sm-2','col-sm-2','col-sm-2'];

        $row12 = $this->detailFormSubparcelasParcelaParcelaMestre->addFields([new TLabel("Observação:", null, '14px', null),$subparcelas_parcela_parcela_mestre_obs]);
        $row12->layout = [' col-sm-12'];

        $row13 = $this->detailFormSubparcelasParcelaParcelaMestre->addFields([],[$button_adicionar_subparcela_subparcelas_parcela_parcela_mestre],[]);
        $row13->layout = [' col-sm-12 col-lg-4',' col-sm-2 col-lg-4',' col-sm-2 col-lg-4'];

        $row14 = $this->detailFormSubparcelasParcelaParcelaMestre->addFields([new THidden('subparcelas_parcela_parcela_mestre__row__id')]);
        $this->subparcelas_parcela_parcela_mestre_criteria = new TCriteria();

        $this->subparcelas_parcela_parcela_mestre_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->subparcelas_parcela_parcela_mestre_list->disableHtmlConversion();;
        $this->subparcelas_parcela_parcela_mestre_list->generateHiddenFields();
        $this->subparcelas_parcela_parcela_mestre_list->setId('subparcelas_parcela_parcela_mestre_list');

        $this->subparcelas_parcela_parcela_mestre_list->style = 'width:100%';
        $this->subparcelas_parcela_parcela_mestre_list->class .= ' table-bordered';

        $column_subparcelas_parcela_parcela_mestre_conta_origem = new TDataGridColumn('conta_origem', "Conta", 'left');
        $column_subparcelas_parcela_parcela_mestre_loja_nome_fantasia = new TDataGridColumn('loja->nome_fantasia', "Loja", 'left');
        $column_subparcelas_parcela_parcela_mestre_fornecedor_razao_social = new TDataGridColumn('fornecedor->razao_social', "Fornecedor", 'left');
        $column_subparcelas_parcela_parcela_mestre_forma_pagamento_subparcelas_parcela_parcela_mestre_fk_conta_bancaria_fornecedor_fk_banco_nome = new TDataGridColumn('{forma_pagamento} / {fk_conta_bancaria_fornecedor->fk_banco->nome}', "Forma pagamento", 'left');
        $column_subparcelas_parcela_parcela_mestre_valor_transformed = new TDataGridColumn('valor', "Valor", 'left');
        $column_subparcelas_parcela_parcela_mestre_vencimento = new TDataGridColumn('vencimento', "Vencimento", 'left');
        $column_subparcelas_parcela_parcela_mestre_obs = new TDataGridColumn('obs', "Obs", 'left');
        $column_subparcelas_parcela_parcela_mestre_tipo_parcela_subparcelas_parcela_parcela_mestre_id_parcela_mestre = new TDataGridColumn('{tipo_parcela}  {id_parcela_mestre}', "Tipo parcela", 'left');
        $column_subparcelas_parcela_parcela_mestre_quitada_transformed = new TDataGridColumn('quitada', "Quitada", 'left');

        $column_subparcelas_parcela_parcela_mestre__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_subparcelas_parcela_parcela_mestre__row__data->setVisibility(false);

        $action_onEditDetailSubparcelasParcela = new TDataGridAction(array('ParcelasContaForm', 'onEditDetailSubparcelasParcela'));
        $action_onEditDetailSubparcelasParcela->setUseButton(false);
        $action_onEditDetailSubparcelasParcela->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailSubparcelasParcela->setLabel("Editar");
        $action_onEditDetailSubparcelasParcela->setImage('far:edit #478fca');
        $action_onEditDetailSubparcelasParcela->setFields(['__row__id', '__row__data']);

        $this->subparcelas_parcela_parcela_mestre_list->addAction($action_onEditDetailSubparcelasParcela);
        $action_onDeleteDetailSubparcelasParcela = new TDataGridAction(array('ParcelasContaForm', 'onDeleteDetailSubparcelasParcela'));
        $action_onDeleteDetailSubparcelasParcela->setUseButton(false);
        $action_onDeleteDetailSubparcelasParcela->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailSubparcelasParcela->setLabel("Excluir");
        $action_onDeleteDetailSubparcelasParcela->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailSubparcelasParcela->setFields(['__row__id', '__row__data']);

        $this->subparcelas_parcela_parcela_mestre_list->addAction($action_onDeleteDetailSubparcelasParcela);
        $action_onEdit = new TDataGridAction(array('EnviarDepositoForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Enviar depósito para a loja");
        $action_onEdit->setImage('fab:telegram-plane #666fea');
        $action_onEdit->setFields(['__row__id', '__row__data']);
        $action_onEdit->setDisplayCondition('ParcelasContaForm::isSave');
        $action_onEdit->setParameter('key', '{id}');
        $this->subparcelas_parcela_parcela_mestre_list->addAction($action_onEdit);

        $this->subparcelas_parcela_parcela_mestre_list->addColumn($column_subparcelas_parcela_parcela_mestre_conta_origem);
        $this->subparcelas_parcela_parcela_mestre_list->addColumn($column_subparcelas_parcela_parcela_mestre_loja_nome_fantasia);
        $this->subparcelas_parcela_parcela_mestre_list->addColumn($column_subparcelas_parcela_parcela_mestre_fornecedor_razao_social);
        $this->subparcelas_parcela_parcela_mestre_list->addColumn($column_subparcelas_parcela_parcela_mestre_forma_pagamento_subparcelas_parcela_parcela_mestre_fk_conta_bancaria_fornecedor_fk_banco_nome);
        $this->subparcelas_parcela_parcela_mestre_list->addColumn($column_subparcelas_parcela_parcela_mestre_valor_transformed);
        $this->subparcelas_parcela_parcela_mestre_list->addColumn($column_subparcelas_parcela_parcela_mestre_vencimento);
        $this->subparcelas_parcela_parcela_mestre_list->addColumn($column_subparcelas_parcela_parcela_mestre_obs);
        $this->subparcelas_parcela_parcela_mestre_list->addColumn($column_subparcelas_parcela_parcela_mestre_tipo_parcela_subparcelas_parcela_parcela_mestre_id_parcela_mestre);
        $this->subparcelas_parcela_parcela_mestre_list->addColumn($column_subparcelas_parcela_parcela_mestre_quitada_transformed);

        $this->subparcelas_parcela_parcela_mestre_list->addColumn($column_subparcelas_parcela_parcela_mestre__row__data);

        $this->subparcelas_parcela_parcela_mestre_list->createModel();
        $this->detailFormSubparcelasParcelaParcelaMestre->addContent([$this->subparcelas_parcela_parcela_mestre_list]);

        $column_subparcelas_parcela_parcela_mestre_valor_transformed->setTransformer(function($value, $object, $row) 
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_subparcelas_parcela_parcela_mestre_quitada_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value === true || $value == 't' || $value === 1 || $value == '1' || $value == 's' || $value == 'S' || $value == 'T')
                return 'Sim';

            return 'Não';

        });        $row15 = $this->form->addFields([$this->detailFormSubparcelasParcelaParcelaMestre]);
        $row15->layout = [' col-sm-12'];

        $column_subparcelas_parcela_parcela_mestre_quitada_transformed->setTransformer(array($this,'formatarQuitacao'));

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        parent::add($this->form);

    }

    public static function onforncedor($param = null) 
    {
        try 
        {
            $id_fornecedor      = $param['fornecedor_id'];
            $contas_array       = [];
            if($id_fornecedor != "" || $id_fornecedor != null){
                TTransaction::open(self::$database);
                $contas = ContaBancaria::where('fornecedor','=',$id_fornecedor)->load();
                if($contas){
                    foreach($contas as $conta){
                        $banco = new Banco($conta->banco);
                        $contas_array[$conta->id] = "$conta->nome - ag: $conta->agencia  c:$conta->numero_conta b: $banco->nome"; 
                    }
                    TCombo::reload(self::$formName, 'conta_bancaria_fornecedor', $contas_array, true);
                }
                TTransaction::close();
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onPagamentoP($param = null) 
    {
        try 
        {
            $pagamento = $param['forma_pagamento'];
           if($pagamento == "transferencia"|| $pagamento == "boleto" || $pagamento == "cheque"){
            TCombo::enableField(self::$formName, 'conta_bancaria');
           }else{
              TCombo::disableField(self::$formName, 'conta_bancaria'); 
           }

            $pagamento = $param['forma_pagamento'];
        switch($pagamento){
             case "deposito":
                        TCombo::disableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::enableField(self::$formName, 'conta_bancaria_fornecedor');
                        TDBCombo::enableField(self::$formName, 'subparcelas_parcela_parcela_mestre_loja_id');

                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_loja');
                        TCombo::enableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor');
                        TNumeric::enableField(self::$formName, 'subparcelas_parcela_parcela_mestre_valor');
                        TRadioGroup::enableField(self::$formName, 'quitada');
                        break;
                    case "transferencia":
                        TCombo::enableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::enableField(self::$formName, 'conta_bancaria_fornecedor');
                        //TrAVAR SUBPARCELA
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_loja_id');
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor');
                        TNumeric::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_valor');
                        TRadioGroup::disableField(self::$formName, 'quitada');
                        break;
                    case "boleto":
                        TCombo::enableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'conta_bancaria_fornecedor'); 
                        //TrAVAR SUBPARCELA
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_loja_id');
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor');
                        TNumeric::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_valor');
                        TRadioGroup::disableField(self::$formName, 'quitada');
                        break;
                    case "cheque":
                        TCombo::enableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'conta_bancaria_fornecedor'); 
                        //TrAVAR SUBPARCELA
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_loja_id');
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor');
                        TNumeric::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_valor');
                        TRadioGroup::disableField(self::$formName, 'quitada');
                        break;
                    default:
                        TCombo::disableField(self::$formName, 'conta_bancaria_loja'); 
                        TCombo::disableField(self::$formName, 'conta_bancaria_fornecedor'); 
                        //TrAVAR SUBPARCELA
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_loja_id');
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor');
                        TNumeric::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_valor');
                        TRadioGroup::disableField(self::$formName, 'quitada');
                        break;
        }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeLoja($param = null) 
    {
        try 
        {
          $id_fornecedor      = $param['subparcelas_parcela_parcela_mestre_fornecedor_id'];
            $contas_array       = [];
            if($id_fornecedor != "" || $id_fornecedor != null){
                TTransaction::open(self::$database);
                $contas = ContaBancaria::where('fornecedor','=',$id_fornecedor)->load();
                if($contas){
                    foreach($contas as $conta){
                        $banco = new Banco($conta->banco);
                        $contas_array[$conta->id] = "$conta->nome - ag: $conta->agencia  c:$conta->numero_conta b: $banco->nome"; 
                    }
                    TCombo::reload(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor', $contas_array, true);
                }
                TTransaction::close();
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailSubparcelasParcelaParcelaMestre($param = null) 
    {
        try
        {
            $data = $this->form->getData();
            $keep_data = (Object)$param;

            $errors = [];
            $requiredFields = [];
            $requiredFields["subparcelas_parcela_parcela_mestre_conta_origem"] = "Conta origem";
            $requiredFields["subparcelas_parcela_parcela_mestre_loja_id"] = "Loja id";
            $requiredFields["subparcelas_parcela_parcela_mestre_tipo_parcela"] = "Tipo parcela";
            $requiredFields["subparcelas_parcela_parcela_mestre_vencimento"] = "Vencimento";
            $requiredFields["subparcelas_parcela_parcela_mestre_forma_pagamento"] = "Forma pagamento";
            $requiredFields["subparcelas_parcela_parcela_mestre_valor"] = "Valor";
            $requiredFields["subparcelas_parcela_parcela_mestre_quitada"] = "Quitada";
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

            $__row__id = !empty($data->subparcelas_parcela_parcela_mestre__row__id) ? $data->subparcelas_parcela_parcela_mestre__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new SubparcelasParcela();
            $grid_data->__row__id = $__row__id;
            $grid_data->id = $data->subparcelas_parcela_parcela_mestre_id;
            $grid_data->conta_origem = $data->subparcelas_parcela_parcela_mestre_conta_origem;
            $grid_data->id_parcela_mestre = $data->subparcelas_parcela_parcela_mestre_id_parcela_mestre;
            $grid_data->loja_id = $data->subparcelas_parcela_parcela_mestre_loja_id;
            $grid_data->fornecedor_id = $data->subparcelas_parcela_parcela_mestre_fornecedor_id;
            $grid_data->tipo_parcela = $data->subparcelas_parcela_parcela_mestre_tipo_parcela;
            $grid_data->vencimento = $data->subparcelas_parcela_parcela_mestre_vencimento;
            $grid_data->forma_pagamento = $data->subparcelas_parcela_parcela_mestre_forma_pagamento;
            $grid_data->conta_bancaria_loja = $data->subparcelas_parcela_parcela_mestre_conta_bancaria_loja;
            $grid_data->conta_bancaria_fornecedor = $data->subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor;
            $grid_data->valor = $data->subparcelas_parcela_parcela_mestre_valor;
            $grid_data->quitada = $data->subparcelas_parcela_parcela_mestre_quitada;
            $grid_data->obs = $data->subparcelas_parcela_parcela_mestre_obs;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['id'] =  $param['subparcelas_parcela_parcela_mestre_id'] ?? null;
            $__row__data['__display__']['conta_origem'] =  $param['subparcelas_parcela_parcela_mestre_conta_origem'] ?? null;
            $__row__data['__display__']['id_parcela_mestre'] =  $param['subparcelas_parcela_parcela_mestre_id_parcela_mestre'] ?? null;
            $__row__data['__display__']['loja_id'] =  $param['subparcelas_parcela_parcela_mestre_loja_id'] ?? null;
            $__row__data['__display__']['fornecedor_id'] =  $param['subparcelas_parcela_parcela_mestre_fornecedor_id'] ?? null;
            $__row__data['__display__']['tipo_parcela'] =  $param['subparcelas_parcela_parcela_mestre_tipo_parcela'] ?? null;
            $__row__data['__display__']['vencimento'] =  $param['subparcelas_parcela_parcela_mestre_vencimento'] ?? null;
            $__row__data['__display__']['forma_pagamento'] =  $param['subparcelas_parcela_parcela_mestre_forma_pagamento'] ?? null;
            $__row__data['__display__']['conta_bancaria_loja'] =  $param['subparcelas_parcela_parcela_mestre_conta_bancaria_loja'] ?? null;
            $__row__data['__display__']['conta_bancaria_fornecedor'] =  $param['subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor'] ?? null;
            $__row__data['__display__']['valor'] =  $param['subparcelas_parcela_parcela_mestre_valor'] ?? null;
            $__row__data['__display__']['quitada'] =  $param['subparcelas_parcela_parcela_mestre_quitada'] ?? null;
            $__row__data['__display__']['obs'] =  $param['subparcelas_parcela_parcela_mestre_obs'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->subparcelas_parcela_parcela_mestre_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('subparcelas_parcela_parcela_mestre_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->subparcelas_parcela_parcela_mestre_id = '';
            $data->subparcelas_parcela_parcela_mestre_conta_origem = '';
            $data->subparcelas_parcela_parcela_mestre_id_parcela_mestre = '';
            $data->subparcelas_parcela_parcela_mestre_loja_id = '';
            $data->subparcelas_parcela_parcela_mestre_fornecedor_id = '';
            $data->subparcelas_parcela_parcela_mestre_tipo_parcela = '';
            $data->subparcelas_parcela_parcela_mestre_vencimento = '';
            $data->subparcelas_parcela_parcela_mestre_forma_pagamento = 'deposito';
            $data->subparcelas_parcela_parcela_mestre_conta_bancaria_loja = '';
            $data->subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor = '';
            $data->subparcelas_parcela_parcela_mestre_valor = '';
            $data->subparcelas_parcela_parcela_mestre_quitada = 't';
            $data->subparcelas_parcela_parcela_mestre_obs = '';
            $data->subparcelas_parcela_parcela_mestre__row__id = '';

            $data->subparcelas_parcela_parcela_mestre_id = $grid_data->id_parcela_mestre;
            $data->subparcelas_parcela_parcela_mestre_fornecedor_id = $grid_data->fornecedor_id;
            $data->subparcelas_parcela_parcela_mestre_tipo_parcela = $grid_data->tipo_parcela;
            $data->subparcelas_parcela_parcela_mestre_conta_origem = $grid_data->conta_origem;
            $data->subparcelas_parcela_parcela_mestre_vencimento = $grid_data->vencimento;
            $grid_data->conta_bancaria_fornecedor = $grid_data->conta_bancaria_fornecedor;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#60c0f7c478b15');
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

    public static function onEditDetailSubparcelasParcela($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->subparcelas_parcela_parcela_mestre_id = $__row__data->__display__->id ?? null;
            $data->subparcelas_parcela_parcela_mestre_conta_origem = $__row__data->__display__->conta_origem ?? null;
            $data->subparcelas_parcela_parcela_mestre_id_parcela_mestre = $__row__data->__display__->id_parcela_mestre ?? null;
            $data->subparcelas_parcela_parcela_mestre_loja_id = $__row__data->__display__->loja_id ?? null;
            $data->subparcelas_parcela_parcela_mestre_fornecedor_id = $__row__data->__display__->fornecedor_id ?? null;
            $data->subparcelas_parcela_parcela_mestre_tipo_parcela = $__row__data->__display__->tipo_parcela ?? null;
            $data->subparcelas_parcela_parcela_mestre_vencimento = $__row__data->__display__->vencimento ?? null;
            $data->subparcelas_parcela_parcela_mestre_forma_pagamento = $__row__data->__display__->forma_pagamento ?? null;
            $data->subparcelas_parcela_parcela_mestre_conta_bancaria_loja = $__row__data->__display__->conta_bancaria_loja ?? null;
            $data->subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor = $__row__data->__display__->conta_bancaria_fornecedor ?? null;
            $data->subparcelas_parcela_parcela_mestre_valor = $__row__data->__display__->valor ?? null;
            $data->subparcelas_parcela_parcela_mestre_quitada = $__row__data->__display__->quitada ?? null;
            $data->subparcelas_parcela_parcela_mestre_obs = $__row__data->__display__->obs ?? null;
            $data->subparcelas_parcela_parcela_mestre__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#60c0f7c478b15');
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
    public static function onDeleteDetailSubparcelasParcela($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->subparcelas_parcela_parcela_mestre_id = '';
            $data->subparcelas_parcela_parcela_mestre_conta_origem = '';
            $data->subparcelas_parcela_parcela_mestre_id_parcela_mestre = '';
            $data->subparcelas_parcela_parcela_mestre_loja_id = '';
            $data->subparcelas_parcela_parcela_mestre_fornecedor_id = '';
            $data->subparcelas_parcela_parcela_mestre_tipo_parcela = '';
            $data->subparcelas_parcela_parcela_mestre_vencimento = '';
            $data->subparcelas_parcela_parcela_mestre_forma_pagamento = '';
            $data->subparcelas_parcela_parcela_mestre_conta_bancaria_loja = '';
            $data->subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor = '';
            $data->subparcelas_parcela_parcela_mestre_valor = '';
            $data->subparcelas_parcela_parcela_mestre_quitada = '';
            $data->subparcelas_parcela_parcela_mestre_obs = '';
            $data->subparcelas_parcela_parcela_mestre__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('subparcelas_parcela_parcela_mestre_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function isSave($object)
    {
        try 
        {
            if($object->id)
            {
                return true;
            }
            return false;
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

            $object = new ParcelasConta(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $caminho_dir = '/tmp';  

            $object->store(); // save the object 
/*

            $this->saveFile($object, $data, 'caminho', $caminho_dir);
            TForm::sendData(self::$formName, (object)['id' => $object->id]);

*/              
              if($data->caminho != null){
                $config                      = array();
                $config['file_path']         = $data->caminho;
                $config['user']              = TSession::getValue('username');
                $config['vencimento']        = $data->vencimento;
                $config['valor']             = $data->valor;
                $fornecedor                  = new Fornecedor($data->fornecedor_id);
                $config['fornecedor']        = $fornecedor->nome_fantasia;
                $loja                        = new Loja($data->loja_id);
                $config['loja']              = $loja->abreviacao;
                $return                      = ApiManager::sendGoogleFile($config);
                $object->link_comprovante    = $return;
            }
            $subparcelas_parcela_parcela_mestre_items = $this->storeMasterDetailItems('SubparcelasParcela', 'id_parcela_mestre', 'subparcelas_parcela_parcela_mestre', $object, $param['subparcelas_parcela_parcela_mestre_list___row__data'] ?? [], $this->form, $this->subparcelas_parcela_parcela_mestre_list, function($masterObject, $detailObject){ 

                //code here

            }); 

            $data->id = $object->id; 

            $parcelas = SubparcelasParcela::where('id_parcela_mestre','=',$object->id)->load();
            $total_pago = 0;
            if($parcelas){
                foreach($parcelas as $parcela){
                    if($parcela->quitada == 't'){
                        $total_pago = $total_pago + doubleval($parcela->valor);
                    }
                }
            }
            if($total_pago == doubleval($object->valor)){
                $object->quitada = 't';
                $object->store();
            }else{
                $object->quitada = 'f';
                $object->store();
            }
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            $param['id'] = $object->id;
            self::updateTotal($param);

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

                $object = new ParcelasConta($key); // instantiates the Active Record 

                $object->subparcelas_parcela_parcela_mestre_fornecedor_id       = $object->fornecedor_id;
                $object->subparcelas_parcela_parcela_mestre_vencimento          = $object->vencimento;
                $object->subparcelas_parcela_parcela_mestre_forma_pagamento     = $object->forma_pagamento;
                $conta                                                          = new ContaBancaria($object->conta_bancaria);
                $conta->banco                                                   = $conta->fk_banco->nome;
                $object->subparcelas_parcela_parcela_mestre_conta_bancaria      = "$conta->nome - ag: $conta->agencia  c:$conta->numero_conta b: $conta->banco"; 
                $object->subparcelas_parcela_parcela_mestre_tipo_parcela        = 'Subparcela';
                $object->subparcelas_parcela_parcela_mestre_id_parcela_mestre   = $object->id;
                $object->subparcelas_parcela_parcela_mestre_conta_origem        = $object->conta_origem;

                   $pagamento = $object->subparcelas_parcela_parcela_mestre_forma_pagamento;
                   switch($pagamento){
                    case "deposito":
                        TCombo::disableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::enableField(self::$formName, 'conta_bancaria_fornecedor');
                        TDBCombo::enableField(self::$formName, 'subparcelas_parcela_parcela_mestre_loja_id');

                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_loja');
                        TCombo::enableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor');
                        TNumeric::enableField(self::$formName, 'subparcelas_parcela_parcela_mestre_valor');
                        TRadioGroup::enableField(self::$formName, 'quitada');
                        break;
                    case "transferencia":
                        TCombo::enableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::enableField(self::$formName, 'conta_bancaria_fornecedor');
                        //TrAVAR SUBPARCELA
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_loja_id');
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor');
                        TNumeric::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_valor');
                        TRadioGroup::disableField(self::$formName, 'quitada');
                        break;
                    case "boleto":
                        TCombo::enableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'conta_bancaria_fornecedor'); 
                        //TrAVAR SUBPARCELA
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_loja_id');
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor');
                        TNumeric::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_valor');
                        TRadioGroup::disableField(self::$formName, 'quitada');
                        break;
                    case "cheque":
                        TCombo::enableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'conta_bancaria_fornecedor'); 
                        //TrAVAR SUBPARCELA
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_loja_id');
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor');
                        TNumeric::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_valor');
                        TRadioGroup::disableField(self::$formName, 'quitada');
                        break;
                    default:
                        TCombo::disableField(self::$formName, 'conta_bancaria_loja'); 
                        TCombo::disableField(self::$formName, 'conta_bancaria_fornecedor'); 
                        //TrAVAR SUBPARCELA
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_loja_id');
                        TDBCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_conta_bancaria_fornecedor');
                        TNumeric::disableField(self::$formName, 'subparcelas_parcela_parcela_mestre_valor');
                        TRadioGroup::disableField(self::$formName, 'quitada');
                        break;
                }
                    $pagamento = $object->forma_pagamento;
                switch($pagamento){
                    case "deposito":
                        TCombo::disableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::enableField(self::$formName, 'conta_bancaria_fornecedor');
                        break;
                    case "transferencia":
                        TCombo::enableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::enableField(self::$formName, 'conta_bancaria_fornecedor');
                        break;
                    case "boleto":
                        TCombo::enableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'conta_bancaria_fornecedor'); 
                        break;
                    case "cheque":
                        TCombo::enableField(self::$formName, 'conta_bancaria_loja');
                        TCombo::disableField(self::$formName, 'conta_bancaria_fornecedor'); 
                        break;
                    default:
                        TCombo::disableField(self::$formName, 'conta_bancaria_loja'); 
                        TCombo::disableField(self::$formName, 'conta_bancaria_fornecedor'); 
                        break;
                }
                $param['id'] = $object->id;
                self::updateTotal($param);
                $subparcelas_parcela_parcela_mestre_items = $this->loadMasterDetailItems('SubparcelasParcela', 'id_parcela_mestre', 'subparcelas_parcela_parcela_mestre', $object, $this->form, $this->subparcelas_parcela_parcela_mestre_list, $this->subparcelas_parcela_parcela_mestre_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

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

        try{

            if (isset($param['key']))
            {

                $key = $param['key']; 
                TTransaction::open(self::$database); 
                $object = new ParcelasConta($key); 
                $subparcelas_parcela_parcela_mestre_items = $this->loadMasterDetailItems('SubparcelasParcela', 'id_parcela_mestre', 'subparcelas_parcela_parcela_mestre', $object, $this->form, $this->subparcelas_parcela_parcela_mestre_list, $this->subparcelas_parcela_parcela_mestre_criteria, function($masterObject, $detailObject, $objectItems){ 

                }); 
                $this->form->setData($object); 
                TTransaction::close(); 
            }
        }catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    } 

public function updateTotal($param=null){
        TTransaction::open(self::$database);
        $totalPago      = 0;
        $qtdParcelas    = 0;
        $valorProg      = 0;
        $id             = $param['id'];
        $parcela        = new ParcelasConta($id);
        $subparcelas    = SubparcelasParcela::where('id_parcela_mestre','=',$id)->load();
            if($subparcelas){
                foreach($subparcelas as $subparcela){
                    /*echo"<pre>";
                    var_dump($parcela);
                    echo"</pre>";*/
                    $totalPago = $subparcela->quitada=='t'?$totalPago+$subparcela->valor:$totalPago;
                    $valorProg = $subparcela->quitada=='f'?$valorProg+$subparcela->valor:$valorProg;
                    $qtdParcelas ++;
                }
            }
        $object = new stdClass();
        $object->totalPago      = str_replace('.',',',$totalPago);
        $object->qtdParcelas    = $qtdParcelas;
        $object->valorTotal     = str_replace('.',',',$parcela->valor);
        $object->valorProg      = str_replace('.',',',$valorProg);
        $object->valorRestante  = $object->valorTotal - $object->totalPago;

        TForm::sendData(self::$formName, $object);

        TTransaction::close();
    }
    public function formatarQuitacao($stock, $object, $row)
     {
         if ($object->quitada=='t'){
             return "<span style='color:green'>Sim</span>";
         }else{
             return "<span style='color:red'>Não</span>";
         }
     }

}

