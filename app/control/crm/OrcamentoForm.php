<?php

class OrcamentoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Orcamento';
    private static $primaryKey = 'id';
    private static $formName = 'form_Orcamento';

    use Adianti\Base\AdiantiMasterDetailTrait;

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
        $this->form->setFormTitle("Cadastro de orçamento");

        $criteria_cliente_id = new TCriteria();
        $criteria_vendedor_id = new TCriteria();

        $filterVar = Grupo::clientes;
        $criteria_cliente_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 
        $filterVar = Grupo::vendedores;
        $criteria_vendedor_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 

        $id = new TEntry('id');
        $dt_orcamento = new TDateTime('dt_orcamento');
        $cliente_id = new TDBUniqueSearch('cliente_id', 'base_banco', 'Pessoa', 'id', 'nome','nome asc' , $criteria_cliente_id );
        $vendedor_id = new TDBCombo('vendedor_id', 'base_banco', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $frete = new TNumeric('frete', '2', ',', '.' );
        $estado_orcamento_id = new TDBCombo('estado_orcamento_id', 'base_banco', 'EstadoOrcamento', 'id', '{nome}','nome asc'  );
        $obs = new TText('obs');
        $orcamento_item_orcamento_produto_id = new TDBCombo('orcamento_item_orcamento_produto_id', 'base_banco', 'Produto', 'id', '{descricao}','descricao asc'  );
        $orcamento_item_orcamento_valor = new TNumeric('orcamento_item_orcamento_valor', '2', ',', '.' );
        $orcamento_item_orcamento_quantidade = new TNumeric('orcamento_item_orcamento_quantidade', '2', ',', '.' );
        $orcamento_item_orcamento_desconto = new TNumeric('orcamento_item_orcamento_desconto', '2', ',', '.' );
        $orcamento_item_orcamento_id = new THidden('orcamento_item_orcamento_id');

        $orcamento_item_orcamento_produto_id->setChangeAction(new TAction([$this,'onChangeProduto']));

        $dt_orcamento->addValidation("Data do orçamento", new TRequiredValidator()); 
        $cliente_id->addValidation("Cliente", new TRequiredValidator()); 
        $vendedor_id->addValidation("Vendedor", new TRequiredValidator()); 
        $estado_orcamento_id->addValidation("Estado do orçamento", new TRequiredValidator()); 

        $id->setEditable(false);
        $dt_orcamento->setValue(date('d/m/Y H:i'));
        $dt_orcamento->setDatabaseMask('yyyy-mm-dd hh:ii');
        $cliente_id->setMinLength(2);
        $vendedor_id->enableSearch();

        $cliente_id->setMask('{nome}');
        $dt_orcamento->setMask('dd/mm/yyyy hh:ii');

        $id->setSize(100);
        $frete->setSize('100%');
        $obs->setSize('70%', 100);
        $dt_orcamento->setSize(150);
        $cliente_id->setSize('100%');
        $vendedor_id->setSize('100%');
        $estado_orcamento_id->setSize('100%');
        $orcamento_item_orcamento_valor->setSize('100%');
        $orcamento_item_orcamento_desconto->setSize('100%');
        $orcamento_item_orcamento_produto_id->setSize('100%');
        $orcamento_item_orcamento_quantidade->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[new TLabel("Data do orçamento:", '#ff0000', '14px', null)],[$dt_orcamento]);
        $row2 = $this->form->addFields([new TLabel("Cliente:", '#ff0000', '14px', null)],[$cliente_id],[new TLabel("Vendedor:", '#ff0000', '14px', null)],[$vendedor_id]);
        $row3 = $this->form->addFields([new TLabel("Valor do frete:", null, '14px', null)],[$frete],[new TLabel("Estado do orçamento:", '#ff0000', '14px', null)],[$estado_orcamento_id]);
        $row4 = $this->form->addFields([new TLabel("Observação:", null, '14px', null)],[$obs]);
        $row5 = $this->form->addContent([new TFormSeparator("Produtos", '#333333', '18', '#eeeeee')]);
        $row6 = $this->form->addFields([new TLabel("Produto:", '#ff0000', '14px', null)],[$orcamento_item_orcamento_produto_id],[new TLabel("Valor:", '#ff0000', '14px', null)],[$orcamento_item_orcamento_valor]);
        $row7 = $this->form->addFields([new TLabel("Quantidade:", '#ff0000', '14px', null)],[$orcamento_item_orcamento_quantidade],[new TLabel("Desconto:", null, '14px', null)],[$orcamento_item_orcamento_desconto]);
        $row8 = $this->form->addFields([$orcamento_item_orcamento_id]);         
        $add_orcamento_item_orcamento = new TButton('add_orcamento_item_orcamento');

        $action_orcamento_item_orcamento = new TAction(array($this, 'onAddOrcamentoItemOrcamento'));

        $add_orcamento_item_orcamento->setAction($action_orcamento_item_orcamento, "Adicionar");
        $add_orcamento_item_orcamento->setImage('fas:plus #000000');

        $this->form->addFields([$add_orcamento_item_orcamento]);

        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->orcamento_item_orcamento_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->orcamento_item_orcamento_list->style = 'width:100%';
        $this->orcamento_item_orcamento_list->class .= ' table-bordered';
        $this->orcamento_item_orcamento_list->disableDefaultClick();
        $this->orcamento_item_orcamento_list->addQuickColumn('', 'edit', 'left', 50);
        $this->orcamento_item_orcamento_list->addQuickColumn('', 'delete', 'left', 50);

        $column_orcamento_item_orcamento_produto_id = $this->orcamento_item_orcamento_list->addQuickColumn("Produto", 'orcamento_item_orcamento_produto_id', 'left');
        $column_orcamento_item_orcamento_quantidade = $this->orcamento_item_orcamento_list->addQuickColumn("Quantidade", 'orcamento_item_orcamento_quantidade', 'left');
        $column_orcamento_item_orcamento_valor_transformed = $this->orcamento_item_orcamento_list->addQuickColumn("Valor", 'orcamento_item_orcamento_valor', 'left');
        $column_orcamento_item_orcamento_desconto_transformed = $this->orcamento_item_orcamento_list->addQuickColumn("Desconto", 'orcamento_item_orcamento_desconto', 'left');
        $column_calculated_2 = $this->orcamento_item_orcamento_list->addQuickColumn("Valor total", '=( ( {orcamento_item_orcamento_valor} - {orcamento_item_orcamento_desconto} ) * {orcamento_item_orcamento_quantidade}  )', 'left');

        $column_calculated_2->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $this->orcamento_item_orcamento_list->createModel();
        $this->form->addContent([$this->orcamento_item_orcamento_list]);

        $column_orcamento_item_orcamento_valor_transformed->setTransformer(function($value, $object, $row) 
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

        $column_orcamento_item_orcamento_desconto_transformed->setTransformer(function($value, $object, $row) 
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

        $column_calculated_2->setTransformer(function($value, $object, $row) 
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

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'far:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangeProduto($param = null) 
    {
        try 
        {
            if($param['key'])
            {
                TTransaction::open('mini_erp');
                $produto = new Produto($param['key']);
                TTransaction::close();

                if($produto)
                {
                    $object = new stdClass();
                    $object->orcamento_item_orcamento_valor = number_format($produto->preco_venda, 2 , ',', '.');

                    TForm::sendData(self::$formName, $object);    
                }
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Orcamento(); // create an empty object 

            if(!$data->id)
            {
                $object->system_unit_id = TSession::getValue('userunitid');
            }

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $messageAction = new TAction(['OrcamentoList', 'onShow']);   

            if(!empty($param['target_container']))
            {
                $messageAction->setParameter('target_container', $param['target_container']);
            }

            $orcamento_item_orcamento_items = $this->storeItems('OrcamentoItem', 'orcamento_id', $object, 'orcamento_item_orcamento', function($masterObject, $detailObject){ 

                $masterObject->valor_total += ($detailObject->quantidade * ($detailObject->valor - $detailObject->desconto));

            }); 
            $object->store();
            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $messageAction);
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if(!isset($param['negociacao_id']))
            {
                TTransaction::open('mini_erp');
                $negociacao = new Negociacao($param['negociacao_id']);
                TTransaction::close();

                unset($negociacao->id);

                $this->form->setData($negociacao);
                $this->onReload();
            }
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Orcamento($key); // instantiates the Active Record 

                $orcamento_item_orcamento_items = $this->loadItems('OrcamentoItem', 'orcamento_id', $object, 'orcamento_item_orcamento', function($masterObject, $detailObject){ 

                    //code here

                }); 

                $this->form->setData($object); // fill the form 

                    $this->onReload();

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

        TSession::setValue('orcamento_item_orcamento_items', null);

        $this->onReload();
    }

    public function onAddOrcamentoItemOrcamento( $param )
    {
        try
        {
            $data = $this->form->getData();

            if(!$data->orcamento_item_orcamento_produto_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Produtp"));
            }             
            if(!$data->orcamento_item_orcamento_valor)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Valor"));
            }             
            if(!$data->orcamento_item_orcamento_quantidade)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Quantidade"));
            }             

            $orcamento_item_orcamento_items = TSession::getValue('orcamento_item_orcamento_items');
            $key = isset($data->orcamento_item_orcamento_id) && $data->orcamento_item_orcamento_id ? $data->orcamento_item_orcamento_id : 'b'.uniqid();
            $fields = []; 

            $fields['orcamento_item_orcamento_produto_id'] = $data->orcamento_item_orcamento_produto_id;
            $fields['orcamento_item_orcamento_valor'] = $data->orcamento_item_orcamento_valor;
            $fields['orcamento_item_orcamento_quantidade'] = $data->orcamento_item_orcamento_quantidade;
            $fields['orcamento_item_orcamento_desconto'] = $data->orcamento_item_orcamento_desconto;
            $orcamento_item_orcamento_items[ $key ] = $fields;

            TSession::setValue('orcamento_item_orcamento_items', $orcamento_item_orcamento_items);

            $data->orcamento_item_orcamento_id = '';
            $data->orcamento_item_orcamento_produto_id = '';
            $data->orcamento_item_orcamento_valor = '';
            $data->orcamento_item_orcamento_quantidade = '';
            $data->orcamento_item_orcamento_desconto = '';

            $this->form->setData($data);

            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());

            new TMessage('error', $e->getMessage());
        }
    }

    public function onEditOrcamentoItemOrcamento( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('orcamento_item_orcamento_items');

        // get the session item
        $item = $items[$param['orcamento_item_orcamento_id_row_id']];

        $data->orcamento_item_orcamento_produto_id = $item['orcamento_item_orcamento_produto_id'];
        $data->orcamento_item_orcamento_valor = $item['orcamento_item_orcamento_valor'];
        $data->orcamento_item_orcamento_quantidade = $item['orcamento_item_orcamento_quantidade'];
        $data->orcamento_item_orcamento_desconto = $item['orcamento_item_orcamento_desconto'];

        $data->orcamento_item_orcamento_id = $param['orcamento_item_orcamento_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->onReload( $param );

    }

    public function onDeleteOrcamentoItemOrcamento( $param )
    {
        $data = $this->form->getData();

        $data->orcamento_item_orcamento_produto_id = '';
        $data->orcamento_item_orcamento_valor = '';
        $data->orcamento_item_orcamento_quantidade = '';
        $data->orcamento_item_orcamento_desconto = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('orcamento_item_orcamento_items');

        // delete the item from session
        unset($items[$param['orcamento_item_orcamento_id_row_id']]);
        TSession::setValue('orcamento_item_orcamento_items', $items);

        // reload sale items
        $this->onReload( $param );

    }

    public function onReloadOrcamentoItemOrcamento( $param )
    {
        $items = TSession::getValue('orcamento_item_orcamento_items'); 

        $this->orcamento_item_orcamento_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteOrcamentoItemOrcamento')); 
                $action_del->setParameter('orcamento_item_orcamento_id_row_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $action_edi = new TAction(array($this, 'onEditOrcamentoItemOrcamento'));  
                $action_edi->setParameter('orcamento_item_orcamento_id_row_id', $key);  
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_del = new TButton('delete_orcamento_item_orcamento'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm';
                $button_del->title = '';
                $button_del->setImage('far:trash-alt #dd5a43');

                $rowItem->delete = $button_del;

                $button_edi = new TButton('edit_orcamento_item_orcamento'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                $button_edi->title = '';
                $button_edi->setImage('far:edit #478fca');

                $rowItem->edit = $button_edi;

                $rowItem->orcamento_item_orcamento_produto_id = '';
                if(isset($item['orcamento_item_orcamento_produto_id']) && $item['orcamento_item_orcamento_produto_id'])
                {
                    TTransaction::open('base_banco');
                    $produto = Produto::find($item['orcamento_item_orcamento_produto_id']);
                    if($produto)
                    {
                        $rowItem->orcamento_item_orcamento_produto_id = $produto->render('{descricao}');
                    }
                    TTransaction::close();
                }

                $rowItem->orcamento_item_orcamento_valor = isset($item['orcamento_item_orcamento_valor']) ? $item['orcamento_item_orcamento_valor'] : '';
                $rowItem->orcamento_item_orcamento_quantidade = isset($item['orcamento_item_orcamento_quantidade']) ? $item['orcamento_item_orcamento_quantidade'] : '';
                $rowItem->orcamento_item_orcamento_desconto = isset($item['orcamento_item_orcamento_desconto']) ? $item['orcamento_item_orcamento_desconto'] : '';

                $row = $this->orcamento_item_orcamento_list->addItem($rowItem);

                $cont++;
            } 
        } 
    } 

    public function onShow($param = null)
    {

        TSession::setValue('orcamento_item_orcamento_items', null);

        $this->onReload();

    } 

    public function onReload($params = null)
    {
        $this->loaded = TRUE;

        $this->onReloadOrcamentoItemOrcamento($params);
    }

    public function show() 
    { 
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') ) 
        { 
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }

}

