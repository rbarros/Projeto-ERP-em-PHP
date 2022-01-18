<?php

class VendaPorMesAdminChart extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'base_banco';
    private static $activeRecord = 'VendaAlt';
    private static $primaryKey = 'id';
    private static $formName = 'formChart_VendaAlt';

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
        $this->form->setFormTitle("Gráfico de vendas por mês");

        $criteria_cliente_id = new TCriteria();
        $criteria_vendedor_id = new TCriteria();

        $filterVar = Grupo::clientes;
        $criteria_cliente_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 
        $filterVar = Grupo::vendedores;
        $criteria_vendedor_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 

        $system_unit_id = new TDBSelect('system_unit_id', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $estado_venda_id = new TDBCombo('estado_venda_id', 'base_banco', 'EstadoVenda', 'id', '{nome}','nome asc'  );
        $cliente_id = new TDBCombo('cliente_id', 'base_banco', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_cliente_id );
        $vendedor_id = new TDBCombo('vendedor_id', 'base_banco', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $tipo_produto_id = new TDBCombo('tipo_produto_id', 'base_banco', 'CategoriaProduto', 'id', '{nome}','nome asc'  );
        $produto_id = new TCombo('produto_id');
        $dt_venda = new TDate('dt_venda');
        $data_final = new TDate('data_final');

        $tipo_produto_id->setChangeAction(new TAction([$this,'onChangetipo_produto_id']));

        $dt_venda->setDatabaseMask('yyyy-mm-dd');
        $data_final->setDatabaseMask('yyyy-mm-dd');

        $dt_venda->setMask('dd/mm/yyyy');
        $data_final->setMask('dd/mm/yyyy');

        $dt_venda->setSize(100);
        $data_final->setSize(100);
        $cliente_id->setSize('100%');
        $produto_id->setSize('100%');
        $vendedor_id->setSize('100%');
        $estado_venda_id->setSize('100%');
        $tipo_produto_id->setSize('100%');
        $system_unit_id->setSize('100%', 70);

        $row1 = $this->form->addFields([new TLabel("Empresa:", null, '14px', null)],[$system_unit_id],[new TLabel("Estado da venda:", null, '14px', null)],[$estado_venda_id]);
        $row2 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null)],[$cliente_id],[new TLabel("Vendedor:", null, '14px', null)],[$vendedor_id]);
        $row3 = $this->form->addFields([new TLabel("Tipo de produto:", null, '14px', null)],[$tipo_produto_id],[new TLabel("Produto:", null, '14px', null)],[$produto_id]);
        $row4 = $this->form->addFields([new TLabel("Data inicial (venda)", null, '14px', null)],[$dt_venda],[new TLabel("Data final (venda)", null, '14px', null)],[$data_final]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        $this->fireEvents( TSession::getValue(__CLASS__.'_filter_data') );

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

    public static function onChangetipo_produto_id($param)
    {
        try
        {

            if (isset($param['tipo_produto_id']) && $param['tipo_produto_id'])
            { 
                $criteria = TCriteria::create(['categoria_produto_id' => $param['tipo_produto_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'produto_id', 'base_banco', 'Produto', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'produto_id'); 
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
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->system_unit_id) AND ( (is_scalar($data->system_unit_id) AND $data->system_unit_id !== '') OR (is_array($data->system_unit_id) AND (!empty($data->system_unit_id)) )) )
        {

            $filters[] = new TFilter('system_unit_id', 'in', $data->system_unit_id);// create the filter 
        }
        if (isset($data->estado_venda_id) AND ( (is_scalar($data->estado_venda_id) AND $data->estado_venda_id !== '') OR (is_array($data->estado_venda_id) AND (!empty($data->estado_venda_id)) )) )
        {

            $filters[] = new TFilter('estado_venda_id', '=', $data->estado_venda_id);// create the filter 
        }
        if (isset($data->cliente_id) AND ( (is_scalar($data->cliente_id) AND $data->cliente_id !== '') OR (is_array($data->cliente_id) AND (!empty($data->cliente_id)) )) )
        {

            $filters[] = new TFilter('cliente_id', '=', $data->cliente_id);// create the filter 
        }
        if (isset($data->vendedor_id) AND ( (is_scalar($data->vendedor_id) AND $data->vendedor_id !== '') OR (is_array($data->vendedor_id) AND (!empty($data->vendedor_id)) )) )
        {

            $filters[] = new TFilter('vendedor_id', '=', $data->vendedor_id);// create the filter 
        }
        if (isset($data->tipo_produto_id) AND ( (is_scalar($data->tipo_produto_id) AND $data->tipo_produto_id !== '') OR (is_array($data->tipo_produto_id) AND (!empty($data->tipo_produto_id)) )) )
        {

            $filters[] = new TFilter('id', 'in', "(SELECT venda_id FROM venda_item_alt WHERE produto_id in  (SELECT id FROM produto WHERE categoria_produto_id = '{$data->tipo_produto_id}') )");// create the filter 
        }
        if (isset($data->produto_id) AND ( (is_scalar($data->produto_id) AND $data->produto_id !== '') OR (is_array($data->produto_id) AND (!empty($data->produto_id)) )) )
        {

            $filters[] = new TFilter('id', 'in', "(SELECT venda_id FROM venda_item_alt WHERE produto_id in  (SELECT id FROM produto WHERE id = '{$data->produto_id}') )");// create the filter 
        }
        if (isset($data->dt_venda) AND ( (is_scalar($data->dt_venda) AND $data->dt_venda !== '') OR (is_array($data->dt_venda) AND (!empty($data->dt_venda)) )) )
        {

            $filters[] = new TFilter('dt_venda', '>=', $data->dt_venda);// create the filter 
        }
        if (isset($data->data_final) AND ( (is_scalar($data->data_final) AND $data->data_final !== '') OR (is_array($data->data_final) AND (!empty($data->data_final)) )) )
        {

            $filters[] = new TFilter('dt_venda', '<=', $data->data_final);// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);
        $this->fireEvents($data);

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
            // creates a repository for VendaAlt
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

            $monthNames = ['01'=> '01'._t('January'), '02'=>'02'._t('February'), '03'=>'03'._t('March'), '04'=>'04'._t('April'), '05'=>'05'._t('May'), '06'=>'06'._t('June'), '07'=>'07'._t('July'), '08'=>'08'._t('August'), '09'=>'09'._t('September'), '10'=>'10'._t('October'), '11'=>'11'._t('November'), '12'=>'12'._t('December')];

            if ($objects)
            {

                $dataTotals = [];
                $groups = [];
                foreach ($objects as $obj)
                {
                    $group1 = $obj->dt_venda;
                    $group1 = $monthNames[TDateTime::convertToMask($group1, 'yyyy-mm-dd', 'm')];

                    $groups[$group1] = true;
                    $numericField = $obj->valor_total;

                    $dataTotals[$group1]['count'] = isset($dataTotals[$group1]['count']) ? $dataTotals[$group1]['count'] + 1 : 1;
                    $dataTotals[$group1]['sum'] = isset($dataTotals[$group1]['sum']) ? $dataTotals[$group1]['sum'] + $numericField  : $numericField;

                }

                ksort($dataTotals);
                ksort($groups);

                $groups = ['x'=>true]+$groups;
                $tempGroups = ['x'=>true];
                unset($groups['x']);
                foreach($groups as $key=>$value)
                {
                    $tempGroups[substr($key, 2)] = true;
                }
                $groups = $tempGroups;
                $data = [array_keys($groups)];
                $lineData = [_t('Value')];

                foreach ($dataTotals as $group1 => $totals) 
                {    
                    $lineData[] = $totals['sum'];

                }
                $data[] = $lineData;

                $chart = new THtmlRenderer('app/resources/c3_bar_chart.html');
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
                    'title' => 'Vendas por mês',
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

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->tipo_produto_id))
            {
                $value = $object->tipo_produto_id;

                $obj->tipo_produto_id = $value;
            }
            if(isset($object->produto_id))
            {
                $value = $object->produto_id;

                $obj->produto_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->tipo_produto_id))
            {
                $value = $object->tipo_produto_id;

                $obj->tipo_produto_id = $value;
            }
            if(isset($object->produto_id))
            {
                $value = $object->produto_id;

                $obj->produto_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

    public function onShow($param = null)
    {

    }

}

