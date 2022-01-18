<?php

class EstoqueLojaList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'base_banco';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Produto';
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
        $this->form->setFormTitle("EstoqueLojaList");
        $this->limit = 20;

        $descricao = new TEntry('descricao');
        $referencia = new TEntry('referencia');
        $marca = new TDBCombo('marca', 'base_banco', 'Marca', 'id', '{marca}','marca asc'  );
        $fornecedor_id = new TDBCombo('fornecedor_id', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc'  );
        $SKU = new TEntry('SKU');
        $cod_barras = new TEntry('cod_barras');
        $categoria_produto_id = new TDBCombo('categoria_produto_id', 'base_banco', 'CategoriaProduto', 'id', '{nome}','nome asc'  );
        $loja = new TDBCombo('loja', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );


        $loja->setValue('2');
        $SKU->setSize('100%');
        $loja->setSize('100%');
        $marca->setSize('100%');
        $descricao->setSize('100%');
        $referencia->setSize('100%');
        $cod_barras->setSize('100%');
        $fornecedor_id->setSize('100%');
        $categoria_produto_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Descrição do Produto:", null, '14px', null, '100%'),$descricao],[new TLabel("Referencia:", null, '14px', null, '100%'),$referencia],[new TLabel("Marca:", null, '14px', null, '100%'),$marca],[new TLabel("Fornecedor:", null, '14px', null, '100%'),$fornecedor_id]);
        $row1->layout = ['col-sm-6',' col-sm-2','col-sm-2','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("SKU:", null, '14px', null, '100%'),$SKU],[new TLabel("Cod. Barras:", null, '14px', null, '100%'),$cod_barras],[new TLabel("Categoria:", null, '14px', null, '100%'),$categoria_produto_id],[new TLabel("Depósito da Loja:", null, '15px', 'B', '100%'),$loja]);
        $row2->layout = ['col-sm-3','col-sm-3',' col-sm-4','col-sm-2'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onaction = $this->form->addAction("Limpar busca", new TAction([$this, 'onAction']), 'fas:eraser #ff0000');
        $this->btn_onaction = $btn_onaction;

        $btn_ongoprodutos = $this->form->addAction("Visualização por produto", new TAction([$this, 'onGoProdutos']), 'fas:boxes #0ba145');
        $this->btn_ongoprodutos = $btn_ongoprodutos;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_SKU = new TDataGridColumn('SKU', "SKU", 'left');
        $column_cod_barras = new TDataGridColumn('cod_barras', "Cód. barras", 'left');
        $column_descricao = new TDataGridColumn('descricao', "Descrição", 'left');
        $column_referencia = new TDataGridColumn('referencia', "referencia", 'left');
        $column_fk_marca_marca = new TDataGridColumn('fk_marca->marca', "Marca", 'left');
        $column_fornecedor_razao_social = new TDataGridColumn('fornecedor->razao_social', "Fornecedor", 'left');
        $column_dt_cadastro_transformed = new TDataGridColumn('dt_cadastro', "cadastro em:", 'left');
        $column_categoria_produto_nome = new TDataGridColumn('categoria_produto->nome', "categoria", 'left');
        $column_valor_custo = new TDataGridColumn('valor_custo', "Preço de custo", 'center');
        $column_tabela_preco = new TDataGridColumn('tabela_preco', "Tabela preco 1", 'center');
        $column_preco = new TDataGridColumn('preco', "Tabela preço 2", 'center');
        $column_qtd_min = new TDataGridColumn('qtd_min', "Qtd min", 'center');
        $column_qtd_max = new TDataGridColumn('qtd_max', "Qtd max", 'center');
        $column_estoque = new TDataGridColumn('estoque', "Saldo Estoque", 'center');
        $column_deposito = new TDataGridColumn('deposito', "Total Venda", 'center');

        $column_dt_cadastro_transformed->setTransformer(function($value, $object, $row) 
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

        $this->datagrid->addColumn($column_SKU);
        $this->datagrid->addColumn($column_cod_barras);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_referencia);
        $this->datagrid->addColumn($column_fk_marca_marca);
        $this->datagrid->addColumn($column_fornecedor_razao_social);
        $this->datagrid->addColumn($column_dt_cadastro_transformed);
        $this->datagrid->addColumn($column_categoria_produto_nome);
        $this->datagrid->addColumn($column_valor_custo);
        $this->datagrid->addColumn($column_tabela_preco);
        $this->datagrid->addColumn($column_preco);
        $this->datagrid->addColumn($column_qtd_min);
        $this->datagrid->addColumn($column_qtd_max);
        $this->datagrid->addColumn($column_estoque);
        $this->datagrid->addColumn($column_deposito);

        $action_onEdit = new TDataGridAction(array('CategoriaProdutoForm', 'onEdit'));
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
        $dropdown_button_exportar->addPostAction( "CSV", new TAction(['EstoqueLojaList', 'onExportCsv'],['static' => 1]), 'datagrid_'.self::$formName, 'fas:table #00b894' );
        $dropdown_button_exportar->addPostAction( "PDF", new TAction(['EstoqueLojaList', 'onExportPdf'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );
        $dropdown_button_exportar->addPostAction( "XML", new TAction(['EstoqueLojaList', 'onExportXml'],['static' => 1]), 'datagrid_'.self::$formName, 'far:file-code #95a5a6' );

        $head_right_actions->add($dropdown_button_exportar);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Estoque","EstoqueLojaList"]));
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
    public function onAction($param = null) 
    {
        try 
        {
            //code here

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onGoProdutos($param = null) 
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

        if (isset($data->descricao) AND ( (is_scalar($data->descricao) AND $data->descricao !== '') OR (is_array($data->descricao) AND (!empty($data->descricao)) )) )
        {

            $filters[] = new TFilter('id_familia', '=', $data->descricao);// create the filter 
        }

        if (isset($data->referencia) AND ( (is_scalar($data->referencia) AND $data->referencia !== '') OR (is_array($data->referencia) AND (!empty($data->referencia)) )) )
        {

            $filters[] = new TFilter('referencia', '=', $data->referencia);// create the filter 
        }

        if (isset($data->marca) AND ( (is_scalar($data->marca) AND $data->marca !== '') OR (is_array($data->marca) AND (!empty($data->marca)) )) )
        {

            $filters[] = new TFilter('marca', '=', $data->marca);// create the filter 
        }

        if (isset($data->fornecedor_id) AND ( (is_scalar($data->fornecedor_id) AND $data->fornecedor_id !== '') OR (is_array($data->fornecedor_id) AND (!empty($data->fornecedor_id)) )) )
        {

            $filters[] = new TFilter('fornecedor_id', '=', $data->fornecedor_id);// create the filter 
        }

        if (isset($data->SKU) AND ( (is_scalar($data->SKU) AND $data->SKU !== '') OR (is_array($data->SKU) AND (!empty($data->SKU)) )) )
        {

            $filters[] = new TFilter('SKU', '=', $data->SKU);// create the filter 
        }

        if (isset($data->cod_barras) AND ( (is_scalar($data->cod_barras) AND $data->cod_barras !== '') OR (is_array($data->cod_barras) AND (!empty($data->cod_barras)) )) )
        {

            $filters[] = new TFilter('cod_barras', '=', $data->cod_barras);// create the filter 
        }

        if (isset($data->categoria_produto_id) AND ( (is_scalar($data->categoria_produto_id) AND $data->categoria_produto_id !== '') OR (is_array($data->categoria_produto_id) AND (!empty($data->categoria_produto_id)) )) )
        {

            $filters[] = new TFilter('categoria_produto_id', '=', $data->categoria_produto_id);// create the filter 
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

            // creates a repository for Produto
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

            $valor = TSession::getValue('_filter_data');
            var_dump ($valor);

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    $object->valor_custo    = "-";
                    $object->tabela_preco   = "-";
                    $object->preco          = "-";
                    $object->qtd_min        = "-";
                    $object->qtd_max        = "-";
                    $object->estoque        = "-";

                    $estoques = ProdEstoque::where("id_produto","=",$object->id)->load();    //->where("id_deposito","=",$loja)
                    //if($estoques){
                    //-----
                    //colunas de valores
                    $precos1 = Preco::where('id_produto','=',$object->id)->where('id_tabela','=','1')->load();
                    $precos2 = Preco::where('id_produto','=',$object->id)->where('id_tabela','=','2')->load();
                    if($precos1){
                        $preco1 = $precos1[];
                        $object->valor_custo  = "R$".str_replace(".",",",$preco1->preco_custo);
                        $object->tabela_preco = "R$".str_replace(".",",",$preco1->preco_venda);
                    }
                    if($precos2){
                        $preco2 = $precos2[];
                        $object->preco ="R$".str_replace(".",",",$preco2->preco_venda);
                    }
                    //coluna de estoque
                    $estoque = $estoques[];
                    $object->qtd_min = $estoque->qtd_min;
                    $object->qtd_max = $estoque->qtd_max;
                    $object->estoque = $estoque->quantidade;
                    //vendidos
                    $qtd_vendido = 0 ;
                    TTransaction::open('vendas_base');
                    $vendas_item = VendaItem::where("produto_id","=",$object->id)->load();
                    TTransaction::close();
                    if($vendas_item){
                        foreach($vendas_item as $venda_item){
                            $qtd_vendido += $venda_item->quantidade;
                        }
                    }
                    $object->deposito = $qtd_vendido;

                    $contador++;
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

           //$this->pageNavigation->setCount($contador);

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

        $data = $this->form->getValues();
        if(isset($param['id'])){
            $data->loja = $param['id'];
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

