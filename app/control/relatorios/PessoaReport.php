<?php

class PessoaReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'base_banco';
    private static $activeRecord = 'Pessoa';
    private static $primaryKey = 'id';
    private static $formName = 'formReport_Pessoa';

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
        $this->form->setFormTitle("Relatório de pessoas");

        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $system_user_id = new TDBCombo('system_user_id', 'permission', 'SystemUsers', 'id', '{name}','name asc'  );
        $documento = new TEntry('documento');
        $email = new TEntry('email');
        $cidade_estado_id = new TDBCombo('cidade_estado_id', 'base_banco', 'Estado', 'id', '{nome}','nome asc'  );
        $cidade_id = new TCombo('cidade_id');
        $dt_ativacao = new TDate('dt_ativacao');
        $data_ativacao_final = new TDate('data_ativacao_final');
        $dt_desativacao = new TDate('dt_desativacao');
        $data_desativacao_final = new TDate('data_desativacao_final');

        $cidade_estado_id->setChangeAction(new TAction([$this,'onChangecidade_estado_id']));

        $dt_ativacao->setDatabaseMask('yyyy-mm-dd');
        $dt_desativacao->setDatabaseMask('yyyy-mm-dd');
        $data_ativacao_final->setDatabaseMask('yyyy-mm-dd');
        $data_desativacao_final->setDatabaseMask('yyyy-mm-dd');

        $dt_ativacao->setMask('dd/mm/yyyy');
        $dt_desativacao->setMask('dd/mm/yyyy');
        $data_ativacao_final->setMask('dd/mm/yyyy');
        $data_desativacao_final->setMask('dd/mm/yyyy');

        $id->setSize(100);
        $nome->setSize('100%');
        $email->setSize('100%');
        $dt_ativacao->setSize(150);
        $documento->setSize('100%');
        $cidade_id->setSize('100%');
        $dt_desativacao->setSize(150);
        $system_user_id->setSize('100%');
        $cidade_estado_id->setSize('100%');
        $data_ativacao_final->setSize(150);
        $data_desativacao_final->setSize(150);

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[],[]);
        $row2 = $this->form->addFields([new TLabel("Nome:", null, '14px', null)],[$nome],[new TLabel("Usuário:", null, '14px', null)],[$system_user_id]);
        $row3 = $this->form->addFields([new TLabel("Documento:", null, '14px', null)],[$documento],[new TLabel("Email:", null, '14px', null)],[$email]);
        $row4 = $this->form->addFields([new TLabel("Estado:", null, '14px', null)],[$cidade_estado_id],[new TLabel("Cidade:", null, '14px', null)],[$cidade_id]);
        $row5 = $this->form->addFields([new TLabel("Data de ativação (de):", null, '14px', null)],[$dt_ativacao],[new TLabel("Data de ativação (até):", null, '14px', null)],[$data_ativacao_final]);
        $row6 = $this->form->addFields([new TLabel("Data de desativação (de):", null, '14px', null)],[$dt_desativacao],[new TLabel("Data de desativação (até):", null, '14px', null)],[$data_desativacao_final]);

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

    public static function onChangecidade_estado_id($param)
    {
        try
        {

            if (isset($param['cidade_estado_id']) && $param['cidade_estado_id'])
            { 
                $criteria = TCriteria::create(['estado_id' => $param['cidade_estado_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'cidade_id', 'base_banco', 'Cidade', 'id', '{nome}', 'nome asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'cidade_id'); 
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
        if (isset($data->nome) AND ( (is_scalar($data->nome) AND $data->nome !== '') OR (is_array($data->nome) AND (!empty($data->nome)) )) )
        {

            $filters[] = new TFilter('nome', 'like', "%{$data->nome}%");// create the filter 
        }
        if (isset($data->system_user_id) AND ( (is_scalar($data->system_user_id) AND $data->system_user_id !== '') OR (is_array($data->system_user_id) AND (!empty($data->system_user_id)) )) )
        {

            $filters[] = new TFilter('system_user_id', '=', $data->system_user_id);// create the filter 
        }
        if (isset($data->documento) AND ( (is_scalar($data->documento) AND $data->documento !== '') OR (is_array($data->documento) AND (!empty($data->documento)) )) )
        {

            $filters[] = new TFilter('documento', 'like', "%{$data->documento}%");// create the filter 
        }
        if (isset($data->email) AND ( (is_scalar($data->email) AND $data->email !== '') OR (is_array($data->email) AND (!empty($data->email)) )) )
        {

            $filters[] = new TFilter('email', 'like', "%{$data->email}%");// create the filter 
        }
        if (isset($data->cidade_estado_id) AND ( (is_scalar($data->cidade_estado_id) AND $data->cidade_estado_id !== '') OR (is_array($data->cidade_estado_id) AND (!empty($data->cidade_estado_id)) )) )
        {

            $filters[] = new TFilter('cidade_id', 'in', "(SELECT id FROM cidade WHERE estado_id = '{$data->cidade_estado_id}')");// create the filter 
        }
        if (isset($data->cidade_id) AND ( (is_scalar($data->cidade_id) AND $data->cidade_id !== '') OR (is_array($data->cidade_id) AND (!empty($data->cidade_id)) )) )
        {

            $filters[] = new TFilter('cidade_id', '=', $data->cidade_id);// create the filter 
        }
        if (isset($data->dt_ativacao) AND ( (is_scalar($data->dt_ativacao) AND $data->dt_ativacao !== '') OR (is_array($data->dt_ativacao) AND (!empty($data->dt_ativacao)) )) )
        {

            $filters[] = new TFilter('dt_ativacao', '>=', $data->dt_ativacao);// create the filter 
        }
        if (isset($data->data_ativacao_final) AND ( (is_scalar($data->data_ativacao_final) AND $data->data_ativacao_final !== '') OR (is_array($data->data_ativacao_final) AND (!empty($data->data_ativacao_final)) )) )
        {

            $filters[] = new TFilter('dt_ativacao', '<=', $data->data_ativacao_final);// create the filter 
        }
        if (isset($data->dt_desativacao) AND ( (is_scalar($data->dt_desativacao) AND $data->dt_desativacao !== '') OR (is_array($data->dt_desativacao) AND (!empty($data->dt_desativacao)) )) )
        {

            $filters[] = new TFilter('dt_desativacao', '>=', $data->dt_desativacao);// create the filter 
        }
        if (isset($data->data_desativacao_final) AND ( (is_scalar($data->data_desativacao_final) AND $data->data_desativacao_final !== '') OR (is_array($data->data_desativacao_final) AND (!empty($data->data_desativacao_final)) )) )
        {

            $filters[] = new TFilter('dt_desativacao', '<=', $data->data_desativacao_final);// create the filter 
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
            // creates a repository for Pessoa
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

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
                $widths = array(200,200,200,200,200,200,200,200,200,200);

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
                    $tr->addCell("Nome", 'left', 'title');
                    $tr->addCell("Documento", 'left', 'title');
                    $tr->addCell("Telefone", 'left', 'title');
                    $tr->addCell("Email", 'left', 'title');
                    $tr->addCell("Estado", 'left', 'title');
                    $tr->addCell("Cidade", 'left', 'title');
                    $tr->addCell("Usuário", 'left', 'title');
                    $tr->addCell("Data de ativação", 'left', 'title');
                    $tr->addCell("Data de desativação", 'left', 'title');

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
                        $tr->addCell($object->nome, 'left', $style);
                        $tr->addCell($object->documento, 'left', $style);
                        $tr->addCell($object->fone, 'left', $style);
                        $tr->addCell($object->email, 'left', $style);
                        $tr->addCell($object->cidade->estado->nome, 'left', $style);
                        $tr->addCell($object->id, 'left', $style);
                        $tr->addCell($object->id, 'left', $style);
                        $tr->addCell($object->dt_ativacao, 'left', $style);
                        $tr->addCell($object->dt_desativacao, 'left', $style);

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

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->cidade_estado_id))
            {
                $value = $object->cidade_estado_id;

                $obj->cidade_estado_id = $value;
            }
            if(isset($object->cidade_id))
            {
                $value = $object->cidade_id;

                $obj->cidade_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->cidade->estado_id))
            {
                $value = $object->cidade->estado_id;

                $obj->cidade_estado_id = $value;
            }
            if(isset($object->cidade_id))
            {
                $value = $object->cidade_id;

                $obj->cidade_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  


}

