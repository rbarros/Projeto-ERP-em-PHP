<?php

use Automattic\WooCommerce\Client;

class ProdutoLojaList extends TPage
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
        $this->form->setFormTitle("Produtos");
        $this->limit = 50;

        $id = new TEntry('id');
        $descricao = new TEntry('descricao');
        $cod_barras = new TEntry('cod_barras');
        $SKU = new TEntry('SKU');
        $mestre_variavel = new TCombo('mestre_variavel');
        $id_familia = new TEntry('id_familia');
        $dt_cadastro = new TDate('dt_cadastro');
        $dt_venda_final = new TDate('dt_venda_final');
        $categoria_produto_id = new TDBCombo('categoria_produto_id', 'base_banco', 'CategoriaProduto', 'id', '{nome}','nome asc'  );
        $tipo_cadastro = new TDBCombo('tipo_cadastro', 'base_banco', 'TipoCadastroProd', 'id', '{descricao}','descricao asc'  );
        $tabela_preco = new TDBCombo('tabela_preco', 'base_banco', 'TabelaPreco', 'id', '{nome_tabela_preco}','nome_tabela_preco asc'  );
        $sit_tribut = new TCombo('sit_tribut');
        $origem = new TCombo('origem');
        $marca = new TDBCombo('marca', 'base_banco', 'Marca', 'id', '{marca}','marca asc'  );
        $fornecedor_id = new TDBCombo('fornecedor_id', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc'  );
        $referencia = new TEntry('referencia');
        $id_externo = new TEntry('id_externo');


        $descricao->setMaxLength(40);
        $mestre_variavel->setValue('mestre');

        $dt_cadastro->setMask('dd/mm/yyyy');
        $dt_venda_final->setMask('dd/mm/yyyy');

        $dt_cadastro->setDatabaseMask('yyyy-mm-dd');
        $dt_venda_final->setDatabaseMask('yyyy-mm-dd');

        $mestre_variavel->addItems(['1'=>' mestre','2'=>' variação']);
        $sit_tribut->addItems(['1'=>'Substituição Tributaria 1','2'=>'Substituição Tributaria 2','3'=>'Substituição Tributaria 3','4'=>'Isento 1','5'=>'Isento 2','6'=>'Isento 3','7'=>'Não Tributado 1','8'=>'Não Tributado 2','9'=>'Não Tributado 3','10'=>'Isento ISSQN','11'=>'Tributada pelo ISSQN','12'=>'Tributada pelo ICMS']);
        $origem->addItems(['1'=>' 1 - Nacional, exceto as indicadas 3, 4, 5 e 8','2'=>' 2 - Estrangeira - importação direta, exceto indicada no código 6','3'=>' 3 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7','4'=>' 4 - Nacional, mercadoria ou bem de consumo com conteúdo de importação superior a 40% e inferior ou igual a 70%','5'=>' 5 - nacional, cujo produção tenha sido feita em conformidade  com os processos produtivos básicos de que se tratam  as legislações citadas nos  Ajustes','6'=>' 6 - Nacional, mercadoria ou bem com conteúdo de importação inferior ou igual a 40%','7'=>' 7 - Estrangeira - Importação direta ou similar nacional, constante em lista CAMEX','8'=>' 8 - Estrangeira - adquirida no mercado interno, sem  similar nacional , constante em lista da CAMEX','9'=>' 9 - Nacional, mercadoria ou  bem com conteúdo de importação superior a 70%']);

        $id->setSize('100%');
        $SKU->setSize('100%');
        $marca->setSize('100%');
        $origem->setSize('100%');
        $dt_cadastro->setSize(110);
        $descricao->setSize('100%');
        $referencia->setSize('100%');
        $sit_tribut->setSize('100%');
        $id_externo->setSize('100%');
        $cod_barras->setSize('100%');
        $id_familia->setSize('100%');
        $tabela_preco->setSize('100%');
        $tipo_cadastro->setSize('100%');
        $fornecedor_id->setSize('100%');
        $dt_venda_final->setSize('100%');
        $mestre_variavel->setSize('100%');
        $categoria_produto_id->setSize('100%');

        $this->form->appendPage("Busca");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Descrição:", null, '14px', null, '100%'),$descricao],[new TLabel("Cod. barras:", null, '14px', null),$cod_barras],[new TLabel("SKU:", null, '14px', null, '100%'),$SKU],[new TLabel("Mestre variação:", null, '14px', null, '100%'),$mestre_variavel]);
        $row1->layout = [' col-sm-1',' col-sm-5','col-sm-2',' col-sm-2',' col-sm-2'];

        $this->form->appendPage("Detalhada");
        $row2 = $this->form->addFields([new TLabel("Id familia:", null, '14px', null, '100%'),$id_familia],[new TLabel("Registro (inicio):", null, '14px', null, '100%'),$dt_cadastro],[new TLabel("Registro (FIM):", null, '14px', null),$dt_venda_final],[new TLabel("Categoria:", null, '14px', null, '100%'),$categoria_produto_id],[new TLabel("Tipo cadastro:", null, '14px', null, '100%'),$tipo_cadastro],[new TLabel("Tabela preco:", null, '14px', null, '100%'),$tabela_preco]);
        $row2->layout = [' col-sm-2',' col-sm-2','col-sm-2','col-sm-2',' col-sm-2','col-sm-2'];

        $row3 = $this->form->addFields([new TLabel("Situação Tributária", null, '14px', null),$sit_tribut],[new TLabel("Origem:", null, '14px', null, '100%'),$origem],[new TLabel("Marca:", null, '14px', null, '100%'),$marca]);
        $row3->layout = [' col-sm-6',' col-sm-3',' col-sm-3'];

        $row4 = $this->form->addFields([new TLabel("Fornecedor:", null, '14px', null, '100%'),$fornecedor_id],[new TLabel("Referencia do fornecedor:", null, '14px', null, '100%'),$referencia],[new TLabel("Id externo:", null, '14px', null),$id_externo]);
        $row4->layout = [' col-sm-6',' col-sm-4','col-sm-2'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onlimparbusca = $this->form->addAction("Limpar Busca", new TAction([$this, 'onLimparBusca']), 'fas:eraser #ff0000');
        $this->btn_onlimparbusca = $btn_onlimparbusca;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_SKU = new TDataGridColumn('SKU', "SKU / cód. barras", 'left');
        $column_descricao_desc_variacao = new TDataGridColumn('{descricao} {desc_variacao}', "Descrição", 'left' , '30%');
        $column_fk_marca_marca = new TDataGridColumn('fk_marca->marca', "Marca", 'left');
        $column_mestre_variavel = new TDataGridColumn('mestre_variavel', "Variação", 'left');
        $column_preco = new TDataGridColumn('preco', "Preço", 'left' , '20%');
        $column_dt_cadastro_transformed = new TDataGridColumn('dt_cadastro', "Data", 'left');

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
        $this->datagrid->addColumn($column_descricao_desc_variacao);
        $this->datagrid->addColumn($column_fk_marca_marca);
        $this->datagrid->addColumn($column_mestre_variavel);
        $this->datagrid->addColumn($column_preco);
        $this->datagrid->addColumn($column_dt_cadastro_transformed);

        $action_onEdit = new TDataGridAction(array('ConfEtiquProdutoForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Nova tela etiquetas");
        $action_onEdit->setImage('fas:barcode #000000');
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
            $container->add(TBreadCrumb::create(["Loja","Produto"]));
        }
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public function onLimparBusca($param = null) 
    {
        try 
        {
            $this->form->clear();
            $this->onReload();

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

        if (isset($data->descricao) AND ( (is_scalar($data->descricao) AND $data->descricao !== '') OR (is_array($data->descricao) AND (!empty($data->descricao)) )) )
        {

            $filters[] = new TFilter('descricao', 'like', "%{$data->descricao}%");// create the filter 
        }

        if (isset($data->cod_barras) AND ( (is_scalar($data->cod_barras) AND $data->cod_barras !== '') OR (is_array($data->cod_barras) AND (!empty($data->cod_barras)) )) )
        {

            $filters[] = new TFilter('cod_barras', '=', $data->cod_barras);// create the filter 
        }

        if (isset($data->SKU) AND ( (is_scalar($data->SKU) AND $data->SKU !== '') OR (is_array($data->SKU) AND (!empty($data->SKU)) )) )
        {

            $filters[] = new TFilter('SKU', 'like', "%{$data->SKU}%");// create the filter 
        }

        if (isset($data->mestre_variavel) AND ( (is_scalar($data->mestre_variavel) AND $data->mestre_variavel !== '') OR (is_array($data->mestre_variavel) AND (!empty($data->mestre_variavel)) )) )
        {

            $filters[] = new TFilter('mestre_variavel', 'like', "%{$data->mestre_variavel}%");// create the filter 
        }

        if (isset($data->id_familia) AND ( (is_scalar($data->id_familia) AND $data->id_familia !== '') OR (is_array($data->id_familia) AND (!empty($data->id_familia)) )) )
        {

            $filters[] = new TFilter('id_familia', '=', $data->id_familia);// create the filter 
        }

        if (isset($data->dt_cadastro) AND ( (is_scalar($data->dt_cadastro) AND $data->dt_cadastro !== '') OR (is_array($data->dt_cadastro) AND (!empty($data->dt_cadastro)) )) )
        {

            $filters[] = new TFilter('dt_cadastro', '>=', $data->dt_cadastro);// create the filter 
        }

        if (isset($data->dt_venda_final) AND ( (is_scalar($data->dt_venda_final) AND $data->dt_venda_final !== '') OR (is_array($data->dt_venda_final) AND (!empty($data->dt_venda_final)) )) )
        {

            $filters[] = new TFilter('dt_cadastro', '<=', $data->dt_venda_final);// create the filter 
        }

        if (isset($data->categoria_produto_id) AND ( (is_scalar($data->categoria_produto_id) AND $data->categoria_produto_id !== '') OR (is_array($data->categoria_produto_id) AND (!empty($data->categoria_produto_id)) )) )
        {

            $filters[] = new TFilter('categoria_produto_id', '=', $data->categoria_produto_id);// create the filter 
        }

        if (isset($data->tipo_cadastro) AND ( (is_scalar($data->tipo_cadastro) AND $data->tipo_cadastro !== '') OR (is_array($data->tipo_cadastro) AND (!empty($data->tipo_cadastro)) )) )
        {

            $filters[] = new TFilter('tipo_cadastro', '=', $data->tipo_cadastro);// create the filter 
        }

        if (isset($data->tabela_preco) AND ( (is_scalar($data->tabela_preco) AND $data->tabela_preco !== '') OR (is_array($data->tabela_preco) AND (!empty($data->tabela_preco)) )) )
        {

            $filters[] = new TFilter('tabela_preco', '=', $data->tabela_preco);// create the filter 
        }

        if (isset($data->sit_tribut) AND ( (is_scalar($data->sit_tribut) AND $data->sit_tribut !== '') OR (is_array($data->sit_tribut) AND (!empty($data->sit_tribut)) )) )
        {

            $filters[] = new TFilter('sit_tribut', '=', $data->sit_tribut);// create the filter 
        }

        if (isset($data->origem) AND ( (is_scalar($data->origem) AND $data->origem !== '') OR (is_array($data->origem) AND (!empty($data->origem)) )) )
        {

            $filters[] = new TFilter('tabela_preco', '=', $data->origem);// create the filter 
        }

        if (isset($data->marca) AND ( (is_scalar($data->marca) AND $data->marca !== '') OR (is_array($data->marca) AND (!empty($data->marca)) )) )
        {

            $filters[] = new TFilter('marca', 'like', "%{$data->marca}%");// create the filter 
        }

        if (isset($data->fornecedor_id) AND ( (is_scalar($data->fornecedor_id) AND $data->fornecedor_id !== '') OR (is_array($data->fornecedor_id) AND (!empty($data->fornecedor_id)) )) )
        {

            $filters[] = new TFilter('fornecedor_id', '=', $data->fornecedor_id);// create the filter 
        }

        if (isset($data->referencia) AND ( (is_scalar($data->referencia) AND $data->referencia !== '') OR (is_array($data->referencia) AND (!empty($data->referencia)) )) )
        {

            $filters[] = new TFilter('referencia', 'like', "%{$data->referencia}%");// create the filter 
        }

        if (isset($data->id_externo) AND ( (is_scalar($data->id_externo) AND $data->id_externo !== '') OR (is_array($data->id_externo) AND (!empty($data->id_externo)) )) )
        {

            $filters[] = new TFilter('id_externo', '=', $data->id_externo);// create the filter 
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

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                     TTransaction::open(self::$database);
                    $precos = Preco::where('id_produto','=',$object->id)->load();
                    $object->preco = "";
                      if($precos){
                        foreach($precos as $preco){
                            $preco->preco_venda = str_replace('.',',',$preco->preco_venda);
                            $object->preco .= "Tabela-$preco->id_tabela-<b>R$ {$preco->preco_venda}</b><br/>";
                        }
                      }
                    if($object->id_familia){
                        $mestre_variavel = $object->mestre_variavel == '1' ? "mestre":"variação";
                        $object->mestre_variavel = $mestre_variavel.'/'.$object->id_familia;
                    }else{
                        $object->mestre_variavel = 'mestre';
                    }
                    if($object->cod_barras){
                        $object->SKU = $object->SKU.' / '.$object->cod_barras;
                    }
                    if($object->referencia){
                        $object->descricao = $object->descricao.' - '.$object->referencia;
                    }
                    TTransaction::close();
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

        function onAction1($param)
        {
        new TMessage('info',
                "Primeira opção. Valor do parâmetro: {$param['parameter']}");
        }
        function onAction2($param)
        {
        new TMessage('info',
            "Segunda opção. Valor do parâmetro: {$param['parameter']}");
        }

}

