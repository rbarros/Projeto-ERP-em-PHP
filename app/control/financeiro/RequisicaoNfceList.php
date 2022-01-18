<?php

class RequisicaoNfceList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'vendas_base';
    private static $activeRecord = 'Nfce';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Nfce';
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
        $this->form->setFormTitle("Requisições NFCE");
        $this->limit = 100;

        $id = new TEntry('id');
        $ambienteEmissao = new TEntry('ambienteEmissao');
        $numVenda = new TEntry('numVenda');
        $n_nfce = new TEntry('n_nfce');
        $venda_id = new TEntry('venda_id');
        $status = new TCombo('status');
        $id_loja = new TDBCombo('id_loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}  / {cnpj} ','razao_social asc'  );
        $data_de = new TDate('data_de');
        $dt_nfce = new TDate('dt_nfce');


        $status->addItems(['Negada'=>'Negada','Autorizada'=>' Autorizada','completed'=>' finalizada','Erro'=>'Erro','Duplicata'=>'Duplicata','Cancelada'=>'Cancelada','SolicitandoAutorizacao'=>'SolicitandoAutorizacao']);

        $numVenda->setMaxLength(30);
        $ambienteEmissao->setMaxLength(30);

        $data_de->setMask('dd/mm/yyyy');
        $dt_nfce->setMask('dd/mm/yyyy');

        $data_de->setDatabaseMask('yyyy-mm-dd');
        $dt_nfce->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(100);
        $n_nfce->setSize('100%');
        $status->setSize('100%');
        $id_loja->setSize('100%');
        $data_de->setSize('100%');
        $dt_nfce->setSize('100%');
        $numVenda->setSize('100%');
        $venda_id->setSize('100%');
        $ambienteEmissao->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Tipo Emissão:", null, '14px', null, '100%'),$ambienteEmissao],[new TLabel("Nº Venda:", null, '14px', null, '100%'),$numVenda],[new TLabel("Nº Nfce", null, '14px', null, '100%'),$n_nfce],[new TLabel("Venda id:", null, '14px', null, '100%'),$venda_id],[],[new TLabel("Status:", null, '14px', null, '100%'),$status]);
        $row1->layout = [' col-sm-6 col-lg-1',' col-sm-6 col-lg-1','col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Lloja da Nfce:", null, '14px', null, '100%'),$id_loja],[],[new TLabel("Emissão(de):", null, '14px', null, '100%'),$data_de],[new TLabel("Emissão(até):", null, '14px', null, '100%'),$dt_nfce]);
        $row2->layout = ['col-sm-6',' col-sm-2 col-lg-2','col-sm-2',' col-sm-2 col-lg-2'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_oncsv = $this->form->addAction("Gerar Csv", new TAction([$this, 'onCSV']), 'fas:file-csv #FFFFFF');
        $this->btn_oncsv = $btn_oncsv;
        $btn_oncsv->addStyleClass('btn-success'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');
        $column_ambienteEmissao = new TDataGridColumn('ambienteEmissao', "Tipo Emissão:", 'left');
        $column_venda_id = new TDataGridColumn('venda_id', "ID da venda", 'left');
        $column_numVenda = new TDataGridColumn('numVenda', "Nº venda", 'left');
        $column_n_nfce = new TDataGridColumn('n_nfce', "Nº Nfce", 'left');
        $column_id_loja = new TDataGridColumn('id_loja', "Loja/Cnpj", 'left');
        $column_dt_nfce = new TDataGridColumn('dt_nfce', "Emissão", 'left');
        $column_status = new TDataGridColumn('status', "Status", 'left');
        $column_link_cupom = new TDataGridColumn('link_cupom', "Link do PDF da Nfce", 'left');

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_ambienteEmissao);
        $this->datagrid->addColumn($column_venda_id);
        $this->datagrid->addColumn($column_numVenda);
        $this->datagrid->addColumn($column_n_nfce);
        $this->datagrid->addColumn($column_id_loja);
        $this->datagrid->addColumn($column_dt_nfce);
        $this->datagrid->addColumn($column_status);
        $this->datagrid->addColumn($column_link_cupom);

        $column_status->setTransformer(array($this,'formatarStatus'));

        $action_onEdit = new TDataGridAction(array('NfceForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $action_onEdit->setParameter('nfce', '{id}');
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
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['RequisicaoNfceList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:table #00b894' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['RequisicaoNfceList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['RequisicaoNfceList', 'onExportXml'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-code #95a5a6' );

        $head_right_actions->add($dropdown_button_exportar);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Financeiro","Requisições NFCE"]));
        }
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

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
    public function onCSV($param = null) 
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
                $csvColumns = ['id','ambienteEmissao','informacoesAdicionais','presencaConsumidor','numVenda','status','n_nfce','link_cupom','id_loja','retorno_nfce','venda_id','dt_nfce'];
                fputcsv($handle, $csvColumns, ',');
                $to_remove = array("\n","   ","<br>","\r","/\s/");
            foreach($records as $record){
                $csvColumns =[];
                $csvColumns []=$record->id;
                $csvColumns []= $record->ambienteEmissao;
                $csvColumns []= str_replace($to_remove,"",$record->informacoesAdicionais);
                $csvColumns []= $record->presencaConsumidor;
                $csvColumns []= $record->numVenda;
                $csvColumns []= $record->status;
                $csvColumns []= $record->n_nfce;
                $csvColumns []= $record->link_cupom;
                $csvColumns []= $record->id_loja;
                $csvColumns []= $record->retorno_nfce;
                $csvColumns []= $record->venda_id;
                $csvColumns []= $record->dt_nfce;
                $cont++;
                fputcsv($handle, $csvColumns, ',');
            }
             fclose($handle);

             TPage::openFile($file);
             new TMessage('info',"foram exportados {$cont} requisiçôes Nfce do sistema");
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

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->ambienteEmissao) AND ( (is_scalar($data->ambienteEmissao) AND $data->ambienteEmissao !== '') OR (is_array($data->ambienteEmissao) AND (!empty($data->ambienteEmissao)) )) )
        {

            $filters[] = new TFilter('ambienteEmissao', 'like', "%{$data->ambienteEmissao}%");// create the filter 
        }

        if (isset($data->numVenda) AND ( (is_scalar($data->numVenda) AND $data->numVenda !== '') OR (is_array($data->numVenda) AND (!empty($data->numVenda)) )) )
        {

            $filters[] = new TFilter('numVenda', 'like', "%{$data->numVenda}%");// create the filter 
        }

        if (isset($data->n_nfce) AND ( (is_scalar($data->n_nfce) AND $data->n_nfce !== '') OR (is_array($data->n_nfce) AND (!empty($data->n_nfce)) )) )
        {

            $filters[] = new TFilter('n_nfce', '=', $data->n_nfce);// create the filter 
        }

        if (isset($data->venda_id) AND ( (is_scalar($data->venda_id) AND $data->venda_id !== '') OR (is_array($data->venda_id) AND (!empty($data->venda_id)) )) )
        {

            $filters[] = new TFilter('venda_id', '=', $data->venda_id);// create the filter 
        }

        if (isset($data->status) AND ( (is_scalar($data->status) AND $data->status !== '') OR (is_array($data->status) AND (!empty($data->status)) )) )
        {

            $filters[] = new TFilter('status', 'like', "%{$data->status}%");// create the filter 
        }

        if (isset($data->id_loja) AND ( (is_scalar($data->id_loja) AND $data->id_loja !== '') OR (is_array($data->id_loja) AND (!empty($data->id_loja)) )) )
        {

            $filters[] = new TFilter('id_loja', '=', $data->id_loja);// create the filter 
        }

        if (isset($data->data_de) AND ( (is_scalar($data->data_de) AND $data->data_de !== '') OR (is_array($data->data_de) AND (!empty($data->data_de)) )) )
        {

            $filters[] = new TFilter('dt_nfce', '>=', $data->data_de);// create the filter 
        }

        if (isset($data->dt_nfce) AND ( (is_scalar($data->dt_nfce) AND $data->dt_nfce !== '') OR (is_array($data->dt_nfce) AND (!empty($data->dt_nfce)) )) )
        {

            $filters[] = new TFilter('dt_nfce', '<=', $data->dt_nfce);// create the filter 
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
            // open a transaction with database 'vendas_base'
            TTransaction::open(self::$database);

            // creates a repository for Nfce
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

                   /* TTransaction::open("base_banco");
                    $loja = new Loja($object->id_loja);
                    $object->id_loja = $loja->nome_fantasia.' - '.$loja->cnpj;
                    TTransaction::close();*/
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

