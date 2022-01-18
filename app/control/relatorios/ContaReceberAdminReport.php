<?php

class ContaReceberAdminReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'base_banco';
    private static $activeRecord = 'Conta';
    private static $primaryKey = 'id';
    private static $formName = 'formReport_Conta';

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
        $this->form->setFormTitle("Relatório de contas a receber");

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
        $dt_emissao->setSize(150);
        $loja->setSize('100%', 70);
        $fornecedor->setSize('100%');
        $dt_vencimento->setSize(150);
        $natureza_id->setSize('100%');
        $dt_emissao_final->setSize(150);
        $dt_vencimento_final->setSize(150);

        $row1 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null)],[$fornecedor],[new TLabel("Natureza:", null, '14px', null)],[$natureza_id]);
        $row2 = $this->form->addFields([new TLabel("Emissão (de):", null, '14px', null)],[$dt_emissao],[new TLabel("Emissão (até):", null, '14px', null)],[$dt_emissao_final]);
        $row3 = $this->form->addFields([new TLabel("Vencimento (de):", null, '14px', null)],[$dt_vencimento],[new TLabel("Vencimento (até):", null, '14px', null)],[$dt_vencimento_final]);
        $row4 = $this->form->addFields([new TLabel("Quitada:", null, '14px', null)],[$quitada],[new TLabel("Empresa:", null, '14px', null)],[$loja]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

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
            // creates a repository for Conta
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $filterVar = TipoConta::receber;
            $criteria->add(new TFilter('tipo_conta_id', '=', $filterVar));
            $filterVar = TSession::getValue("userunitids");
            $criteria->add(new TFilter('loja', 'in', $filterVar));

            $param['order'] = 'loja,dt_vencimento';
            $param['direction'] = 'desc';

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
                    $tr->addCell("Natureza", 'left', 'title');
                    $tr->addCell("Emissão", 'left', 'title');
                    $tr->addCell("Vencimento", 'left', 'title');
                    $tr->addCell("Quitada", 'left', 'title');
                    $tr->addCell("Valor", 'left', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        if ($object->loja !== $breakValue)
                        {
                            if (!$firstRow)
                            {
                                $tr->addRow();

                                $breakTotal_valor = array_sum($breakTotal['valor']);

                                $breakTotal_valor = call_user_func(function($value)
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
                                }, $breakTotal_valor); 

                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell('', 'center', 'breakTotal');
                                $tr->addCell($breakTotal_valor, 'left', 'breakTotal');
                            }
                            $tr->addRow();
                            $tr->addCell($object->render('{entity_column_id:4139603->entity_column_id:83}'), 'left', 'break', 7);
                            $breakTotal = [];
                        }
                        $breakValue = $object->loja;

                        $grandTotal['valor'][] = $object->valor;
                        $breakTotal['valor'][] = $object->valor;

                        $firstRow = false;

                        $object->dt_emissao = call_user_func(function($value, $object, $row) 
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
                        }, $object->dt_emissao, $object, null);

                        $object->dt_vencimento = call_user_func(function($value, $object, $row) 
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
                        }, $object->dt_vencimento, $object, null);

                        $object->quitada = call_user_func(function($value, $object, $row) 
                        {
                            if($value === true || $value == 't' || $value === 1 || $value == '1' || $value == 's' || $value == 'S' || $value == 'T')
                                return 'Sim';

                            return 'Não';

                        }, $object->quitada, $object, null);

                        $object->valor = call_user_func(function($value, $object, $row) 
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
                        }, $object->valor, $object, null);

                        $tr->addRow();

                        $tr->addCell($object->id, 'left', $style);
                        $tr->addCell($object->entity_column_id:4139605->entity_column_id:4139818, 'left', $style);
                        $tr->addCell($object->natureza->nome, 'left', $style);
                        $tr->addCell($object->dt_emissao, 'left', $style);
                        $tr->addCell($object->dt_vencimento, 'left', $style);
                        $tr->addCell($object->quitada, 'left', $style);
                        $tr->addCell($object->valor, 'left', $style);

                        $colour = !$colour;
                    }

                    $tr->addRow();

                    $breakTotal_valor = array_sum($breakTotal['valor']);

                    $breakTotal_valor = call_user_func(function($value)
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
                    }, $breakTotal_valor); 

                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell('', 'center', 'breakTotal');
                    $tr->addCell($breakTotal_valor, 'left', 'breakTotal');

                    $tr->addRow();

                    $grandTotal_valor = array_sum($grandTotal['valor']);

                    $grandTotal_valor = call_user_func(function($value)
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
                    }, $grandTotal_valor); 

                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_valor, 'left', 'total');

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


}

