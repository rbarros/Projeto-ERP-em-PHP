<?php

class RetornoNfceList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'webhook';
    private static $activeRecord = 'NfceRetorno';
    private static $primaryKey = 'id';
    private static $formName = 'formList_NfceRetorno';
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
        $this->form->setFormTitle("Respostas das NFce's emitidas");
        $this->limit = 20;

        $serie = new TEntry('serie');
        $numero = new TEntry('numero');
        $id_externo = new TEntry('id_externo');
        $empresaId = new TDBCombo('empresaId', 'base_banco', 'Loja', 'idEmpresa', '{nome_fantasia}  - {cnpj} ','razao_social asc'  );
        $dataEmissao = new TDate('dataEmissao');
        $dataEmissaoAte = new TDate('dataEmissaoAte');
        $array_xml = new THidden('array_xml');


        $id_externo->setMaxLength(20);
        $array_xml->setValue('null');

        $dataEmissao->setMask('dd/mm/yyyy');
        $dataEmissaoAte->setMask('dd/mm/yyyy');

        $dataEmissao->setDatabaseMask('yyyy-mm-dd');
        $dataEmissaoAte->setDatabaseMask('yyyy-mm-dd');

        $serie->setSize('100%');
        $numero->setSize('100%');
        $array_xml->setSize(200);
        $empresaId->setSize('100%');
        $id_externo->setSize('100%');
        $dataEmissao->setSize('100%');
        $dataEmissaoAte->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Serie:", null, '14px', null, '100%'),$serie],[new TLabel("Numero:", null, '14px', null, '100%'),$numero],[new TLabel("nº da venda:", null, '14px', null, '100%'),$id_externo],[new TLabel("Loja:", null, '14px', null, '100%'),$empresaId],[new TLabel("Data de Emissão(de):", null, '14px', null, '100%'),$dataEmissao],[new TLabel("Data de Emissão(até):", null, '14px', null, '100%'),$dataEmissaoAte,$array_xml]);
        $row1->layout = [' col-sm-6 col-lg-1',' col-sm-2 col-lg-1','col-sm-2',' col-sm-2 col-lg-4','col-sm-2','col-sm-2'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onlimpar = $this->form->addAction("Limpar formulário", new TAction([$this, 'onlimpar']), 'fas:eraser #E91E63');
        $this->btn_onlimpar = $btn_onlimpar;

        $btn_onvalores = $this->form->addAction("Listar Valores", new TAction([$this, 'onValores'],['static' => 1]), 'fas:dollar-sign #E91E63');
        $this->btn_onvalores = $btn_onvalores;

        $btn_onextrair = $this->form->addAction("Extrair XML", new TAction([$this, 'onExtrair']), 'fas:download #FF9800');
        $this->btn_onextrair = $btn_onextrair;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');
        $column_empresaId = new TDataGridColumn('empresaId', "Loja / Cnpj", 'left');
        $column_id_externo = new TDataGridColumn('id_externo', "Nº venda", 'left');
        $column_serie = new TDataGridColumn('serie', "Série", 'left');
        $column_numero = new TDataGridColumn('numero', "Nº nota", 'left');
        $column_dataEmissao_transformed = new TDataGridColumn('dataEmissao', "Data", 'left');
        $column_linkDanfe = new TDataGridColumn('linkDanfe', "LinkDanfe", 'left');
        $column_status = new TDataGridColumn('status', "Status", 'left');
        $column_motivoStatus = new TDataGridColumn('motivoStatus', "Motivo status", 'left');

        $column_dataEmissao_transformed->setTransformer(function($value, $object, $row)
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

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->builder_datagrid_check_all = new TCheckButton('builder_datagrid_check_all');
        $this->builder_datagrid_check_all->setIndexValue('on');
        $this->builder_datagrid_check_all->onclick = "Builder.checkAll(this)";
        $this->builder_datagrid_check_all->style = 'cursor:pointer';
        $this->builder_datagrid_check_all->setProperty('class', 'filled-in');
        $this->builder_datagrid_check_all->id = 'builder_datagrid_check_all';

        $label = new TLabel('');
        $label->style = 'margin:0';
        $label->class = 'checklist-label';
        $this->builder_datagrid_check_all->after($label);
        $label->for = 'builder_datagrid_check_all';

        $this->builder_datagrid_check = $this->datagrid->addColumn( new TDataGridColumn('builder_datagrid_check', $this->builder_datagrid_check_all, 'center',  '1%') );

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_empresaId);
        $this->datagrid->addColumn($column_id_externo);
        $this->datagrid->addColumn($column_serie);
        $this->datagrid->addColumn($column_numero);
        $this->datagrid->addColumn($column_dataEmissao_transformed);
        $this->datagrid->addColumn($column_linkDanfe);
        $this->datagrid->addColumn($column_status);
        $this->datagrid->addColumn($column_motivoStatus);

        $action_onDownloadXml = new TDataGridAction(array('RetornoNfceList', 'onDownloadXml'));
        $action_onDownloadXml->setUseButton(false);
        $action_onDownloadXml->setButtonClass('btn btn-default btn-sm');
        $action_onDownloadXml->setLabel("Baixar Xml");
        $action_onDownloadXml->setImage('fas:file-download #FF9800');
        $action_onDownloadXml->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDownloadXml);

        $action_onVenda = new TDataGridAction(array('RetornoNfceList', 'onVenda'));
        $action_onVenda->setUseButton(false);
        $action_onVenda->setButtonClass('btn btn-default btn-sm');
        $action_onVenda->setLabel("ir para a venda");
        $action_onVenda->setImage('fas:shopping-cart #4CAF50');
        $action_onVenda->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onVenda);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $this->datagrid->disableDefaultClick();

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

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

        $button_baixar_selecionados = new TButton('button_button_baixar_selecionados');
        $button_baixar_selecionados->setAction(new TAction(['RetornoNfceList', 'onEditBatchArray']), "Baixar selecionados");
        $button_baixar_selecionados->addStyleClass('');
        $button_baixar_selecionados->setImage('fas:file-download #FF9800');
        $this->datagrid_form->addField($button_baixar_selecionados);

        $head_left_actions->add($button_baixar_selecionados);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Financeiro","XML"]));
        }
        $container->add($this->form);

        $container->add($panel);

        parent::add($container);

    }

    public function onDownloadXml($param = null) 
    {
        try 
        {
            //code here

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onVenda($param = null) 
    {
        try 
        {
            //code here

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function onEditBatchArray($param = null) 
    {
        if(isset($param['confirmAction']) && $param['confirmAction'] == 1)
        {
            try
            {
                if($param['builder_datagrid_check'])
                {
                    TTransaction::open(self::$database);
                    foreach($param['builder_datagrid_check'] as $check_id)
                    {
                        $object = new NfceRetorno($check_id);

                        //$object->column1 = 'value';

                        $object->store();
                    }
                    TTransaction::close();

                    TToast::show("success", "Registros atualizados", "topRight", "fas fa-info-circle");                
                    TApplication::loadPage(__CLASS__, 'onShow', []);
                }
            }
            catch (Exception $e) // in case of exception
            {
                new TMessage('error', $e->getMessage()); // shows the exception error message
                TTransaction::rollback(); // undo all pending operations
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array(__CLASS__, 'onEditBatchArray'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('confirmAction', 1);

            new TQuestion('Executar ação?', $action);   
        }
    }
    public function onlimpar($param = null) 
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
    public function onValores($param = null) 
    {
        try 
        {/*
            $data =array();
            $data['dataEmissao']    = $param['dataEmissao'] != null || $param['dataEmissao'] != '' ? strtotime($param['dataEmissao']) : new DateTime('Y-m-d') ;
            $data['dataEmissaoAte'] = $param['dataEmissaoAte'] != null || $param['dataEmissaoAte'] != '' ? strtotime($param['dataEmissaoAte']) : new DateTime('Y-m-d') ;
*/
            var_dump($data);

           EnviarXml::listarDiretorio();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onExtrair($param = null) 
    {
        try 
        {
            $repository = new TRepository(self::$activeRecord);
            $criteria   = clone $this->filter_criteria;
            if($filters = TSession::getValue(__CLASS__.'_filters')){
                foreach ($filters as $filter){
                    $criteria->add($filter);       
                }
                TTransaction::open(self::$database);
                $objects = $repository->load($criteria, FALSE);
                TTransaction::close();
                EnviarXml::ObterValores($objects);
            }else{
                EnviarXml::ObterValores();
            }

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

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->serie) AND ( (is_scalar($data->serie) AND $data->serie !== '') OR (is_array($data->serie) AND (!empty($data->serie)) )) )
        {

            $filters[] = new TFilter('serie', '=', $data->serie);// create the filter 
        }

        if (isset($data->numero) AND ( (is_scalar($data->numero) AND $data->numero !== '') OR (is_array($data->numero) AND (!empty($data->numero)) )) )
        {

            $filters[] = new TFilter('numero', '=', $data->numero);// create the filter 
        }

        if (isset($data->id_externo) AND ( (is_scalar($data->id_externo) AND $data->id_externo !== '') OR (is_array($data->id_externo) AND (!empty($data->id_externo)) )) )
        {

            $filters[] = new TFilter('id_externo', 'like', "%{$data->id_externo}%");// create the filter 
        }

        if (isset($data->empresaId) AND ( (is_scalar($data->empresaId) AND $data->empresaId !== '') OR (is_array($data->empresaId) AND (!empty($data->empresaId)) )) )
        {

            $filters[] = new TFilter('empresaId', 'like', "%{$data->empresaId}%");// create the filter 
        }

        if (isset($data->dataEmissao) AND ( (is_scalar($data->dataEmissao) AND $data->dataEmissao !== '') OR (is_array($data->dataEmissao) AND (!empty($data->dataEmissao)) )) )
        {

            $filters[] = new TFilter('dataEmissao', '>=', $data->dataEmissao);// create the filter 
        }

        if (isset($data->dataEmissaoAte) AND ( (is_scalar($data->dataEmissaoAte) AND $data->dataEmissaoAte !== '') OR (is_array($data->dataEmissaoAte) AND (!empty($data->dataEmissaoAte)) )) )
        {

            $filters[] = new TFilter('dataEmissao', '<=', $data->dataEmissaoAte);// create the filter 
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
            // open a transaction with database 'webhook'
            TTransaction::open(self::$database);

            // creates a repository for NfceRetorno
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

            TTransaction::open('base_banco');
            $lojas_obj                      = Loja::getObjects();
            TTransaction::close();
            $lojas                          = array();
            foreach($lojas_obj as $loja){
                $lojas[$loja->idEmpresa]    = $loja->nome_fantasia.' - '.$loja->cnpj;
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    $check = new TCheckButton('builder_datagrid_check[]');
                    $check->setIndexValue($object->id);
                    $object->builder_datagrid_check = $check;

                    $object->empresaId = $lojas[$object->empresaId];
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

}

