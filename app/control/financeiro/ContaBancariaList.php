<?php

class ContaBancariaList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'base_banco';
    private static $activeRecord = 'ContaBancaria';
    private static $primaryKey = 'id';
    private static $formName = 'formList_ContaBancaria';
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
        $this->form->setFormTitle("Contas Bancárias");
        $this->limit = 20;

        $id = new TEntry('id');
        $agencia = new TEntry('agencia');
        $numero_conta = new TEntry('numero_conta');
        $banco = new TDBCombo('banco', 'base_banco', 'Banco', 'id', '{cod} - {nome} ','nome asc'  );
        $nome = new TEntry('nome');
        $id_referencia_tipo = new TDBCombo('id_referencia_tipo', 'base_banco', 'TipoContaBancaria', 'id', '{conta_bancaria}','conta_bancaria asc'  );
        $loja = new TDBCombo('loja', 'base_banco', 'Loja', 'id', '{razao_social}','razao_social asc'  );
        $fornecedor = new TDBCombo('fornecedor', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc'  );
        $colaborador = new TDBCombo('colaborador', 'base_banco', 'Colaborador', 'id', '{id}','id asc'  );
        $parceiro = new TEntry('parceiro');
        $cliente = new TEntry('cliente');


        $nome->setMaxLength(50);
        $id->setSize(100);
        $nome->setSize('100%');
        $loja->setSize('100%');
        $banco->setSize('100%');
        $agencia->setSize('100%');
        $cliente->setSize('100%');
        $parceiro->setSize('100%');
        $fornecedor->setSize('100%');
        $colaborador->setSize('100%');
        $numero_conta->setSize('100%');
        $id_referencia_tipo->setSize('100%');

        //</onBeforeAddFieldsToForm>
        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[],[new TLabel("Agencia:", null, '14px', null, '100%'),$agencia],[new TLabel("Numero conta:", null, '14px', null, '100%'),$numero_conta],[new TLabel("Banco:", null, '14px', null, '100%'),$banco],[new TLabel("Descrição:", null, '14px', null, '100%'),$nome],[new TLabel("Tipo de conta:", null, '14px', null, '100%'),$id_referencia_tipo]);
        $row1->layout = [' col-sm-6 col-lg-1',' col-sm-6 col-lg-1','col-sm-2','col-sm-2','col-sm-2',' col-sm-2 col-lg-2','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Loja", null, '14px', null, '100%'),$loja],[new TLabel("Fornecedor:", null, '14px', null, '100%'),$fornecedor],[new TLabel("Colaborador:", null, '14px', null, '100%'),$colaborador],[new TLabel("Parceiro:", null, '14px', null, '100%'),$parceiro],[new TLabel("Cliente:", null, '14px', null, '100%'),$cliente]);
        $row2->layout = [' col-sm-3 col-lg-3',' col-sm-3 col-lg-3',' col-sm-6 col-lg-2','col-sm-2','col-sm-2'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['ContaBancariaForm', 'onShow']), 'fas:plus #69aa46');
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

        $column_id = new TDataGridColumn('id', "Id", 'center' , '46px');
        $column_nome = new TDataGridColumn('nome', "Descrição", 'left');
        $column_fk_loja_nome_fantasia = new TDataGridColumn('fk_loja->nome_fantasia', "Loja", 'left');
        $column_fk_fornecedor_nome_fantasia = new TDataGridColumn('fk_fornecedor->nome_fantasia', "Fornecedor", 'left');
        $column_colaborador = new TDataGridColumn('colaborador', "Colaborador", 'left');
        $column_cliente = new TDataGridColumn('cliente', "Cliente", 'left');
        $column_agencia = new TDataGridColumn('agencia', "Agencia", 'left');
        $column_numero_conta = new TDataGridColumn('numero_conta', "Nº conta", 'left');
        $column_fk_banco_nome = new TDataGridColumn('fk_banco->nome', "Banco", 'left');
        $column_referencia_tipo_conta_bancaria = new TDataGridColumn('referencia_tipo->conta_bancaria', "Tipo de conta", 'left');

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_fk_loja_nome_fantasia);
        $this->datagrid->addColumn($column_fk_fornecedor_nome_fantasia);
        $this->datagrid->addColumn($column_colaborador);
        $this->datagrid->addColumn($column_cliente);
        $this->datagrid->addColumn($column_agencia);
        $this->datagrid->addColumn($column_numero_conta);
        $this->datagrid->addColumn($column_fk_banco_nome);
        $this->datagrid->addColumn($column_referencia_tipo_conta_bancaria);

        $action_onEdit = new TDataGridAction(array('ContaBancariaForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('ContaBancariaList', 'onDelete'));
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

        $button_csv = new TButton('button_button_csv');
        $button_csv->setAction(new TAction(['ContaBancariaList', 'onExportCsv'],['static' => 1]), "CSV");
        $button_csv->addStyleClass('');
        $button_csv->setImage('fas:table #00b894');
        $this->datagrid_form->addField($button_csv);

        $button_pdf = new TButton('button_button_pdf');
        $button_pdf->setAction(new TAction(['ContaBancariaList', 'onExportPdf'],['static' => 1]), "PDF");
        $button_pdf->addStyleClass('');
        $button_pdf->setImage('far:file-pdf #e74c3c');
        $this->datagrid_form->addField($button_pdf);

        $button_xml = new TButton('button_button_xml');
        $button_xml->setAction(new TAction(['ContaBancariaList', 'onExportXml'],['static' => 1]), "XML");
        $button_xml->addStyleClass('');
        $button_xml->setImage('far:file-code #95a5a6');
        $this->datagrid_form->addField($button_xml);

        $head_right_actions->add($button_csv);
        $head_right_actions->add($button_pdf);
        $head_right_actions->add($button_xml);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Financeiro","Contas Bancárias"]));
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
                $object = new ContaBancaria($key, FALSE); 

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

        if (isset($data->agencia) AND ( (is_scalar($data->agencia) AND $data->agencia !== '') OR (is_array($data->agencia) AND (!empty($data->agencia)) )) )
        {

            $filters[] = new TFilter('agencia', '=', $data->agencia);// create the filter 
        }

        if (isset($data->numero_conta) AND ( (is_scalar($data->numero_conta) AND $data->numero_conta !== '') OR (is_array($data->numero_conta) AND (!empty($data->numero_conta)) )) )
        {

            $filters[] = new TFilter('numero_conta', '=', $data->numero_conta);// create the filter 
        }

        if (isset($data->banco) AND ( (is_scalar($data->banco) AND $data->banco !== '') OR (is_array($data->banco) AND (!empty($data->banco)) )) )
        {

            $filters[] = new TFilter('banco', '=', $data->banco);// create the filter 
        }

        if (isset($data->nome) AND ( (is_scalar($data->nome) AND $data->nome !== '') OR (is_array($data->nome) AND (!empty($data->nome)) )) )
        {

            $filters[] = new TFilter('nome', 'like', "%{$data->nome}%");// create the filter 
        }

        if (isset($data->id_referencia_tipo) AND ( (is_scalar($data->id_referencia_tipo) AND $data->id_referencia_tipo !== '') OR (is_array($data->id_referencia_tipo) AND (!empty($data->id_referencia_tipo)) )) )
        {

            $filters[] = new TFilter('id_referencia_tipo', '=', $data->id_referencia_tipo);// create the filter 
        }

        if (isset($data->loja) AND ( (is_scalar($data->loja) AND $data->loja !== '') OR (is_array($data->loja) AND (!empty($data->loja)) )) )
        {

            $filters[] = new TFilter('loja', '=', $data->loja);// create the filter 
        }

        if (isset($data->fornecedor) AND ( (is_scalar($data->fornecedor) AND $data->fornecedor !== '') OR (is_array($data->fornecedor) AND (!empty($data->fornecedor)) )) )
        {

            $filters[] = new TFilter('fornecedor', '=', $data->fornecedor);// create the filter 
        }

        if (isset($data->colaborador) AND ( (is_scalar($data->colaborador) AND $data->colaborador !== '') OR (is_array($data->colaborador) AND (!empty($data->colaborador)) )) )
        {

            $filters[] = new TFilter('colaborador', '=', $data->colaborador);// create the filter 
        }

        if (isset($data->parceiro) AND ( (is_scalar($data->parceiro) AND $data->parceiro !== '') OR (is_array($data->parceiro) AND (!empty($data->parceiro)) )) )
        {

            $filters[] = new TFilter('parceiro', '=', $data->parceiro);// create the filter 
        }

        if (isset($data->cliente) AND ( (is_scalar($data->cliente) AND $data->cliente !== '') OR (is_array($data->cliente) AND (!empty($data->cliente)) )) )
        {

            $filters[] = new TFilter('cliente', '=', $data->cliente);// create the filter 
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

            // creates a repository for ContaBancaria
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

