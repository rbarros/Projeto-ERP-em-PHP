<?php

class VendaItemTipoProdutoChart extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'base_banco';
    private static $activeRecord = 'VendaItemAlt';
    private static $primaryKey = 'id';
    private static $formName = 'formChart_VendaItemAlt';

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
        $this->form->setFormTitle("Gráfico de vendas por tipo de produto");

        $produto_categoria_produto_id = new TDBCombo('produto_categoria_produto_id', 'base_banco', 'CategoriaProduto', 'id', '{nome}','nome asc'  );

        $produto_categoria_produto_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Tipo de produto:", null, '14px', null)],[$produto_categoria_produto_id],[],[]);

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

        if (isset($data->produto_categoria_produto_id) AND ( (is_scalar($data->produto_categoria_produto_id) AND $data->produto_categoria_produto_id !== '') OR (is_array($data->produto_categoria_produto_id) AND (!empty($data->produto_categoria_produto_id)) )) )
        {

            $filters[] = new TFilter('produto_id', 'in', "(SELECT id FROM produto WHERE categoria_produto_id = '{$data->produto_categoria_produto_id}')");// create the filter 
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
            // creates a repository for VendaItemAlt
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $filterVar = TSession::getValue("userunitid");
            $criteria->add(new TFilter('venda_id', 'in', "(SELECT id FROM venda_alt WHERE system_unit_id = '{$filterVar}')"));

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
                    $group1 = $obj->produto->categoria_produto->nome;

                    $groups[$group1] = true;
                    $numericField = $obj->evaluate('=( ( {valor_unitario} - {valor_desconto} ) * {quantidade}  )');

                    $dataTotals[$group1]['count'] = isset($dataTotals[$group1]['count']) ? $dataTotals[$group1]['count'] + 1 : 1;
                    $dataTotals[$group1]['sum'] = isset($dataTotals[$group1]['sum']) ? $dataTotals[$group1]['sum'] + $numericField  : $numericField;

                }

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
                    'prefix' => '',
                    'sufix' => '',
                    'width' => 100,
                    'widthType' => '%',
                    'title' => 'Total vendido por tipo de produto',
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

