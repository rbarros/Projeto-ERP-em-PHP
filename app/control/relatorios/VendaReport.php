<?php

class VendaReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'base_banco';
    private static $activeRecord = 'VendaAlt';
    private static $primaryKey = 'id';
    private static $formName = 'formReport_VendaAlt';

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Relatório de vendas");

        $criteria_cliente_id = new TCriteria();
        $criteria_vendedor_id = new TCriteria();

        $filterVar = Grupo::clientes;
        $criteria_cliente_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 
        $filterVar = Grupo::vendedores;
        $criteria_vendedor_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 

        $id = new TEntry('id');
        $estado_venda_id = new TDBCombo('estado_venda_id', 'base_banco', 'EstadoVenda', 'id', '{nome}','nome asc'  );
        $cliente_id = new TDBUniqueSearch('cliente_id', 'base_banco', 'Pessoa', 'id', 'nome','nome asc' , $criteria_cliente_id );
        $vendedor_id = new TDBCombo('vendedor_id', 'base_banco', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $tipo_produto = new TDBCombo('tipo_produto', 'base_banco', 'CategoriaProduto', 'id', '{nome}','nome asc'  );
        $produto = new TCombo('produto');
        $dt_venda = new TDate('dt_venda');
        $dt_venda_final = new TDate('dt_venda_final');

        $tipo_produto->setChangeAction(new TAction([$this,'onChangetipo_produto']));

        $cliente_id->setMinLength(2);

        $dt_venda->setDatabaseMask('yyyy-mm-dd');
        $dt_venda_final->setDatabaseMask('yyyy-mm-dd');

        $cliente_id->setMask('{nome}');
        $dt_venda->setMask('dd/mm/yyyy');
        $dt_venda_final->setMask('dd/mm/yyyy');

        $id->setSize(100);
        $dt_venda->setSize(100);
        $produto->setSize('100%');
        $cliente_id->setSize('100%');
        $vendedor_id->setSize('100%');
        $tipo_produto->setSize('97%');
        $dt_venda_final->setSize(100);
        $estado_venda_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[new TLabel("Estado da venda:", null, '14px', null)],[$estado_venda_id]);
        $row2 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null)],[$cliente_id],[new TLabel("Vendedor:", null, '14px', null)],[$vendedor_id]);
        $row3 = $this->form->addFields([new TLabel("Tipo de produto:", null, '14px', null)],[$tipo_produto],[new TLabel("Produto:", null, '14px', null)],[$produto]);
        $row4 = $this->form->addFields([new TLabel("Data da venda (de):", null, '14px', null)],[$dt_venda],[new TLabel("Data da venda (até): ", null, '14px', null)],[$dt_venda_final]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        $this->fireEvents( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongeneratehtml = $this->form->addAction("Gerar HTML", new TAction([$this, 'onGenerateHtml']), 'fas:code #ffffff');
        $this->btn_ongeneratehtml = $btn_ongeneratehtml;
        $btn_ongeneratehtml->addStyleClass('btn-primary'); 

        $btn_ongeneratepdf = $this->form->addAction("Gerar PDF", new TAction([$this, 'onGeneratePdf']), 'far:file-pdf #d44734');
        $this->btn_ongeneratepdf = $btn_ongeneratepdf;

        $btn_ongeneratertf = $this->form->addAction("Gerar RTF", new TAction([$this, 'onGenerateRtf']), 'far:file-alt #324bcc');
        $this->btn_ongeneratertf = $btn_ongeneratertf;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangetipo_produto($param)
    {
        try
        {

            if (isset($param['tipo_produto']) && $param['tipo_produto'])
            { 
                $criteria = TCriteria::create(['categoria_produto_id' => $param['tipo_produto']]);
                TDBCombo::reloadFromModel(self::$formName, 'produto', 'base_banco', 'Produto', 'id', '{descricao}', 'descricao asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'produto'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public function onGenerateHtml($param = null) 
    {
        $this->onGenerate('html');
    }

    public function onGeneratePdf($param = null) 
    {
        $this->onGenerate('pdf');
    }

    public function onGenerateRtf($param = null) 
    {
        $this->onGenerate('rtf');
    }

    /**
     * Register the filter in the session
     */
    public function getFilters()
    {
        // get the search form data
        $data = $this->form->getData();

        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
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
        if (isset($data->tipo_produto) AND ( (is_scalar($data->tipo_produto) AND $data->tipo_produto !== '') OR (is_array($data->tipo_produto) AND (!empty($data->tipo_produto)) )) )
        {

            $filters[] = new TFilter('id', 'in', "(SELECT venda_id FROM venda_item_alt WHERE produto_id in  (SELECT id FROM produto WHERE categoria_produto_id = '{$data->tipo_produto}') )");// create the filter 
        }
        if (isset($data->produto) AND ( (is_scalar($data->produto) AND $data->produto !== '') OR (is_array($data->produto) AND (!empty($data->produto)) )) )
        {

            $filters[] = new TFilter('id', 'in', "(SELECT venda_id FROM venda_item_alt WHERE produto_id in  (SELECT id FROM produto WHERE id = '{$data->produto}') )");// create the filter 
        }
        if (isset($data->dt_venda) AND ( (is_scalar($data->dt_venda) AND $data->dt_venda !== '') OR (is_array($data->dt_venda) AND (!empty($data->dt_venda)) )) )
        {

            $filters[] = new TFilter('dt_venda', '>=', $data->dt_venda);// create the filter 
        }
        if (isset($data->dt_venda_final) AND ( (is_scalar($data->dt_venda_final) AND $data->dt_venda_final !== '') OR (is_array($data->dt_venda_final) AND (!empty($data->dt_venda_final)) )) )
        {

            $filters[] = new TFilter('dt_venda', '<=', $data->dt_venda_final);// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);
        $this->fireEvents($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);

        return $filters;
    }

    public function onGenerate($format)
    {
        try
        {
            $filters = $this->getFilters();
            // open a transaction with database 'base_banco'
            TTransaction::open(self::$database);
            $param = [];
            // creates a repository for VendaAlt
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $filterVar = TSession::getValue("userunitid");
            $criteria->add(new TFilter('system_unit_id', '=', $filterVar));

            $criteria->setProperties($param);

            if ($filters)
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
                $widths = array(200,200,200,200,200,200,200);

                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLS($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths, 'L', 'A4');
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $tr = new TTableWriterRTF($widths, 'L', 'A4');
                        break;
                }

                if (!empty($tr))
                {
                    // create the document styles
                    $tr->addStyle('title', 'Helvetica', '10', 'B',   '#000000', '#dbdbdb');
                    $tr->addStyle('datap', 'Arial', '10', '',    '#333333', '#f0f0f0');
                    $tr->addStyle('datai', 'Arial', '10', '',    '#333333', '#ffffff');
                    $tr->addStyle('header', 'Helvetica', '16', 'B',   '#5a5a5a', '#6B6B6B');
                    $tr->addStyle('footer', 'Helvetica', '10', 'B',  '#5a5a5a', '#A3A3A3');
                    $tr->addStyle('break', 'Helvetica', '10', 'B',  '#ffffff', '#9a9a9a');
                    $tr->addStyle('total', 'Helvetica', '10', 'I',  '#000000', '#c7c7c7');
                    $tr->addStyle('breakTotal', 'Helvetica', '10', 'I',  '#000000', '#c6c8d0');

                    // add titles row
                    $tr->addRow();
                    $tr->addCell("Id", 'left', 'title');
                    $tr->addCell("Cliente", 'left', 'title');
                    $tr->addCell("Vendedor", 'left', 'title');
                    $tr->addCell("Estado da venda", 'left', 'title');
                    $tr->addCell("Data da venda", 'left', 'title');
                    $tr->addCell("Valor do frete", 'left', 'title');
                    $tr->addCell("Valor total", 'left', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        $grandTotal['id_venda'][] = $object->id_venda;
                        $breakTotal['id_venda'][] = $object->id_venda;
                        $grandTotal['valor_total'][] = $object->valor_total;
                        $breakTotal['valor_total'][] = $object->valor_total;

                        $firstRow = false;

                        $object->dt_venda = call_user_func(function($value, $object, $row) 
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
                        }, $object->dt_venda, $object, null);

                        $object->id_venda = call_user_func(function($value, $object, $row) 
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
                        }, $object->id_venda, $object, null);

                        $object->valor_total = call_user_func(function($value, $object, $row) 
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
                        }, $object->valor_total, $object, null);

                        $tr->addRow();

                        $tr->addCell($object->id, 'left', $style);
                        $tr->addCell($object->entity_column_id:4139965->entity_column_id:4139818, 'left', $style);
                        $tr->addCell($object->entity_column_id:4139967->entity_column_id:4139818, 'left', $style);
                        $tr->addCell($object->entity_column_id:4139968->entity_column_id:4139656, 'left', $style);
                        $tr->addCell($object->dt_venda, 'left', $style);
                        $tr->addCell($object->id_venda, 'left', $style);
                        $tr->addCell($object->valor_total, 'left', $style);

                        $colour = !$colour;
                    }

                    $tr->addRow();

                    $grandTotal_id_venda = array_sum($grandTotal['id_venda']);
                    $grandTotal_valor_total = array_sum($grandTotal['valor_total']);

                    $grandTotal_id_venda = call_user_func(function($value)
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
                    }, $grandTotal_id_venda); 

                    $grandTotal_valor_total = call_user_func(function($value)
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
                    }, $grandTotal_valor_total); 

                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_id_venda, 'left', 'total');
                    $tr->addCell($grandTotal_valor_total, 'left', 'total');

                    $file = 'report_'.uniqid().".{$format}";
                    // stores the file
                    if (!file_exists("app/output/{$file}") || is_writable("app/output/{$file}"))
                    {
                        $tr->save("app/output/{$file}");
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . "app/output/{$file}");
                    }

                    parent::openFile("app/output/{$file}");

                    // shows the success message
                    new TMessage('info', _t('Report generated. Please, enable popups'));
                }
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

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->tipo_produto))
            {
                $value = $object->tipo_produto;

                $obj->tipo_produto = $value;
            }
            if(isset($object->produto))
            {
                $value = $object->produto;

                $obj->produto = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->tipo_produto))
            {
                $value = $object->tipo_produto;

                $obj->tipo_produto = $value;
            }
            if(isset($object->produto))
            {
                $value = $object->produto;

                $obj->produto = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  


}

