<?php

class VendaChart extends TWindow
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'vendas_base';
    private static $activeRecord = 'Venda';
    private static $primaryKey = 'id';
    private static $formName = 'form_VendaChart';
    private $showMethods = ['onReload', 'onSearch'];
    private $limit = 20;

    use BuilderSeekWindowTrait;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Lista das ultimas vendas");
        parent::setProperty('class', 'window_modal');

        $param['_seek_window_id'] = $this->getId();
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        $this->limit = 10;

        // define the form title
        $this->form->setFormTitle("VendasChart");

        $loja = new TDBCombo('loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $forma_pagamento = new TCombo('forma_pagamento');
        $status = new TCombo('status');
        $dt_venda = new TDateTime('dt_venda');
        $dt_venda_ate = new TDateTime('dt_venda_ate');
        $fiscal = new TRadioGroup('fiscal');
        $indicadorDinheiroFiscal = new BIndicator('indicadorDinheiroFiscal');
        $indicadorDinheiro = new BIndicator('indicadorDinheiro');
        $indicadorPix = new BIndicator('indicadorPix');
        $indicadorCredito = new BIndicator('indicadorCredito');
        $indicadorDebito = new BIndicator('indicadorDebito');
        $indicadorQuantidade = new BIndicator('indicadorQuantidade');
        $indicadorFiscal = new BIndicator('indicadorFiscal');
        $indicadorTotal = new BIndicator('indicadorTotal');
        $indicadorCusto = new BIndicator('indicadorCusto');
        $hiDinheiroDescN = new THidden('hiDinheiroDescN');
        $hiDinheiroN = new THidden('hiDinheiroN');
        $hiDinheiroFiscal = new THidden('hiDinheiroFiscal');
        $hiCreditoDesc = new THidden('hiCreditoDesc');
        $hiCredito = new THidden('hiCredito');
        $hiDebito = new THidden('hiDebito');
        $hiFiscal = new THidden('hiFiscal');
        $hiDebitoDesc = new THidden('hiDebitoDesc');
        $hiTotal = new THidden('hiTotal');
        $hiFiscalDesc = new THidden('hiFiscalDesc');
        $hiTotalDesc = new THidden('hiTotalDesc');
        $hiPix = new THidden('hiPix');
        $hiPixDesc = new THidden('hiPixDesc');
        $hiDinheiroFiscalDesc = new THidden('hiDinheiroFiscalDesc');
        $hiCusto = new THidden('hiCusto');

        $loja->setChangeAction(new TAction([$this,'onChangeLoja']));

        $dt_venda->setExitAction(new TAction([$this,'onChangeDataIni']));
        $dt_venda_ate->setExitAction(new TAction([$this,'onChangedataFim']));

        $fiscal->setLayout('horizontal');
        $dt_venda->setMask('dd/mm/yyyy hh:ii');
        $dt_venda_ate->setMask('dd/mm/yyyy hh:ii');

        $dt_venda->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_venda_ate->setDatabaseMask('yyyy-mm-dd hh:ii');

        $loja->setValue('TODOS');
        $dt_venda->setValue(date("01/m/Y 00:00"));
        $dt_venda_ate->setValue(date("d-m-Y 23:59"));

        $fiscal->addItems(['T'=>'sim','F'=>'não']);
        $forma_pagamento->addItems(['Dinheiro'=>' Dinheiro','Pagamento misto'=>' Pagamento misto','Cartão Credito à Vista'=>' Cartão Credito à Vista','Cartão Débito'=>' Cartão Débito','Cartão Credito parcelado'=>' Cartão Credito parcelado','pix'=>' Pix']);
        $status->addItems(['Negada'=>'Negada','Autorizada'=>' Autorizada','completed'=>' finalizada','Erro'=>'Erro','Duplicata'=>'Duplicata','Cancelada'=>'Cancelada','SolicitandoAutorizacao'=>'SolicitandoAutorizacao','AutorizadaAguardandoGeracaoPDF'=>'AutorizadaAguardandoGeracaoPDF']);

        $hiPix->setSize(200);
        $loja->setSize('100%');
        $hiTotal->setSize(200);
        $hiCusto->setSize(200);
        $hiFiscal->setSize(200);
        $hiDebito->setSize(200);
        $hiPixDesc->setSize(200);
        $status->setSize('100%');
        $fiscal->setSize('100%');
        $hiCredito->setSize(200);
        $hiTotalDesc->setSize(200);
        $dt_venda->setSize('100%');
        $hiDinheiroN->setSize(200);
        $hiDebitoDesc->setSize(200);
        $hiFiscalDesc->setSize(200);
        $hiCreditoDesc->setSize(200);
        $hiDinheiroDescN->setSize(200);
        $dt_venda_ate->setSize('100%');
        $hiDinheiroFiscal->setSize(200);
        $forma_pagamento->setSize('100%');
        $hiDinheiroFiscalDesc->setSize(200);

        $indicadorDinheiroFiscal->setDatabase('base_banco');
        $indicadorDinheiroFiscal->setFieldValue("venda_alt.valor_total");
        $indicadorDinheiroFiscal->setModel('VendaAlt');
        $indicadorDinheiroFiscal->setTotal('count');
        $indicadorDinheiroFiscal->setTarget(100000, '#ffffff', function($percentage, $target){
            return "{$percentage}% de R$ 100.000,00";
        });
        $indicadorDinheiroFiscal->setColors('#16A085', '#ffffff', '#1ABC9C', '#ffffff');
        $indicadorDinheiroFiscal->setTitle("Dinheiro Fiscal          ", '#ffffff', '20', '');
        $indicadorDinheiroFiscal->setIcon(new TImage('fas:money-bill-wave #ffffff'));
        $indicadorDinheiroFiscal->setValueSize("20");
        $indicadorDinheiroFiscal->setValueColor("#ffffff", 'B');
        $indicadorDinheiroFiscal->setSize('100%', 100);
        $indicadorDinheiroFiscal->setLayout('horizontal', 'left');

        $indicadorDinheiro->setDatabase('base_banco');
        $indicadorDinheiro->setFieldValue("venda_alt.valor_total");
        $indicadorDinheiro->setModel('VendaAlt');
        $indicadorDinheiro->setTotal('count');
        $indicadorDinheiro->setTarget(100000, '#ffffff', function($percentage, $target){
            return "{$percentage}% de R$ 100.000,00";
        });
        $indicadorDinheiro->setColors('#27ae60', '#ffffff', '#2ecc71', '#ffffff');
        $indicadorDinheiro->setTitle("dinheiro", '#ffffff', '20', '');
        $indicadorDinheiro->setIcon(new TImage('fas:money-bill #ffffff'));
        $indicadorDinheiro->setValueSize("20");
        $indicadorDinheiro->setValueColor("#ffffff", 'B');
        $indicadorDinheiro->setSize('100%', 95);
        $indicadorDinheiro->setLayout('horizontal', 'left');

        $indicadorPix->setDatabase('base_banco');
        $indicadorPix->setFieldValue("venda_alt.valor_total");
        $indicadorPix->setModel('VendaAlt');
        $indicadorPix->setTotal('sum');
        $indicadorPix->setTarget(100000, '#ffffff', function($percentage, $target){
            return "{$percentage}% de R$ 100.000,00";
        });
        $indicadorPix->setColors('#9B59B6', '#ffffff', '#8E44AD', '#ffffff');
        $indicadorPix->setTitle("Pix", '#ffffff', '20', '');
        $indicadorPix->setIcon(new TImage('fas:piggy-bank #ffffff'));
        $indicadorPix->setValueSize("20");
        $indicadorPix->setValueColor("#ffffff", 'B');
        $indicadorPix->setSize('100%', 95);
        $indicadorPix->setLayout('horizontal', 'left');

        $indicadorCredito->setDatabase('base_banco');
        $indicadorCredito->setFieldValue("venda_alt.valor_total");
        $indicadorCredito->setModel('VendaAlt');
        $indicadorCredito->setTotal('count');
        $indicadorCredito->setTarget(100000, '#ffffff', function($percentage, $target){
            return "{$percentage}% de R$ 100.000,00";
        });
        $indicadorCredito->setColors('#3498DB', '#ffffff', '#2980B9', '#ffffff');
        $indicadorCredito->setTitle("Crédito", '#ffffff', '20', '');
        $indicadorCredito->setIcon(new TImage('fas:credit-card #ffffff'));
        $indicadorCredito->setValueSize("20");
        $indicadorCredito->setValueColor("#ffffff", 'B');
        $indicadorCredito->setSize('100%', 95);
        $indicadorCredito->setLayout('horizontal', 'left');

        $indicadorDebito->setDatabase('base_banco');
        $indicadorDebito->setFieldValue("venda_alt.valor_total");
        $indicadorDebito->setModel('VendaAlt');
        $indicadorDebito->setTotal('count');
        $indicadorDebito->setTarget(100000, '#ffffff', function($percentage, $target){
            return "{$percentage}% de R$ 100.000,00";
        });
        $indicadorDebito->setColors('#2E86DE', '#ffffff', '#54A0FF', '#ffffff');
        $indicadorDebito->setTitle("Débito", '#ffffff', '20', '');
        $indicadorDebito->setIcon(new TImage('far:credit-card #ffffff'));
        $indicadorDebito->setValueSize("20");
        $indicadorDebito->setValueColor("#ffffff", 'B');
        $indicadorDebito->setSize('100%', 95);
        $indicadorDebito->setLayout('horizontal', 'left');

        $indicadorQuantidade->setDatabase('base_banco');
        $indicadorQuantidade->setFieldValue("venda_alt.valor_total");
        $indicadorQuantidade->setModel('VendaAlt');
        $indicadorQuantidade->setTotal('sum');
        $indicadorQuantidade->setTarget(100000, '#ffffff', function($percentage, $target){
            return "{$percentage}% de R$ 100.000,00";
        });
        $indicadorQuantidade->setColors('#95A5A6', '#ffffff', '#7F8C8D', '#ffffff');
        $indicadorQuantidade->setTitle("quantidade", '#ffffff', '20', '');
        $indicadorQuantidade->setIcon(new TImage('fas:bars #ffffff'));
        $indicadorQuantidade->setValueSize("20");
        $indicadorQuantidade->setValueColor("#ffffff", 'B');
        $indicadorQuantidade->setSize('100%', 95);
        $indicadorQuantidade->setLayout('horizontal', 'left');

        $indicadorFiscal->setDatabase('base_banco');
        $indicadorFiscal->setFieldValue("venda_alt.valor_total");
        $indicadorFiscal->setModel('VendaAlt');
        $indicadorFiscal->setTotal('sum');
        $indicadorFiscal->setTarget(100000, '#ffffff', function($percentage, $target){
            return "{$percentage}% de R$ 100.000,00";
        });
        $indicadorFiscal->setColors('#E67E22', '#ffffff', '#D35400', '#ffffff');
        $indicadorFiscal->setTitle("Fiscal", '#ffffff', '20', '');
        $indicadorFiscal->setIcon(new TImage('fas:balance-scale #ffffff'));
        $indicadorFiscal->setValueSize("20");
        $indicadorFiscal->setValueColor("#ffffff", 'B');
        $indicadorFiscal->setSize('100%', 95);
        $indicadorFiscal->setLayout('horizontal', 'left');

        $indicadorTotal->setDatabase('base_banco');
        $indicadorTotal->setFieldValue("venda_alt.total_pagamentos");
        $indicadorTotal->setModel('VendaAlt');
        $indicadorTotal->setTotal('sum');
        $indicadorTotal->setTarget(100000, '#ffffff', function($percentage, $target){
            return "{$percentage}% de R$ 100.000,00";
        });
        $indicadorTotal->setColors('#E74C3C', '#ffffff', '#C0392B', '#ffffff');
        $indicadorTotal->setTitle("Total", '#ffffff', '20', '');
        $indicadorTotal->setIcon(new TImage('fas:equals #ffffff'));
        $indicadorTotal->setValueSize("20");
        $indicadorTotal->setValueColor("#ffffff", 'B');
        $indicadorTotal->setSize('100%', 95);
        $indicadorTotal->setLayout('horizontal', 'left');

        $indicadorCusto->setDatabase('base_banco');
        $indicadorCusto->setFieldValue("conta.tipo_conta_id");
        $indicadorCusto->setModel('Conta');
        $indicadorCusto->setTotal('sum');
        $indicadorCusto->setTarget(100000, '#ffffff', function($percentage, $target){
            return "{$percentage}% de R$ 100.000,00";
        });
        $indicadorCusto->setColors('#E74C3C', '#ffffff', '#C0392B', '#ffffff');
        $indicadorCusto->setTitle("Custo", '#ffffff', '20', '');
        $indicadorCusto->setIcon(new TImage('fas:sort-amount-down #ffffff'));
        $indicadorCusto->setValueSize("20");
        $indicadorCusto->setValueColor("#ffffff", 'B');
        $indicadorCusto->setSize('100%', 95);
        $indicadorCusto->setLayout('horizontal', 'left');

        $indicadorDinheiroFiscal->id    = 'indicadorDinheiroFiscal';ons
        $indicadorDinheiro->id          = 'indicadorDinheiro';
        $indicadorCredito->id           = 'indicadorCredito';
        $indicadorDebito->id            = 'indicadorDebito';
        $indicadorPix->id               = 'indicadorPix';
        $indicadorFiscal->id            = 'indicadorFiscal';
        $indicadorTotal->id             = 'indicadorTotal';
        $indicadorQuantidade->id        = 'indicadorQuantidade';

        //ADD GRAFICO AQUI
        /*
        $html = new THtmlRenderer('app/resources/google_pie_chart.html');
        $data = array();
        $data[] = [ 'Pessoa', 'Value' ];
        $data[] = [ 'Pedro',   40 ];
        $data[] = [ 'Maria',   30 ];
        $data[] = [ 'João',    30 ];

        $html->enableSection('main', array('data'   => json_encode($data),
                                           'width'  => '100%',
                                           'height'  => '300px',
                                           'title'  => 'Custos neste periodo',
                                           'ytitle' => 'Accesses', 
                                           'xtitle' => 'Day',
                                           'uniqid' => uniqid()));
*/  

        $row1 = $this->form->addFields([new TFormSeparator("<b>Filtros de Busca</b>", '#333', '18', '#FF0091')]);
        $row1->layout = [' col-sm-6 col-lg-12'];

        $row2 = $this->form->addFields([new TLabel("Loja:", null, '14px', null, '100%'),$loja],[new TLabel("Forma pagamento:", null, '14px', null, '100%'),$forma_pagamento]);
        $row2->layout = [' col-sm-6 col-lg-10','col-sm-2'];

        $row3 = $this->form->addFields([new TLabel("Status:", null, '14px', null, '100%'),$status],[new TLabel("Data da venda (de):", null, '14px', null, '100%'),$dt_venda],[new TLabel("Data de venda (até):", null, '14px', null),$dt_venda_ate],[new TLabel("Fiscal:", null, '14px', null, '100%'),$fiscal]);
        $row3->layout = [' col-sm-2 col-lg-3',' col-sm-2 col-lg-3',' col-sm-2 col-lg-3',' col-sm-2 col-lg-3'];

        $row4 = $this->form->addFields([new TFormSeparator("<b>Indicadores de vendas</b>", '#333333', '18', '#FF0091')]);
        $row4->layout = [' col-sm-3 col-lg-12'];

        $row5 = $this->form->addFields([$indicadorDinheiroFiscal],[$indicadorDinheiro],[$indicadorPix]);
        $row5->layout = [' col-sm-3 col-lg-4',' col-sm-2 col-lg-4',' col-sm-2 col-lg-4'];

        $row6 = $this->form->addFields([$indicadorCredito],[$indicadorDebito],[$indicadorQuantidade]);
        $row6->layout = [' col-sm-3 col-lg-4',' col-sm-3 col-lg-4',' col-sm-6 col-lg-4'];

        $row7 = $this->form->addFields([$indicadorFiscal],[$indicadorTotal],[$indicadorCusto]);
        $row7->layout = [' col-sm-3 col-lg-4',' col-sm-3 col-lg-4',' col-sm-6 col-lg-4'];

        $row8 = $this->form->addFields([$hiDinheiroDescN,$hiDinheiroN,$hiDinheiroFiscal,$hiCreditoDesc,$hiCredito,$hiDebito,$hiFiscal,$hiDebitoDesc,$hiTotal,$hiFiscalDesc,$hiTotalDesc,$hiPix,$hiPixDesc,$hiDinheiroFiscalDesc,$hiCusto],[]);
        $row8->layout = [' col-sm-3 col-lg-6',' col-sm-2 col-lg-6'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Atualizar", new TAction([$this, 'onSearch']), 'fas:redo #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 

        $this->setSeekParameters($btn_onsearch->getAction(), $param);
        $btn_onlimpar = $this->form->addAction("Limpar Busca", new TAction([$this, 'onLimpar']), 'fas:eraser #E91E63');

        $this->setSeekParameters($btn_onlimpar->getAction(), $param);
        $btn_onvendas = $this->form->addAction("Visualizar Vendas", new TAction([$this, 'onVendas']), 'far:eye #00BCD4');

        $this->setSeekParameters($btn_onvendas->getAction(), $param);

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = $this->getSeekFiltersCriteria($param);

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_n_venda = new TDataGridColumn('n_venda', "N venda", 'left');
        $column_loja = new TDataGridColumn('loja', "Loja", 'left');
        $column_dt_venda_transformed = new TDataGridColumn('dt_venda', "Data da venda", 'left');
        $column_valor_total_transformed = new TDataGridColumn('valor_total', "Valor total", 'left');
        $column_total_desconto_transformed = new TDataGridColumn('total_desconto', "Total desconto", 'left');
        $column_forma_pagamento = new TDataGridColumn('forma_pagamento', "Forma pagamento", 'left');
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

        $this->datagrid->addColumn($column_n_venda);
        $this->datagrid->addColumn($column_loja);
        $this->datagrid->addColumn($column_dt_venda_transformed);
        $this->datagrid->addColumn($column_valor_total_transformed);
        $this->datagrid->addColumn($column_total_desconto_transformed);
        $this->datagrid->addColumn($column_forma_pagamento);
        $this->datagrid->addColumn($column_status);

        $action_onShow = new TDataGridAction(array('EstadoVendaForm', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Selecionar");
        $action_onShow->setImage('far:hand-pointer #44bd32');
        $action_onShow->setField(self::$primaryKey);
        $this->setSeekParameters($action_onShow, $param);

        $action_onShow->setParameter('key', '{id}');
        $this->datagrid->addAction($action_onShow);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $navigationAction = new TAction(array($this, 'onReload'));
        $this->setSeekParameters($navigationAction, $param);
        $this->pageNavigation->setAction($navigationAction);
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->datagrid);
        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        parent::add($this->form);
        parent::add($panel);

    }

    public static function onChangeDataIni($param = null) 
    {
        try 
        {
            self::onSearch($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangedataFim($param = null) 
    {
        try 
        {
            self::onSearch($param);

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
            var_dump($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onLimpar($param = null) 
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
    public function onVendas($param = null) 
    {
        try 
        {
            TApplication::loadPage('VendasList', 'onShow');

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
        // get the search form data
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->loja) AND ( (is_scalar($data->loja) AND $data->loja !== '') OR (is_array($data->loja) AND (!empty($data->loja)) )) )
        {

            $filters[] = new TFilter('loja', '=', $data->loja);// create the filter 
        }

        if (isset($data->forma_pagamento) AND ( (is_scalar($data->forma_pagamento) AND $data->forma_pagamento !== '') OR (is_array($data->forma_pagamento) AND (!empty($data->forma_pagamento)) )) )
        {

            $filters[] = new TFilter('forma_pagamento', 'like', "%{$data->forma_pagamento}%");// create the filter 
        }

        if (isset($data->status) AND ( (is_scalar($data->status) AND $data->status !== '') OR (is_array($data->status) AND (!empty($data->status)) )) )
        {

            $filters[] = new TFilter('status', 'like', "%{$data->status}%");// create the filter 
        }

        if (isset($data->dt_venda) AND ( (is_scalar($data->dt_venda) AND $data->dt_venda !== '') OR (is_array($data->dt_venda) AND (!empty($data->dt_venda)) )) )
        {

            $filters[] = new TFilter('dt_venda', '>=', $data->dt_venda);// create the filter 
        }

        if (isset($data->dt_venda_ate) AND ( (is_scalar($data->dt_venda_ate) AND $data->dt_venda_ate !== '') OR (is_array($data->dt_venda_ate) AND (!empty($data->dt_venda_ate)) )) )
        {

            $filters[] = new TFilter('dt_venda', '<=', $data->dt_venda_ate);// create the filter 
        }

        if (isset($data->fiscal) AND ( (is_scalar($data->fiscal) AND $data->fiscal !== '') OR (is_array($data->fiscal) AND (!empty($data->fiscal)) )) )
        {

            $filters[] = new TFilter('fiscal', 'like', "%{$data->fiscal}%");// create the filter 
        }

         TTransaction::open(self::$database);
        $dinheiroBusca          =0;
        $dinheiroBuscaFiscal    =0;
        $creditoBusca           =0;
        $debitoBusca            =0;
        $pixBusca               =0;
        $buscaTotal             =0;
        $qtd                    =0;

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
                  $qtd++;
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
                                   case 'pix':
                                        $pixBusca      +=$object->valor_total;
                                        break;
                               }
                           }
                       }
                       break;
                   case 'Cartão Credito à Vista':
                   case 'Cartão Credito parcelado':   
                       $creditoBusca   +=$object->valor_total;
                       break;
                   case 'Cartão Débito':
                       $debitoBusca    +=$object->valor_total;
                       break;
                   case 'pix':
                       $pixBusca      +=$object->valor_total;
                       break;
                   default:
                       //Chat::sendMessage("venda: $object->id_interno, não possui forma de pagamento definida");
                    } 
                }
            }
            //envia par a tela
            $indicadores                         = array();

            $total                               = round(($dinheiroBusca+$creditoBusca+$debitoBusca),2,PHP_ROUND_HALF_UP) ;
            $dinheiroBuscaFiscal                 = round($dinheiroBuscaFiscal,2,PHP_ROUND_HALF_UP) ;
            $dinheiroBusca                       = round($dinheiroBusca, 2, PHP_ROUND_HALF_UP) ;
            $dinheiroBuscaFiscal                 = round($dinheiroBuscaFiscal,2,PHP_ROUND_HALF_UP) ;
            $creditoBusca                        = round($creditoBusca,2,PHP_ROUND_HALF_UP) ;
            $debitoBusca                         = round($debitoBusca,2,PHP_ROUND_HALF_UP) ;
            $pixBusca                            = round($pixBusca,2,PHP_ROUND_HALF_UP) ;

            $indicadores['dinheiroFiscal']       = $dinheiroBuscaFiscal;
            $indicadores['dinheiro']             = $dinheiroBusca;
            $indicadores['credito']              = $creditoBusca;
            $indicadores['debito']               = $debitoBusca;
            $indicadores['pix']                  = $pixBusca;
            $indicadores['total']                = $total;
            $indicadores['qtd']                  = $qtd;

            self::alterarIndicadores($indicadores);

            $this->form->setData($data);
        }
        TTransaction::close();

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        if (isset($param['static']) && ($param['static'] == '1') )
        {
            $class = get_class($this);
            AdiantiCoreApplication::loadPage($class, 'onReload', ['offset' => 0, 'first_page' => 1]);
        }
        else
        {
            $this->onReload(['offset' => 0, 'first_page' => 1]);
        }
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
                    // add the object inside the datagrid

                    $this->datagrid->addItem($object);

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

public function alterarIndicadores($indicadores){

    //trata as variaveis dos Indicadores
    $indicadorDinheiroFiscal        = isset($indicadores['dinheiroFiscal'])        ? $indicadores['dinheiroFiscal']       : 0 ;
    $indicadorDinheiro              = isset($indicadores['dinheiro'])              ? $indicadores['dinheiro']             : 0 ;
    $indicadorCredito               = isset($indicadores['credito'])               ? $indicadores['credito']              : 0 ;
    $indicadorDebito                = isset($indicadores['debito'])                ? $indicadores['debito']               : 0 ;
    $indicadorPix                   = isset($indicadores['pix'])                   ? $indicadores['pix']                  : 0 ;
    $indicadorFiscal                = isset($indicadores['fiscal'])                ? $indicadores['fiscal']               : ($indicadorDinheiroFiscal+$indicadorCredito+$indicadorDebito+$indicadorPix) ;
    $totalLojas                     = isset($indicadores['totalLojas'])            ? $indicadores['totalLojas']           : 0 ;
    $indicadorQuantidade                            = isset($indicadores['qtd'])                   ? $indicadores['qtd']                  : 0 ;
    $total                          = isset($indicadores['total'])                 ? $indicadores['total']                : ($indicadorDinheiro+$indicadorCredito+$indicadorDebito+$indicadorPix) ;

    //var_dump($indicadores);
    echo'</pre>';
    $object = new stdClass();//envia para o form para alguma coisa

    //INDICADOR
    //enviar para o THidden
    $object->hiDinheiroFiscal       = $indicadorDinheiroFiscal;
    $object->hiDinheiro             = $indicadorDinheiro;
    $object->hiCredito              = $indicadorCredito;
    $object->hiDebito               = $indicadorDebito;
    $object->hiPix                  = $indicadorPix;
    $object->hiFiscal               = $indicadorFiscal;
    $object->hiTotal                = $totalLojas;
    TForm::sendData(self::$formName, $object);
    //---

    //INDICADOR DINHEIRO FISCAL
    $script                         = new TElement('script'); 
    $script->type                   = 'text/javascript';
    $porcentagem                    = round(($indicadorDinheiroFiscal/$indicadorDinheiro),PHP_ROUND_HALF_UP)*100 ;
    $script->add(
    //-- codigo javascript --
    "
    var value = $indicadorDinheiroFiscal;
    var dinheiro = $indicadorDinheiro;
    document.querySelector('#indicadorDinheiroFiscal > div > div > span.info-box-number').textContent = value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    document.querySelector('#indicadorDinheiroFiscal > div > div > div > div').style.width = '$porcentagem%'
    document.querySelector('#indicadorDinheiroFiscal > div > div > span.progress-description').textContent = $porcentagem+'% de '+dinheiro.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    "
    );//-- fim codigo --
    parent::add($script); 

     //INDICADOR DINHEIRO 
     $script                         = new TElement('script'); 
     $script->type                   = 'text/javascript';
     $porcentagem                    = round(($indicadorDinheiro/$total),PHP_ROUND_HALF_UP)*100 ;
     $script->add(
     //-- codigo javascript --
     "
     var value = $indicadorDinheiro;
     var total = $total;
     document.querySelector('#indicadorDinheiro > div > div > span.info-box-number').textContent = value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
     document.querySelector('#indicadorDinheiro > div > div > div > div').style.width = '$porcentagem%'
     document.querySelector('#indicadorDinheiro > div > div > span.progress-description').textContent = $porcentagem+'% de '+total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
     "
     );//-- fim codigo --
     parent::add($script);  

     //INDICADOR CREDITO 
     $script                         = new TElement('script'); 
     $script->type                   = 'text/javascript';
     $porcentagem                    = round(($indicadorCredito/$total),PHP_ROUND_HALF_UP)*100 ;
     $script->add(
     //-- codigo javascript --
     "
     var value = $indicadorCredito;
     var total = $total;
     document.querySelector('#indicadorCredito > div > div > span.info-box-number').textContent = value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
     document.querySelector('#indicadorCredito > div > div > div > div').style.width = '$porcentagem%'
     document.querySelector('#indicadorCredito > div > div > span.progress-description').textContent = $porcentagem+'% de '+total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
     "
     );//-- fim codigo --
     parent::add($script);    

     //INDICADOR DEBITO 
     $script                         = new TElement('script'); 
     $script->type                   = 'text/javascript';
     $porcentagem                    = round(($indicadorDebito/$total),PHP_ROUND_HALF_UP)*100;
     $script->add(
     //-- codigo javascript --
     "
     var value = $indicadorDebito;
     var total = $total;
     document.querySelector('#indicadorDebito > div > div > span.info-box-number').textContent = value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
     document.querySelector('#indicadorDebito > div > div > div > div').style.width = '$porcentagem%'
     document.querySelector('#indicadorDebito > div > div > span.progress-description').textContent = $porcentagem+'% de '+total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
     "
     );//-- fim codigo --
     parent::add($script); 

     //INDICADOR QUANTIDADE 
    $script                         = new TElement('script'); 
    $script->type                   = 'text/javascript';
    $porcentagem                    = round(($total/$indicadorQuantidade),PHP_ROUND_HALF_UP);
    $script->add(
    //-- codigo javascript --
    "
    var value = $indicadorQuantidade;
    var total = $porcentagem;
    document.querySelector('#indicadorQuantidade > div > div > span.info-box-number').textContent = value+' vendas';
    document.querySelector('#indicadorQuantidade > div > div > span.progress-description').textContent = 'Ticket Médio de '+total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    document.querySelector('#indicadorQuantidade > div > div > div > div').style.width = '$porcentagem'
    "
    );//-- fim codigo --
    parent::add($script); 

     //INDICADOR PIX 
     $script                         = new TElement('script'); 
     $script->type                   = 'text/javascript';
     $porcentagem                    = round(($indicadorPix/$total),PHP_ROUND_HALF_UP)*100 ;
     $script->add(
     //-- codigo javascript --
     "
     var value = $indicadorPix;
     var total = $total;
     document.querySelector('#indicadorPix > div > div > span.info-box-number').textContent = value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
     document.querySelector('#indicadorPix > div > div > div > div').style.width = '$porcentagem%'
     document.querySelector('#indicadorPix > div > div > span.progress-description').textContent = $porcentagem+'% de '+total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
     "
     );//-- fim codigo --
     parent::add($script); 

     //INDICADOR FISCAL 
    $script                         = new TElement('script'); 
    $script->type                   = 'text/javascript';
    $porcentagem                    = round(($indicadorFiscal/$total),PHP_ROUND_HALF_UP)*100 ;
    $script->add(
    //-- codigo javascript --
    "
    var value = $indicadorFiscal;
    var total = $total;
    document.querySelector('#indicadorFiscal > div > div > span.info-box-number').textContent = value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
     document.querySelector('#indicadorFiscal > div > div > div > div').style.width = '$porcentagem%'
     document.querySelector('#indicadorFiscal > div > div > span.progress-description').textContent = $porcentagem+'% de '+total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    "
    );//-- fim codigo --
    parent::add($script); 

     //INDICADOR TOTAL 
     $script                         = new TElement('script'); 
     $script->type                   = 'text/javascript';
     $porcentagem                    = round(($total/$totalLojas),PHP_ROUND_HALF_UP)*100 ;
     $script->add(
     //-- codigo javascript --
     "
     var value = $total;
     var total = $totalLojas;
     document.querySelector('#indicadorTotal > div > div > span.info-box-number').textContent = value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
     document.querySelector('#indicadorTotal > div > div > div > div').style.width = '$porcentagem%'
     document.querySelector('#indicadorTotal > div > div > span.progress-description').textContent = $porcentagem+'% de '+total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

     "
     );//-- fim codigo --

     parent::add($script); 

    }

}

