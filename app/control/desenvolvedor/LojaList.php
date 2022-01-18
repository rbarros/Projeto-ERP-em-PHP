<?php

class LojaList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'base_banco';
    private static $activeRecord = 'Loja';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Loja';
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
        $this->form->setFormTitle("Lojas");
        $this->limit = 40;

        $id = new TEntry('id');
        $nome_fantasia = new TEntry('nome_fantasia');
        $cnpj = new TEntry('cnpj');
        $razao_social = new TEntry('razao_social');
        $fk_grupo_nome = new TDBCombo('fk_grupo_nome', 'base_banco', 'GrupoLojas', 'id', '{nome}','id asc'  );


        $id->setSize(100);
        $cnpj->setSize('100%');
        $razao_social->setSize('100%');
        $nome_fantasia->setSize('100%');
        $fk_grupo_nome->setSize('100%');

        $this->form->appendPage("Busca Rápida");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Nome fantasia:", null, '14px', null, '100%'),$nome_fantasia],[new TLabel("Cnpj:", null, '14px', null, '100%'),$cnpj]);
        $row1->layout = [' col-sm-2',' col-sm-5',' col-sm-5'];

        $row2 = $this->form->addFields([new TLabel("Razão social:", null, '14px', null, '100%'),$razao_social],[new TLabel("Grupo:", null, '14px', null, '100%'),$fk_grupo_nome]);
        $row2->layout = [' col-sm-8',' col-sm-4'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['LojaForm', 'onShow']), 'fas:plus #69aa46');
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

        $column_razao_social = new TDataGridColumn('razao_social', "Razao social", 'left');
        $column_nome_fantasia = new TDataGridColumn('nome_fantasia', "Nome fantasia", 'left');
        $column_abreviacao = new TDataGridColumn('abreviacao', "Abreviacao", 'left');
        $column_cnpj = new TDataGridColumn('cnpj', "Cnpj", 'left');
        $column_cidade = new TDataGridColumn('cidade', "Cidade", 'left');
        $column_deposito = new TDataGridColumn('deposito', "Deposito", 'left');

        $this->datagrid->addColumn($column_razao_social);
        $this->datagrid->addColumn($column_nome_fantasia);
        $this->datagrid->addColumn($column_abreviacao);
        $this->datagrid->addColumn($column_cnpj);
        $this->datagrid->addColumn($column_cidade);
        $this->datagrid->addColumn($column_deposito);

        $action_onEdit = new TDataGridAction(array('LojaForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('LojaList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

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
            $container->add(TBreadCrumb::create(["Desenvolvedor","Lojas"]));
        }
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new Loja($key, FALSE); 

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
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

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->nome_fantasia) AND ( (is_scalar($data->nome_fantasia) AND $data->nome_fantasia !== '') OR (is_array($data->nome_fantasia) AND (!empty($data->nome_fantasia)) )) )
        {

            $filters[] = new TFilter('nome_fantasia', 'like', "%{$data->nome_fantasia}%");// create the filter 
        }

        if (isset($data->cnpj) AND ( (is_scalar($data->cnpj) AND $data->cnpj !== '') OR (is_array($data->cnpj) AND (!empty($data->cnpj)) )) )
        {

            $filters[] = new TFilter('cnpj', 'like', "%{$data->cnpj}%");// create the filter 
        }

        if (isset($data->razao_social) AND ( (is_scalar($data->razao_social) AND $data->razao_social !== '') OR (is_array($data->razao_social) AND (!empty($data->razao_social)) )) )
        {

            $filters[] = new TFilter('razao_social', 'like', "%{$data->razao_social}%");// create the filter 
        }

        if (isset($data->fk_grupo_nome) AND ( (is_scalar($data->fk_grupo_nome) AND $data->fk_grupo_nome !== '') OR (is_array($data->fk_grupo_nome) AND (!empty($data->fk_grupo_nome)) )) )
        {

            $filters[] = new TFilter('grupo', '=', $data->fk_grupo_nome);// create the filter 
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

            // creates a repository for Loja
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

                    $cidade = new Cidade($object->cidade);
                    $object->cidade = $cidade->nome;
                    $deposito = new Deposito($object->deposito);
                    $object->deposito = $deposito->nome_deposito;
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

