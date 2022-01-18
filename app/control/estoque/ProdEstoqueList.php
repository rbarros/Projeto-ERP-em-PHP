<?php

class ProdEstoqueList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'base_banco';
    private static $activeRecord = 'ProdEstoque';
    private static $primaryKey = 'id';
    private static $formName = 'formList_ProdEstoque';
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
        $this->form->setFormTitle("Estoque");
        $this->limit = 50;

        $id = new TEntry('id');
        $id_produto = new TDBCombo('id_produto', 'base_banco', 'Produto', 'id', '{descricao}','descricao asc'  );
        $produto_cod_barras = new TEntry('produto_cod_barras');
        $produto_referencia = new TEntry('produto_referencia');
        $produto_marca = new TDBCombo('produto_marca', 'base_banco', 'Marca', 'id', '{marca}','marca asc'  );
        $produto_sku = new TEntry('produto_sku');
        $produto_nome = new TDBEntry('produto_nome', 'base_banco', 'ProdEstoque', 'produto_nome','id asc'  );
        $produto_categoria = new TDBCombo('produto_categoria', 'base_banco', 'CategoriaProduto', 'id', '{nome}','nome asc'  );
        $produto_fornecedor = new TDBCombo('produto_fornecedor', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc'  );
        $id_deposito = new TDBCombo('id_deposito', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );


        $produto_nome->setDisplayMask('{produto_nome}  {produto_nome_variacao}  {produto_sku} {produto_referencia} ');

        $produto_sku->setMaxLength(100);
        $produto_cod_barras->setMaxLength(13);
        $produto_referencia->setMaxLength(30);

        $id->setSize(100);
        $id_produto->setSize('100%');
        $produto_sku->setSize('100%');
        $id_deposito->setSize('100%');
        $produto_nome->setSize('100%');
        $produto_marca->setSize('100%');
        $produto_categoria->setSize('100%');
        $produto_cod_barras->setSize('100%');
        $produto_referencia->setSize('100%');
        $produto_fornecedor->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("ID:", null, '14px', null, '100%'),$id],[new TLabel("ID Produto:", null, '14px', null, '100%'),$id_produto],[new TLabel("Cob.barras:", null, '14px', null, '100%'),$produto_cod_barras],[new TLabel("Referencia:", null, '14px', null, '100%'),$produto_referencia],[new TLabel("Marca:", null, '14px', null, '100%'),$produto_marca],[new TLabel("SKU:", null, '14px', null, '100%'),$produto_sku]);
        $row1->layout = [' col-sm-6 col-lg-2','col-sm-2',' col-sm-2 col-lg-2',' col-sm-2 col-lg-2',' col-sm-2 col-lg-2',' col-sm-2 col-lg-2'];

        $row2 = $this->form->addFields([new TLabel("Descrição produto:", null, '14px', null, '100%'),$produto_nome],[new TLabel("Categoria:", null, '14px', null, '100%'),$produto_categoria],[new TLabel("Fornecedor:", null, '14px', null, '100%'),$produto_fornecedor]);
        $row2->layout = ['col-sm-6','col-sm-2',' col-sm-2 col-lg-4'];

        $row3 = $this->form->addFields([new TLabel("Selecionar Depósito:", null, '14px', null),$id_deposito]);
        $row3->layout = [' col-sm-3 col-lg-12'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['CategoriaProdutoForm', 'onShow']), 'fas:plus #69aa46');
        $this->btn_onshow = $btn_onshow;

        $btn_onprod = $this->form->addAction("Visualização por produto", new TAction([$this, 'onProd']), 'fas:boxes #4CAF50');
        $this->btn_onprod = $btn_onprod;

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
        $column_produto_id = new TDataGridColumn('produto->id', "Id produto", 'left');
        $column_produto_sku = new TDataGridColumn('produto_sku', "Produto sku", 'left');
        $column_produto_cod_barras = new TDataGridColumn('produto->cod_barras', "Produto cod barras", 'left');
        $column_produto_nome = new TDataGridColumn('produto_nome', "Produto nome", 'left');
        $column_produto_desc_variacao = new TDataGridColumn('produto->desc_variacao', "Produto nome variacao", 'left');
        $column_produto_referencia = new TDataGridColumn('produto->referencia', "Produto referencia", 'left');
        $column_fk_produto_marca_marca = new TDataGridColumn('fk_produto_marca->marca', "Produto marca", 'left');
        $column_produto_fornecedor_nome_fantasia = new TDataGridColumn('produto->fornecedor->nome_fantasia', "Produto fornecedor", 'left');
        $column_produto_categoria_produto_nome = new TDataGridColumn('produto->categoria_produto->nome', "Produto categoria", 'left');
        $column_deposito_nome_deposito = new TDataGridColumn('deposito->nome_deposito', "Id deposito", 'left');
        $column_qtd_min = new TDataGridColumn('qtd_min', "Qtd min", 'center');
        $column_qtd_max = new TDataGridColumn('qtd_max', "Qtd max", 'center');
        $column_quantidade = new TDataGridColumn('quantidade', "Quantidade", 'center');
        $column_curva = new TDataGridColumn('curva', "Curva", 'center');

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_produto_id);
        $this->datagrid->addColumn($column_produto_sku);
        $this->datagrid->addColumn($column_produto_cod_barras);
        $this->datagrid->addColumn($column_produto_nome);
        $this->datagrid->addColumn($column_produto_desc_variacao);
        $this->datagrid->addColumn($column_produto_referencia);
        $this->datagrid->addColumn($column_fk_produto_marca_marca);
        $this->datagrid->addColumn($column_produto_fornecedor_nome_fantasia);
        $this->datagrid->addColumn($column_produto_categoria_produto_nome);
        $this->datagrid->addColumn($column_deposito_nome_deposito);
        $this->datagrid->addColumn($column_qtd_min);
        $this->datagrid->addColumn($column_qtd_max);
        $this->datagrid->addColumn($column_quantidade);
        $this->datagrid->addColumn($column_curva);

        $column_quantidade->setTransformer(array($this,'formatarQuantidade'));
        $column_curva->setTransformer(array($this,'formatarCurva'));

        $action_onEdit = new TDataGridAction(array('ProdutoNForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $action_onEdit->setParameter('key', '{id_produto}');
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
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['ProdEstoqueList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:table #00b894' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['ProdEstoqueList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['ProdEstoqueList', 'onExportXml'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-code #95a5a6' );

        $head_right_actions->add($dropdown_button_exportar);

         if(isset($param['key'])){
            $data = $this->form->getData();
            $data->id_deposito = $param['id'];
            $this->form->setData($data);
            $filters = [];
            TSession::setValue(__CLASS__.'_filter_data', NULL);
            TSession::setValue(__CLASS__.'_filters', NULL);

            if (isset($data->id_deposito) AND ( (is_scalar($data->id_deposito) AND $data->id_deposito !== '') OR (is_array($data->id_deposito) AND (!empty($data->id_deposito)) )) )
            {

                $filters[] = new TFilter('id_deposito', '=', $data->id_deposito);
            }

            $this->form->setData($data);

            // keep the search data in the session
            TSession::setValue(__CLASS__.'_filter_data', $data);
            TSession::setValue(__CLASS__.'_filters', $filters);

            $this->onReload(['offset' => 0, 'first_page' => 1]);
            }

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Estoque","Estoque"]));
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
    public function onProd($param = null) 
    {
        try 
        {
            TApplication::loadPage('EstoqueList', 'onShow');

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

        if (isset($data->id_produto) AND ( (is_scalar($data->id_produto) AND $data->id_produto !== '') OR (is_array($data->id_produto) AND (!empty($data->id_produto)) )) )
        {

            $filters[] = new TFilter('id_produto', '=', $data->id_produto);// create the filter 
        }

        if (isset($data->produto_cod_barras) AND ( (is_scalar($data->produto_cod_barras) AND $data->produto_cod_barras !== '') OR (is_array($data->produto_cod_barras) AND (!empty($data->produto_cod_barras)) )) )
        {

            $filters[] = new TFilter('produto_cod_barras', 'like', "%{$data->produto_cod_barras}%");// create the filter 
        }

        if (isset($data->produto_referencia) AND ( (is_scalar($data->produto_referencia) AND $data->produto_referencia !== '') OR (is_array($data->produto_referencia) AND (!empty($data->produto_referencia)) )) )
        {

            $filters[] = new TFilter('produto_referencia', 'like', "%{$data->produto_referencia}%");// create the filter 
        }

        if (isset($data->produto_marca) AND ( (is_scalar($data->produto_marca) AND $data->produto_marca !== '') OR (is_array($data->produto_marca) AND (!empty($data->produto_marca)) )) )
        {

            $filters[] = new TFilter('produto_marca', '=', $data->produto_marca);// create the filter 
        }

        if (isset($data->produto_sku) AND ( (is_scalar($data->produto_sku) AND $data->produto_sku !== '') OR (is_array($data->produto_sku) AND (!empty($data->produto_sku)) )) )
        {

            $filters[] = new TFilter('produto_sku', 'like', "%{$data->produto_sku}%");// create the filter 
        }

        if (isset($data->produto_nome) AND ( (is_scalar($data->produto_nome) AND $data->produto_nome !== '') OR (is_array($data->produto_nome) AND (!empty($data->produto_nome)) )) )
        {

            $filters[] = new TFilter('produto_nome', 'like', "%{$data->produto_nome}%");// create the filter 
        }

        if (isset($data->produto_categoria) AND ( (is_scalar($data->produto_categoria) AND $data->produto_categoria !== '') OR (is_array($data->produto_categoria) AND (!empty($data->produto_categoria)) )) )
        {

            $filters[] = new TFilter('produto_categoria', '=', $data->produto_categoria);// create the filter 
        }

        if (isset($data->produto_fornecedor) AND ( (is_scalar($data->produto_fornecedor) AND $data->produto_fornecedor !== '') OR (is_array($data->produto_fornecedor) AND (!empty($data->produto_fornecedor)) )) )
        {

            $filters[] = new TFilter('produto_fornecedor', '=', $data->produto_fornecedor);// create the filter 
        }

        if (isset($data->id_deposito) AND ( (is_scalar($data->id_deposito) AND $data->id_deposito !== '') OR (is_array($data->id_deposito) AND (!empty($data->id_deposito)) )) )
        {

            $filters[] = new TFilter('id_deposito', '=', $data->id_deposito);// create the filter 
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

            // creates a repository for ProdEstoque
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

            $data = $this->form->getData();
            echo'<pre>';
            echo $data->data_inicio;
            echo $data->data_fim;
            echo $data->deposito;
            echo'</pre>';

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

public function formatarQuantidade($stock, $object, $row)
 {
     $number = $stock;
     switch($number){
         case ('X'):
             return "<span style='color:orange'>$number</span>";
             break;
         case ($number<0):
             return "<span style='color:red'>$number</span>";
             break;
         case($number>=0):
             return "<span style='color:blue'>$number</span>";
             break;

     }
 }
 public function formatarCurva($stock, $object, $row)
 {
     switch($object->curva){
         case ('A'):
             return "<span style='color:green'>$object</span>";
             break;
         case ('B'):
             return "<span style='color:orange'>$object</span>";
             break;
         case('C'):
             return "<span style='color:red'>$object</span>";
             break;
         default:
             return "<span style='color:red'>X</span>";
     }
 }

}

