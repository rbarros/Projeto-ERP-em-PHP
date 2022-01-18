<?php

class ContaaPagarForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Conta';
    private static $primaryKey = 'id';
    private static $formName = 'form_Conta';

    use BuilderMasterDetailTrait;

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
        $this->form->setFormTitle("Cadastro de conta a pagar");

        $criteria_loja = new TCriteria();
        $criteria_parcelas_conta_fk_conta_origem_conta_bancaria_loja = new TCriteria();

        $filterVar = TipoContaBancaria::Loja;
        $criteria_parcelas_conta_fk_conta_origem_conta_bancaria_loja->add(new TFilter('id_referencia_tipo', '=', $filterVar)); 

        $id = new TEntry('id');
        $tipo_conta_id = new TDBRadioGroup('tipo_conta_id', 'base_banco', 'TipoConta', 'id', '{nome}','nome asc'  );
        $quitada = new TRadioGroup('quitada');
        $loja = new TDBCombo('loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc' , $criteria_loja );
        $natureza_id = new TDBCombo('natureza_id', 'base_banco', 'Natureza', 'id', '{nome}','nome asc'  );
        $dt_emissao = new TDate('dt_emissao');
        $dt_vencimento = new TDate('dt_vencimento');
        $fornecedor = new TDBUniqueSearch('fornecedor', 'base_banco', 'Fornecedor', 'id', 'nome_fantasia','razao_social asc'  );
        $button_adicionar_fornecedor = new TButton('button_adicionar_fornecedor');
        $valor = new TNumeric('valor', '2', ',', '.' );
        $multa = new TNumeric('multa', '2', ',', '.' );
        $desconto = new TNumeric('desconto', '2', ',', '.' );
        $obs = new TText('obs');
        $qtdParcelas = new TEntry('qtdParcelas');
        $valorTotal = new TNumeric('valorTotal', '2', ',', '.' );
        $totalPago = new TNumeric('totalPago', '2', ',', '.' );
        $valorProg = new TNumeric('valorProg', '2', ',', '.' );
        $valorRestante = new TNumeric('valorRestante', '2', ',', '.' );
        $parcelas_conta_fk_conta_origem_loja_id = new TDBCombo('parcelas_conta_fk_conta_origem_loja_id', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $parcelas_conta_fk_conta_origem_id = new THidden('parcelas_conta_fk_conta_origem_id');
        $parcelas_conta_fk_conta_origem_fornecedor_id = new TDBCombo('parcelas_conta_fk_conta_origem_fornecedor_id', 'base_banco', 'Fornecedor', 'id', '{nome_fantasia}','razao_social asc'  );
        $isQuitar = new THidden('isQuitar');
        $parcelas_conta_fk_conta_origem_tipo_parcela = new TEntry('parcelas_conta_fk_conta_origem_tipo_parcela');
        $parcelas_conta_fk_conta_origem_forma_pagamento = new TCombo('parcelas_conta_fk_conta_origem_forma_pagamento');
        $parcelas_conta_fk_conta_origem_conta_bancaria_loja = new TDBCombo('parcelas_conta_fk_conta_origem_conta_bancaria_loja', 'base_banco', 'ContaBancaria', 'id', '{nome}','id asc' , $criteria_parcelas_conta_fk_conta_origem_conta_bancaria_loja );
        $button___parcelas_conta_fk_conta_origem = new TButton('button___parcelas_conta_fk_conta_origem');
        $parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor = new TCombo('parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor');
        $button__parcelas_conta_fk_conta_origem = new TButton('button__parcelas_conta_fk_conta_origem');
        $parcelas_conta_fk_conta_origem_valor = new TNumeric('parcelas_conta_fk_conta_origem_valor', '2', ',', '.' );
        $parcelas_conta_fk_conta_origem_vencimento = new TDate('parcelas_conta_fk_conta_origem_vencimento');
        $parcelas_conta_fk_conta_origem_quitada = new TRadioGroup('parcelas_conta_fk_conta_origem_quitada');
        $parcelas_conta_fk_conta_origem_obs = new TText('parcelas_conta_fk_conta_origem_obs');
        $parcelas_conta_fk_conta_origem_link_comprovante = new TEntry('parcelas_conta_fk_conta_origem_link_comprovante');
        $button_adicionar_parcela_parcelas_conta_fk_conta_origem = new TButton('button_adicionar_parcela_parcelas_conta_fk_conta_origem');

        $loja->setChangeAction(new TAction([$this,'onChangeLoja']));
        $fornecedor->setChangeAction(new TAction([$this,'onchangeFornecedor']));
        $parcelas_conta_fk_conta_origem_loja_id->setChangeAction(new TAction([$this,'onChangeContaLoja']));
        $parcelas_conta_fk_conta_origem_fornecedor_id->setChangeAction(new TAction([$this,'onChangeContaFornecedor']));
        $parcelas_conta_fk_conta_origem_forma_pagamento->setChangeAction(new TAction([$this,'onPagamento']));

        $dt_vencimento->setExitAction(new TAction([$this,'onChangeVencimento']));
        $valor->setExitAction(new TAction([$this,'onChangeValor']));

        $loja->addValidation("Loja", new TRequiredValidator()); 
        $natureza_id->addValidation("Natureza", new TRequiredValidator()); 
        $dt_emissao->addValidation("Data de emissão", new TRequiredValidator()); 
        $dt_vencimento->addValidation("Data de vencimento", new TRequiredValidator()); 
        $valor->addValidation("Valor", new TRequiredValidator()); 

        $parcelas_conta_fk_conta_origem_forma_pagamento->setDefaultOption(false);
        $fornecedor->setMinLength(2);
        $quitada->setLayout('horizontal');
        $tipo_conta_id->setLayout('horizontal');
        $parcelas_conta_fk_conta_origem_quitada->setLayout('horizontal');

        $quitada->addItems(['t'=>'SIM','f'=>'NÃO']);
        $parcelas_conta_fk_conta_origem_quitada->addItems(['t'=>'SIM','f'=>'NÃO']);
        $parcelas_conta_fk_conta_origem_forma_pagamento->addItems(['deposito'=>' Depósito','transferencia'=>' Transferência','cheque'=>' Cheque','dinheiro'=>'Dinheiro','boleto'=>' Boleto']);

        $loja->autofocus = 'autofocus';
        $button_adicionar_parcela_parcelas_conta_fk_conta_origem->id = '60b784f3e1105';

        $dt_emissao->setDatabaseMask('yyyy-mm-dd');
        $dt_vencimento->setDatabaseMask('yyyy-mm-dd');
        $parcelas_conta_fk_conta_origem_vencimento->setDatabaseMask('yyyy-mm-dd');

        $dt_emissao->setMask('dd/mm/yyyy');
        $dt_vencimento->setMask('dd/mm/yyyy');
        $fornecedor->setMask('{nome_fantasia}');
        $parcelas_conta_fk_conta_origem_vencimento->setMask('dd/mm/yyyy');

        $button_adicionar_fornecedor->setAction(new TAction(['FornecedorForm', 'onEdit']), "Adicionar fornecedor");
        $button__parcelas_conta_fk_conta_origem->setAction(new TAction(['ContaBancariaForm', 'onEdit'],['tipo_conta' => '3']), "");
        $button___parcelas_conta_fk_conta_origem->setAction(new TAction(['ContaBancariaForm', 'onEdit'],['tipo_conta' => '4']), "  ");
        $button_adicionar_parcela_parcelas_conta_fk_conta_origem->setAction(new TAction([$this, 'onAddDetailParcelasContaFkContaOrigem'],['static' => 1]), "Adicionar parcela");

        $button_adicionar_fornecedor->addStyleClass('btn-default');
        $button__parcelas_conta_fk_conta_origem->addStyleClass('btn-default');
        $button___parcelas_conta_fk_conta_origem->addStyleClass('btn-default');
        $button_adicionar_parcela_parcelas_conta_fk_conta_origem->addStyleClass('btn-success');

        $button_adicionar_fornecedor->setImage('fas:plus #ff0091');
        $button__parcelas_conta_fk_conta_origem->setImage('fas:plus #ff0091');
        $button___parcelas_conta_fk_conta_origem->setImage('fas:plus #ff0091');
        $button_adicionar_parcela_parcelas_conta_fk_conta_origem->setImage('fas:plus #06e43f');

        $quitada->setValue('f');
        $isQuitar->setValue('false');
        $tipo_conta_id->setValue('2');
        $parcelas_conta_fk_conta_origem_quitada->setValue('f');
        $parcelas_conta_fk_conta_origem_tipo_parcela->setValue('Parcela');

        $id->setEditable(false);
        $quitada->setEditable(false);
        $totalPago->setEditable(false);
        $valorProg->setEditable(false);
        $valorTotal->setEditable(false);
        $qtdParcelas->setEditable(false);
        $tipo_conta_id->setEditable(false);
        $valorRestante->setEditable(false);
        $parcelas_conta_fk_conta_origem_loja_id->setEditable(false);
        $parcelas_conta_fk_conta_origem_tipo_parcela->setEditable(false);
        $parcelas_conta_fk_conta_origem_fornecedor_id->setEditable(false);
        $parcelas_conta_fk_conta_origem_link_comprovante->setEditable(false);
        $parcelas_conta_fk_conta_origem_conta_bancaria_loja->setEditable(false);

        $id->setSize('100%');
        $loja->setSize('100%');
        $isQuitar->setSize(200);
        $valor->setSize('100%');
        $multa->setSize('100%');
        $quitada->setSize('100%');
        $obs->setSize('100%', 70);
        $desconto->setSize('100%');
        $tipo_conta_id->setSize(80);
        $totalPago->setSize('100%');
        $valorProg->setSize('100%');
        $fornecedor->setSize('100%');
        $dt_emissao->setSize('100%');
        $valorTotal->setSize('100%');
        $qtdParcelas->setSize('100%');
        $natureza_id->setSize('100%');
        $valorRestante->setSize('100%');
        $dt_vencimento->setSize('100%');
        $parcelas_conta_fk_conta_origem_id->setSize(200);
        $parcelas_conta_fk_conta_origem_valor->setSize('100%');
        $parcelas_conta_fk_conta_origem_loja_id->setSize('100%');
        $parcelas_conta_fk_conta_origem_quitada->setSize('100%');
        $parcelas_conta_fk_conta_origem_obs->setSize('100%', 70);
        $parcelas_conta_fk_conta_origem_vencimento->setSize('100%');
        $parcelas_conta_fk_conta_origem_tipo_parcela->setSize('100%');
        $parcelas_conta_fk_conta_origem_fornecedor_id->setSize('100%');
        $parcelas_conta_fk_conta_origem_forma_pagamento->setSize('100%');
        $parcelas_conta_fk_conta_origem_link_comprovante->setSize('100%');
        $parcelas_conta_fk_conta_origem_conta_bancaria_loja->setSize('100%');
        $parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor->setSize('100%');

        $this->form->appendPage("Conta");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addContent([new TFormSeparator("Informações da conta", '#333333', '18', '#ff0091')]);
        $row2 = $this->form->addFields([new TLabel("ID da conta:", null, '14px', null),$id],[],[new TLabel("Tipo de conta:", null, '14px', null, '100%'),$tipo_conta_id],[new TLabel("Quitada ?", null, '14px', 'B', '100%'),$quitada]);
        $row2->layout = [' col-sm-1',' col-sm-7','col-sm-2',' col-sm-2'];

        $row3 = $this->form->addFields([new TLabel("Loja:", null, '14px', null, '100%'),$loja],[new TLabel("Natureza:", null, '14px', null, '100%'),$natureza_id],[new TLabel("Data de emissão:", null, '14px', null, '100%'),$dt_emissao],[new TLabel("Data de vencimento:", null, '14px', null, '100%'),$dt_vencimento]);
        $row3->layout = [' col-sm-6',' col-sm-2','col-sm-2',' col-sm-2'];

        $row4 = $this->form->addFields([new TLabel("Fornecedor:", null, '14px', null, '100%'),$fornecedor],[new TLabel("  ", null, '14px', null, '100%'),$button_adicionar_fornecedor],[new TLabel("Valor(R$):", null, '14px', null, '100%'),$valor],[new TLabel("Multa:", null, '14px', null, '100%'),$multa],[new TLabel("Desconto:", null, '14px', null, '100%'),$desconto]);
        $row4->layout = [' col-sm-6',' col-sm-2','col-sm-2',' col-sm-1',' col-sm-1'];

        $row5 = $this->form->addFields([new TLabel("Observação:", null, '14px', null),$obs]);
        $row5->layout = [' col-sm-12'];

        $row6 = $this->form->addContent([new TFormSeparator("Quitação", '#333333', '14', '#ff0091')]);
        $row7 = $this->form->addFields([new TLabel("Quantidade de parcelas:", null, '14px', null),$qtdParcelas],[new TLabel("Valor Total:", null, '14px', null),$valorTotal],[new TLabel("Total pago:", null, '14px', null, '100%'),$totalPago],[new TLabel("Valor programado:", null, '14px', null, '100%'),$valorProg],[],[new TLabel("Valor restante:", null, '15px', 'B'),$valorRestante]);
        $row7->layout = [' col-sm-2',' col-sm-2',' col-sm-2',' col-sm-2',' col-sm-2',' col-sm-2'];

        $this->form->appendPage("Parcelas");
        $row8 = $this->form->addContent([new TFormSeparator("Parcelas", '#333333', '18', '#ff0091')]);

        $this->detailFormParcelasContaFkContaOrigem = new BootstrapFormBuilder('detailFormParcelasContaFkContaOrigem');
        $this->detailFormParcelasContaFkContaOrigem->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormParcelasContaFkContaOrigem->setProperty('class', 'form-horizontal builder-detail-form');

        $row9 = $this->detailFormParcelasContaFkContaOrigem->addFields([new TLabel("Loja:", null, '14px', null, '100%'),$parcelas_conta_fk_conta_origem_loja_id,$parcelas_conta_fk_conta_origem_id],[new TLabel("Fornecedor:", null, '14px', null, '100%'),$parcelas_conta_fk_conta_origem_fornecedor_id,$isQuitar],[new TLabel("Tipo Parcela:", null, '14px', null, '100%'),$parcelas_conta_fk_conta_origem_tipo_parcela]);
        $row9->layout = [' col-sm-4 col-lg-5',' col-sm-4 col-lg-5','col-sm-2'];

        $row10 = $this->detailFormParcelasContaFkContaOrigem->addFields([new TLabel("Forma de pagamento:", null, '14px', null, '100%'),$parcelas_conta_fk_conta_origem_forma_pagamento],[new TLabel("Conta Bancária da Loja:", null, '14px', null, '100%'),$parcelas_conta_fk_conta_origem_conta_bancaria_loja],[new TLabel("   ", null, '14px', null, '100%'),$button___parcelas_conta_fk_conta_origem],[new TLabel("Conta bancária do fornecedor:", null, '14px', null),$parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor],[new TLabel("    ", null, '14px', null, '100%'),$button__parcelas_conta_fk_conta_origem],[new TLabel("Valor(R$):", null, '14px', null, '100%'),$parcelas_conta_fk_conta_origem_valor],[new TLabel("Vencimento:", null, '14px', null, '100%'),$parcelas_conta_fk_conta_origem_vencimento],[new TLabel("Quitação:", null, '14px', null, '100%'),$parcelas_conta_fk_conta_origem_quitada]);
        $row10->layout = [' col-sm-4 col-lg-2',' col-sm-2 col-lg-2',' col-sm-2 col-lg-1',' col-sm-2 col-lg-2',' col-sm-2 col-lg-1',' col-sm-2 col-lg-1',' col-sm-2 col-lg-1','col-sm-2'];

        $row11 = $this->detailFormParcelasContaFkContaOrigem->addFields([new TLabel("Observação da parcela:", null, '14px', null),$parcelas_conta_fk_conta_origem_obs]);
        $row11->layout = [' col-sm-12'];

        $row12 = $this->detailFormParcelasContaFkContaOrigem->addFields([new TLabel("Link comprovante:", null, '14px', null, '100%'),$parcelas_conta_fk_conta_origem_link_comprovante]);
        $row12->layout = [' col-sm-3 col-lg-12'];

        $row13 = $this->detailFormParcelasContaFkContaOrigem->addFields([],[$button_adicionar_parcela_parcelas_conta_fk_conta_origem],[]);
        $row13->layout = [' col-sm-2 col-lg-5','col-sm-2',' col-sm-10 col-lg-5'];

        $row14 = $this->detailFormParcelasContaFkContaOrigem->addFields([new TFormSeparator("   ", '#ff0091', '18', '#ff0091')]);
        $row14->layout = [' col-sm-6 col-lg-12'];

        $row15 = $this->detailFormParcelasContaFkContaOrigem->addFields([new THidden('parcelas_conta_fk_conta_origem__row__id')]);
        $this->parcelas_conta_fk_conta_origem_criteria = new TCriteria();

        $this->parcelas_conta_fk_conta_origem_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->parcelas_conta_fk_conta_origem_list->disableHtmlConversion();;
        $this->parcelas_conta_fk_conta_origem_list->generateHiddenFields();
        $this->parcelas_conta_fk_conta_origem_list->setId('parcelas_conta_fk_conta_origem_list');

        $this->parcelas_conta_fk_conta_origem_list->style = 'width:100%';
        $this->parcelas_conta_fk_conta_origem_list->class .= ' table-bordered';

        $column_parcelas_conta_fk_conta_origem_conta_origem = new TDataGridColumn('conta_origem', "Conta", 'left');
        $column_parcelas_conta_fk_conta_origem_loja_nome_fantasia = new TDataGridColumn('loja->nome_fantasia', "Loja", 'left');
        $column_parcelas_conta_fk_conta_origem_fornecedor_razao_social = new TDataGridColumn('fornecedor->razao_social', "Fornecedor", 'left');
        $column_parcelas_conta_fk_conta_origem_forma_pagamento_parcelas_conta_fk_conta_origem_fk_conta_bancaria_loja_nome = new TDataGridColumn('{forma_pagamento}  {fk_conta_bancaria_loja->nome}', "Forma pagamento", 'left');
        $column_parcelas_conta_fk_conta_origem_valor_transformed = new TDataGridColumn('valor', "Valor", 'center');
        $column_parcelas_conta_fk_conta_origem_vencimento = new TDataGridColumn('vencimento', "Vencimento:", 'left');
        $column_parcelas_conta_fk_conta_origem_obs = new TDataGridColumn('obs', "Obs", 'left');
        $column_parcelas_conta_fk_conta_origem_quitada_transformed = new TDataGridColumn('quitada', "Quitada", 'left');

        $column_parcelas_conta_fk_conta_origem__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_parcelas_conta_fk_conta_origem__row__data->setVisibility(false);

        $column_parcelas_conta_fk_conta_origem_valor_transformed->enableTotal('sum', 'R$', 2, ',', '.');

        $action_onEditDetailParcelasConta = new TDataGridAction(array('ContaaPagarForm', 'onEditDetailParcelasConta'));
        $action_onEditDetailParcelasConta->setUseButton(false);
        $action_onEditDetailParcelasConta->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailParcelasConta->setLabel("Editar");
        $action_onEditDetailParcelasConta->setImage('far:edit #478fca');
        $action_onEditDetailParcelasConta->setFields(['__row__id', '__row__data']);

        $this->parcelas_conta_fk_conta_origem_list->addAction($action_onEditDetailParcelasConta);
        $action_onDeleteDetailParcelasConta = new TDataGridAction(array('ContaaPagarForm', 'onDeleteDetailParcelasConta'));
        $action_onDeleteDetailParcelasConta->setUseButton(false);
        $action_onDeleteDetailParcelasConta->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailParcelasConta->setLabel("Excluir");
        $action_onDeleteDetailParcelasConta->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailParcelasConta->setFields(['__row__id', '__row__data']);

        $this->parcelas_conta_fk_conta_origem_list->addAction($action_onDeleteDetailParcelasConta);
        $action_onEdit = new TDataGridAction(array('ParcelasContaForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Adicionar/ gerenciar  subparcela");
        $action_onEdit->setImage('far:clone #2ecc71');
        $action_onEdit->setFields(['__row__id', '__row__data']);
        $action_onEdit->setDisplayCondition('ContaaPagarForm::isSaved');
        $action_onEdit->setParameter('key', '{id}');
        $this->parcelas_conta_fk_conta_origem_list->addAction($action_onEdit);
        $action_onQuitar = new TDataGridAction(array('ContaaPagarForm', 'onQuitar'));
        $action_onQuitar->setUseButton(false);
        $action_onQuitar->setButtonClass('btn btn-default btn-sm');
        $action_onQuitar->setLabel("");
        $action_onQuitar->setImage('fas:check #2ecc71');
        $action_onQuitar->setFields(['__row__id', '__row__data']);
        $action_onQuitar->setDisplayCondition('ContaaPagarForm::isQuitada');

        $this->parcelas_conta_fk_conta_origem_list->addAction($action_onQuitar);

        $this->parcelas_conta_fk_conta_origem_list->addColumn($column_parcelas_conta_fk_conta_origem_conta_origem);
        $this->parcelas_conta_fk_conta_origem_list->addColumn($column_parcelas_conta_fk_conta_origem_loja_nome_fantasia);
        $this->parcelas_conta_fk_conta_origem_list->addColumn($column_parcelas_conta_fk_conta_origem_fornecedor_razao_social);
        $this->parcelas_conta_fk_conta_origem_list->addColumn($column_parcelas_conta_fk_conta_origem_forma_pagamento_parcelas_conta_fk_conta_origem_fk_conta_bancaria_loja_nome);
        $this->parcelas_conta_fk_conta_origem_list->addColumn($column_parcelas_conta_fk_conta_origem_valor_transformed);
        $this->parcelas_conta_fk_conta_origem_list->addColumn($column_parcelas_conta_fk_conta_origem_vencimento);
        $this->parcelas_conta_fk_conta_origem_list->addColumn($column_parcelas_conta_fk_conta_origem_obs);
        $this->parcelas_conta_fk_conta_origem_list->addColumn($column_parcelas_conta_fk_conta_origem_quitada_transformed);

        $this->parcelas_conta_fk_conta_origem_list->addColumn($column_parcelas_conta_fk_conta_origem__row__data);

        $this->parcelas_conta_fk_conta_origem_list->createModel();
        $this->detailFormParcelasContaFkContaOrigem->addContent([$this->parcelas_conta_fk_conta_origem_list]);

        $column_parcelas_conta_fk_conta_origem_valor_transformed->setTransformer(function($value, $object, $row) 
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

        $column_parcelas_conta_fk_conta_origem_quitada_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value === true || $value == 't' || $value === 1 || $value == '1' || $value == 's' || $value == 'S' || $value == 'T')
                return 'Sim';

            return 'Não';

        });        $row16 = $this->form->addFields([$this->detailFormParcelasContaFkContaOrigem]);
        $row16->layout = [' col-sm-12'];

        $column_parcelas_conta_fk_conta_origem_quitada_transformed->setTransformer(array($this,'formatarQuitacao'));

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'far:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar Campos", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onvoltar = $this->form->addAction("Voltar", new TAction([$this, 'onVoltar']), 'fas:arrow-left #ffffff');
        $this->btn_onvoltar = $btn_onvoltar;
        $btn_onvoltar->addStyleClass('btn-success'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Financeiro","Conta a Pagar"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangeVencimento($param = null) 
    {
        try 
        {
            $valor = $param['dt_vencimento'];
            $object = new stdClass();
            $object->parcelas_conta_fk_conta_origem_vencimento = $valor;
            TForm::sendData(self::$formName, $object);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeValor($param = null) 
    {
        try 
        {
            $valor = $param['valor'];
            $object = new stdClass();
            $object->parcelas_conta_fk_conta_origem_valor = $valor;
            TForm::sendData(self::$formName, $object);

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
            $object = new stdClass();
            $valor = $param['loja'];
            TTransaction::open(self::$database);
            $contas = ContaBancaria::where('loja','=',$valor)->load();
            if($contas){
                $conta = $contas[0];
                $object->parcelas_conta_fk_conta_origem_conta_bancaria_loja = $conta->id;
            }else{
                $object->parcelas_conta_fk_conta_origem_conta_bancaria_loja = '';
            }
            TTransaction::close();
            $object = new stdClass();
            $object->parcelas_conta_fk_conta_origem_loja_id = $valor;
            TForm::sendData(self::$formName, $object);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onchangeFornecedor($param = null) 
    {
        try 
        {
           $valor = $param['fornecedor'];
            $object = new stdClass();
            $object->parcelas_conta_fk_conta_origem_fornecedor_id = $valor;
            TForm::sendData(self::$formName, $object);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeContaLoja($param = null) 
    {
        try 
        {
           /* $id_loja            = $param['parcelas_conta_fk_conta_origem_loja_id'];
            $contas_array       = [];
            if($id_loja != "" || $id_loja != null){
                TTransaction::open(self::$database);
                $contas = ContaBancaria::where('loja','=',$id_loja)->load();
                if($contas){
                    foreach($contas as $conta){
                        $banco = new Banco($conta->banco);
                        $contas_array[$conta->id] = "$conta->nome - ag: $conta->agencia  c:$conta->numero_conta b: $banco->nome"; 
                    }
                    TCombo::reload(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja', $contas_array, true);
                }
                TTransaction::close();
            }
*/

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeContaFornecedor($param = null) 
    {
        try 
        {
            $id_fornecedor      = $param['parcelas_conta_fk_conta_origem_fornecedor_id'];
            $contas_array       = [];
            if($id_fornecedor != "" || $id_fornecedor != null){
                TTransaction::open(self::$database);
                $contas = ContaBancaria::where('fornecedor','=',$id_fornecedor)->load();
                if($contas){
                    foreach($contas as $conta){
                        $banco = new Banco($conta->banco);
                        $contas_array[$conta->id] = "$conta->nome - ag: $conta->agencia  c:$conta->numero_conta b: $banco->nome"; 
                    }
                    TCombo::reload(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor', $contas_array, true);
                }
                TTransaction::close();
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onPagamento($param = null) 
    {
        try 
        {
        $pagamento = $param['parcelas_conta_fk_conta_origem_forma_pagamento'];
        switch($pagamento){
            case "deposito":
                TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja');
                TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor');
                break;
            case "transferencia":
                TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja');
                TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor');
                break;
            case "boleto":
                TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja');
                TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor'); 
                break;
            case "cheque":
                TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja');
                TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor'); 
                break;
            default:
                TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja'); 
                TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor'); 
                break;
        }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailParcelasContaFkContaOrigem($param = null) 
    {
        try
        {
            $data = $this->form->getData();
            if($data->parcelas_conta_fk_conta_origem_loja_id == "" || $data->parcelas_conta_fk_conta_origem_fornecedor_id == ""){
                $data->parcelas_conta_fk_conta_origem_loja_id           = $data->loja;
                $data->parcelas_conta_fk_conta_origem_fornecedor_id     = $data->fornecedor;
            }
            $data->parcelas_conta_fk_conta_origem_tipo_parcela = "Parcela";
            $isQuitar = $data->isQuitar;

            $errors = [];
            $requiredFields = [];
            $requiredFields["parcelas_conta_fk_conta_origem_loja_id"] = "quem pagou";
            $requiredFields["parcelas_conta_fk_conta_origem_forma_pagamento"] = "Forma pagamento";
            $requiredFields["parcelas_conta_fk_conta_origem_valor"] = "Valor";
            $requiredFields["parcelas_conta_fk_conta_origem_vencimento"] = "Data parcela";
            $requiredFields["parcelas_conta_fk_conta_origem_quitada"] = "Quitação da parcela";
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

            $__row__id = !empty($data->parcelas_conta_fk_conta_origem__row__id) ? $data->parcelas_conta_fk_conta_origem__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new ParcelasConta();
            $grid_data->__row__id = $__row__id;
            $grid_data->loja_id = $data->parcelas_conta_fk_conta_origem_loja_id;
            $grid_data->id = $data->parcelas_conta_fk_conta_origem_id;
            $grid_data->fornecedor_id = $data->parcelas_conta_fk_conta_origem_fornecedor_id;
            $grid_data->isQuitar = $data->isQuitar;
            $grid_data->tipo_parcela = $data->parcelas_conta_fk_conta_origem_tipo_parcela;
            $grid_data->forma_pagamento = $data->parcelas_conta_fk_conta_origem_forma_pagamento;
            $grid_data->conta_bancaria_loja = $data->parcelas_conta_fk_conta_origem_conta_bancaria_loja;
            $grid_data->conta_bancaria_fornecedor = $data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor;
            $grid_data->valor = $data->parcelas_conta_fk_conta_origem_valor;
            $grid_data->vencimento = $data->parcelas_conta_fk_conta_origem_vencimento;
            $grid_data->quitada = $data->parcelas_conta_fk_conta_origem_quitada;
            $grid_data->obs = $data->parcelas_conta_fk_conta_origem_obs;
            $grid_data->link_comprovante = $data->parcelas_conta_fk_conta_origem_link_comprovante;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['loja_id'] =  $param['parcelas_conta_fk_conta_origem_loja_id'] ?? null;
            $__row__data['__display__']['id'] =  $param['parcelas_conta_fk_conta_origem_id'] ?? null;
            $__row__data['__display__']['fornecedor_id'] =  $param['parcelas_conta_fk_conta_origem_fornecedor_id'] ?? null;
            $__row__data['__display__']['isQuitar'] =  $param['isQuitar'] ?? null;
            $__row__data['__display__']['tipo_parcela'] =  $param['parcelas_conta_fk_conta_origem_tipo_parcela'] ?? null;
            $__row__data['__display__']['forma_pagamento'] =  $param['parcelas_conta_fk_conta_origem_forma_pagamento'] ?? null;
            $__row__data['__display__']['conta_bancaria_loja'] =  $param['parcelas_conta_fk_conta_origem_conta_bancaria_loja'] ?? null;
            $__row__data['__display__']['conta_bancaria_fornecedor'] =  $param['parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor'] ?? null;
            $__row__data['__display__']['valor'] =  $param['parcelas_conta_fk_conta_origem_valor'] ?? null;
            $__row__data['__display__']['vencimento'] =  $param['parcelas_conta_fk_conta_origem_vencimento'] ?? null;
            $__row__data['__display__']['quitada'] =  $param['parcelas_conta_fk_conta_origem_quitada'] ?? null;
            $__row__data['__display__']['obs'] =  $param['parcelas_conta_fk_conta_origem_obs'] ?? null;
            $__row__data['__display__']['link_comprovante'] =  $param['parcelas_conta_fk_conta_origem_link_comprovante'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->parcelas_conta_fk_conta_origem_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('parcelas_conta_fk_conta_origem_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->parcelas_conta_fk_conta_origem_loja_id = '';
            $data->parcelas_conta_fk_conta_origem_id = '';
            $data->parcelas_conta_fk_conta_origem_fornecedor_id = '';
            $data->isQuitar = 'false';
            $data->parcelas_conta_fk_conta_origem_tipo_parcela = 'Parcela';
            $data->parcelas_conta_fk_conta_origem_forma_pagamento = '';
            $data->parcelas_conta_fk_conta_origem_conta_bancaria_loja = '';
            $data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor = '';
            $data->parcelas_conta_fk_conta_origem_valor = '';
            $data->parcelas_conta_fk_conta_origem_vencimento = '';
            $data->parcelas_conta_fk_conta_origem_quitada = 'f';
            $data->parcelas_conta_fk_conta_origem_obs = '';
            $data->parcelas_conta_fk_conta_origem_link_comprovante = '';
            $data->parcelas_conta_fk_conta_origem__row__id = '';

            $data->parcelas_conta_fk_conta_origem_loja_id                       = $grid_data->loja_id;
            $data->parcelas_conta_fk_conta_origem_fornecedor_id                 = $grid_data->fornecedor_id;
            $data->parcelas_conta_fk_conta_origem_forma_pagamento               = $grid_data->forma_pagamento;
            $data->parcelas_conta_fk_conta_origem_vencimento                    = $grid_data->vencimento;
            $data->parcelas_conta_fk_conta_origem_conta_bancaria_loja           = $grid_data->conta_bancaria_loja;                         
            $data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor     = $grid_data->conta_bancaria_fornecedor;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#60b784f3e1105');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

            if($isQuitar){
                TDate::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_data_parcela');
                TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_forma_pagamento');
                TDBCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria');
                TNumeric::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_valor');
                TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_quitada');

            }
            self::updateTotal($param);
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public static function onEditDetailParcelasConta($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->parcelas_conta_fk_conta_origem_loja_id = $__row__data->__display__->loja_id ?? null;
            $data->parcelas_conta_fk_conta_origem_id = $__row__data->__display__->id ?? null;
            $data->parcelas_conta_fk_conta_origem_fornecedor_id = $__row__data->__display__->fornecedor_id ?? null;
            $data->isQuitar = $__row__data->__display__->isQuitar ?? null;
            $data->parcelas_conta_fk_conta_origem_tipo_parcela = $__row__data->__display__->tipo_parcela ?? null;
            $data->parcelas_conta_fk_conta_origem_forma_pagamento = $__row__data->__display__->forma_pagamento ?? null;
            $data->parcelas_conta_fk_conta_origem_conta_bancaria_loja = $__row__data->__display__->conta_bancaria_loja ?? null;
            $data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor = $__row__data->__display__->conta_bancaria_fornecedor ?? null;
            $data->parcelas_conta_fk_conta_origem_valor = $__row__data->__display__->valor ?? null;
            $data->parcelas_conta_fk_conta_origem_vencimento = $__row__data->__display__->vencimento ?? null;
            $data->parcelas_conta_fk_conta_origem_quitada = $__row__data->__display__->quitada ?? null;
            $data->parcelas_conta_fk_conta_origem_obs = $__row__data->__display__->obs ?? null;
            $data->parcelas_conta_fk_conta_origem_link_comprovante = $__row__data->__display__->link_comprovante ?? null;
            $data->parcelas_conta_fk_conta_origem__row__id = $__row__data->__row__id;

            /*switch($data->parcelas_conta_fk_conta_origem_forma_pagamento){
            case "deposito":
                if($data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor = ''  ||
                   $data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor =null ||
                   !isset($data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor)){
                    throw new Exception('para depósitos, informe a conta bancária do fornecedor.');
                }
                break;
            case "transferencia":
                if($data->parcelas_conta_fk_conta_origem_conta_bancaria_loja = ''               ||
                   $data->parcelas_conta_fk_conta_origem_conta_bancaria_loja =null              ||
                   !isset($data->parcelas_conta_fk_conta_origem_conta_bancaria_loja)            ||
                   $data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor = ''         ||
                   $data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor =null        ||
                   !isset($data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor)){

                    throw new Exception('para transferências, é preciso preencher as contas da loja e fornecedor.');
                }
                break;
            case "boleto":
                /*if($data->parcelas_conta_fk_conta_origem_conta_bancaria_loja = ''  ||
                   $data->parcelas_conta_fk_conta_origem_conta_bancaria_loja = null ||
                   !isset($data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor)){
                    throw new Exception('para depósitos, informe a conta bancária do fornecedor.');
                }
                TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja');
                TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor'); 
                break;
            case "cheque":
                /*TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja');
                TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor'); 
                break;
            default:
                break;
        }*/

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#60b784f3e1105');
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
    public static function onDeleteDetailParcelasConta($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->parcelas_conta_fk_conta_origem_loja_id = '';
            $data->parcelas_conta_fk_conta_origem_id = '';
            $data->parcelas_conta_fk_conta_origem_fornecedor_id = '';
            $data->isQuitar = '';
            $data->parcelas_conta_fk_conta_origem_tipo_parcela = '';
            $data->parcelas_conta_fk_conta_origem_forma_pagamento = '';
            $data->parcelas_conta_fk_conta_origem_conta_bancaria_loja = '';
            $data->parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor = '';
            $data->parcelas_conta_fk_conta_origem_valor = '';
            $data->parcelas_conta_fk_conta_origem_vencimento = '';
            $data->parcelas_conta_fk_conta_origem_quitada = '';
            $data->parcelas_conta_fk_conta_origem_obs = '';
            $data->parcelas_conta_fk_conta_origem_link_comprovante = '';
            $data->parcelas_conta_fk_conta_origem__row__id = '';

            $totalPago          = $param['totalPago'];
            $totalPago          = $totalPago - $__row__data->valor;
            $qtdParcelas        = $param['qtdParcelas'];
            $qtdParcelas        = $qtdParcelas - 1;

            $data->totalPago    = doubleval($totalPago);
            $data->qtdParcelas  = $qtdParcelas;

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('parcelas_conta_fk_conta_origem_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function isSaved($object)
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
    public static function onQuitar($param = null) 
    {
        try 
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->parcelas_conta_fk_conta_origem_loja_id           = $__row__data->__display__->loja_id ?? null;
            $data->parcelas_conta_fk_conta_origem_id                = $__row__data->__display__->id ?? null;
            $data->parcelas_conta_fk_conta_origem_fornecedor_id     = $__row__data->__display__->fornecedor_id ?? null;
            $data->parcelas_conta_fk_conta_origem_tipo_parcela      = $__row__data->__display__->tipo_parcela ?? null;
            $data->parcelas_conta_fk_conta_origem_vencimento        = $__row__data->__display__->vencimento ?? null;
            $data->parcelas_conta_fk_conta_origem_forma_pagamento   = $__row__data->__display__->forma_pagamento ?? null;
            $data->parcelas_conta_fk_conta_origem_conta_bancaria    = $__row__data->__display__->conta_bancaria ?? null;
            $data->parcelas_conta_fk_conta_origem_valor             = $__row__data->__display__->valor ?? null;
            $data->parcelas_conta_fk_conta_origem_quitada           = 't';
            $data->parcelas_conta_fk_conta_origem_obs               = $__row__data->__display__->obs ?? null;
            $data->parcelas_conta_fk_conta_origem__row__id          = $__row__data->__row__id;

            $data->isQuitar                                         = true;
            TDate::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_data_parcela');
            TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_forma_pagamento');
            TDBCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria');
            TNumeric::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_valor');
            TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_quitada');

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#60b784f3e1105');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='fas fa-check' style='color:#2ecc71;padding-right:4px;'></i>Quitar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function isQuitada($object)
    {
        try 
        {
            if($object->quitada == "t" || $object->quitada == "T")
            {
                return false;
            }

            return true;
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
            $object = new Conta(); // create an empty object 
            $data = $this->form->getData(); // get form data as array
            $data->parcelas_conta_fk_conta_origem_fornecedor_id  = $data->fornecedor;
            $data->parcelas_conta_fk_conta_origem_loja_id        = $data->loja;
            $data->parcelas_conta_fk_conta_origem_tipo_parcela   = "Parcela";
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $messageAction = new TAction(['ContaReceber', 'onShow']);   

            if(!empty($param['target_container']))
            {
                $messageAction->setParameter('target_container', $param['target_container']);
            }

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            $parcelas_conta_fk_conta_origem_items = $this->storeMasterDetailItems('ParcelasConta', 'conta_origem', 'parcelas_conta_fk_conta_origem', $object, $param['parcelas_conta_fk_conta_origem_list___row__data'] ?? [], $this->form, $this->parcelas_conta_fk_conta_origem_list, function($masterObject, $detailObject){ 

                //code here

            }); 
            $parcelas = ParcelasConta::where('conta_origem','=',$object->id)->load();
            $total_pago = 0;
            if($parcelas){
                foreach($parcelas as $parcela){
                    if($parcela->quitada == 't'){
                        $total_pago = $total_pago + doubleval($parcela->valor);
                        $object->forma_pagamento = $parcela->forma_pagamento;
                    }else{
                        $object->forma_pagamento = $parcela->forma_pagamento;
                    }
                }
            }else{
                throw new Exception('Por favor cadastre uma parcela a esta conta');
            }
            if($total_pago == doubleval($object->valor)){
                $object->quitada = 't';
                $object->store();

            }else{
                $object->quitada = 'f';
                $object->store();
            }
            $data->id = $object->id; 
            self::updateTotal($param);
            //enviar arquivo apra o GDrive
            if(isset($data->caminho) && $data->caminho != null){
                $config                      = array();
                $config['file_path']         = $data->caminho;
                $config['user']              = TSession::getValue('username');
                $config['vencimento']        = $data->parcelas_conta_fk_conta_origem_vencimento;
                $config['valor']             = $data->parcelas_conta_fk_conta_origem_valor;
                $fornecedor                  = new Fornecedor($data->fornecedor);
                $config['fornecedor']        = $fornecedor->nome_fantasia;
                $return                      = ApiManager::sendGoogleFile($config);
                echo $return;
            }

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onVoltar($param = null) 
    {
        try 
        {
    TApplication::loadPage('ContaAPagarAdminList', 'onShow');

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
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Conta($key); // instantiates the Active Record 
                $pagamento = $object->parcelas_conta_fk_conta_origem_forma_pagamento;
                    switch($pagamento){
                        case "deposito":
                            TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja');
                            TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor');
                            break;
                        case "transferencia":
                            TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja');
                            TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor');
                            break;
                        case "boleto":
                            TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja');
                            TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor'); 
                            break;
                        case "cheque":
                            TCombo::enableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja');
                            TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor'); 
                            break;
                        default:
                            TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_loja'); 
                            TCombo::disableField(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor'); 
                            break;
                    }
                    $contas = ContaBancaria::where('fornecedor','=',$object->fornecedor)->load();
                        if($contas){
                            $contas_array       = [];
                            foreach($contas as $conta){
                                $banco = $conta->fk_banco;
                                $contas_array[$conta->id] = "$conta->nome - ag: $conta->agencia  c:$conta->numero_conta b: $banco->nome"; 
                            }
                            TCombo::reload(self::$formName, 'parcelas_conta_fk_conta_origem_conta_bancaria_fornecedor', $contas_array, true);
                        }

                $parcelas_conta_fk_conta_origem_items = $this->loadMasterDetailItems('ParcelasConta', 'conta_origem', 'parcelas_conta_fk_conta_origem', $object, $this->form, $this->parcelas_conta_fk_conta_origem_list, $this->parcelas_conta_fk_conta_origem_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 
                $data = $this->form->getData();
                $setUpData = new stdClass();
                $setUpData = $object;
                $setUpData->parcelas_conta_fk_conta_origem_fornecedor_id  = $object->fornecedor;
                $setUpData->parcelas_conta_fk_conta_origem_loja_id        = $object->loja;
                $setUpData->parcelas_conta_fk_conta_origem_tipo_parcela   = "Parcela";
                $setUpData->parcelas_conta_fk_conta_origem_quitada        = "f";

                self::updateTotal($param);

                /*
                $this->form->setData($object); // fill the form 
                */
                $this->form->setData($setUpData); // 

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

        TCombo::disableField(self::$formName, 'desconto');

    }

    public function onShow($param = null)
    {

    } 

    public function updateTotal($param=null){
        TTransaction::open(self::$database);
        $totalPago      = 0;
        $qtdParcelas    = 0;
        $valorProg      = 0;
        $id             = $param['id'];
        $conta          = new Conta($id);
        $parcelas = ParcelasConta::where('conta_origem','=',$id)->load();
            if($parcelas){
                foreach($parcelas as $parcela){
                    $totalPago = $parcela->quitada=='t'?$totalPago+doubleval($parcela->valor):$totalPago;
                    $valorProg = $parcela->quitada=='f'?$valorProg+doubleval($parcela->valor):$valorProg;
                    $qtdParcelas ++;
                }
            }
        $object = new stdClass();
        $object->totalPago      = doubleval(str_replace('.',',',$totalPago));
        $object->qtdParcelas    = intval($qtdParcelas);
        $object->valorTotal     = doubleval(str_replace('.',',',$conta->valor));
        $object->valorProg      = doubleval(str_replace('.',',',$valorProg));
        $object->valorRestante  = doubleval($object->valorTotal - $object->totalPago);
        if($object->totalPago == $object->valorTotal){
            $object->quitada    = 't';
        }
        TForm::sendData(self::$formName, $object);

        TTransaction::close();
    }
    public function formatarQuitacao($stock, $object, $row)
     {
         if ($object->quitada == 't'){
             return "<span style='color:green'>Sim</span>";
         }else{
             return "<span style='color:red'>Não</span>";
         }
     }

}

