<?php

class OrcamentoAdminChart extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'base_banco';
    private static $activeRecord = 'Orcamento';
    private static $primaryKey = 'id';
    private static $formName = 'formChart_Orcamento';

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
        $this->form->setFormTitle("Gráfico de orçamentos por dia");

        $criteria_cliente_id = new TCriteria();
        $criteria_vendedor_id = new TCriteria();

        $filterVar = Grupo::clientes;
        $criteria_cliente_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 
        $filterVar = Grupo::vendedores;
        $criteria_vendedor_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 

        $id = new TDBSelect('id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $estado_orcamento_id = new TDBCombo('estado_orcamento_id', 'base_banco', 'EstadoOrcamento', 'id', '{nome}','nome asc'  );
        $cliente_id = new TDBUniqueSearch('cliente_id', 'base_banco', 'Pessoa', 'id', 'nome','nome asc' , $criteria_cliente_id );
        $vendedor_id = new TDBCombo('vendedor_id', 'base_banco', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $dt_orcamento = new TDate('dt_orcamento');
        $dt_orcamento_final = new TDate('dt_orcamento_final');

        $cliente_id->setMinLength(2);
        $vendedor_id->enableSearch();

        $dt_orcamento->setDatabaseMask('yyyy-mm-dd');
        $dt_orcamento_final->setDatabaseMask('yyyy-mm-dd');

        $cliente_id->setMask('{nome}');
        $dt_orcamento->setMask('dd/mm/yyyy');
        $dt_orcamento_final->setMask('dd/mm/yyyy');

        $id->setSize('100%', 70);
        $dt_orcamento->setSize(100);
        $cliente_id->setSize('100%');
        $vendedor_id->setSize('100%');
        $dt_orcamento_final->setSize(100);
        $estado_orcamento_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Empresa:", null, '14px', null)],[$id],[new TLabel("Estado do orçamento:", null, '14px', null)],[$estado_orcamento_id]);
        $row2 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null)],[$cliente_id],[new TLabel("Vendedor:", null, '14px', null)],[$vendedor_id]);
        $row3 = $this->form->addFields([new TLabel("Data inicial:", null, '14px', null)],[$dt_orcamento],[new TLabel("Data final:", null, '14px', null)],[$dt_orcamento_final]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongenerate = $this->form->addAction("Gerar", new TAction([$this, 'onGenerate']), 'fas:search #777777');
        $this->btn_ongenerate = $btn_ongenerate;

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

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('system_unit_id', 'in', $data->id);// create the filter 
        }
        if (isset($data->estado_orcamento_id) AND ( (is_scalar($data->estado_orcamento_id) AND $data->estado_orcamento_id !== '') OR (is_array($data->estado_orcamento_id) AND (!empty($data->estado_orcamento_id)) )) )
        {

            $filters[] = new TFilter('estado_orcamento_id', '=', $data->estado_orcamento_id);// create the filter 
        }
        if (isset($data->cliente_id) AND ( (is_scalar($data->cliente_id) AND $data->cliente_id !== '') OR (is_array($data->cliente_id) AND (!empty($data->cliente_id)) )) )
        {

            $filters[] = new TFilter('cliente_id', '=', $data->cliente_id);// create the filter 
        }
        if (isset($data->vendedor_id) AND ( (is_scalar($data->vendedor_id) AND $data->vendedor_id !== '') OR (is_array($data->vendedor_id) AND (!empty($data->vendedor_id)) )) )
        {

            $filters[] = new TFilter('vendedor_id', '=', $data->vendedor_id);// create the filter 
        }
        if (isset($data->dt_orcamento) AND ( (is_scalar($data->dt_orcamento) AND $data->dt_orcamento !== '') OR (is_array($data->dt_orcamento) AND (!empty($data->dt_orcamento)) )) )
        {

            $filters[] = new TFilter('dt_orcamento', '>=', $data->dt_orcamento);// create the filter 
        }
        if (isset($data->dt_orcamento_final) AND ( (is_scalar($data->dt_orcamento_final) AND $data->dt_orcamento_final !== '') OR (is_array($data->dt_orcamento_final) AND (!empty($data->dt_orcamento_final)) )) )
        {

            $filters[] = new TFilter('dt_orcamento', '<=', $data->dt_orcamento_final);// create the filter 
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
            // creates a repository for Orcamento
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $filterVar = TSession::getValue("userunitids");
            $criteria->add(new TFilter('system_unit_id', 'in', $filterVar));

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
                $data = [];
                $groups = [];
                foreach ($objects as $obj)
                {
                    $group1 = $obj->estado_orcamento->nome;
                    $group2 = $obj->dt_orcamento;

                    $group2 = TDateTime::convertToMask($group2, 'yyyy-mm-dd', 'd/m/Y');

                    $groups[$group2] = true;
                    $numericField = $obj->valor_total;

                    $dataTotals[$group1][$group2]['count'] = isset($dataTotals[$group1][$group2]['count']) ? $dataTotals[$group1][$group2]['count'] + 1 : 1;
                    $dataTotals[$group1][$group2]['sum'] = isset($dataTotals[$group1][$group2]['sum']) ? $dataTotals[$group1][$group2]['sum'] + $numericField  : $numericField;

                }

                $groups = ['x'=>true]+$groups;
                $data = [array_keys($groups)];
                $line = array_fill(0, count($groups), NULL);

                foreach ($dataTotals as $group1 => $group1Totals) 
                {

                    $lineData = $line;

                    $lineData[0] = $group1;
                    foreach ($group1Totals as $group2 => $totals) 
                    {
                        $posi = array_search($group2, array_keys($groups));

                        $lineData[$posi] = $totals['sum'];

                    }
                    $data[] = $lineData;
                }

                $chart = new THtmlRenderer('app/resources/c3_bar_chart.html');
                $chart->enableSection('main', [
                    'data'=> json_encode($data),
                    'height' => 300,
                    'precision' => 2,
                    'decimalSeparator' => ',',
                    'thousandSeparator' => '.',
                    'prefix' => '',
                    'sufix' => '',
                    'width' => 100,
                    'widthType' => '%',
                    'title' => 'Orçamentos por dia/estado',
                    'showLegend' => 'true',
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

