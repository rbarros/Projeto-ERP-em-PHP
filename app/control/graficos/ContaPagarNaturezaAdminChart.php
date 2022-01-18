<?php

class ContaPagarNaturezaAdminChart extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'base_banco';
    private static $activeRecord = 'Conta';
    private static $primaryKey = 'id';
    private static $formName = 'formChart_Conta';

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Gráfico de contas a pagar por natureza");

        $criteria_fornecedor = new TCriteria();

        $filterVar = Grupo::clientes;
        $criteria_fornecedor->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 

        $fornecedor = new TDBUniqueSearch('fornecedor', 'base_banco', 'Pessoa', 'id', 'nome','nome asc' , $criteria_fornecedor );
        $natureza_id = new TDBCombo('natureza_id', 'base_banco', 'Natureza', 'id', '{nome}','nome asc'  );
        $dt_emissao = new TDate('dt_emissao');
        $dt_emissao_final = new TDate('dt_emissao_final');
        $dt_vencimento = new TDate('dt_vencimento');
        $dt_vencimento_final = new TDate('dt_vencimento_final');
        $quitada = new TCombo('quitada');
        $loja = new TDBSelect('loja', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );

        $fornecedor->setMinLength(2);
        $quitada->addItems(['t'=>'Sim','f'=>'Não']);

        $dt_emissao->setDatabaseMask('yyyy-mm-dd');
        $dt_vencimento->setDatabaseMask('yyyy-mm-dd');
        $dt_emissao_final->setDatabaseMask('yyyy-mm-dd');
        $dt_vencimento_final->setDatabaseMask('yyyy-mm-dd');

        $fornecedor->setMask('{nome}');
        $dt_emissao->setMask('dd/mm/yyyy');
        $dt_vencimento->setMask('dd/mm/yyyy');
        $dt_emissao_final->setMask('dd/mm/yyyy');
        $dt_vencimento_final->setMask('dd/mm/yyyy');

        $quitada->setSize('100%');
        $dt_emissao->setSize(100);
        $loja->setSize('100%', 100);
        $fornecedor->setSize('100%');
        $dt_vencimento->setSize(100);
        $natureza_id->setSize('100%');
        $dt_emissao_final->setSize(100);
        $dt_vencimento_final->setSize(100);

        $row1 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null)],[$fornecedor],[new TLabel("Natureza:", null, '14px', null)],[$natureza_id]);
        $row2 = $this->form->addFields([new TLabel("Data de emissão inicial:", null, '14px', null)],[$dt_emissao],[new TLabel("Data de emissão final:", null, '14px', null)],[$dt_emissao_final]);
        $row3 = $this->form->addFields([new TLabel("Data de vencimento inicial:", null, '14px', null)],[$dt_vencimento],[new TLabel("Data de vencimento fiinal:", null, '14px', null)],[$dt_vencimento_final]);
        $row4 = $this->form->addFields([new TLabel("Quitada:", null, '14px', null)],[$quitada],[new TLabel("Empresa:", null, '14px', null)],[$loja]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongenerate = $this->form->addAction("Gerar", new TAction([$this, 'onGenerate']), 'fas:search #ffffff');
        $this->btn_ongenerate = $btn_ongenerate;
        $btn_ongenerate->addStyleClass('btn-primary'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($this->form);

        parent::add($container);

    }

    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->fornecedor) AND ( (is_scalar($data->fornecedor) AND $data->fornecedor !== '') OR (is_array($data->fornecedor) AND (!empty($data->fornecedor)) )) )
        {

            $filters[] = new TFilter('fornecedor', '=', $data->fornecedor);// create the filter 
        }
        if (isset($data->natureza_id) AND ( (is_scalar($data->natureza_id) AND $data->natureza_id !== '') OR (is_array($data->natureza_id) AND (!empty($data->natureza_id)) )) )
        {

            $filters[] = new TFilter('natureza_id', '=', $data->natureza_id);// create the filter 
        }
        if (isset($data->dt_emissao) AND ( (is_scalar($data->dt_emissao) AND $data->dt_emissao !== '') OR (is_array($data->dt_emissao) AND (!empty($data->dt_emissao)) )) )
        {

            $filters[] = new TFilter('dt_emissao', '>=', $data->dt_emissao);// create the filter 
        }
        if (isset($data->dt_emissao_final) AND ( (is_scalar($data->dt_emissao_final) AND $data->dt_emissao_final !== '') OR (is_array($data->dt_emissao_final) AND (!empty($data->dt_emissao_final)) )) )
        {

            $filters[] = new TFilter('dt_emissao', '<=', $data->dt_emissao_final);// create the filter 
        }
        if (isset($data->dt_vencimento) AND ( (is_scalar($data->dt_vencimento) AND $data->dt_vencimento !== '') OR (is_array($data->dt_vencimento) AND (!empty($data->dt_vencimento)) )) )
        {

            $filters[] = new TFilter('dt_vencimento', '>=', $data->dt_vencimento);// create the filter 
        }
        if (isset($data->dt_vencimento_final) AND ( (is_scalar($data->dt_vencimento_final) AND $data->dt_vencimento_final !== '') OR (is_array($data->dt_vencimento_final) AND (!empty($data->dt_vencimento_final)) )) )
        {

            $filters[] = new TFilter('dt_vencimento', '<=', $data->dt_vencimento_final);// create the filter 
        }
        if (isset($data->quitada) AND ( (is_scalar($data->quitada) AND $data->quitada !== '') OR (is_array($data->quitada) AND (!empty($data->quitada)) )) )
        {

            $filters[] = new TFilter('quitada', '=', $data->quitada);// create the filter 
        }
        if (isset($data->loja) AND ( (is_scalar($data->loja) AND $data->loja !== '') OR (is_array($data->loja) AND (!empty($data->loja)) )) )
        {

            $filters[] = new TFilter('loja', 'in', $data->loja);// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);
    }

    /**
     * Load the datagrid with data
     */
    public function onGenerate()
    {
        try
        {
            $this->onSearch();
            // open a transaction with database 'base_banco'
            TTransaction::open(self::$database);
            $param = [];
            // creates a repository for Conta
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $filterVar = TipoConta::pagar;
            $criteria->add(new TFilter('tipo_conta_id', '=', $filterVar));
            $filterVar = TSession::getValue("userunitids");
            $criteria->add(new TFilter('loja', 'in', $filterVar));

            if ($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            if ($objects)
            {

                $dataTotals = [];
                $groups = [];
                $data = [];
                foreach ($objects as $obj)
                {
                    $group1 = $obj->natureza->nome;

                    $groups[$group1] = true;
                    $numericField = $obj->valor;

                    $dataTotals[$group1]['count'] = isset($dataTotals[$group1]['count']) ? $dataTotals[$group1]['count'] + 1 : 1;
                    $dataTotals[$group1]['sum'] = isset($dataTotals[$group1]['sum']) ? $dataTotals[$group1]['sum'] + $numericField  : $numericField;

                }

                ksort($dataTotals);
                ksort($groups);

                $groups = ['x'=>true]+$groups;

                foreach ($dataTotals as $group1 => $totals) 
                {    

                    array_push($data, [$group1, $totals['sum']]);

                }

                $chart = new THtmlRenderer('app/resources/c3_pizza_chart.html');
                $chart->enableSection('main', [
                    'data'=> json_encode($data),
                    'height' => 300,
                    'precision' => 2,
                    'decimalSeparator' => ',',
                    'thousandSeparator' => '.',
                    'prefix' => 'R$ ',
                    'sufix' => '',
                    'width' => 100,
                    'widthType' => '%',
                    'title' => 'Contas a pagar por natureza',
                    'showLegend' => 'false',
                    'showPercentage' => 'false',
                    'barDirection' => 'false'
                ]);

                parent::add($chart);
            }
            else
            {
                new TMessage('error', _t('No records found'));
            }

            // close the transaction
            TTransaction::close();
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

}

