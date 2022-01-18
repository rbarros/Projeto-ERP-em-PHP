<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TransferenciaEtqList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'base_banco';
    private static $activeRecord = 'TransferenciaEtq';
    private static $primaryKey = 'id';
    private static $formName = 'formList_TransferenciaEtq';
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
        $this->form->setFormTitle("Transferências de estoque");
        $this->limit = 30;

        $estoque_id = new TDBUniqueSearch('estoque_id', 'base_banco', 'ProdEstoque', 'id', 'id','id asc'  );
        $estoque_produto_SKU = new TDBCombo('estoque_produto_SKU', 'base_banco', 'Produto', 'id', '{descricao} {desc_variacao} {referencia} {SKU} ','descricao asc'  );
        $dt_registro = new TDateTime('dt_registro');
        $dt_registro_ate = new TDateTime('dt_registro_ate');
        $id = new TEntry('id');
        $id_ate = new TEntry('id_ate');
        $usuario = new TDBCombo('usuario', 'permission', 'SystemUsers', 'id', '{name}','name asc'  );
        $deposito_env = new TDBCombo('deposito_env', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $deposito_rec = new TDBCombo('deposito_rec', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );


        $estoque_id->setMinLength(2);

        $dt_registro->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_registro_ate->setDatabaseMask('yyyy-mm-dd hh:ii');

        $dt_registro->setMask('dd/mm/yyyy hh:ii');
        $dt_registro_ate->setMask('dd/mm/yyyy hh:ii');
        $estoque_id->setMask('{produto_sku}  {produto_nome} ');

        $id->setSize('100%');
        $id_ate->setSize('100%');
        $usuario->setSize('100%');
        $estoque_id->setSize('100%');
        $dt_registro->setSize('100%');
        $deposito_env->setSize('100%');
        $deposito_rec->setSize('100%');
        $dt_registro_ate->setSize('100%');
        $estoque_produto_SKU->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Estoque do produto:", null, '14px', null, '100%'),$estoque_id],[new TLabel("Produto descrição:", null, '14px', null, '100%'),$estoque_produto_SKU],[],[new TLabel("Data(a partir):", null, '14px', null, '100%'),$dt_registro],[new TLabel("Data (Até):", null, '14px', null, '100%'),$dt_registro_ate]);
        $row1->layout = [' col-sm-7 col-lg-4','col-sm-2 col-lg-3',' col-sm-2 col-lg-1','col-sm-2',' col-sm-3 col-lg-2'];

        $row2 = $this->form->addFields([new TLabel("Nº (de):", null, '14px', null, '100%'),$id],[new TLabel("Nº (até):", null, '14px', null, '100%'),$id_ate],[new TLabel("Usuário:", null, '14px', null, '100%'),$usuario],[],[new TLabel("Deposito origem:", null, '14px', null, '100%'),$deposito_env],[new TLabel("Deposito destino:", null, '14px', null, '100%'),$deposito_rec]);
        $row2->layout = ['col-sm-2','col-sm-2','col-sm-3',' col-sm-2 col-lg-1','col-sm-2',' col-sm-3 col-lg-2'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onlimpar = $this->form->addAction("Limpar Busca", new TAction([$this, 'onLimpar']), 'fas:eraser #f80000');
        $this->btn_onlimpar = $btn_onlimpar;

        $btn_onshow = $this->form->addAction("Transferir produto", new TAction(['TransferenciaEtqForm', 'onShow']), 'fas:exchange-alt #2005ff');
        $this->btn_onshow = $btn_onshow;

        $btn_onexcel = $this->form->addAction("Gerar Excel", new TAction([$this, 'onExcel']), 'fas:file-excel #28a205');
        $this->btn_onexcel = $btn_onexcel;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "Nº", 'center' , '46px');
        $column_fk_deposito_env_nome_deposito = new TDataGridColumn('fk_deposito_env->nome_deposito', "Deposito origem", 'left');
        $column_deposito_rec = new TDataGridColumn('deposito_rec', "Deposito destino", 'left');
        $column_produto_SKU = new TDataGridColumn('produto->SKU', "SKU", 'left');
        $column_desc_produto = new TDataGridColumn('desc_produto', "Produto", 'left');
        $column_quantidade = new TDataGridColumn('quantidade', "Quantidade", 'left');
        $column_dt_registro_transformed = new TDataGridColumn('dt_registro', "Data", 'left');
        $column_usuario = new TDataGridColumn('usuario', "Realizado por:", 'left');
        $column_tipo_transferencia = new TDataGridColumn('tipo_transferencia', "Tipo", 'left');

        $column_dt_registro_transformed->setTransformer(function($value, $object, $row)
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

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_fk_deposito_env_nome_deposito);
        $this->datagrid->addColumn($column_deposito_rec);
        $this->datagrid->addColumn($column_produto_SKU);
        $this->datagrid->addColumn($column_desc_produto);
        $this->datagrid->addColumn($column_quantidade);
        $this->datagrid->addColumn($column_dt_registro_transformed);
        $this->datagrid->addColumn($column_usuario);
        $this->datagrid->addColumn($column_tipo_transferencia);


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
            $container->add(TBreadCrumb::create(["Estoque","Transferências de estoque"]));
        }
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

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
    public function onExcel($param = null) 
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

            $transferencias = $repository->load($criteria); // load the objects according to criteria
            if ($transferencias)
            {
            //gera um sheet
            $spreadsheet        = new Spreadsheet();
            $sheet              = $spreadsheet->getActiveSheet();
            $colunas_letra      = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
            $coluna_indice      = 0;
            $linha              = 1;
            //seta valores para o sheet
            //cabeçalho--
            $sheet->setCellValue($colunas_letra[].$linha,'ID');
            $sheet->setCellValue($colunas_letra[1].$linha,'DEPOSITO DESTINO ID');
            $sheet->setCellValue($colunas_letra[2].$linha,'DEPOSITO DESTINO NOME');
            $sheet->setCellValue($colunas_letra[3].$linha,'DEPOSITO ORIGEM ID');
            $sheet->setCellValue($colunas_letra[4].$linha,'DEPOSITO ORIGEM NOME');
            $sheet->setCellValue($colunas_letra[5].$linha,'ESTOQUE');
            $sheet->setCellValue($colunas_letra[6].$linha,'PRODUTO ESTOQUE NOME');
            $sheet->setCellValue($colunas_letra[7].$linha,'QUANTIDADE');
            $sheet->setCellValue($colunas_letra[8].$linha,'PREÇO VENDA TABELA 1');
            $sheet->setCellValue($colunas_letra[9].$linha,'PREÇO VENDA TABELA 2');
            $sheet->setCellValue($colunas_letra[10].$linha,'DATA');
            $sheet->setCellValue($colunas_letra[11].$linha,'USUARIO ID');
            $sheet->setCellValue($colunas_letra[12].$linha,'USUARIO NOME');
            $sheet->setCellValue($colunas_letra[12].$linha,'ID PRODUTO');
            $sheet->setCellValue($colunas_letra[13].$linha,'TIPO');
            //dados da planilha--

            foreach($transferencias as $transferencia){
                $linha++;
                $coluna_indice=0;
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$transferencia->id);
                $coluna_indice++;
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$transferencia->deposito_env);
                $coluna_indice++;
                $deposito = new Deposito($transferencia->deposito_env);
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$deposito->nome_deposito);
                $coluna_indice++;
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$transferencia->deposito_rec);
                $coluna_indice++;
                $deposito = new Deposito($transferencia->deposito_rec);
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$deposito->nome_deposito);
                $coluna_indice++;
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$transferencia->deposito_rec);
                $coluna_indice++;
                $estoque = new ProdEstoque($transferencia->estoque_id);
                $produto = new Produto($estoque->id_produto);
                $SKU;
                if(isset($produto->cod_barras)){
                    $SKU = $produto->cod_barras;
                }else{
                    $SKU = $produto->SKU;
                }
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,"$SKU - $produto->descricao $produto->desc_variacao - $produto->referencia");
                $coluna_indice++;
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$transferencia->quantidade);
                $coluna_indice++;
                $preco1 = Preco::where('id_produto','=',$produto->id)->where('id_tabela','=',1)->load();
                $preco2 = Preco::where('id_produto','=',$produto->id)->where('id_tabela','=',2)->load();
                if($preco1){
                    $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$preco1[]->preco_venda);
                    $coluna_indice++;
                }else{
                    $coluna_indice++;
                }
                if($preco2){
                    $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$preco2[]->preco_venda);
                    $coluna_indice++;
                }else{
                    $coluna_indice++;
                }
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$transferencia->dt_registro);
                $coluna_indice++;
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$transferencia->usuario);
                $coluna_indice++;
                TTransaction::open('permission');
                $usuario = new SystemUsers($transferencia->usuario);
                TTransaction::close();
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$usuario->name);
                $coluna_indice++;
                $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$transferencia->id_produto);
                $coluna_indice++;
                if($transferencia->tipo_transferencia != 'entrada'){
                    $transferencia->tipo_transferencia  = "transferencia";
                    $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$transferencia->tipo_transferencia);
                    $coluna_indice++;
                }else{
                    $sheet->setCellValue($colunas_letra[$coluna_indice].$linha,$transferencia->tipo_transferencia);
                    $coluna_indice++;
                }

            }
            TTransaction::close();
            //salva e abre o arquivo
            $writer = new Xlsx($spreadsheet);
            $writer->save('tmp/Transferências de estoque.xlsx');
            TPage::openfile('tmp/Transferências de estoque.xlsx'); 
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

        if (isset($data->estoque_id) AND ( (is_scalar($data->estoque_id) AND $data->estoque_id !== '') OR (is_array($data->estoque_id) AND (!empty($data->estoque_id)) )) )
        {

            $filters[] = new TFilter('estoque_id', '=', $data->estoque_id);// create the filter 
        }

        if (isset($data->estoque_produto_SKU) AND ( (is_scalar($data->estoque_produto_SKU) AND $data->estoque_produto_SKU !== '') OR (is_array($data->estoque_produto_SKU) AND (!empty($data->estoque_produto_SKU)) )) )
        {

            $filters[] = new TFilter('id_produto', '=', $data->estoque_produto_SKU);// create the filter 
        }

        if (isset($data->dt_registro) AND ( (is_scalar($data->dt_registro) AND $data->dt_registro !== '') OR (is_array($data->dt_registro) AND (!empty($data->dt_registro)) )) )
        {

            $filters[] = new TFilter('dt_registro', '>=', $data->dt_registro);// create the filter 
        }

        if (isset($data->dt_registro_ate) AND ( (is_scalar($data->dt_registro_ate) AND $data->dt_registro_ate !== '') OR (is_array($data->dt_registro_ate) AND (!empty($data->dt_registro_ate)) )) )
        {

            $filters[] = new TFilter('dt_registro', '<=', $data->dt_registro_ate);// create the filter 
        }

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '>=', $data->id);// create the filter 
        }

        if (isset($data->id_ate) AND ( (is_scalar($data->id_ate) AND $data->id_ate !== '') OR (is_array($data->id_ate) AND (!empty($data->id_ate)) )) )
        {

            $filters[] = new TFilter('id', '<=', $data->id_ate);// create the filter 
        }

        if (isset($data->usuario) AND ( (is_scalar($data->usuario) AND $data->usuario !== '') OR (is_array($data->usuario) AND (!empty($data->usuario)) )) )
        {

            $filters[] = new TFilter('usuario', '=', $data->usuario);// create the filter 
        }

        if (isset($data->deposito_env) AND ( (is_scalar($data->deposito_env) AND $data->deposito_env !== '') OR (is_array($data->deposito_env) AND (!empty($data->deposito_env)) )) )
        {

            $filters[] = new TFilter('deposito_env', '=', $data->deposito_env);// create the filter 
        }

        if (isset($data->deposito_rec) AND ( (is_scalar($data->deposito_rec) AND $data->deposito_rec !== '') OR (is_array($data->deposito_rec) AND (!empty($data->deposito_rec)) )) )
        {

            $filters[] = new TFilter('deposito_rec', '=', $data->deposito_rec);// create the filter 
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

            // creates a repository for TransferenciaEtq
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

                    $deposito_origem        = new Deposito($object->deposito_rec);
                    $object->deposito_rec   = $deposito_origem->nome_deposito;
                    TTransaction::open ('permission');
                    $usuario                = new SystemUsers($object->usuario);
                    $object->usuario        = $usuario->name;
                    TTransaction::close();
                    $produto                = new Produto($object->id_produto);
                    $object->desc_produto   = $produto->descricao." ".$produto->desc_variacao." ".$produto->referencia;
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

