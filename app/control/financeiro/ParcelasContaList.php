<?php

class ParcelasContaList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'base_banco';
    private static $activeRecord = 'ParcelasConta';
    private static $primaryKey = 'id';
    private static $formName = 'formList_ParcelasConta';
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
        $this->form->setFormTitle("Visualização por parcela");
        $this->limit = 20;

        $id = new TEntry('id');
        $id_parcela_mestre = new TEntry('id_parcela_mestre');
        $fk_conta_origem_natureza_id = new TDBCombo('fk_conta_origem_natureza_id', 'base_banco', 'Natureza', 'id', '{id}','nome asc'  );
        $conta_bancaria_loja = new TDBCombo('conta_bancaria_loja', 'base_banco', 'ContaBancaria', 'id', '{id}','id asc'  );
        $tipo_parcela = new TEntry('tipo_parcela');
        $vencimento = new TDate('vencimento');
        $conta_origem = new TDBCombo('conta_origem', 'base_banco', 'Conta', 'id', '{id}','id asc'  );
        $quitada = new TRadioGroup('quitada');
        $loja_id = new TDBCombo('loja_id', 'base_banco', 'Loja', 'id', '{razao_social}','razao_social asc'  );
        $fornecedor_id = new TDBUniqueSearch('fornecedor_id', 'base_banco', 'Fornecedor', 'id', 'nome_fantasia','nome_fantasia asc'  );
        $vencimento_ate = new TDate('vencimento_ate');
        $valor = new TNumeric('valor', '2', ',', '.' );
        $forma_pagamento = new TCombo('forma_pagamento');


        $tipo_parcela->setMaxLength(30);
        $quitada->setLayout('horizontal');
        $fornecedor_id->setMinLength(2);

        $vencimento->setDatabaseMask('yyyy-mm-dd');
        $vencimento_ate->setDatabaseMask('yyyy-mm-dd');

        $quitada->addItems(['t'=>'SIM','f'=>'NÃO']);
        $forma_pagamento->addItems(['deposito'=>' Depósito','transferencia'=>' Transferência','cheque'=>' Cheque','dinheiro'=>'Dinheiro','boleto'=>' Boleto']);

        $vencimento->setMask('dd/mm/yyyy');
        $vencimento_ate->setMask('dd/mm/yyyy');
        $fornecedor_id->setMask('{id}  {nome_fantasia}  {razao_social}  {cnpj} ');

        $id->setSize(100);
        $valor->setSize('100%');
        $quitada->setSize('100%');
        $loja_id->setSize('100%');
        $vencimento->setSize('100%');
        $tipo_parcela->setSize('100%');
        $conta_origem->setSize('100%');
        $fornecedor_id->setSize('100%');
        $vencimento_ate->setSize('100%');
        $forma_pagamento->setSize('100%');
        $id_parcela_mestre->setSize('100%');
        $conta_bancaria_loja->setSize('100%');
        $fk_conta_origem_natureza_id->setSize('100%');

        $this->form->appendPage("Pesquisa");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("ID Parcela:", null, '14px', null, '100%'),$id_parcela_mestre],[new TLabel("Natureza:", null, '14px', null, '100%'),$fk_conta_origem_natureza_id],[new TLabel("Conta bancaria:", null, '14px', null, '100%'),$conta_bancaria_loja],[new TLabel("Tipo parcela:", null, '14px', null, '100%'),$tipo_parcela],[new TLabel("Vencimento(de):", null, '14px', null, '100%'),$vencimento],[new TLabel("Conta origem:", null, '14px', null, '100%'),$conta_origem],[new TLabel("Quitada:", null, '14px', null, '100%'),$quitada]);
        $row1->layout = [' col-sm-1',' col-sm-1',' col-sm-2',' col-sm-1',' col-sm-1','col-sm-2','col-sm-2','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Loja:", null, '14px', null, '100%'),$loja_id],[new TLabel("Fornecedor:", null, '14px', null, '100%'),$fornecedor_id],[new TLabel("Vencimento(até):", null, '14px', null, '100%'),$vencimento_ate],[new TLabel("Valor:", null, '14px', null, '100%'),$valor],[new TLabel("Forma pagamento:", null, '14px', null, '100%'),$forma_pagamento]);
        $row2->layout = [' col-sm-3',' col-sm-3','col-sm-2','col-sm-2','col-sm-2'];

        $this->form->appendPage("Totalizadores");
        $row3 = $this->form->addFields([new TLabel("Rótulo:", null, '14px', null)],[]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onclean = $this->form->addAction("Limpar pesquisa", new TAction([$this, 'onClean']), 'fas:eraser #f80000');
        $this->btn_onclean = $btn_onclean;

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['ContaaPagarForm', 'onShow']), 'fas:plus #69aa46');
        $this->btn_onshow = $btn_onshow;

        $btn_onaction = $this->form->addAction("Gerar PDF", new TAction([$this, 'onAction']), 'fas:file-pdf #ff0000');
        $this->btn_onaction = $btn_onaction;

        $btn_onconta = $this->form->addAction("visualizar por Conta", new TAction([$this, 'onConta']), 'fas:eye #ffffff');
        $this->btn_onconta = $btn_onconta;
        $btn_onconta->addStyleClass('btn-success'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_conta_origem = new TDataGridColumn('conta_origem', "Conta", 'left');
        $column_loja_nome_fantasia = new TDataGridColumn('loja->nome_fantasia', "Loja", 'left');
        $column_fornecedor_nome_fantasia = new TDataGridColumn('fornecedor->nome_fantasia', "Fornecedor", 'left');
        $column_forma_pagamento_fk_conta_bancaria_loja_nome = new TDataGridColumn('{forma_pagamento}  {fk_conta_bancaria_loja->nome}', "Forma pagamento", 'left');
        $column_valor_transformed = new TDataGridColumn('valor', "Valor", 'left');
        $column_vencimento_transformed = new TDataGridColumn('vencimento', "Vencimento", 'left');
        $column_obs = new TDataGridColumn('obs', "Obs", 'left');
        $column_quitada_transformed = new TDataGridColumn('quitada', "Quitada", 'left');

        $column_valor_transformed->setTransformer(function($value, $object, $row) 
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_vencimento_transformed->setTransformer(function($value, $object, $row) 
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

        $column_quitada_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value === true || $value == 't' || $value === 1 || $value == '1' || $value == 's' || $value == 'S' || $value == 'T')
                return 'Sim';

            return 'Não';

        });        

        $column_quitada_transformed->setTransformer(array($this,'formatarQuitacao'));

        $this->datagrid->addColumn($column_conta_origem);
        $this->datagrid->addColumn($column_loja_nome_fantasia);
        $this->datagrid->addColumn($column_fornecedor_nome_fantasia);
        $this->datagrid->addColumn($column_forma_pagamento_fk_conta_bancaria_loja_nome);
        $this->datagrid->addColumn($column_valor_transformed);
        $this->datagrid->addColumn($column_vencimento_transformed);
        $this->datagrid->addColumn($column_obs);
        $this->datagrid->addColumn($column_quitada_transformed);

        $action_onEdit = new TDataGridAction(array('ParcelasContaForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Gerenciar parcelas e subparcelas");
        $action_onEdit->setImage('fas:clone #2ecc71');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('ParcelasContaList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        $action_onShow = new TDataGridAction(array('ContaaPagarForm', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Visualizar conta");
        $action_onShow->setImage('fas:file-alt #5a27fe');
        $action_onShow->setField(self::$primaryKey);

        $action_onShow->setParameter('conta_id', '{conta_origem}');
        $this->datagrid->addAction($action_onShow);

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
            $container->add(TBreadCrumb::create(["Financeiro","Visualização por parcela"]));
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
                $object = new ParcelasConta($key, FALSE); 

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
    public function onClean($param = null) 
    {
        try 
        {
            $this->form->clear();
            $this->onSearch();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
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
    public function onConta($param = null) 
    {
        try 
        {
            TApplication::loadPage('ContaAPagarAdminList', 'onShow');

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

        if (isset($data->id_parcela_mestre) AND ( (is_scalar($data->id_parcela_mestre) AND $data->id_parcela_mestre !== '') OR (is_array($data->id_parcela_mestre) AND (!empty($data->id_parcela_mestre)) )) )
        {

            $filters[] = new TFilter('id_parcela_mestre', '=', $data->id_parcela_mestre);// create the filter 
        }

        if (isset($data->conta_bancaria_loja) AND ( (is_scalar($data->conta_bancaria_loja) AND $data->conta_bancaria_loja !== '') OR (is_array($data->conta_bancaria_loja) AND (!empty($data->conta_bancaria_loja)) )) )
        {

            $filters[] = new TFilter('conta_bancaria_loja', '=', $data->conta_bancaria_loja);// create the filter 
        }

        if (isset($data->tipo_parcela) AND ( (is_scalar($data->tipo_parcela) AND $data->tipo_parcela !== '') OR (is_array($data->tipo_parcela) AND (!empty($data->tipo_parcela)) )) )
        {

            $filters[] = new TFilter('tipo_parcela', 'like', "%{$data->tipo_parcela}%");// create the filter 
        }

        if (isset($data->vencimento) AND ( (is_scalar($data->vencimento) AND $data->vencimento !== '') OR (is_array($data->vencimento) AND (!empty($data->vencimento)) )) )
        {

            $filters[] = new TFilter('vencimento', '>=', $data->vencimento);// create the filter 
        }

        if (isset($data->conta_origem) AND ( (is_scalar($data->conta_origem) AND $data->conta_origem !== '') OR (is_array($data->conta_origem) AND (!empty($data->conta_origem)) )) )
        {

            $filters[] = new TFilter('conta_origem', '=', $data->conta_origem);// create the filter 
        }

        if (isset($data->quitada) AND ( (is_scalar($data->quitada) AND $data->quitada !== '') OR (is_array($data->quitada) AND (!empty($data->quitada)) )) )
        {

            $filters[] = new TFilter('quitada', '=', $data->quitada);// create the filter 
        }

        if (isset($data->loja_id) AND ( (is_scalar($data->loja_id) AND $data->loja_id !== '') OR (is_array($data->loja_id) AND (!empty($data->loja_id)) )) )
        {

            $filters[] = new TFilter('loja_id', '=', $data->loja_id);// create the filter 
        }

        if (isset($data->fornecedor_id) AND ( (is_scalar($data->fornecedor_id) AND $data->fornecedor_id !== '') OR (is_array($data->fornecedor_id) AND (!empty($data->fornecedor_id)) )) )
        {

            $filters[] = new TFilter('fornecedor_id', '=', $data->fornecedor_id);// create the filter 
        }

        if (isset($data->vencimento_ate) AND ( (is_scalar($data->vencimento_ate) AND $data->vencimento_ate !== '') OR (is_array($data->vencimento_ate) AND (!empty($data->vencimento_ate)) )) )
        {

            $filters[] = new TFilter('vencimento', '<=', $data->vencimento_ate);// create the filter 
        }

        if (isset($data->valor) AND ( (is_scalar($data->valor) AND $data->valor !== '') OR (is_array($data->valor) AND (!empty($data->valor)) )) )
        {

            $filters[] = new TFilter('valor', '=', $data->valor);// create the filter 
        }

        if (isset($data->forma_pagamento) AND ( (is_scalar($data->forma_pagamento) AND $data->forma_pagamento !== '') OR (is_array($data->forma_pagamento) AND (!empty($data->forma_pagamento)) )) )
        {

            $filters[] = new TFilter('forma_pagamento', 'like', "%{$data->forma_pagamento}%");// create the filter 
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

            // creates a repository for ParcelasConta
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

public function formatarQuitacao($stock, $object, $row)
 {
     if ($stock){
         return "<span style='color:green'>Sim</span>";
     }else{
         return "<span style='color:red'>Não</span>";
     }
 }

}

