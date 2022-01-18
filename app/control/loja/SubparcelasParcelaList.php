<?php

class SubparcelasParcelaList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'base_banco';
    private static $activeRecord = 'SubparcelasParcela';
    private static $primaryKey = 'id';
    private static $formName = 'formList_SubparcelasParcela';
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
        $this->form->setFormTitle("Solicitações de depósitos");
        $this->limit = 20;

        $criteria_loja_id = new TCriteria();

        $id = new TEntry('id');
        $loja_id = new TDBCombo('loja_id', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc' , $criteria_loja_id );
        $conta_origem = new TDBCombo('conta_origem', 'base_banco', 'Conta', 'id', '{id}','id asc'  );
        $fornecedor_id = new TDBCombo('fornecedor_id', 'base_banco', 'Fornecedor', 'id', '{nome_fantasia}','razao_social asc'  );
        $valor = new TNumeric('valor', '2', ',', '.' );
        $vencimento = new TDate('vencimento');
        $conta_bancaria_fornecedor = new TDBCombo('conta_bancaria_fornecedor', 'base_banco', 'ContaBancaria', 'id', '{id}','id asc'  );
        $quitada = new TRadioGroup('quitada');


        $loja_id->setEditable(false);
        $loja_id->setDefaultOption(false);
        $vencimento->setMask('dd/mm/yyyy');
        $vencimento->setDatabaseMask('yyyy-mm-dd');
        $quitada->addItems(['t'=>'SIM','f'=>'NÃO']);
        $quitada->setLayout('horizontal');

        $id->setSize(100);
        $valor->setSize('100%');
        $loja_id->setSize('100%');
        $quitada->setSize('100%');
        $vencimento->setSize('100%');
        $conta_origem->setSize('100%');
        $fornecedor_id->setSize('100%');
        $conta_bancaria_fornecedor->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Loja:", null, '14px', null, '100%'),$loja_id],[new TLabel("Conta origem:", null, '14px', null, '100%'),$conta_origem],[new TLabel("Fornecedor:", null, '14px', null, '100%'),$fornecedor_id],[new TLabel("Valor:", null, '14px', null, '100%'),$valor],[new TLabel("Vencimento:", null, '14px', null, '100%'),$vencimento]);
        $row1->layout = [' col-sm-6 col-lg-1','col-sm-2',' col-sm-6 col-lg-3','col-sm-2','col-sm-2','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Conta bancaria fornecedor:", null, '14px', null, '100%'),$conta_bancaria_fornecedor],[],[new TLabel("Quitada:", null, '14px', null, '100%'),$quitada]);
        $row2->layout = ['col-sm-6',' col-sm-2 col-lg-4','col-sm-2'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['ComprovanteForm', 'onShow']), 'fas:plus #69aa46');
        $this->btn_onshow = $btn_onshow;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_loja_nome_fantasia = new TDataGridColumn('loja->nome_fantasia', "Loja", 'left');
        $column_fornecedor_razao_social = new TDataGridColumn('fornecedor->razao_social', "Fornecedor", 'left');
        $column_valor_transformed = new TDataGridColumn('valor', "Valor", 'left');
        $column_conta_bancaria_fornecedor = new TDataGridColumn('conta_bancaria_fornecedor', "Conta bancaria do fornecedor", 'left');
        $column_obs = new TDataGridColumn('obs', "Observação", 'left');
        $column_vencimento = new TDataGridColumn('vencimento', "Vencimento", 'left');
        $column_quitada_transformed = new TDataGridColumn('quitada', "Quitada", 'left');

        $column_valor_transformed->setTransformer(function($value, $object, $row) 
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

        $column_quitada_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value === true || $value == 't' || $value === 1 || $value == '1' || $value == 's' || $value == 'S' || $value == 'T')
                return 'Sim';

            return 'Não';

        });        

        $this->datagrid->addColumn($column_loja_nome_fantasia);
        $this->datagrid->addColumn($column_fornecedor_razao_social);
        $this->datagrid->addColumn($column_valor_transformed);
        $this->datagrid->addColumn($column_conta_bancaria_fornecedor);
        $this->datagrid->addColumn($column_obs);
        $this->datagrid->addColumn($column_vencimento);
        $this->datagrid->addColumn($column_quitada_transformed);

        $action_onEdit = new TDataGridAction(array('ComprovanteForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

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

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Loja","Solicitações de depósitos"]));
        }
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->loja_id) AND ( (is_scalar($data->loja_id) AND $data->loja_id !== '') OR (is_array($data->loja_id) AND (!empty($data->loja_id)) )) )
        {

            $filters[] = new TFilter('loja_id', '=', $data->loja_id);// create the filter 
        }

        if (isset($data->conta_origem) AND ( (is_scalar($data->conta_origem) AND $data->conta_origem !== '') OR (is_array($data->conta_origem) AND (!empty($data->conta_origem)) )) )
        {

            $filters[] = new TFilter('conta_origem', '=', $data->conta_origem);// create the filter 
        }

        if (isset($data->fornecedor_id) AND ( (is_scalar($data->fornecedor_id) AND $data->fornecedor_id !== '') OR (is_array($data->fornecedor_id) AND (!empty($data->fornecedor_id)) )) )
        {

            $filters[] = new TFilter('fornecedor_id', '=', $data->fornecedor_id);// create the filter 
        }

        if (isset($data->valor) AND ( (is_scalar($data->valor) AND $data->valor !== '') OR (is_array($data->valor) AND (!empty($data->valor)) )) )
        {

            $filters[] = new TFilter('valor', '=', $data->valor);// create the filter 
        }

        if (isset($data->vencimento) AND ( (is_scalar($data->vencimento) AND $data->vencimento !== '') OR (is_array($data->vencimento) AND (!empty($data->vencimento)) )) )
        {

            $filters[] = new TFilter('vencimento', '=', $data->vencimento);// create the filter 
        }

        if (isset($data->conta_bancaria_fornecedor) AND ( (is_scalar($data->conta_bancaria_fornecedor) AND $data->conta_bancaria_fornecedor !== '') OR (is_array($data->conta_bancaria_fornecedor) AND (!empty($data->conta_bancaria_fornecedor)) )) )
        {

            $filters[] = new TFilter('conta_bancaria_fornecedor', '=', $data->conta_bancaria_fornecedor);// create the filter 
        }

        if (isset($data->quitada) AND ( (is_scalar($data->quitada) AND $data->quitada !== '') OR (is_array($data->quitada) AND (!empty($data->quitada)) )) )
        {

            $filters[] = new TFilter('quitada', '=', $data->quitada);// create the filter 
        }

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
            // open a transaction with database 'base_banco'
            TTransaction::open(self::$database);

            // creates a repository for SubparcelasParcela
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

            $unit       = TSession::getValue('userunitid');
            $lojas      = Loja::where('unidade','=',$unit)->load();
            $loja       = 0;
            if($lojas){
                $loja   = $lojas[];
                $loja   = $loja->id;
            }
            $data       = $this->form->getData();
            $quitada    = 'f';
            if($data->quitada == '' || $data->quitada == 'f'){
                $quitada    = 'f';
            }else{
                $quitada    = 't';
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    if($object->loja_id == $loja){
                        if($object->quitada == $quitada){
                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                        }
                    }
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

            $data       = $this->form->getData();
            $unit       = TSession::getValue('userunitid');
            TTransaction::open(self::$database);
            $lojas      = Loja::where('unidade','=',$unit)->load();
            TTransaction::close();
            $loja       = 0;
            if($lojas){
                $loja           = $lojas[];
                $data->loja_id  = $loja->id;
                $data->quitada  = 'f';
                $this->form->setData($data);
            }
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

}

