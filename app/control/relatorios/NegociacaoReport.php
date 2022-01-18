<?php

class NegociacaoReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'base_banco';
    private static $activeRecord = 'Negociacao';
    private static $primaryKey = 'id';
    private static $formName = 'formReport_Negociacao';

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
        $this->form->setFormTitle("Relatório de negociações");

        $criteria_cliente_id = new TCriteria();
        $criteria_vendedor_id = new TCriteria();

        $filterVar = Grupo::clientes;
        $criteria_cliente_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 
        $filterVar = Grupo::vendedores;
        $criteria_vendedor_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 

        $id = new TEntry('id');
        $tipo_negociacao_id = new TDBCombo('tipo_negociacao_id', 'base_banco', 'TipoNegociacao', 'id', '{nome}','nome asc'  );
        $cliente_id = new TDBUniqueSearch('cliente_id', 'base_banco', 'Pessoa', 'id', 'nome','nome asc' , $criteria_cliente_id );
        $vendedor_id = new TDBCombo('vendedor_id', 'base_banco', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $origem_negociacao_id = new TDBCombo('origem_negociacao_id', 'base_banco', 'OrigemNegociacao', 'id', '{nome}','nome asc'  );
        $estado_negociacao_id = new TDBCombo('estado_negociacao_id', 'base_banco', 'EstadoNegociacao', 'id', '{nome}','nome asc'  );
        $dt_inicio_negociacao_de = new TDate('dt_inicio_negociacao_de');
        $dt_inicio_negociacao_ate = new TDate('dt_inicio_negociacao_ate');
        $fim_negociacao_de = new TDate('fim_negociacao_de');
        $fim_negociacao_ate = new TDate('fim_negociacao_ate');

        $cliente_id->setMinLength(2);

        $fim_negociacao_de->setDatabaseMask('yyyy-mm-dd');
        $fim_negociacao_ate->setDatabaseMask('yyyy-mm-dd');
        $dt_inicio_negociacao_de->setDatabaseMask('yyyy-mm-dd');
        $dt_inicio_negociacao_ate->setDatabaseMask('yyyy-mm-dd');

        $cliente_id->setMask('{nome}');
        $fim_negociacao_de->setMask('dd/mm/yyyy');
        $fim_negociacao_ate->setMask('dd/mm/yyyy');
        $dt_inicio_negociacao_de->setMask('dd/mm/yyyy');
        $dt_inicio_negociacao_ate->setMask('dd/mm/yyyy');

        $id->setSize(100);
        $cliente_id->setSize('100%');
        $vendedor_id->setSize('100%');
        $fim_negociacao_de->setSize(150);
        $fim_negociacao_ate->setSize(150);
        $tipo_negociacao_id->setSize('100%');
        $origem_negociacao_id->setSize('100%');
        $estado_negociacao_id->setSize('100%');
        $dt_inicio_negociacao_de->setSize(150);
        $dt_inicio_negociacao_ate->setSize(150);

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[new TLabel("Tipo da negociação:", null, '14px', null)],[$tipo_negociacao_id]);
        $row2 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null)],[$cliente_id],[new TLabel("Vendedor:", null, '14px', null)],[$vendedor_id]);
        $row3 = $this->form->addFields([new TLabel("Origem da negociação:", null, '14px', null)],[$origem_negociacao_id],[new TLabel("Fase da negociação:", null, '14px', null)],[$estado_negociacao_id]);
        $row4 = $this->form->addFields([new TLabel("Início negociação (de):", null, '14px', null)],[$dt_inicio_negociacao_de],[new TLabel("Início negociação (até):", null, '14px', null)],[$dt_inicio_negociacao_ate]);
        $row5 = $this->form->addFields([new TLabel("Fim negociação (de):", null, '14px', null)],[$fim_negociacao_de],[new TLabel("Fim negociação (até):", null, '14px', null)],[$fim_negociacao_ate]);

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

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }
        if (isset($data->tipo_negociacao_id) AND ( (is_scalar($data->tipo_negociacao_id) AND $data->tipo_negociacao_id !== '') OR (is_array($data->tipo_negociacao_id) AND (!empty($data->tipo_negociacao_id)) )) )
        {

            $filters[] = new TFilter('tipo_negociacao_id', '=', $data->tipo_negociacao_id);// create the filter 
        }
        if (isset($data->cliente_id) AND ( (is_scalar($data->cliente_id) AND $data->cliente_id !== '') OR (is_array($data->cliente_id) AND (!empty($data->cliente_id)) )) )
        {

            $filters[] = new TFilter('cliente_id', '=', $data->cliente_id);// create the filter 
        }
        if (isset($data->vendedor_id) AND ( (is_scalar($data->vendedor_id) AND $data->vendedor_id !== '') OR (is_array($data->vendedor_id) AND (!empty($data->vendedor_id)) )) )
        {

            $filters[] = new TFilter('vendedor_id', '=', $data->vendedor_id);// create the filter 
        }
        if (isset($data->origem_negociacao_id) AND ( (is_scalar($data->origem_negociacao_id) AND $data->origem_negociacao_id !== '') OR (is_array($data->origem_negociacao_id) AND (!empty($data->origem_negociacao_id)) )) )
        {

            $filters[] = new TFilter('origem_negociacao_id', '=', $data->origem_negociacao_id);// create the filter 
        }
        if (isset($data->estado_negociacao_id) AND ( (is_scalar($data->estado_negociacao_id) AND $data->estado_negociacao_id !== '') OR (is_array($data->estado_negociacao_id) AND (!empty($data->estado_negociacao_id)) )) )
        {

            $filters[] = new TFilter('estado_negociacao_id', '=', $data->estado_negociacao_id);// create the filter 
        }
        if (isset($data->dt_inicio_negociacao_de) AND ( (is_scalar($data->dt_inicio_negociacao_de) AND $data->dt_inicio_negociacao_de !== '') OR (is_array($data->dt_inicio_negociacao_de) AND (!empty($data->dt_inicio_negociacao_de)) )) )
        {

            $filters[] = new TFilter('dt_inicio_negociacao', '>=', $data->dt_inicio_negociacao_de);// create the filter 
        }
        if (isset($data->dt_inicio_negociacao_ate) AND ( (is_scalar($data->dt_inicio_negociacao_ate) AND $data->dt_inicio_negociacao_ate !== '') OR (is_array($data->dt_inicio_negociacao_ate) AND (!empty($data->dt_inicio_negociacao_ate)) )) )
        {

            $filters[] = new TFilter('dt_inicio_negociacao', '<=', $data->dt_inicio_negociacao_ate);// create the filter 
        }
        if (isset($data->fim_negociacao_de) AND ( (is_scalar($data->fim_negociacao_de) AND $data->fim_negociacao_de !== '') OR (is_array($data->fim_negociacao_de) AND (!empty($data->fim_negociacao_de)) )) )
        {

            $filters[] = new TFilter('dt_fim_negociacao', '>=', $data->fim_negociacao_de);// create the filter 
        }
        if (isset($data->fim_negociacao_ate) AND ( (is_scalar($data->fim_negociacao_ate) AND $data->fim_negociacao_ate !== '') OR (is_array($data->fim_negociacao_ate) AND (!empty($data->fim_negociacao_ate)) )) )
        {

            $filters[] = new TFilter('dt_fim_negociacao', '<=', $data->fim_negociacao_ate);// create the filter 
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
            // creates a repository for Negociacao
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
                $widths = array(200,200,200,200,200,200,200,200);

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
                    $tr->addCell("Origem", 'left', 'title');
                    $tr->addCell("Tipo", 'left', 'title');
                    $tr->addCell("Fase", 'left', 'title');
                    $tr->addCell("Início negociação", 'left', 'title');
                    $tr->addCell("Fim negociação", 'left', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        $firstRow = false;

                        $tr->addRow();

                        $tr->addCell($object->id, 'left', $style);
                        $tr->addCell($object->cliente->nome, 'left', $style);
                        $tr->addCell($object->vendedor->nome, 'left', $style);
                        $tr->addCell($object->origem_negociacao->nome, 'left', $style);
                        $tr->addCell($object->tipo_negociacao->nome, 'left', $style);
                        $tr->addCell($object->estado_negociacao->nome, 'left', $style);
                        $tr->addCell($object->dt_inicio_negociacao, 'left', $style);
                        $tr->addCell($object->dt_fim_negociacao, 'left', $style);

                        $colour = !$colour;
                    }

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

