<?php

class EstoqueList extends TPage
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
        $this->form->setFormTitle("Estoque do produto");
        $this->limit = 100;

        $descricao = new TEntry('descricao');
        $referencia = new TDBUniqueSearch('referencia', 'base_banco', 'Produto', 'id', 'referencia','referencia asc'  );
        $marca = new TDBCombo('marca', 'base_banco', 'Marca', 'id', '{marca}','marca asc'  );
        $fornecedor_id = new TDBCombo('fornecedor_id', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc'  );
        $SKU = new TEntry('SKU');
        $cod_barras = new TEntry('cod_barras');
        $id = new TDBCombo('id', 'base_banco', 'CategoriaProduto', 'id', '{nome}','nome asc'  );
        $depositos = new TCheckList('depositos');
        $periodo_venda = new TRadioGroup('periodo_venda');
        $mes_venda = new TCombo('mes_venda');
        $ano_venda = new TCombo('ano_venda');

        $periodo_venda->setChangeAction(new TAction([$this,'onChangePeriodo']));

        $referencia->setMinLength(2);
        $referencia->setMask('{referencia}');
        $periodo_venda->setLayout('horizontal');

        $mes_venda->setEditable(false);
        $ano_venda->setEditable(false);

        $periodo_venda->addItems(['false'=>'Desabilitado','semana'=>'Semanal','quinzena'=>'Quinzenal','mes'=>'Mensal','outros'=>'Outros meses']);
        $ano_venda->addItems(['2020'=>'2020','2021'=>'2021','2022'=>'2022','2023'=>'2023','2024'=>'2024','2025'=>'2025','2026'=>'2026','2027'=>'2027','2028'=>'2028','2029'=>'2029','2030'=>'2030']);
        $mes_venda->addItems(['1'=>'Janeiro','2'=>'Fevereiro','3'=>'Março','4'=>'Abril','5'=>'Maio','6'=>'Junho','7'=>'Julho','8'=>'Agosto','9'=>'Setembro','10'=>'Outubro','11'=>'Novembro','12'=>'Dezembro']);

        $id->setValue('1');
        $ano_venda->setValue('2021');
        $periodo_venda->setValue('false');
        $mes_venda->setValue('date("m")');

        $id->setSize('100%');
        $SKU->setSize('100%');
        $marca->setSize('100%');
        $mes_venda->setSize('30%');
        $ano_venda->setSize('30%');
        $descricao->setSize('100%');
        $referencia->setSize('100%');
        $cod_barras->setSize('100%');
        $fornecedor_id->setSize('100%');
        $periodo_venda->setSize('100%');

        $depositos->setIdColumn('id');

        $column_depositos_id = $depositos->addColumn('id', "Id", 'left' , '25%');
        $column_depositos_nome_deposito = $depositos->addColumn('nome_deposito', "Nome deposito", 'left' , '25%');

        $depositos->setHeight(250);
        $depositos->makeScrollable();

        $depositos->fillWith('base_banco', 'Deposito', 'id', 'nome_deposito asc' );

        $row1 = $this->form->addFields([new TLabel("Descrição do Produto:", null, '14px', null, '100%'),$descricao],[new TLabel("Referencia:", null, '14px', null, '100%'),$referencia],[new TLabel("Marca:", null, '14px', null, '100%'),$marca],[new TLabel("Fornecedor:", null, '14px', null),$fornecedor_id]);
        $row1->layout = ['col-sm-6',' col-sm-2','col-sm-2','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("SKU:", null, '14px', null, '100%'),$SKU],[new TLabel("Cod. Barras:", null, '14px', null, '100%'),$cod_barras],[new TLabel("Categoria:", null, '14px', null),$id]);
        $row2->layout = ['col-sm-3','col-sm-3','col-sm-6'];

        $row3 = $this->form->addContent([new TFormSeparator("Depositos", '#333', '16', '#FF0091')]);
        $row4 = $this->form->addFields([$depositos],[new TLabel("   ", null, '14px', null, '100%'),new TLabel("Periodo de vendas:", null, '14px', null, '100%'),$periodo_venda,new TLabel("  ", null, '14px', null, '100%'),new TLabel("Defina o período:", null, '14px', null, '100%'),new TLabel("Mês:", null, '14px', null),$mes_venda,new TLabel("Ano:", null, '14px', null),$ano_venda]);
        $row4->layout = [' col-sm-3 col-lg-6',' col-sm-2 col-lg-6'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onlimpar = $this->form->addAction("Limpar busca", new TAction([$this, 'onLimpar']), 'fas:eraser #ff0000');
        $this->btn_onlimpar = $btn_onlimpar;

        $btn_ongoloja = $this->form->addAction("Visualizar por loja", new TAction([$this, 'onGoLoja']), 'fas:warehouse #1a2dd9');
        $this->btn_ongoloja = $btn_ongoloja;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_SKU = new TDataGridColumn('SKU', "SKU / cód. Barras", 'left');
        $column_descricao = new TDataGridColumn('descricao', "Descrição do produto", 'left');
        $column_categoria_produto_nome = new TDataGridColumn('{categoria_produto->nome}', "Categoria", 'left');
        $column_fk_marca_marca = new TDataGridColumn('fk_marca->marca', "Marca", 'left');
        $column_preco = new TDataGridColumn('preco', "Preço C / V", 'left');

        $order_SKU = new TAction(array($this, 'onReload'));
        $order_SKU->setParameter('order', 'SKU');
        $column_SKU->setAction($order_SKU);
        $order_descricao = new TAction(array($this, 'onReload'));
        $order_descricao->setParameter('order', 'descricao');
        $column_descricao->setAction($order_descricao);
        $order_preco = new TAction(array($this, 'onReload'));
        $order_preco->setParameter('order', 'preco');
        $column_preco->setAction($order_preco);

        $this->datagrid->addColumn($column_SKU);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_categoria_produto_nome);
        $this->datagrid->addColumn($column_fk_marca_marca);
        $this->datagrid->addColumn($column_preco);


        if(isset($param['deposito'])){
            $depositos      = $param['deposito'];
            $valor          = TSession::setValue('deposito', $depositos);

            foreach($depositos as $deposito){
                TTransaction::open(self::$database);
                $deposito       = new Deposito($deposito);
                TTransaction::close();
                $nome_deposito  = $deposito->nome_deposito;
                $column         = new TDataGridColumn($nome_deposito,$nome_deposito, 'center');
                $this->datagrid->addColumn($column);
                $column->setTransformer(array($this,'formatarEstoque'));
            }
        }

        // create the datagrid model
        $this->datagrid->createModel();

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';
        $this->datagrid_form->class = ' table-fixed-header ';
        $this->datagrid_form->style = ' height:900px;';

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Estoque","Estoque do produto"]));
        }
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public static function onChangePeriodo($param = null) 
    {
        try 
        {
           $is_venda = $param ['periodo_venda'];
           if($is_venda == "outros"){
                TCombo::enableField(self::$formName, 'mes_venda');
                TCombo::enableField(self::$formName, 'ano_venda');
           }

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
    public function onGoLoja($param = null) 
    {
        try 
        {
            TApplication::loadPage('ProdEstoqueList', 'onShow');

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

            $filters[] = new TFilter('descricao', 'like', "%{$data->descricao}%");// create the filter 
        }

        if (isset($data->referencia) AND ( (is_scalar($data->referencia) AND $data->referencia !== '') OR (is_array($data->referencia) AND (!empty($data->referencia)) )) )
        {

            $filters[] = new TFilter('referencia', 'like', "%{$data->referencia}%");// create the filter 
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

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('categoria_produto_id', '=', $data->id);// create the filter 
        }

        if($data->depositos == null){
            $depositos      = $param['deposito'];
            $valor          = TSession::setValue('deposito', $depositos);
        }
        $is_venda                   = [];
        $is_venda['periodo_venda']  = $data->periodo_venda;
        $is_venda['mes_venda']      = $data->mes_venda;
        $is_venda['ano_venda']      = $data->ano_venda;
        $valor                      = TSession::setValue('is_venda', $is_venda);

        $depositos = $data->depositos;
        $pageParam = ['deposito'=>$depositos]; 
        TApplication::loadPage('EstoqueList', 'onShow', $pageParam);

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

            $depositos      = TSession::getValue('deposito');
            if(!is_array($depositos)){
                $depositos[] =  TSession::getValue('deposito');
            }
            $is_venda       = TSession::getValue('is_venda');
            $periodo_venda  = $is_venda['periodo_venda'];
            $mes_venda      = $is_venda['mes_venda'];
            $ano_venda      = $is_venda['ano_venda'];

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    //ESTOQUES--------------------
                    if($depositos[] != null){

                        foreach($depositos as $deposito){
                            $deposito_       = new Deposito($deposito);
                            $contador_venda  = 0;
                            $estoques        = ProdEstoque::where('id_produto','=',$object->id) 
                                                          ->where('id_deposito','=',$deposito_->id)
                                                          ->load();
                            if($estoques){
                                $estoque                = $estoques[];
                                $nome_deposito          = $deposito_->nome_deposito;
                                $object->$nome_deposito = $estoque->quantidade;
                                if($periodo_venda!= "" && $periodo_venda!= false ){
                                    switch($periodo_venda){
                                        case 'semana':
                                            TTransaction::open('compilador');
                                            $produtosdiario = Produtodiario::where('produto_id','=',$object->id)
                                                                           ->where('deposito_id','=',$deposito_->id)
                                                                           ->load();
                                            if($produtosdiario){
                                                $produtoDiario          = $produtosdiario[];
                                                $object->$nome_deposito = $object->$nome_deposito."/".$produtoDiario->semanal;
                                            }
                                            TTransaction::close();
                                            break;
                                        case 'quinzena':
                                            TTransaction::open('compilador');
                                            $produtosdiario = Produtodiario::where('produto_id','=',$object->id)
                                                                           ->where('deposito_id','=',$deposito_->id)
                                                                           ->load();
                                            if($produtosdiario){
                                                $produtoDiario          = $produtosdiario[];
                                                $object->$nome_deposito = $object->$nome_deposito."/".$produtoDiario->quinzena;
                                            }
                                            TTransaction::close();
                                            break;
                                        case 'mes':
                                            TTransaction::open('compilador');
                                            $produtosdiario = Produtodiario::where('produto_id','=',$object->id)
                                                                           ->where('deposito_id','=',$deposito_->id)
                                                                           ->load();
                                            if($produtosdiario){
                                                $produtoDiario          = $produtosdiario[];
                                                $object->$nome_deposito = $object->$nome_deposito."/".$produtoDiario->mensal;
                                            }
                                            TTransaction::close();
                                            break;
                                        case 'outros':
                                            TTransaction::open('compilador');
                                            $produtosdiario = Produtomensal::where('produto_id','=',$object->id)
                                                                           ->where('deposito_id','=',$deposito_->id)
                                                                           ->where('mes','=',$mes_venda)
                                                                           ->where('ano','=',$ano_venda)
                                                                           ->load();
                                            if($produtosdiario){
                                                $produtoDiario          = $produtosdiario[];
                                                $object->$nome_deposito = $object->$nome_deposito."/".$produtoDiario->quantidade;
                                            }
                                            TTransaction::close();
                                            break;
                                    }
                                }else{
                                    echo "NO";
                                }
                            }else{
                                $nome_deposito          = $deposito_->nome_deposito;
                                $object->$nome_deposito = 'X';
                            }
                        }
                    }

                 if($object->cod_barras != "" ){
                    $object->SKU = $object->cod_barras;
                }

                //PRECOS-----------------------
                $object->descricao = "$object->descricao $object->desc_variacao";
                $precos = Preco::where('id_produto','=',$object->id)->load();
                if($precos){
                    $preco = $precos[];
                    $object->preco = "R$$preco->preco_custo/ <br>R$$preco->preco_venda";
                    if(isset($precos[1])){
                        $preco = $precos[1];
                        $object->preco = "-T1-$object->preco<br>-T2-R$$preco->preco_custo/ <br>R$$preco->preco_venda";
                    }
                    }

                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

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

public function formatarEstoque($stock, $object, $row)
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

}

