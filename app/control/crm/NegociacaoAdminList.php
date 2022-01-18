<?php

class NegociacaoAdminList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'base_banco';
    private static $activeRecord = 'Negociacao';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Negociacao';
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
        $this->form->setFormTitle("Listagem de negociações");
        $this->limit = 20;

        $criteria_cliente_id = new TCriteria();
        $criteria_vendedor_id = new TCriteria();

        $filterVar = Grupo::clientes;
        $criteria_cliente_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 
        $filterVar = Grupo::vendedores;
        $criteria_vendedor_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 

        $id = new TEntry('id');
        $tipo_negociacao_id = new TDBCombo('tipo_negociacao_id', 'base_banco', 'TipoNegociacao', 'id', '{nome}','nome asc'  );
        $cliente_id = new TDBUniqueSearch('cliente_id', 'base_banco', 'Pessoa', 'id', 'nome','nome asc' , $criteria_cliente_id );
        $vendedor_id = new TDBCombo('vendedor_id', 'base_banco', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $origem_negociacao_id = new TDBCombo('origem_negociacao_id', 'base_banco', 'OrigemNegociacao', 'id', '{nome}','nome asc'  );
        $estado_negociacao_id = new TDBCombo('estado_negociacao_id', 'base_banco', 'EstadoNegociacao', 'id', '{nome}','nome asc'  );
        $dt_inicio_negociacao = new TDate('dt_inicio_negociacao');
        $dt_inicio_negociacao_ate = new TDate('dt_inicio_negociacao_ate');
        $dt_fim_negociacao = new TDate('dt_fim_negociacao');
        $dt_fim_negociacao_ate = new TDate('dt_fim_negociacao_ate');
        $system_unit_id = new TDBCombo('system_unit_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );


        $cliente_id->setMinLength(2);

        $dt_fim_negociacao->setDatabaseMask('yyyy-mm-dd');
        $dt_inicio_negociacao->setDatabaseMask('yyyy-mm-dd');
        $dt_fim_negociacao_ate->setDatabaseMask('yyyy-mm-dd');
        $dt_inicio_negociacao_ate->setDatabaseMask('yyyy-mm-dd');

        $cliente_id->setMask('{nome}');
        $dt_fim_negociacao->setMask('dd/mm/yyyy');
        $dt_inicio_negociacao->setMask('dd/mm/yyyy');
        $dt_fim_negociacao_ate->setMask('dd/mm/yyyy');
        $dt_inicio_negociacao_ate->setMask('dd/mm/yyyy');

        $id->setSize(150);
        $cliente_id->setSize('100%');
        $vendedor_id->setSize('100%');
        $dt_fim_negociacao->setSize(150);
        $system_unit_id->setSize('100%');
        $dt_inicio_negociacao->setSize(150);
        $tipo_negociacao_id->setSize('100%');
        $dt_fim_negociacao_ate->setSize(150);
        $origem_negociacao_id->setSize('100%');
        $estado_negociacao_id->setSize('100%');
        $dt_inicio_negociacao_ate->setSize(150);

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[new TLabel("Tipo da negociação:", null, '14px', null)],[$tipo_negociacao_id]);
        $row2 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null)],[$cliente_id],[new TLabel("Vendedor:", null, '14px', null)],[$vendedor_id]);
        $row3 = $this->form->addFields([new TLabel("Origem da negociação:", null, '14px', null)],[$origem_negociacao_id],[new TLabel("Fase da negociação:", null, '14px', null)],[$estado_negociacao_id]);
        $row4 = $this->form->addFields([new TLabel("Início da negociação (de):", null, '14px', null)],[$dt_inicio_negociacao],[new TLabel("Início da negociação (até):", null, '14px', null)],[$dt_inicio_negociacao_ate]);
        $row5 = $this->form->addFields([new TLabel("Fim da negociação (de):", null, '14px', null)],[$dt_fim_negociacao],[new TLabel("Fim da negociação (até):", null, '14px', null)],[$dt_fim_negociacao_ate]);
        $row6 = $this->form->addFields([new TLabel("Empresa:", null, '14px', null)],[$system_unit_id],[],[]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onexportcsv = $this->form->addAction("Exportar como CSV", new TAction([$this, 'onExportCsv']), 'far:file-alt #000000');
        $this->btn_onexportcsv = $btn_onexportcsv;

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['NegociacaoForm', 'onShow']), 'fas:plus #69aa46');
        $this->btn_onshow = $btn_onshow;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $filterVar = TSession::getValue("userunitids");
        $this->filter_criteria->add(new TFilter('system_unit_id', 'in', $filterVar));

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "Id", 'left' , '70px');
        $column_entity_column_id4139761_entity_column_id83 = new TDataGridColumn('entity_column_id:4139761->entity_column_id:83', "Empresa", 'left');
        $column_descricao = new TDataGridColumn('descricao', "Descrição", 'left');
        $column_cliente_nome = new TDataGridColumn('cliente->nome', "Cliente", 'left');
        $column_vendedor_nome = new TDataGridColumn('vendedor->nome', "Vendedor", 'left');
        $column_origem_negociacao_nome = new TDataGridColumn('origem_negociacao->nome', "Origem", 'left');
        $column_tipo_negociacao_nome = new TDataGridColumn('tipo_negociacao->nome', "Tipo", 'left');
        $column_estado_negociacao_nome = new TDataGridColumn('estado_negociacao->nome', "Estado", 'left');
        $column_dt_inicio_negociacao_transformed = new TDataGridColumn('dt_inicio_negociacao', "Início", 'left');
        $column_dt_fim_negociacao_transformed = new TDataGridColumn('dt_fim_negociacao', "Fim", 'left');

        $column_dt_inicio_negociacao_transformed->setTransformer(function($value, $object, $row) 
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_dt_fim_negociacao_transformed->setTransformer(function($value, $object, $row) 
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });        

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_entity_column_id4139761_entity_column_id83);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_cliente_nome);
        $this->datagrid->addColumn($column_vendedor_nome);
        $this->datagrid->addColumn($column_origem_negociacao_nome);
        $this->datagrid->addColumn($column_tipo_negociacao_nome);
        $this->datagrid->addColumn($column_estado_negociacao_nome);
        $this->datagrid->addColumn($column_dt_inicio_negociacao_transformed);
        $this->datagrid->addColumn($column_dt_fim_negociacao_transformed);

        $action_onEdit = new TDataGridAction(array('NegociacaoForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('NegociacaoAdminList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluír");
        $action_onDelete->setImage('far:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
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
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
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
                $key=$param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                $class = self::$activeRecord;

                // instantiates object
                $object = new $class($key, FALSE);

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

    public function onExportCsv($param = null) 
    {
        try
        {
            $this->onSearch();

            TTransaction::open(self::$database); // open a transaction
            $repository = new TRepository(self::$activeRecord); // creates a repository for Customer
            $criteria = new TCriteria; // creates a criteria

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
                $columns = $this->datagrid->getColumns();

                $csvColumns = [];
                foreach($columns as $column)
                {
                    $csvColumns[] = $column->getLabel();
                }
                fputcsv($handle, $csvColumns, ';');

                foreach ($records as $record)
                {
                    $csvColumns = [];
                    foreach($columns as $column)
                    {
                        $name = $column->getName();
                        $csvColumns[] = $record->{$name};
                    }
                    fputcsv($handle, $csvColumns, ';');
                }
                fclose($handle);

                TPage::openFile($file);
            }
            else
            {
                new TMessage('info', _t('No records found'));       
            }

            TTransaction::close(); // close the transaction
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
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

        if (isset($data->tipo_negociacao_id) AND ( (is_scalar($data->tipo_negociacao_id) AND $data->tipo_negociacao_id !== '') OR (is_array($data->tipo_negociacao_id) AND (!empty($data->tipo_negociacao_id)) )) )
        {

            $filters[] = new TFilter('tipo_negociacao_id', '=', $data->tipo_negociacao_id);// create the filter 
        }

        if (isset($data->cliente_id) AND ( (is_scalar($data->cliente_id) AND $data->cliente_id !== '') OR (is_array($data->cliente_id) AND (!empty($data->cliente_id)) )) )
        {

            $filters[] = new TFilter('cliente_id', '=', $data->cliente_id);// create the filter 
        }

        if (isset($data->vendedor_id) AND ( (is_scalar($data->vendedor_id) AND $data->vendedor_id !== '') OR (is_array($data->vendedor_id) AND (!empty($data->vendedor_id)) )) )
        {

            $filters[] = new TFilter('vendedor_id', '=', $data->vendedor_id);// create the filter 
        }

        if (isset($data->origem_negociacao_id) AND ( (is_scalar($data->origem_negociacao_id) AND $data->origem_negociacao_id !== '') OR (is_array($data->origem_negociacao_id) AND (!empty($data->origem_negociacao_id)) )) )
        {

            $filters[] = new TFilter('origem_negociacao_id', '=', $data->origem_negociacao_id);// create the filter 
        }

        if (isset($data->estado_negociacao_id) AND ( (is_scalar($data->estado_negociacao_id) AND $data->estado_negociacao_id !== '') OR (is_array($data->estado_negociacao_id) AND (!empty($data->estado_negociacao_id)) )) )
        {

            $filters[] = new TFilter('estado_negociacao_id', '=', $data->estado_negociacao_id);// create the filter 
        }

        if (isset($data->dt_inicio_negociacao) AND ( (is_scalar($data->dt_inicio_negociacao) AND $data->dt_inicio_negociacao !== '') OR (is_array($data->dt_inicio_negociacao) AND (!empty($data->dt_inicio_negociacao)) )) )
        {

            $filters[] = new TFilter('dt_inicio_negociacao', '>=', $data->dt_inicio_negociacao);// create the filter 
        }

        if (isset($data->dt_inicio_negociacao_ate) AND ( (is_scalar($data->dt_inicio_negociacao_ate) AND $data->dt_inicio_negociacao_ate !== '') OR (is_array($data->dt_inicio_negociacao_ate) AND (!empty($data->dt_inicio_negociacao_ate)) )) )
        {

            $filters[] = new TFilter('dt_inicio_negociacao', '<=', $data->dt_inicio_negociacao_ate);// create the filter 
        }

        if (isset($data->dt_fim_negociacao) AND ( (is_scalar($data->dt_fim_negociacao) AND $data->dt_fim_negociacao !== '') OR (is_array($data->dt_fim_negociacao) AND (!empty($data->dt_fim_negociacao)) )) )
        {

            $filters[] = new TFilter('dt_fim_negociacao', '>=', $data->dt_fim_negociacao);// create the filter 
        }

        if (isset($data->dt_fim_negociacao_ate) AND ( (is_scalar($data->dt_fim_negociacao_ate) AND $data->dt_fim_negociacao_ate !== '') OR (is_array($data->dt_fim_negociacao_ate) AND (!empty($data->dt_fim_negociacao_ate)) )) )
        {

            $filters[] = new TFilter('dt_fim_negociacao', '<=', $data->dt_fim_negociacao_ate);// create the filter 
        }

        if (isset($data->system_unit_id) AND ( (is_scalar($data->system_unit_id) AND $data->system_unit_id !== '') OR (is_array($data->system_unit_id) AND (!empty($data->system_unit_id)) )) )
        {

            $filters[] = new TFilter('system_unit_id', '=', $data->system_unit_id);// create the filter 
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

            // creates a repository for Negociacao
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

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

