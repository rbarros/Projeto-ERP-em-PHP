<?php

class ContaAPagarAdminList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'base_banco';
    private static $activeRecord = 'Conta';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Conta';
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
        $this->form->setFormTitle("Listagem de contas a pagar");
        $this->limit = 50;

        $id = new TEntry('id');
        $forma_pagamento = new TCombo('forma_pagamento');
        $natureza_id = new TDBCombo('natureza_id', 'base_banco', 'Natureza', 'id', '{nome}','nome asc'  );
        $quitada = new TRadioGroup('quitada');
        $dt_vencimento = new TDate('dt_vencimento');
        $dt_emissao = new TDate('dt_emissao');
        $dt_vencimento_final = new TDate('dt_vencimento_final');
        $dt_emissao_final = new TDate('dt_emissao_final');
        $loja = new TDBCombo('loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $fornecedor = new TDBUniqueSearch('fornecedor', 'base_banco', 'Fornecedor', 'id', 'nome_fantasia','nome_fantasia asc'  );
        $a_pagar = new TNumeric('a_pagar', '2', ',', '.' );
        $pago = new TNumeric('pago', '2', ',', '.' );


        $quitada->setLayout('horizontal');
        $fornecedor->setMinLength(2);

        $quitada->addItems(['t'=>'SIM','f'=>'NÃO']);
        $forma_pagamento->addItems(['deposito'=>' Depósito','transferencia'=>' Transferência','cheque'=>' Cheque','dinheiro'=>'Dinheiro','boleto'=>' Boleto']);

        $pago->setEditable(false);
        $a_pagar->setEditable(false);

        $dt_emissao->setDatabaseMask('yyyy-mm-dd');
        $dt_vencimento->setDatabaseMask('yyyy-mm-dd');
        $dt_emissao_final->setDatabaseMask('yyyy-mm-dd');
        $dt_vencimento_final->setDatabaseMask('yyyy-mm-dd');

        $dt_emissao->setMask('dd/mm/yyyy');
        $dt_vencimento->setMask('dd/mm/yyyy');
        $fornecedor->setMask('{nome_fantasia}');
        $dt_emissao_final->setMask('dd/mm/yyyy');
        $dt_vencimento_final->setMask('dd/mm/yyyy');

        $id->setSize('100%');
        $pago->setSize('80%');
        $loja->setSize('100%');
        $a_pagar->setSize('80%');
        $quitada->setSize('100%');
        $dt_emissao->setSize('100%');
        $fornecedor->setSize('100%');
        $natureza_id->setSize('100%');
        $dt_vencimento->setSize('100%');
        $forma_pagamento->setSize('100%');
        $dt_emissao_final->setSize('100%');
        $dt_vencimento_final->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[],[new TLabel("Forma de pagamento:", null, '14px', null, '100%'),$forma_pagamento],[new TLabel("Natureza:", null, '14px', null, '100%'),$natureza_id],[new TLabel("Quitada:", null, '14px', null, '100%'),$quitada]);
        $row1->layout = [' col-sm-2 col-lg-1',' col-sm-4 col-lg-5',' col-sm-2 col-lg-2',' col-sm-2 col-lg-2',' col-sm-2 col-lg-2'];

        $row2 = $this->form->addFields([new TLabel("Vencimento (de):", null, '14px', null, '100%'),$dt_vencimento,new TLabel("Emissão (de):", null, '14px', null, '100%'),$dt_emissao],[new TLabel("Vencimento (até):", null, '14px', null, '100%'),$dt_vencimento_final,new TLabel("Emissão (até):", null, '14px', null, '100%'),$dt_emissao_final],[new TLabel("Loja", null, '14px', null, '100%'),$loja,new TLabel("Fornecedor:", null, '14px', null, '100%'),$fornecedor]);
        $row2->layout = ['col-sm-3','col-sm-3','col-sm-6'];

        $row3 = $this->form->addFields([new TFormSeparator("Totalizador", '#333333', '18', '#ff0091')]);
        $row3->layout = [' col-sm-3 col-lg-12'];

        $row4 = $this->form->addFields([new TLabel("Valor a pagar(R$):", null, '14px', null),$a_pagar],[new TLabel("Quitadas(R$):", null, '14px', null),$pago]);
        $row4->layout = [' col-sm-3 col-lg-6',' col-sm-2 col-lg-6'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onclean = $this->form->addAction("Limpar pesquisa", new TAction([$this, 'onClean']), 'fas:eraser #f80000');
        $this->btn_onclean = $btn_onclean;

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['ContaaPagarForm', 'onShow']), 'fas:plus #69aa46');
        $this->btn_onshow = $btn_onshow;

        $btn_onpdf = $this->form->addAction("Gerar PDF", new TAction([$this, 'onPDF']), 'fas:file-pdf #f00000');
        $this->btn_onpdf = $btn_onpdf;

        $btn_onparcelas = $this->form->addAction("Visualizar como parcelas", new TAction([$this, 'onParcelas']), 'fas:eye #ffffff');
        $this->btn_onparcelas = $btn_onparcelas;
        $btn_onparcelas->addStyleClass('btn-success'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $filterVar = TipoConta::pagar;
        $this->filter_criteria->add(new TFilter('tipo_conta_id', '=', $filterVar));

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "ID da conta", 'left');
        $column_fk_loja_nome_fantasia = new TDataGridColumn('fk_loja->nome_fantasia', "Loja", 'left');
        $column_fornecedor = new TDataGridColumn('fornecedor', "Fornecedor", 'left');
        $column_natureza_nome = new TDataGridColumn('natureza->nome', "Natureza", 'left');
        $column_dt_vencimento_transformed = new TDataGridColumn('dt_vencimento', "Vencimento", 'left');
        $column_obs = new TDataGridColumn('obs', "Obs", 'left' , '20%');
        $column_forma_pagamento = new TDataGridColumn('forma_pagamento', "Forma pagamento", 'left');
        $column_valor_transformed = new TDataGridColumn('valor', "Valor", 'left');
        $column_quitada_transformed = new TDataGridColumn('quitada', "Quitada", 'right');

        $column_dt_vencimento_transformed->setTransformer(function($value, $object, $row) 
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

        $column_quitada_transformed->setTransformer(function($value, $object, $row) 
        {
            if($value === true || $value == 't' || $value === 1 || $value == '1' || $value == 's' || $value == 'S' || $value == 'T')
                return 'Sim';

            return 'Não';

        });        

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        $order_dt_vencimento_transformed = new TAction(array($this, 'onReload'));
        $order_dt_vencimento_transformed->setParameter('order', 'dt_vencimento');
        $column_dt_vencimento_transformed->setAction($order_dt_vencimento_transformed);
        $order_quitada_transformed = new TAction(array($this, 'onReload'));
        $order_quitada_transformed->setParameter('order', 'quitada');
        $column_quitada_transformed->setAction($order_quitada_transformed);

        $column_qtdParcelas          = new TDataGridColumn('qtdParcelas', "Qtd. parcelas", 'center');

        $column_totalPago            = new TDataGridColumn('totalPago', "Total pago", 'left');
        $column_totalPago->setTransformer(function($value, $object, $row) 
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
        $column_valorProg            = new TDataGridColumn('valorProg', "Valor Programado", 'left');
        $column_valorProg->setTransformer(function($value, $object, $row) 
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
        $column_valorRestante        = new TDataGridColumn('valorRestante', "Valor restante", 'left');
        $column_valorRestante->setTransformer(function($value, $object, $row) 
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

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_fk_loja_nome_fantasia);
        $this->datagrid->addColumn($column_fornecedor);
        $this->datagrid->addColumn($column_natureza_nome);
        $this->datagrid->addColumn($column_dt_vencimento_transformed);
        $this->datagrid->addColumn($column_obs);
        $this->datagrid->addColumn($column_forma_pagamento);
        $this->datagrid->addColumn($column_valor_transformed);
        $this->datagrid->addColumn($column_quitada_transformed);

        $this->datagrid->addColumn($column_qtdParcelas);
        $this->datagrid->addColumn($column_totalPago);
        $this->datagrid->addColumn($column_valorProg);
        $this->datagrid->addColumn($column_valorRestante);

        $column_quitada_transformed->setTransformer(array($this,'formatarQuitacao'));

        $action_onEdit = new TDataGridAction(array('ContaaPagarForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('ContaAPagarAdminList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluír");
        $action_onDelete->setImage('far:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
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
            $container->add(TBreadCrumb::create(["Financeiro","Contas a Pagar"]));
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
                $key=$param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                $class = self::$activeRecord;

                // instantiates object
                $object = new $class($key, FALSE);
                $subParcelas = SubparcelasParcela::where('conta_origem','=',$object->id)->load();
                if($subParcelas){
                    foreach($subParcelas as $subParcela){
                        $subParcela->delete();
                    }
                }
                $parcelas = ParcelasConta::where('conta_origem','=',$object->id)->load();
                if($parcelas){
                    foreach($parcelas as $parcela){
                        $parcela->delete();
                    }
                }

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

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onPDF($param = null) 
    {
        try 
        {

                $this->onSearch();
                TTransaction::open(self::$database); // open a transaction
                $repository = new TRepository(self::$activeRecord); // creates a repository for Customer
                $criteria = new TCriteria; // creates a criteria

                if($filters = TSession::getValue(__CLASS__.'_filters'))
                {
                    foreach ($filters as $filter) 
                    {
                        $criteria->add($filter);       
                    }
                }
                $objects = $repository->load($criteria, FALSE);
                $widths = array(130,400,400,300,200,200,200,800,100);
                $table = new TTableWriterPDF($widths, 'L');
                if($objects){
                    // cria os estilos de escrita para as células
                    $table->addStyle('title','Arial', '10', '','#000000', '#ff0091');
                    $table->addStyle('datap','Arial', '10', '', '#000000','#ffd9f7');
                    $table->addStyle('datai','Arial', '10', '', '#000000','#ffffff');
                    $table->addStyle('header','Times', '16', 'BI','#000000','#CECBCA');
                    $table->addStyle('footer','Times', '12', '','#000000','#CECBCA');
                    $table->setHeaderCallback( function($table) {
                    $table->addRow();
                    $table->addCell('Contas a pagar', 'center', 'header', 5);
                    $table->addRow();
                    $table->addCell('Codigo',               'center', 'title');
                    $table->addCell('Loja',                 'center', 'title');
                    $table->addCell('Natureza',             'center', 'title');
                    $table->addCell('Fornecedor',           'center', 'title');
                    $table->addCell("Forma \nPagamento",    'center', 'title');
                    $table->addCell('Vencimento',           'center', 'title');
                    $table->addCell('Valor',                'center', 'title');
                    $table->addCell('Observação',           'center', 'title');
                    $table->addCell('Quitada',              'center', 'title');

                    });

                    $total_apagar   = 0;
                    $total_pago     = 0;
                    $deposito       = 0;
                    $boleto         = 0;
                    $cheque         = 0;
                    $transferencia  = 0;

                    $table->setFooterCallback( function($table) {
                    $table->addRow();
                    $table->addCell("Emissão", 'center', 'footer',5 );
                    $table->addCell(date('Y-m-d h:i:s'), 'center', 'footer',5);
                    });
                    $colour= FALSE;
                    foreach ($objects as $object) {
                        $loja                       = new Loja($object->loja);
                        $fornecedor                 = new Fornecedor($object->fornecedor);
                        $natureza                   = new Natureza($object->natureza_id);
                        $object->fornecedor         = $fornecedor->nome_fantasia;
                        $object->loja               = str_replace("Fashion Biju","",$loja->nome_fantasia);
                        $object->natureza_id        = $natureza->nome;
                        $date                       = new DateTime($object->dt_vencimento);
                        $object->dt_vencimento      = $date->format('d/m/Y');
                        $object->valor              = "R$ $object->valor";
                        $parcelas                   = ParcelasConta::where('conta_origem','=',$object->id)->load();

                        if($parcelas){
                            $parcela = $parcelas[];
                            $object->forma_pagamento = $parcela->forma_pagamento;
                            foreach($parcelas as $parcela){
                                if($parcela->quitada == "t"){
                                    $total_pago += doubleval($parcela->valor);
                                }
                                switch($parcela->forma_pagamento){
                                    case "boleto":
                                        $boleto += doubleval($parcela->valor);
                                        break;
                                    case "transferencia":
                                        $transferencia += doubleval($parcela->valor);
                                        break;
                                    case "cheque":
                                        $cheque += doubleval($parcela->valor);
                                        break;
                                    case "deposito":
                                        $deposito += doubleval($parcela->valor);
                                        break;
                                }
                            }
                            $total_apagar           += $parcela->valor;
                        }
                        if($object->quitada == "t"){
                            $object->quitada = "SIM";
                        }else{
                            $object->quitada = "NÃO";
                        }

                        $style = $colour ? 'datap' : 'datai';
                        $table->addRow();
                        $table->addCell($object->id,                     'left', $style);
                        $table->addCell($object->loja,                   'left',$style);
                        $table->addCell($object->natureza_id,            'left',$style);
                        $table->addCell($object->fornecedor,             'left',$style);
                        $table->addCell($object->forma_pagamento,        'left',$style);    
                        $table->addCell($object->dt_vencimento,          'left',$style);
                        $table->addCell($object->valor,                  'left',$style);
                        $table->addCell($object->obs,                    'left',$style);
                        $table->addCell($object->quitada,                'left',$style);

                        $colour = !$colour;
                     }

                     foreach ($objects as $object) {

                    }
                    $total_apagar   = 'R$ '.str_replace('.',',',$total_apagar);
                    $total_pago     = 'R$ '.str_replace('.',',',$total_pago);
                    $deposito       = 'R$ '.str_replace('.',',',$deposito);
                    $boleto         = 'R$ '.str_replace('.',',',$boleto);
                    $cheque         = 'R$ '.str_replace('.',',',$cheque);
                    $transferencia  = 'R$ '.str_replace('.',',',$transferencia);

                    $table->addRow();
                    $table->addCell("Total à pagar", 'center', 'footer',2);
                    $table->addCell($total_apagar, 'center', 'footer',2);
                    $table->addCell("Total pago", 'center', 'footer',2);
                    $table->addCell($total_pago, 'center', 'footer',2);
                    $table->addRow();
                    $table->addCell("Deposito", 'center', 'footer',2);
                    $table->addCell($deposito, 'center', 'footer',2);
                    $table->addCell("Boleto", 'center', 'footer',2);
                    $table->addCell($boleto, 'center', 'footer',2);
                    $table->addRow();
                    $table->addCell("Cheque", 'center', 'footer',2);
                    $table->addCell($cheque, 'center', 'footer',2);
                    $table->addCell("Transferência", 'center', 'footer',2);
                    $table->addCell($transferencia, 'center', 'footer',2);
                    $table->addRow();

                     $output = "app/output/tabular.pdf";
                     // grava o arquivo resultante
                         if (!file_exists($output) OR is_writable($output)) {
                         $table->save($output);
                         parent::openFile($output);
                     }else{
                        throw new Exception(_t('Permission denied') . ': ' . $output);
                     }
                     new TMessage('info', 'Report generated');
                 }else{
                     new TMessage('error', 'No records found');
                 }
            $this->form->setData($this->form->getData());
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onParcelas($param = null) 
    {
        try
        {
            TApplication::loadPage('ParcelasContaList', 'onShow');

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
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

        if (isset($data->forma_pagamento) AND ( (is_scalar($data->forma_pagamento) AND $data->forma_pagamento !== '') OR (is_array($data->forma_pagamento) AND (!empty($data->forma_pagamento)) )) )
        {

            $filters[] = new TFilter('forma_pagamento', '=', $data->forma_pagamento);// create the filter 
        }

        if (isset($data->natureza_id) AND ( (is_scalar($data->natureza_id) AND $data->natureza_id !== '') OR (is_array($data->natureza_id) AND (!empty($data->natureza_id)) )) )
        {

            $filters[] = new TFilter('natureza_id', '=', $data->natureza_id);// create the filter 
        }

        if (isset($data->quitada) AND ( (is_scalar($data->quitada) AND $data->quitada !== '') OR (is_array($data->quitada) AND (!empty($data->quitada)) )) )
        {

            $filters[] = new TFilter('quitada', '=', $data->quitada);// create the filter 
        }

        if (isset($data->dt_vencimento) AND ( (is_scalar($data->dt_vencimento) AND $data->dt_vencimento !== '') OR (is_array($data->dt_vencimento) AND (!empty($data->dt_vencimento)) )) )
        {

            $filters[] = new TFilter('dt_vencimento', '>=', $data->dt_vencimento);// create the filter 
        }

        if (isset($data->dt_emissao) AND ( (is_scalar($data->dt_emissao) AND $data->dt_emissao !== '') OR (is_array($data->dt_emissao) AND (!empty($data->dt_emissao)) )) )
        {

            $filters[] = new TFilter('dt_emissao', '>=', $data->dt_emissao);// create the filter 
        }

        if (isset($data->dt_vencimento_final) AND ( (is_scalar($data->dt_vencimento_final) AND $data->dt_vencimento_final !== '') OR (is_array($data->dt_vencimento_final) AND (!empty($data->dt_vencimento_final)) )) )
        {

            $filters[] = new TFilter('dt_vencimento', '<=', $data->dt_vencimento_final);// create the filter 
        }

        if (isset($data->dt_emissao_final) AND ( (is_scalar($data->dt_emissao_final) AND $data->dt_emissao_final !== '') OR (is_array($data->dt_emissao_final) AND (!empty($data->dt_emissao_final)) )) )
        {

            $filters[] = new TFilter('dt_emissao', '<=', $data->dt_emissao_final);// create the filter 
        }

        if (isset($data->loja) AND ( (is_scalar($data->loja) AND $data->loja !== '') OR (is_array($data->loja) AND (!empty($data->loja)) )) )
        {

            $filters[] = new TFilter('loja', '=', $data->loja);// create the filter 
        }

        if (isset($data->fornecedor) AND ( (is_scalar($data->fornecedor) AND $data->fornecedor !== '') OR (is_array($data->fornecedor) AND (!empty($data->fornecedor)) )) )
        {

            $filters[] = new TFilter('fornecedor', '=', $data->fornecedor);// create the filter 
        }

        TTransaction::open(self::$database);
        $a_pagar    = 0;
        $pago       = 0;
        //PESQUISA o repositorio
        $repository = new TRepository(self::$activeRecord);
        $criteria2  = new Tcriteria;
        foreach ($filters as $filter) 
        {
            $criteria2->add($filter); 
        }
        $objects = $repository->load($criteria2, FALSE);

        foreach($objects as $object){
            if($object->quitada=='t'){
                $pago       += $object->valor;
            }else{
                $a_pagar    += $object->valor;
            }
        }
        //envia par a tela

        $data           = $this->form->getData();
        $data->a_pagar  = $a_pagar;
        $data->pago     = $pago;
        $this->form->setData($data);

        TTransaction::close();

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

            // creates a repository for Conta
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

                    //parcelas
                    TTransaction::open(self::$database);
                    $totalPago      = 0;
                    $qtdParcelas    = 0;
                    $valorProg      = 0;
                    $parcelas       = ParcelasConta::where('conta_origem','=',$object->id)->load();
                    $pagamento      = false; 
                    if($parcelas){
                        foreach($parcelas as $parcela){
                            $totalPago = $parcela->quitada=='t'?$totalPago+$parcela->valor:$totalPago;
                            $valorProg = $parcela->quitada=='f'?$valorProg+$parcela->valor:$valorProg;
                            $qtdParcelas ++;

                        }
                    }
                    $object->qtdParcelas    = $qtdParcelas;
                    $object->totalPago      = $totalPago;
                    $object->valorProg      = $valorProg;
                    $object->valorRestante  = $object->valor - $totalPago;

                    $fornecedor             = new Fornecedor($object->fornecedor);
                    $object->fornecedor     = $fornecedor->razao_social;

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

public function formatarQuitacao($stock, $object, $row)
 {
     if ($object->quitada =='t'){
         return "<span style='color:green'>Sim</span>";
     }else{
         return "<span style='color:red'>Não</span>";
     }
 }

}

