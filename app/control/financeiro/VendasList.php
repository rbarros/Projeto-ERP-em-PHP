<?php

class VendasList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'vendas_base';
    private static $activeRecord = 'Venda';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Venda';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters'];
    private $limit = 20;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Vendas");
        $this->limit = 100;

        $id = new TEntry('id');
        $id_interno = new TEntry('id_interno');
        $n_venda = new TEntry('n_venda');
        $forma_pagamento = new TCombo('forma_pagamento');
        $forma_diferente = new TCombo('forma_diferente');
        $valor_total = new TNumeric('valor_total', '2', ',', '.' );
        $dt_venda = new TDateTime('dt_venda');
        $dt_venda_final = new TDateTime('dt_venda_final');
        $desconto = new TRadioGroup('desconto');
        $fiscal = new TRadioGroup('fiscal');
        $status = new TCombo('status');
        $Loja_ = new TEntry('Loja_');
        $cliente_id = new TEntry('cliente_id');
        $vendedor_id = new TEntry('vendedor_id');
        $estado_venda_id = new TEntry('estado_venda_id');
        $system_unit_id = new TEntry('system_unit_id');
        $variavel_duplicidade = new TEntry('variavel_duplicidade');
        $total_produtos = new TNumeric('total_produtos', '2', ',', '.' );
        $caixa = new TCombo('caixa');
        $func_caixa = new TDBEntry('func_caixa', 'vendas_base', 'Venda', 'func_caixa','func_caixa asc'  );
        $total_desconto = new TNumeric('total_desconto', '2', ',', '.' );
        $id_venda = new TEntry('id_venda');
        $total_pagamentos = new TNumeric('total_pagamentos', '2', ',', '.' );
        $loja = new TDBCombo('loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $dinheiroDiarioFiscal = new TNumeric('dinheiroDiarioFiscal', '2', ',', '.' );
        $dinheiroMensalFiscal = new TNumeric('dinheiroMensalFiscal', '2', ',', '.' );
        $dinheiroBuscaFiscal = new TNumeric('dinheiroBuscaFiscal', '2', ',', '.' );
        $dinheiroDiario = new TNumeric('dinheiroDiario', '2', ',', '.' );
        $dinheiroMensal = new TNumeric('dinheiroMensal', '2', ',', '.' );
        $dinheiroBusca = new TNumeric('dinheiroBusca', '2', ',', '.' );
        $creditoDiario = new TNumeric('creditoDiario', '2', ',', '.' );
        $creditoMensal = new TNumeric('creditoMensal', '2', ',', '.' );
        $creditoBusca = new TNumeric('creditoBusca', '2', ',', '.' );
        $debitoDiario = new TNumeric('debitoDiario', '2', ',', '.' );
        $debitoMensal = new TNumeric('debitoMensal', '2', ',', '.' );
        $debitoBusca = new TNumeric('debitoBusca', '2', ',', '.' );
        $pixDiario = new TNumeric('pixDiario', '2', ',', '.' );
        $pixMensal = new TNumeric('pixMensal', '2', ',', '.' );
        $pixBusca = new TNumeric('pixBusca', '2', ',', '.' );
        $diarioTotal = new TNumeric('diarioTotal', '2', ',', '.' );
        $mensalTotal = new TNumeric('mensalTotal', '2', ',', '.' );
        $buscaTotal = new TNumeric('buscaTotal', '2', ',', '.' );

        $loja->setChangeAction(new TAction([$this,'onChangeLoja']));

        $desconto->setBooleanMode();
        $func_caixa->setDisplayMask('{func_caixa}');
        $pixDiario->setAllowNegative(false);

        $dt_venda->setMask('dd/mm/yyyy hh:ii');
        $dt_venda_final->setMask('dd/mm/yyyy hh:ii');

        $dt_venda->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_venda_final->setDatabaseMask('yyyy-mm-dd hh:ii');

        $fiscal->setLayout('horizontal');
        $desconto->setLayout('horizontal');

        $n_venda->setMaxLength(30);
        $id_interno->setMaxLength(20);
        $variavel_duplicidade->setMaxLength(600);

        $fiscal->addItems(['T'=>'sim','F'=>'não']);
        $desconto->addItems(['1'=>'Sim','2'=>'Não']);
        $caixa->addItems(['Caixa1'=>' Caixa1','Caixa2'=>' Caixa2','Caixa3'=>' Caixa3']);
        $forma_pagamento->addItems(['Dinheiro'=>' Dinheiro','Pagamento misto'=>' Pagamento misto','Cartão Credito à Vista'=>' Cartão Credito à Vista','Cartão Débito'=>' Cartão Débito','Cartão Credito parcelado'=>' Cartão Credito parcelado','pix'=>' Pix']);
        $forma_diferente->addItems(['Dinheiro'=>' Dinheiro','Pagamento misto'=>' Pagamento misto','Cartão Credito à Vista'=>' Cartão Credito à Vista','Cartão Débito'=>' Cartão Débito','Cartão Credito parcelado'=>' Cartão Credito parcelado','pix'=>' Pix']);
        $status->addItems(['Negada'=>'Negada','Autorizada'=>' Autorizada','completed'=>' finalizada','Erro'=>'Erro','Duplicata'=>'Duplicata','Cancelada'=>'Cancelada','SolicitandoAutorizacao'=>'SolicitandoAutorizacao','AutorizadaAguardandoGeracaoPDF'=>'AutorizadaAguardandoGeracaoPDF']);

        $pixBusca->setEditable(false);
        $pixMensal->setEditable(false);
        $pixDiario->setEditable(false);
        $buscaTotal->setEditable(false);
        $mensalTotal->setEditable(false);
        $diarioTotal->setEditable(false);
        $debitoBusca->setEditable(false);
        $debitoMensal->setEditable(false);
        $debitoDiario->setEditable(false);
        $creditoBusca->setEditable(false);
        $creditoMensal->setEditable(false);
        $creditoDiario->setEditable(false);
        $dinheiroBusca->setEditable(false);
        $dinheiroMensal->setEditable(false);
        $dinheiroDiario->setEditable(false);
        $dinheiroBuscaFiscal->setEditable(false);
        $dinheiroMensalFiscal->setEditable(false);
        $dinheiroDiarioFiscal->setEditable(false);

        $id->setSize(100);
        $loja->setSize('100%');
        $desconto->setSize(80);
        $caixa->setSize('100%');
        $Loja_->setSize('100%');
        $fiscal->setSize('100%');
        $status->setSize('100%');
        $n_venda->setSize('100%');
        $pixBusca->setSize('100%');
        $id_venda->setSize('100%');
        $dt_venda->setSize('100%');
        $pixMensal->setSize('100%');
        $pixDiario->setSize('100%');
        $buscaTotal->setSize('100%');
        $id_interno->setSize('100%');
        $func_caixa->setSize('100%');
        $cliente_id->setSize('100%');
        $diarioTotal->setSize('100%');
        $mensalTotal->setSize('100%');
        $valor_total->setSize('100%');
        $debitoBusca->setSize('100%');
        $vendedor_id->setSize('100%');
        $debitoMensal->setSize('100%');
        $debitoDiario->setSize('100%');
        $creditoBusca->setSize('100%');
        $dinheiroBusca->setSize('100%');
        $creditoMensal->setSize('100%');
        $creditoDiario->setSize('100%');
        $dt_venda_final->setSize('100%');
        $dinheiroMensal->setSize('100%');
        $dinheiroDiario->setSize('100%');
        $total_desconto->setSize('100%');
        $total_produtos->setSize('100%');
        $system_unit_id->setSize('100%');
        $forma_diferente->setSize('100%');
        $forma_pagamento->setSize('100%');
        $estado_venda_id->setSize('100%');
        $total_pagamentos->setSize('100%');
        $dinheiroBuscaFiscal->setSize('100%');
        $dinheiroMensalFiscal->setSize('100%');
        $dinheiroDiarioFiscal->setSize('100%');
        $variavel_duplicidade->setSize('100%');

        $this->form->appendPage("Busca");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Id interno:", null, '14px', null, '100%'),$id_interno],[new TLabel("N venda:", null, '14px', null, '100%'),$n_venda],[new TLabel("Forma pagamento:", null, '14px', null, '100%'),$forma_pagamento],[new TLabel("forma pgto. (diferente de:)", null, '14px', null, '100%'),$forma_diferente],[new TLabel("Valor total:", null, '14px', null, '100%'),$valor_total]);
        $row1->layout = [' col-sm-6 col-lg-1','col-sm-2',' col-sm-6 col-lg-1',' col-sm-2 col-lg-2',' col-sm-2 col-lg-3',' col-sm-2 col-lg-3'];

        $row2 = $this->form->addFields([new TLabel("Data da venda (de):", null, '14px', null, '100%'),$dt_venda],[new TLabel("Data da venda (até):", null, '14px', null, '100%'),$dt_venda_final],[new TLabel("Vendas com desconto:", null, '14px', null, '100%'),$desconto],[new TLabel("Venda fiscal:", null, '14px', null, '100%'),$fiscal],[new TLabel("Status:", null, '14px', null),$status],[new TLabel("Loja:", null, '14px', null, '100%'),$Loja_]);
        $row2->layout = [' col-sm-3 col-lg-2',' col-sm-3 col-lg-2','col-sm-2','col-sm-6 col-lg-3','col-sm-2 col-lg-1','col-sm-2'];

        $this->form->appendPage("Busca avançada");
        $row3 = $this->form->addFields([new TLabel("Rótulo:", null, '14px', null)],[]);
        $row4 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null, '100%'),$cliente_id],[new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor_id]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([new TLabel("Estado da venda:", null, '14px', null, '100%'),$estado_venda_id],[new TLabel("Empresa:", null, '14px', null, '100%'),$system_unit_id]);
        $row5->layout = ['col-sm-6','col-sm-6'];

        $row6 = $this->form->addFields([new TLabel("Variavel duplicidade:", null, '14px', null, '100%'),$variavel_duplicidade],[new TLabel("Total produtos:", null, '14px', null, '100%'),$total_produtos]);
        $row6->layout = ['col-sm-6','col-sm-6'];

        $row7 = $this->form->addFields([new TLabel("Caixa:", null, '14px', null, '100%'),$caixa],[new TLabel("Func caixa:", null, '14px', null, '100%'),$func_caixa]);
        $row7->layout = ['col-sm-6','col-sm-6'];

        $row8 = $this->form->addFields([new TLabel("Total desconto:", null, '14px', null, '100%'),$total_desconto],[new TLabel("Valor do frete:", null, '14px', null, '100%'),$id_venda]);
        $row8->layout = ['col-sm-6','col-sm-6'];

        $row9 = $this->form->addFields([new TLabel("Total pagamentos:", null, '14px', null, '100%'),$total_pagamentos],[]);
        $row9->layout = ['col-sm-6','col-sm-6'];

        $this->form->appendPage("Totalizadores");
        $row10 = $this->form->addContent([new TFormSeparator("Totalizadores", '#333333', '18', '#ff0091')]);
        $row11 = $this->form->addFields([],[new TLabel("    ", null, '14px', null, '100%')]);
        $row11->layout = [' col-sm-3 col-lg-2','col-sm-2'];

        $row12 = $this->form->addFields([new TLabel("Selecione a Loja:", null, '16px', 'B')],[$loja]);
        $row12->layout = [' col-sm-3 col-lg-2',' col-sm-3 col-lg-6'];

        $row13 = $this->form->addFields([],[new TLabel("Vendas do dia Anterior (R$):", null, '14px', null)],[new TLabel("Total do mês (R$):", null, '14px', null)],[new TLabel("Total da busca (R$):", null, '14px', null)]);
        $row13->layout = [' col-sm-3 col-lg-2',' col-sm-3 col-lg-2',' col-sm-6 col-lg-2','col-sm-2'];

        $row14 = $this->form->addFields([new TLabel("Dinheiro fiscal:", null, '14px', null)],[$dinheiroDiarioFiscal],[$dinheiroMensalFiscal],[$dinheiroBuscaFiscal]);
        $row14->layout = [' col-sm-3 col-lg-2',' col-sm-3 col-lg-2',' col-sm-6 col-lg-2','col-sm-2'];

        $row15 = $this->form->addFields([new TLabel("Dinheiro:", null, '14px', null)],[$dinheiroDiario],[$dinheiroMensal],[$dinheiroBusca]);
        $row15->layout = [' col-sm-3 col-lg-2',' col-sm-3 col-lg-2',' col-sm-6 col-lg-2','col-sm-2'];

        $row16 = $this->form->addFields([new TLabel("Crédito:", null, '14px', null)],[$creditoDiario],[$creditoMensal],[$creditoBusca]);
        $row16->layout = [' col-sm-3 col-lg-2',' col-sm-3 col-lg-2',' col-sm-6 col-lg-2','col-sm-2'];

        $row17 = $this->form->addFields([new TLabel("Débito:", null, '14px', null)],[$debitoDiario],[$debitoMensal],[$debitoBusca]);
        $row17->layout = [' col-sm-3 col-lg-2',' col-sm-3 col-lg-2',' col-sm-6 col-lg-2','col-sm-2'];

        $row18 = $this->form->addFields([new TLabel("Pix", null, '14px', null)],[$pixDiario],[$pixMensal],[$pixBusca]);
        $row18->layout = [' col-sm-3 col-lg-2',' col-sm-3 col-lg-2',' col-sm-6 col-lg-2','col-sm-2'];

        $row19 = $this->form->addFields([new TLabel("Total:", null, '15px', 'B')],[$diarioTotal],[$mensalTotal],[$buscaTotal]);
        $row19->layout = [' col-sm-3 col-lg-2',' col-sm-3 col-lg-2',' col-sm-6 col-lg-2','col-sm-2'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onlimparbusca = $this->form->addAction("Limpar formulário", new TAction([$this, 'onLimparBusca'],['static' => 1]), 'fas:eraser #ff0000');
        $this->btn_onlimparbusca = $btn_onlimparbusca;

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['VendaForm', 'onShow']), 'fas:plus #69aa46');
        $this->btn_onshow = $btn_onshow;

        $btn_oncsv = $this->form->addAction("Gerar Csv", new TAction([$this, 'onCsv']), 'fas:file-csv #69aa46');
        $this->btn_oncsv = $btn_oncsv;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_n_venda = new TDataGridColumn('n_venda', "Nº venda", 'left');
        $column_dt_venda_transformed = new TDataGridColumn('dt_venda', "Data", 'left');
        $column_loja = new TDataGridColumn('loja', "Loja", 'left');
        $column_caixa = new TDataGridColumn('caixa', "Caixa", 'left');
        $column_func_caixa = new TDataGridColumn('func_caixa', "Func caixa", 'left');
        $column_forma_pagamento = new TDataGridColumn('forma_pagamento', "Forma pagamento", 'left');
        $column_total_desconto_transformed = new TDataGridColumn('total_desconto', "Total desconto", 'left');
        $column_valor_total_transformed = new TDataGridColumn('valor_total', "Valor total", 'left');
        $column_status = new TDataGridColumn('status', "Status", 'left');

        $column_dt_venda_transformed->setTransformer(function($value, $object, $row)
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_total_desconto_transformed->setTransformer(function($value, $object, $row) 
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

        $column_valor_total_transformed->setTransformer(function($value, $object, $row) 
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

        $column_status->setTransformer(array($this,'formatarStatus'));

        $this->datagrid->addColumn($column_n_venda);
        $this->datagrid->addColumn($column_dt_venda_transformed);
        $this->datagrid->addColumn($column_loja);
        $this->datagrid->addColumn($column_caixa);
        $this->datagrid->addColumn($column_func_caixa);
        $this->datagrid->addColumn($column_forma_pagamento);
        $this->datagrid->addColumn($column_total_desconto_transformed);
        $this->datagrid->addColumn($column_valor_total_transformed);
        $this->datagrid->addColumn($column_status);

        $action_onEdit = new TDataGridAction(array('VendaForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('fas:eye #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onEdit = new TDataGridAction(array('NfceForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Gerir nfce");
        $action_onEdit->setImage('fas:envelope-open-text #000000');
        $action_onEdit->setField(self::$primaryKey);

        $action_onEdit->setParameter('venda', '{id}');
        $this->datagrid->addAction($action_onEdit);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->addFooter($this->pageNavigation);

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';
        $headerActions->style = 'background-color:#fff; justify-content: space-between;';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $panel->getBody()->insert(0, $headerActions);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['VendasList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:table #00b894' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['VendasList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['VendasList', 'onExportXml'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-code #95a5a6' );

        $head_right_actions->add($dropdown_button_exportar);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Financeiro","Vendas"]));
        }
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public static function onChangeLoja($param = null) 
    {
        try 
        {
           TScript::create("$('label:contains(\"    \")').html('Carregando...')");
           $loja = $param['loja'];
           TTransaction::open(self::$database);
           $ontem    = date('d',strtotime("-1 days"));
           $mes      = date('m');
           $ano      = date('Y');
           $este_mes = $ano.'-'.$mes;

           //Get Mensal de vendas--------------
           $vendas = Venda::where("loja","=",$loja)
                          ->where("dt_venda","like",$este_mes.'%')
                          ->load();

           $dinheiroDiario          =0;
           $dinheiroDiarioFiscal    =0;
           $creditoDiario           =0;
           $debitoDiario            =0;
           $pixDiario               =0;
           $dinheiroMensal          =0;
           $dinheiroMensalFiscal    =0;
           $creditoMensal           =0;
           $debitoMensal            =0;
           $diaTotal                =0;
           $mesTotal                =0;
           $pixMensal               =0;

           if($vendas){
           foreach($vendas as $venda){
               if($venda->status != "Duplicata" ){
                   switch($venda->forma_pagamento){
                       case 'Dinheiro':
                           if($venda->fiscal == "T"){
                               $dinheiroMensalFiscal    +=$venda->valor_total;
                               $dinheiroMensal          +=$venda->valor_total;
                           }else{
                               $dinheiroMensal          +=$venda->valor_total;
                           }
                           break;
                       case 'Pagamento misto':
                           $pagamentos = VendaPagamento::where("venda_id","=",$venda->id)->load();
                           if($pagamentos){
                               foreach($pagamentos as $pagamento){
                                   switch($pagamento->metodo_pgto){
                                       case 'Dinheiro':
                                           if($venda->fiscal == "T"){
                                               $dinheiroMensalFiscal        +=$pagamento->valor_pgto;
                                               $dinheiroMensal              +=$pagamento->valor_pgto;
                                           }else{
                                               $dinheiroMensal              +=$pagamento->valor_pgto;
                                           }
                                       break;
                                       case 'Cartão Credito à Vista':
                                       case 'Cartão Credito parcelado': 
                                           $creditoMensal  += $pagamento->valor_pgto;
                                           break;
                                       case 'Cartão Débito':
                                           $debitoMensal   += $pagamento->valor_pgto;
                                           break;
                                   }
                               }
                           }
                           break;
                       case 'Cartão Credito à Vista':
                       case 'Cartão Credito parcelado':   
                           $creditoMensal   +=$venda->valor_total;
                           break;
                       case 'Cartão Débito':
                           $debitoMensal    +=$venda->valor_total;
                           break;
                       case 'pix':
                           $pixMensal       +=$venda->valor_total;
                           break;
                       default:
                           //Chat::sendMessage("venda: $venda->id_interno, não possui forma de pagamento definida");
                   }
                }
               }
               //get Diario de vendas--------------
               $vendas = Venda::where("loja","=",$loja)
                          ->where("dt_venda","like",$este_mes.'-'.$ontem.'%')
                          ->load();

              foreach($vendas as $venda){
                   if($venda->status == "Duplicata"){
                   switch($venda->forma_pagamento){
                   case 'Dinheiro':
                       if($venda->fiscal == "T"){
                           $dinheiroDiarioFiscal  +=$venda->valor_total;
                           $dinheiroDiario  +=$venda->valor_total;
                           //Chat::sendMessage($dinheiroDiarioFiscal);
                       }else{
                           $dinheiroDiario  +=$venda->valor_total;
                       }
                       break;
                   case 'Pagamento misto':
                       $pagamentos = VendaPagamento::where("venda_id","=",$venda->id)->load();
                           if($pagamentos){
                               foreach($pagamentos as $pagamento){
                                   switch($pagamento->metodo_pgto){
                                       case 'Dinheiro':
                                           if($venda->fiscal == "T"){
                                               $dinheiroDiarioFiscal     +=$pagamento->valor_pgto;
                                               $dinheiroDiario           +=$pagamento->valor_pgto;
                                           }else{
                                               $dinheiroDiario           +=$pagamento->valor_pgto;
                                           }
                                       break;
                                       case 'Cartão Credito à Vista':
                                       case 'Cartão Credito parcelado': 
                                           $creditoDiario  += $pagamento->valor_pgto;
                                           break;
                                       case 'Cartão Débito':
                                           $debitoDiario   += $pagamento->valor_pgto;
                                           break;
                                   }
                               }
                           }
                       break;
                   case 'Cartão Credito à Vista':
                   case 'Cartão Credito parcelado':   
                       $creditoDiario   +=$venda->valor_total;
                       break;
                   case 'Cartão Débito':
                       $debitoDiario    +=$venda->valor_total;
                       break;
                   case 'pix':
                       $pixDiario       +=$venda->valor_total;
                       break;
                   default:
                       //Chat::sendMessage("venda: $venda->id_interno, não possui forma de pagamento definida");
                   }
                }
              }

                $object = new stdClass();
                $object->dinheiroDiario         = round($dinheiroDiario, 2, PHP_ROUND_HALF_UP) ;
                $object->dinheiroDiarioFiscal   = round($dinheiroDiarioFiscal, 2, PHP_ROUND_HALF_UP) ;
                $object->creditoDiario          = round($creditoDiario, 2, PHP_ROUND_HALF_UP) ;
                $object->debitoDiario           = round($debitoDiario, 2, PHP_ROUND_HALF_UP) ;
                $object->pixDiario              = round($pixDiario, 2, PHP_ROUND_HALF_UP) ;
                $object->diarioTotal            = round(($debitoDiario+$creditoDiario+$dinheiroDiario+$pixDiario), 2, PHP_ROUND_HALF_UP) ;

                $object->dinheiroMensal         = round($dinheiroMensal, 2, PHP_ROUND_HALF_UP) ;
                $object->dinheiroMensalFiscal   = round($dinheiroMensalFiscal, 2, PHP_ROUND_HALF_UP) ;
                $object->creditoMensal          = round($creditoMensal, 2, PHP_ROUND_HALF_UP) ;
                $object->debitoMensal           = round($debitoMensal, 2, PHP_ROUND_HALF_UP) ;
                $object->pixMensal              = round($pixMensal, 2, PHP_ROUND_HALF_UP) ;
                $object->mensalTotal            = round(($debitoMensal+$creditoMensal+$dinheiroMensal+$pixMensal), 2, PHP_ROUND_HALF_UP) ;

                $object->dinheiroBusca          = '';
                $object->dinheiroBuscaFiscal    = '';
                $object->creditoBusca           = '';
                $object->debitoBusca            = '';
                $object->pixBusca               = '';
                $object->buscaTotal             = '';

                TForm::sendData(self::$formName, $object);
                TScript::create("$('label:contains(\"Carregando...\")').html('   ')");
            }
           TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onExportCsv($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.csv';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $objects = $this->onReload();

                if ($objects)
                {
                    $handler = fopen($output, 'w');
                    TTransaction::open(self::$database);

                    foreach ($objects as $object)
                    {
                        $row = [];
                        foreach ($this->datagrid->getColumns() as $column)
                        {
                            $column_name = $column->getName();

                            if (isset($object->$column_name))
                            {
                                $row[] = is_scalar($object->$column_name) ? $object->$column_name : '';
                            }
                            else if (method_exists($object, 'render'))
                            {
                                $column_name = (strpos($column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $row[] = $object->render($column_name);
                            }
                        }

                        fputcsv($handler, $row);
                    }

                    fclose($handler);
                    TTransaction::close();
                }
                else
                {
                    throw new Exception(_t('No records found'));
                }

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportPdf($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.pdf';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $this->datagrid->prepareForPrinting();
                $this->onReload();

                $html = clone $this->datagrid;
                $contents = file_get_contents('app/resources/styles-print.html') . $html->getContents();

                $dompdf = new \Dompdf\Dompdf;
                $dompdf->loadHtml($contents);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                file_put_contents($output, $dompdf->output());

                $window = TWindow::create('PDF', 0.8, 0.8);
                $object = new TElement('object');
                $object->data  = $output;
                $object->type  = 'application/pdf';
                $object->style = "width: 100%; height:calc(100% - 10px)";

                $window->add($object);
                $window->show();
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onExportXml($param = null) 
    {
        try
        {
            $output = 'app/output/'.uniqid().'.xml';

            if ( (!file_exists($output) && is_writable(dirname($output))) OR is_writable($output))
            {
                $this->limit = 0;
                $objects = $this->onReload();

                if ($objects)
                {
                    TTransaction::open(self::$database);

                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->{'formatOutput'} = true;
                    $dataset = $dom->appendChild( $dom->createElement('dataset') );

                    foreach ($objects as $object)
                    {
                        $row = $dataset->appendChild( $dom->createElement( self::$activeRecord ) );

                        foreach ($this->datagrid->getColumns() as $column)
                        {
                            $column_name = $column->getName();
                            $column_name_raw = str_replace(['(','{','->', '-','>','}',')', ' '], ['','','_','','','','','_'], $column_name);

                            if (isset($object->$column_name))
                            {
                                $value = is_scalar($object->$column_name) ? $object->$column_name : '';
                                $row->appendChild($dom->createElement($column_name_raw, $value)); 
                            }
                            else if (method_exists($object, 'render'))
                            {
                                $column_name = (strpos($column_name, '{') === FALSE) ? ( '{' . $column_name . '}') : $column_name;
                                $value = $object->render($column_name);
                                $row->appendChild($dom->createElement($column_name_raw, $value));
                            }
                        }
                    }

                    $dom->save($output);

                    TTransaction::close();
                }
                else
                {
                    throw new Exception(_t('No records found'));
                }

                TPage::openFile($output);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onLimparBusca($param = null) 
    {
        try 
        {
            $this->form->clear();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onCsv($param = null) 
    {
        try 
        {
            $this->onSearch();

            TTransaction::open(self::$database); // open a transaction
            ini_set('max_execution_time', 0);
            $repository = new TRepository(self::$activeRecord); // creates a repository for Customer
            $criteria = new TCriteria; // creates a criteria
            $cont=0;
            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }
            $records = $repository->load($criteria); // load the objects according to criteria
            if ($records)
            {
                $file = 'tmp/'.uniqid().'.csv';
                $handle = fopen($file, 'w');
                $csvColumns =[];
                $csvColumns = ['id','n_venda','id_interno','cliente_id','status','vendedor_id','estado_venda_id',
                'system_unit_id','dt_venda','obs','valor_total','total_desconto','loja','id_venda',
                'variavel_duplicidade','forma_pagamento','caixa','func_caixa','fiscal','total_produtos',
                'total_pagamentos'];
                fputcsv($handle, $csvColumns, ';');
                $to_remove = array("\n","   ","<br>","\r","/\s/");
            foreach($records as $record){
                $csvColumns = [];
                $csvColumns [] = $record->id;
                $csvColumns [] = $record->n_venda;
                $csvColumns [] = $record->id_interno;
                $csvColumns [] = $record->cliente_id;
                $csvColumns [] = $record->status;
                $csvColumns [] = $record->vendedor_id;
                $csvColumns [] = $record->estado_venda_id;
                $csvColumns [] = $record->system_unit_id;
                $csvColumns [] = $record->dt_venda;
                $csvColumns [] = str_replace($to_remove,"",$record->obs);
                $csvColumns [] = str_replace(".",",",$record->valor_total);
                $csvColumns [] = str_replace(".",",",$record->total_desconto);
                $csvColumns [] = $record->loja;
                $csvColumns [] = $record->id_venda;
                $csvColumns [] = $record->variavel_duplicidade;
                $csvColumns [] = $record->forma_pagamento;
                $csvColumns [] = $record->caixa;
                $csvColumns [] = $record->func_caixa;
                $csvColumns [] = $record->fiscal;
                $csvColumns [] = str_replace(".",",",$record->total_produtos);
                $csvColumns [] = str_replace(".",",",$record->total_pagamentos);

                $cont++;
                fputcsv($handle, $csvColumns, ';');
            }
             fclose($handle);

             TPage::openFile($file);
             new TMessage('info',"foram exportados {$cont} Venda(as) do sistema");
            }
            else
            {
                new TMessage('info', _t('No records found'));       
            }

            TTransaction::close(); // close the transaction

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

       /* $data->dt_venda = str_replace(" ","T",$data->dt_venda);
        $data->dt_venda_final = str_replace(" ","T",$data->dt_venda_final);*/

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->id_interno) AND ( (is_scalar($data->id_interno) AND $data->id_interno !== '') OR (is_array($data->id_interno) AND (!empty($data->id_interno)) )) )
        {

            $filters[] = new TFilter('id_interno', 'like', "%{$data->id_interno}%");// create the filter 
        }

        if (isset($data->n_venda) AND ( (is_scalar($data->n_venda) AND $data->n_venda !== '') OR (is_array($data->n_venda) AND (!empty($data->n_venda)) )) )
        {

            $filters[] = new TFilter('n_venda', 'like', "%{$data->n_venda}%");// create the filter 
        }

        if (isset($data->forma_pagamento) AND ( (is_scalar($data->forma_pagamento) AND $data->forma_pagamento !== '') OR (is_array($data->forma_pagamento) AND (!empty($data->forma_pagamento)) )) )
        {

            $filters[] = new TFilter('forma_pagamento', 'like', "%{$data->forma_pagamento}%");// create the filter 
        }

        if (isset($data->forma_diferente) AND ( (is_scalar($data->forma_diferente) AND $data->forma_diferente !== '') OR (is_array($data->forma_diferente) AND (!empty($data->forma_diferente)) )) )
        {

            $filters[] = new TFilter('forma_pagamento', '!=', $data->forma_diferente);// create the filter 
        }

        if (isset($data->valor_total) AND ( (is_scalar($data->valor_total) AND $data->valor_total !== '') OR (is_array($data->valor_total) AND (!empty($data->valor_total)) )) )
        {

            $filters[] = new TFilter('valor_total', '=', $data->valor_total);// create the filter 
        }

        if (isset($data->dt_venda) AND ( (is_scalar($data->dt_venda) AND $data->dt_venda !== '') OR (is_array($data->dt_venda) AND (!empty($data->dt_venda)) )) )
        {

            $filters[] = new TFilter('dt_venda', '>=', $data->dt_venda);// create the filter 
        }

        if (isset($data->dt_venda_final) AND ( (is_scalar($data->dt_venda_final) AND $data->dt_venda_final !== '') OR (is_array($data->dt_venda_final) AND (!empty($data->dt_venda_final)) )) )
        {

            $filters[] = new TFilter('dt_venda', '<=', $data->dt_venda_final);// create the filter 
        }

        if (isset($data->fiscal) AND ( (is_scalar($data->fiscal) AND $data->fiscal !== '') OR (is_array($data->fiscal) AND (!empty($data->fiscal)) )) )
        {

            $filters[] = new TFilter('fiscal', 'like', "%{$data->fiscal}%");// create the filter 
        }

        if (isset($data->status) AND ( (is_scalar($data->status) AND $data->status !== '') OR (is_array($data->status) AND (!empty($data->status)) )) )
        {

            $filters[] = new TFilter('status', 'like', "%{$data->status}%");// create the filter 
        }

        if (isset($data->Loja_) AND ( (is_scalar($data->Loja_) AND $data->Loja_ !== '') OR (is_array($data->Loja_) AND (!empty($data->Loja_)) )) )
        {

            $filters[] = new TFilter('loja', '=', $data->Loja_);// create the filter 
        }

        if (isset($data->cliente_id) AND ( (is_scalar($data->cliente_id) AND $data->cliente_id !== '') OR (is_array($data->cliente_id) AND (!empty($data->cliente_id)) )) )
        {

            $filters[] = new TFilter('cliente_id', '=', $data->cliente_id);// create the filter 
        }

        if (isset($data->vendedor_id) AND ( (is_scalar($data->vendedor_id) AND $data->vendedor_id !== '') OR (is_array($data->vendedor_id) AND (!empty($data->vendedor_id)) )) )
        {

            $filters[] = new TFilter('vendedor_id', '=', $data->vendedor_id);// create the filter 
        }

        if (isset($data->estado_venda_id) AND ( (is_scalar($data->estado_venda_id) AND $data->estado_venda_id !== '') OR (is_array($data->estado_venda_id) AND (!empty($data->estado_venda_id)) )) )
        {

            $filters[] = new TFilter('estado_venda_id', '=', $data->estado_venda_id);// create the filter 
        }

        if (isset($data->system_unit_id) AND ( (is_scalar($data->system_unit_id) AND $data->system_unit_id !== '') OR (is_array($data->system_unit_id) AND (!empty($data->system_unit_id)) )) )
        {

            $filters[] = new TFilter('system_unit_id', '=', $data->system_unit_id);// create the filter 
        }

        if (isset($data->variavel_duplicidade) AND ( (is_scalar($data->variavel_duplicidade) AND $data->variavel_duplicidade !== '') OR (is_array($data->variavel_duplicidade) AND (!empty($data->variavel_duplicidade)) )) )
        {

            $filters[] = new TFilter('variavel_duplicidade', 'like', "%{$data->variavel_duplicidade}%");// create the filter 
        }

        if (isset($data->total_produtos) AND ( (is_scalar($data->total_produtos) AND $data->total_produtos !== '') OR (is_array($data->total_produtos) AND (!empty($data->total_produtos)) )) )
        {

            $filters[] = new TFilter('total_produtos', '=', $data->total_produtos);// create the filter 
        }

        if (isset($data->caixa) AND ( (is_scalar($data->caixa) AND $data->caixa !== '') OR (is_array($data->caixa) AND (!empty($data->caixa)) )) )
        {

            $filters[] = new TFilter('caixa', '=', $data->caixa);// create the filter 
        }

        if (isset($data->func_caixa) AND ( (is_scalar($data->func_caixa) AND $data->func_caixa !== '') OR (is_array($data->func_caixa) AND (!empty($data->func_caixa)) )) )
        {

            $filters[] = new TFilter('func_caixa', 'like', "%{$data->func_caixa}%");// create the filter 
        }

        if (isset($data->total_desconto) AND ( (is_scalar($data->total_desconto) AND $data->total_desconto !== '') OR (is_array($data->total_desconto) AND (!empty($data->total_desconto)) )) )
        {

            $filters[] = new TFilter('total_desconto', '=', $data->total_desconto);// create the filter 
        }

        if (isset($data->id_venda) AND ( (is_scalar($data->id_venda) AND $data->id_venda !== '') OR (is_array($data->id_venda) AND (!empty($data->id_venda)) )) )
        {

            $filters[] = new TFilter('id_venda', '=', $data->id_venda);// create the filter 
        }

        if (isset($data->total_pagamentos) AND ( (is_scalar($data->total_pagamentos) AND $data->total_pagamentos !== '') OR (is_array($data->total_pagamentos) AND (!empty($data->total_pagamentos)) )) )
        {

            $filters[] = new TFilter('total_pagamentos', '=', $data->total_pagamentos);// create the filter 
        }

        if (isset($data->loja) AND ( (is_scalar($data->loja) AND $data->loja !== '') OR (is_array($data->loja) AND (!empty($data->loja)) )) )
        {

            $filters[] = new TFilter('loja', '=', $data->loja);// create the filter 
        }

        echo $data->desconto;
        if ( $data->desconto == 1)
        {
            $filters[] = new TFilter('total_desconto', 'IS NOT', null);// create the filter 
            $filters[] = new TFilter('total_desconto', '!=', 0);// create the filter 
        }
        TTransaction::open(self::$database);
        $dinheiroBusca          =0;
        $dinheiroBuscaFiscal    =0;
        $creditoBusca           =0;
        $debitoBusca            =0;
        $pixBusca               =0;
        $buscaTotal             =0;
        //PESQUISA o repositorio
        $repository = new TRepository(self::$activeRecord);
        $criteria2  = new TCriteria;
        $carregar   = false;
        foreach ($filters as $filter) 
        {
            $criteria2->add($filter); 
            $carregar = true;
        }
        if($carregar){
        $objects = $repository->load($criteria2, FALSE);

        foreach($objects as $object){
             if($object->status != "Duplicata"){
                switch($object->forma_pagamento){
                   case 'Dinheiro':
                       if($object->fiscal == "T"){
                           $dinheiroBuscaFiscal     +=$object->valor_total;
                           $dinheiroBusca           +=$object->valor_total;
                       }else{
                           $dinheiroBusca    +=$object->valor_total;
                       }
                       break;
                   case 'Pagamento misto':
                       $pagamentos = VendaPagamento::where("venda_id","=",$object->id)->load();
                       if($pagamentos){
                           foreach($pagamentos as $pagamento){
                               switch($pagamento->metodo_pgto){
                                   case 'Dinheiro':
                                       if($object->fiscal == "T"){
                                           $dinheiroBuscaFiscal     +=$pagamento->valor_pgto;
                                           $dinheiroBusca           +=$pagamento->valor_pgto;
                                       }else{
                                           $dinheiroBusca           +=$pagamento->valor_pgto;
                                       }
                                       break;
                                   case 'Cartão Credito à Vista':
                                   case 'Cartão Credito parcelado': 
                                       $creditoBusca  += $pagamento->valor_pgto;
                                       break;
                                   case 'Cartão Débito':
                                       $debitoBusca   += $pagamento->valor_pgto;
                                       break;
                               }
                           }
                       }
                       break;
                   case 'Cartão Credito à Vista':
                   case 'Cartão Credito parcelado':   
                       $creditoBusca   += $object->valor_total;
                       break;
                   case 'Cartão Débito':
                       $debitoBusca    += $object->valor_total;
                       break;
                   case 'pix':
                       $pixBusca       += $object->valor_total;
                       break;
                   default:
                       //Chat::sendMessage("venda: $object->id_interno, não possui forma de pagamento definida");
                    } 
                }
            }
            //envia par a tela
            $data->dinheiroBusca       = round($dinheiroBusca, 2, PHP_ROUND_HALF_UP) ;
            $data->dinheiroBuscaFiscal = round($dinheiroBuscaFiscal,2,PHP_ROUND_HALF_UP) ;
            $data->creditoBusca        = round($creditoBusca,2,PHP_ROUND_HALF_UP) ;
            $data->debitoBusca         = round($debitoBusca,2,PHP_ROUND_HALF_UP) ;
            $data->pixBusca            = round($pixBusca,2,PHP_ROUND_HALF_UP) ;
            $data->buscaTotal          = round(($dinheiroBusca+$creditoBusca+$debitoBusca+$pixBusca),2,PHP_ROUND_HALF_UP) ;

            //zerar o resto do form
            $data->dinheiroMensalFiscal = "";
            $data->dinheiroMensal       = "";
            $data->dinheiroDiarioFiscal = "";
            $data->dinheiroDiario       = "";
            $data->debitoMensal         = "";
            $data->debitoDiario         = "";
            $data->creditoMensal        = "";
            $data->creditoDiario        = "";
            $data->pixMensal            = "";
            $data->pixDiario            = "";
            $data->diarioTotal          = "";
            $data->mensalTotal          = "";

            $this->form->setData($data);
        }
        TTransaction::close();

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'vendas_base'
            TTransaction::open(self::$database);

            // creates a repository for Venda
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $this->limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                 TTransaction::open(self::$database);
                //obtem a loja
                TTransaction::open('base_banco');
                $loja = new Loja ($object->loja);
                TTransaction::close();
                $object->loja = $loja->nome_fantasia;
                //ajusta o status
                switch($object->status){//permite mascarar outros status
                    case "Autorizada":
                        $object->status = "Autorizada";//"Nfc-e emitida";
                        break;
                    case "completed":
                        $object->status = "Finalizada";
                        break;
                    default:
                        break;
                }
                TTransaction::close();
                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($this->limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;

            return $objects;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

public function formatarStatus($stock, $object, $row)
 {
    switch($stock){
        case "Autorizada":
            return "<b><font color='green'>$stock</font></b>";
            break;
        case "Finalizada":
            return "<font color='green'>$stock</font>";
            break;
        case "Negada":
            return "<b><font color='red'>$stock</font></b>";
            break;
        case "Cancelada":
            return "<font color='red'>$stock</font>";
            break;
        case "Duplicata":
            return "<font color='orange'>$stock</font>";
            break;
        case "Erro":
            return "<b><font color='red'>$stock</font></b>";
            break;
        case "SolicitandoAutorizacao":
            return "<b><font color='red'>$stock</font></b>";
            break;
        default:
            return $stock;
            break;
    }
 }

}

