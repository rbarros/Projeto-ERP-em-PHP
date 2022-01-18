<?php

class VendaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'vendas_base';
    private static $activeRecord = 'Venda';
    private static $primaryKey = 'id';
    private static $formName = 'form_Venda';

    use BuilderMasterDetailTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Venda");


        $id = new TEntry('id');
        $id_interno = new TEntry('id_interno');
        $n_venda = new TEntry('n_venda');
        $id_venda = new TEntry('id_venda');
        $variavel_duplicidade = new TEntry('variavel_duplicidade');
        $dt_venda = new TDateTime('dt_venda');
        $loja = new TDBCombo('loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $cliente_id = new TDBCombo('cliente_id', 'base_banco', 'Pessoa', 'id', '{nome}','nome asc'  );
        $vendedor_id = new TEntry('vendedor_id');
        $status = new TCombo('status');
        $forma_pagamento = new TCombo('forma_pagamento');
        $caixa = new TEntry('caixa');
        $func_caixa = new TEntry('func_caixa');
        $fiscal = new TCombo('fiscal');
        $id_nfce = new TEntry('id_nfce');
        $nfce = new TEntry('nfce');
        $status_nfce = new TEntry('status_nfce');
        $obs = new TText('obs');
        $total_pagamentos = new TNumeric('total_pagamentos', '2', ',', '.' );
        $total_produtos = new TNumeric('total_produtos', '2', ',', '.' );
        $total_desconto = new TNumeric('total_desconto', '2', ',', '.' );
        $valor_total = new TNumeric('valor_total', '2', ',', '.' );

        $id_interno->addValidation("Id interno", new TRequiredValidator()); 
        $n_venda->addValidation("N venda", new TRequiredValidator()); 
        $dt_venda->addValidation("Data da venda", new TRequiredValidator()); 
        $loja->addValidation("Loja", new TRequiredValidator()); 

        $dt_venda->setMask('dd/mm/yyyy hh:ii');
        $dt_venda->setDatabaseMask('yyyy-mm-dd hh:ii');
        $fiscal->setDefaultOption(false);

        $fiscal->addItems(['T'=>'SIM','F'=>'NÃO']);
        $status->addItems(['Negada'=>'Negada','Autorizada'=>' Autorizada','completed'=>' finalizada','Erro'=>'Erro','Duplicata'=>'Duplicata','Cancelada'=>'Cancelada']);
        $forma_pagamento->addItems(['Dinheiro'=>' Dinheiro','Pagamento misto'=>' Pagamento misto','Cartão Credito à Vista'=>' Cartão Credito à Vista','Cartão Débito'=>' Cartão Débito','Cartão Credito parcelado'=>' Cartão Credito parcelado','pix'=>' Pix']);

        $caixa->setMaxLength(30);
        $n_venda->setMaxLength(30);
        $id_interno->setMaxLength(20);
        $func_caixa->setMaxLength(50);
        $variavel_duplicidade->setMaxLength(600);

        $id->setEditable(false);
        $loja->setEditable(false);
        $nfce->setEditable(false);
        $caixa->setEditable(false);
        $n_venda->setEditable(false);
        $id_nfce->setEditable(false);
        $dt_venda->setEditable(false);
        $id_venda->setEditable(false);
        $cliente_id->setEditable(false);
        $id_interno->setEditable(false);
        $func_caixa->setEditable(false);
        $vendedor_id->setEditable(false);
        $status_nfce->setEditable(false);
        $valor_total->setEditable(false);
        $total_produtos->setEditable(false);
        $total_desconto->setEditable(false);
        $total_pagamentos->setEditable(false);
        $variavel_duplicidade->setEditable(false);

        $id->setSize(100);
        $loja->setSize('100%');
        $nfce->setSize('100%');
        $caixa->setSize('100%');
        $fiscal->setSize('100%');
        $status->setSize('100%');
        $n_venda->setSize('100%');
        $obs->setSize('100%', 70);
        $id_nfce->setSize('100%');
        $id_venda->setSize('100%');
        $dt_venda->setSize('100%');
        $cliente_id->setSize('100%');
        $id_interno->setSize('100%');
        $func_caixa->setSize('100%');
        $status_nfce->setSize('100%');
        $valor_total->setSize('100%');
        $vendedor_id->setSize('100%');
        $total_produtos->setSize('100%');
        $total_desconto->setSize('100%');
        $forma_pagamento->setSize('100%');
        $total_pagamentos->setSize('100%');
        $variavel_duplicidade->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("ID venda:", null, '14px', null, '100%'),$id],[],[new TLabel("ID interno:", '#000000', '14px', null, '100%'),$id_interno],[new TLabel("Nº da venda:", '#0c0b0b', '14px', null, '100%'),$n_venda],[new TLabel("ID PDV:", null, '14px', null),$id_venda],[new TLabel("Variavel duplicidade:", null, '14px', null, '100%'),$variavel_duplicidade],[new TLabel("Data da venda:", '#000000', '14px', null, '100%'),$dt_venda]);
        $row1->layout = [' col-sm-6 col-lg-1',' col-sm-2 col-lg-3',' col-sm-2 col-lg-1',' col-sm-6 col-lg-1',' col-sm-2 col-lg-1',' col-sm-2 col-lg-3',' col-sm-2 col-lg-2'];

        $row2 = $this->form->addFields([new TLabel("Loja:", '#ff0000', '14px', null, '100%'),$loja],[new TLabel("Cliente:", null, '14px', null, '100%'),$cliente_id],[new TLabel("Vendedor:", null, '14px', null, '100%'),$vendedor_id],[],[new TLabel("Status:", null, '14px', null, '100%'),$status],[new TLabel("Forma pagamento:", null, '14px', null, '100%'),$forma_pagamento]);
        $row2->layout = [' col-sm-6 col-lg-2',' col-sm-6 col-lg-2','col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row3 = $this->form->addFields([new TLabel("Caixa:", null, '14px', null, '100%'),$caixa],[new TLabel("Func caixa:", null, '14px', null, '100%'),$func_caixa],[],[new TLabel("Fiscal:", null, '14px', null, '100%'),$fiscal]);
        $row3->layout = ['col-sm-3','col-sm-3',' col-sm-6 col-lg-4','col-sm-2'];

        $row4 = $this->form->addFields([new TFormSeparator("Fiscal", '#333333', '14', '#ff0091')]);
        $row4->layout = [' col-sm-3 col-lg-12'];

        $row5 = $this->form->addFields([new TLabel("ID nfce:", null, '14px', null, '100%'),$id_nfce],[new TLabel("nº da nfce", null, '14px', null, '100%'),$nfce],[],[new TLabel("Status da emissão:", null, '14px', null, '100%'),$status_nfce]);
        $row5->layout = [' col-sm-6 col-lg-2','col-sm-2',' col-sm-2 col-lg-6','col-sm-2'];

        $row6 = $this->form->addFields([new TLabel("Observação:", null, '14px', null),$obs]);
        $row6->layout = [' col-sm-3 col-lg-12'];

        $this->detailFormVendaItemVenda = new BootstrapFormBuilder('detailFormVendaItemVenda');
        $this->detailFormVendaItemVenda->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormVendaItemVenda->setProperty('class', 'form-horizontal builder-detail-form');

        $row7 = $this->detailFormVendaItemVenda->addFields([new TFormSeparator("Itens da venda", '#333333', '14', '#ff0091')]);
        $row7->layout = [' col-sm-12'];

        $row8 = $this->detailFormVendaItemVenda->addFields([new THidden('venda_item_venda__row__id')]);
        $this->venda_item_venda_criteria = new TCriteria();

        $this->venda_item_venda_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->venda_item_venda_list->disableHtmlConversion();;
        $this->venda_item_venda_list->generateHiddenFields();
        $this->venda_item_venda_list->setId('venda_item_venda_list');

        $this->venda_item_venda_list->disableDefaultClick();
        $this->venda_item_venda_list->style = 'width:100%';
        $this->venda_item_venda_list->class .= ' table-bordered';

        $column_venda_item_venda_produto_id = new TDataGridColumn('produto_id', "ID produto", 'center' , '70px');
        $column_venda_item_venda_SKU = new TDataGridColumn('SKU', "SKU", 'left');
        $column_venda_item_venda_name = new TDataGridColumn('name', "Descrição", 'left');
        $column_venda_item_venda_valor_unitario_transformed = new TDataGridColumn('valor_unitario', "Valor", 'left');
        $column_venda_item_venda_quantidade = new TDataGridColumn('quantidade', "Quantidade", 'left');
        $column_venda_item_venda_valor_desconto_transformed = new TDataGridColumn('valor_desconto', "Desconto", 'left');
        $column_venda_item_venda_valor_total_transformed = new TDataGridColumn('valor_total', "Valor total", 'left');

        $column_venda_item_venda__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_venda_item_venda__row__data->setVisibility(false);

        $action_onEdit = new TDataGridAction(array('VendaItemForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Visualizar item");
        $action_onEdit->setImage('fas:box #18da13');
        $action_onEdit->setFields(['__row__id', '__row__data']);

        $action_onEdit->setParameter('key', '{id}');
        $this->venda_item_venda_list->addAction($action_onEdit);

        $this->venda_item_venda_list->addColumn($column_venda_item_venda_produto_id);
        $this->venda_item_venda_list->addColumn($column_venda_item_venda_SKU);
        $this->venda_item_venda_list->addColumn($column_venda_item_venda_name);
        $this->venda_item_venda_list->addColumn($column_venda_item_venda_valor_unitario_transformed);
        $this->venda_item_venda_list->addColumn($column_venda_item_venda_quantidade);
        $this->venda_item_venda_list->addColumn($column_venda_item_venda_valor_desconto_transformed);
        $this->venda_item_venda_list->addColumn($column_venda_item_venda_valor_total_transformed);

        $this->venda_item_venda_list->addColumn($column_venda_item_venda__row__data);

        $this->venda_item_venda_list->createModel();
        $this->detailFormVendaItemVenda->addContent([$this->venda_item_venda_list]);

        $column_venda_item_venda_valor_unitario_transformed->setTransformer(function($value, $object, $row) 
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

        $column_venda_item_venda_valor_desconto_transformed->setTransformer(function($value, $object, $row) 
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

        $column_venda_item_venda_valor_total_transformed->setTransformer(function($value, $object, $row) 
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
        });        $row9 = $this->form->addFields([$this->detailFormVendaItemVenda]);
        $row9->layout = [' col-sm-3 col-lg-12'];

        $this->detailFormVendaPagamentoVenda = new BootstrapFormBuilder('detailFormVendaPagamentoVenda');
        $this->detailFormVendaPagamentoVenda->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormVendaPagamentoVenda->setProperty('class', 'form-horizontal builder-detail-form');

        $row10 = $this->detailFormVendaPagamentoVenda->addFields([new TFormSeparator("Parcelas", '#333333', '14', '#ff0091')]);
        $row10->layout = [' col-sm-12'];

        $row11 = $this->detailFormVendaPagamentoVenda->addFields([new THidden('venda_pagamento_venda__row__id')]);
        $this->venda_pagamento_venda_criteria = new TCriteria();

        $this->venda_pagamento_venda_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->venda_pagamento_venda_list->disableHtmlConversion();;
        $this->venda_pagamento_venda_list->generateHiddenFields();
        $this->venda_pagamento_venda_list->setId('venda_pagamento_venda_list');

        $this->venda_pagamento_venda_list->disableDefaultClick();
        $this->venda_pagamento_venda_list->style = 'width:100%';
        $this->venda_pagamento_venda_list->class .= ' table-bordered';

        $column_venda_pagamento_venda_id = new TDataGridColumn('id', "ID pagametno", 'center' , '70px');
        $column_venda_pagamento_venda_dt_venda_transformed = new TDataGridColumn('dt_venda', "Data", 'left');
        $column_venda_pagamento_venda_metodo_pgto = new TDataGridColumn('metodo_pgto', "Forma de pagamento", 'left');
        $column_venda_pagamento_venda_valor_pgto_transformed = new TDataGridColumn('valor_pgto', "Valor", 'left');

        $column_venda_pagamento_venda__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_venda_pagamento_venda__row__data->setVisibility(false);

        $action_onEdit = new TDataGridAction(array('VendaPagamentoForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("");
        $action_onEdit->setImage('fas:dollar-sign #f6c833');
        $action_onEdit->setFields(['__row__id', '__row__data']);

        $action_onEdit->setParameter('key', '{id}');
        $this->venda_pagamento_venda_list->addAction($action_onEdit);

        $this->venda_pagamento_venda_list->addColumn($column_venda_pagamento_venda_id);
        $this->venda_pagamento_venda_list->addColumn($column_venda_pagamento_venda_dt_venda_transformed);
        $this->venda_pagamento_venda_list->addColumn($column_venda_pagamento_venda_metodo_pgto);
        $this->venda_pagamento_venda_list->addColumn($column_venda_pagamento_venda_valor_pgto_transformed);

        $this->venda_pagamento_venda_list->addColumn($column_venda_pagamento_venda__row__data);

        $this->venda_pagamento_venda_list->createModel();
        $this->detailFormVendaPagamentoVenda->addContent([$this->venda_pagamento_venda_list]);

        $column_venda_pagamento_venda_dt_venda_transformed->setTransformer(function($value, $object, $row)
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $column_venda_pagamento_venda_valor_pgto_transformed->setTransformer(function($value, $object, $row) 
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
        });        $row12 = $this->form->addFields([$this->detailFormVendaPagamentoVenda]);
        $row12->layout = [' col-sm-3 col-lg-12'];

        $row13 = $this->form->addFields([new TLabel("Total pagamentos:", null, '14px', null, '100%'),$total_pagamentos],[new TLabel("Total produtos:", null, '14px', null, '100%'),$total_produtos],[new TLabel("Descontos:", null, '14px', null, '100%'),$total_desconto],[],[new TLabel("Valor total", null, '15px', 'B'),$valor_total]);
        $row13->layout = ['col-sm-6 col-lg-2','col-sm-6 col-lg-2','col-sm-2',' col-sm-2 col-lg-4','col-sm-2'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onvoltar = $this->form->addAction("Voltar", new TAction([$this, 'onVoltar']), 'fas:arrow-left #ffffff');
        $this->btn_onvoltar = $btn_onvoltar;
        $btn_onvoltar->addStyleClass('btn-success'); 

        $btn_oncancelar = $this->form->addAction("Cancelar venda", new TAction([$this, 'onCancelar']), 'fas:ban #fe0000');
        $this->btn_oncancelar = $btn_oncancelar;

        $btn_onnfce = $this->form->addAction("Emitir/Reemitir Nfce", new TAction([$this, 'onNfce']), 'fas:envelope-open-text #18da13');
        $this->btn_onnfce = $btn_onnfce;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Financeiro","VendaForm"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Venda(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            $venda_pagamento_venda_items = $this->storeMasterDetailItems('VendaPagamento', 'venda_id', 'venda_pagamento_venda', $object, $param['venda_pagamento_venda_list___row__data'] ?? [], $this->form, $this->venda_pagamento_venda_list, function($masterObject, $detailObject){ 

                //code here

            }); 

            $venda_item_venda_items = $this->storeMasterDetailItems('VendaItem', 'venda_id', 'venda_item_venda', $object, $param['venda_item_venda_list___row__data'] ?? [], $this->form, $this->venda_item_venda_list, function($masterObject, $detailObject){ 

                //code here

            }); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onVoltar($param = null) 
    {
        try 
        {
            TApplication::loadPage('VendasList', 'onReload');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onCancelar($param = null) 
    {
        try 
        {
               if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                $object = new StdClass();//para manter o form preenchido
                $object->id_interno         = $param['id_interno'];
                $object->id_externo         = $param['id_externo'];
                $object->loja               = $param['loja'];
                $object->dt_venda           = $param['dt_venda'];
                $object->tabela_preco       = $param['tabela_preco'];
                $object->func_caixa         = $param['func_caixa'];
                $object->cliente_id         = $param['cliente_id'];
                $object->fiscal             = $param['fiscal'];
                $object->nfce               = $param['nfce'];
                $object->status_nfce        = $param['status_nfce'];
                $object->sub_total          = $param['sub_total'];
                $object->valor_pago         = $param['valor_pago'];
                $object->total_desconto     = $param['total_desconto'];
                $object->valor_total        = $param['valor_total'];
                $object->obs                = $param['obs'];
                $object->id                 = $param['id'];

                VendaService::CancelarVenda($param['id']);

                TForm::sendData(self::$formName, $object);
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
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
            TTransaction::rollback(); 
        }
    }
    public function onNfce($param = null) 
    {
        try 
        {
            $data= $this->form->getData();
            TTransaction::open(self::$database);
            $venda              = new Venda($data->id);
            $param              = array();
            $param['usuario']   = TSession::getValue('username');
            $param['origem']    = "ERP";
            $param['fiscal']    = 1;
            $retorno            = VendaService::isFiscal($venda,$param);
            new TMessage('info',"NFC-e Gerada, cupom: $retorno");
            var_dump($retorno);
            $this->form->setData($data);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());   
            $this->form->setData($this->form->getData());
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Venda($key); // instantiates the Active Record 

                $venda_pagamento_venda_items = $this->loadMasterDetailItems('VendaPagamento', 'venda_id', 'venda_pagamento_venda', $object, $this->form, $this->venda_pagamento_venda_list, $this->venda_pagamento_venda_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $venda_item_venda_items = $this->loadMasterDetailItems('VendaItem', 'venda_id', 'venda_item_venda', $object, $this->form, $this->venda_item_venda_list, $this->venda_item_venda_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 
                $nfces = Nfce::where('numVenda','=',$object->id_interno)->load();
                if($nfces){
                   $nfce                = $nfces[0]; 
                   $object->nfce        = $nfce->n_nfce;
                   $object->status_nfce = $nfce->status;
                   $object->id_nfce     = $nfce->id;
                }
                /*
                foreach($venda_pagamento_venda_items as $pagamentoVenda){
                    $object->total_pagamentos = $object->total_pagamentos + $pagamentoVenda->valor_pgto;
                }
                foreach($venda_item_venda_items as $vendaItem){
                    $object->total_produtos = $object->total_produtos === $vendaItem->valor_total ? $object->total_produtos :  $vendaItem->valor_total;
                }*/

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    } 

}

