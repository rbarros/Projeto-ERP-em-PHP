<?php

class NegociacaoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Negociacao';
    private static $primaryKey = 'id';
    private static $formName = 'form_Negociacao';

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
        $this->form->setFormTitle("Cadastro de negociação");

        $criteria_cliente_id = new TCriteria();
        $criteria_vendedor_id = new TCriteria();

        $filterVar = Grupo::clientes;
        $criteria_cliente_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 
        $filterVar = Grupo::vendedores;
        $criteria_vendedor_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 
        $filterVar = TSession::getValue("userid");
        $criteria_vendedor_id->add(new TFilter('system_user_id', '=', $filterVar)); 

        $id = new TEntry('id');
        $descricao = new TEntry('descricao');
        $tipo_negociacao_id = new TDBCombo('tipo_negociacao_id', 'base_banco', 'TipoNegociacao', 'id', '{nome}','nome asc'  );
        $cliente_id = new TDBUniqueSearch('cliente_id', 'base_banco', 'Pessoa', 'id', 'nome','nome asc' , $criteria_cliente_id );
        $vendedor_id = new TDBCombo('vendedor_id', 'base_banco', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $origem_negociacao_id = new TDBCombo('origem_negociacao_id', 'base_banco', 'OrigemNegociacao', 'id', '{nome}','nome asc'  );
        $estado_negociacao_id = new TDBCombo('estado_negociacao_id', 'base_banco', 'EstadoNegociacao', 'id', '{nome}','nome asc'  );
        $dt_inicio_negociacao = new TDateTime('dt_inicio_negociacao');
        $dt_fim_negociacao = new TDateTime('dt_fim_negociacao');
        $obs = new TText('obs');
        $negociacao_produto_negociacao_produto_id = new TDBCombo('negociacao_produto_negociacao_produto_id', 'base_banco', 'Produto', 'id', '{descricao}','id asc'  );
        $negociacao_produto_negociacao_id = new THidden('negociacao_produto_negociacao_id');

        $descricao->addValidation("Descrição", new TRequiredValidator()); 
        $tipo_negociacao_id->addValidation("Tipo da negociação", new TRequiredValidator()); 
        $cliente_id->addValidation("Cliente", new TRequiredValidator()); 
        $vendedor_id->addValidation("Vendedor", new TRequiredValidator()); 
        $origem_negociacao_id->addValidation("Origem da negociação", new TRequiredValidator()); 
        $estado_negociacao_id->addValidation("Fase da negociação", new TRequiredValidator()); 
        $dt_inicio_negociacao->addValidation("Início da negociação", new TRequiredValidator()); 

        $id->setEditable(false);
        $cliente_id->setMinLength(2);

        $dt_fim_negociacao->setDatabaseMask('yyyy-mm-dd hh:ii');
        $dt_inicio_negociacao->setDatabaseMask('yyyy-mm-dd hh:ii');

        $cliente_id->setMask('{nome}');
        $dt_fim_negociacao->setMask('dd/mm/yyyy hh:ii');
        $dt_inicio_negociacao->setMask('dd/mm/yyyy hh:ii');

        $id->setSize(100);
        $obs->setSize('100%', 90);
        $descricao->setSize('100%');
        $cliente_id->setSize('100%');
        $vendedor_id->setSize('100%');
        $dt_fim_negociacao->setSize(150);
        $dt_inicio_negociacao->setSize(150);
        $tipo_negociacao_id->setSize('100%');
        $origem_negociacao_id->setSize('100%');
        $estado_negociacao_id->setSize('100%');
        $negociacao_produto_negociacao_produto_id->setSize('71%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id]);
        $row2 = $this->form->addFields([new TLabel("Descrição:", '#ff0000', '14px', null)],[$descricao],[new TLabel("Tipo da negociação:", '#ff0000', '14px', null)],[$tipo_negociacao_id]);
        $row3 = $this->form->addFields([new TLabel("Cliente:", '#ff0000', '14px', null)],[$cliente_id],[new TLabel("Vendedor:", '#ff0000', '14px', null)],[$vendedor_id]);
        $row4 = $this->form->addFields([new TLabel("Origem da negociação:", '#ff0000', '14px', null)],[$origem_negociacao_id],[new TLabel("Estado da negociação:", '#ff0000', '14px', null)],[$estado_negociacao_id]);
        $row5 = $this->form->addFields([new TLabel("Início da negociação:", '#ff0000', '14px', null)],[$dt_inicio_negociacao],[new TLabel("Fim da negociação:", null, '14px', null)],[$dt_fim_negociacao]);
        $row6 = $this->form->addFields([new TLabel("Observação:", null, '14px', null)],[$obs]);
        $row7 = $this->form->addContent([new TFormSeparator("Produtos", '#333333', '18', '#eeeeee')]);
        $row8 = $this->form->addFields([new TLabel("Produto:", '#ff0000', '14px', null)],[$negociacao_produto_negociacao_produto_id]);
        $row9 = $this->form->addFields([$negociacao_produto_negociacao_id]);         
        $add_negociacao_produto_negociacao = new TButton('add_negociacao_produto_negociacao');

        $action_negociacao_produto_negociacao = new TAction(array($this, 'onAddNegociacaoProdutoNegociacao'));

        $add_negociacao_produto_negociacao->setAction($action_negociacao_produto_negociacao, "Adicionar");
        $add_negociacao_produto_negociacao->setImage('fas:plus #000000');

        $this->form->addFields([$add_negociacao_produto_negociacao]);

        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->negociacao_produto_negociacao_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->negociacao_produto_negociacao_list->style = 'width:100%';
        $this->negociacao_produto_negociacao_list->class .= ' table-bordered';
        $this->negociacao_produto_negociacao_list->disableDefaultClick();
        $this->negociacao_produto_negociacao_list->addQuickColumn('', 'edit', 'left', 50);
        $this->negociacao_produto_negociacao_list->addQuickColumn('', 'delete', 'left', 50);

        $column_negociacao_produto_negociacao_produto_id = $this->negociacao_produto_negociacao_list->addQuickColumn("Produto", 'negociacao_produto_negociacao_produto_id', 'left');

        $this->negociacao_produto_negociacao_list->createModel();
        $this->form->addContent([$this->negociacao_produto_negociacao_list]);

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

            $object = new Negociacao(); // create an empty object 

            if(!$data->id)
            {
                $object->system_unit_id = TSession::getValue('userunitid');
            }

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $messageAction = new TAction(['NegociacaoList', 'onShow']);   

            if(!empty($param['target_container']))
            {
                $messageAction->setParameter('target_container', $param['target_container']);
            }

            $negociacao_produto_negociacao_items = $this->storeItems('NegociacaoProduto', 'negociacao_id', $object, 'negociacao_produto_negociacao', function($masterObject, $detailObject){ 

                //code here

            }); 

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
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Negociacao($key); // instantiates the Active Record 

                $negociacao_produto_negociacao_items = $this->loadItems('NegociacaoProduto', 'negociacao_id', $object, 'negociacao_produto_negociacao', function($masterObject, $detailObject, $objectItems){ 

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

        TSession::setValue('negociacao_produto_negociacao_items', null);

        $this->onReload();
    }

    public function onAddNegociacaoProdutoNegociacao( $param )
    {
        try
        {
            $data = $this->form->getData();

            if(!$data->negociacao_produto_negociacao_produto_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Produto"));
            }             

            $negociacao_produto_negociacao_items = TSession::getValue('negociacao_produto_negociacao_items');
            $key = isset($data->negociacao_produto_negociacao_id) && $data->negociacao_produto_negociacao_id ? $data->negociacao_produto_negociacao_id : 'b'.uniqid();
            $fields = []; 

            $fields['negociacao_produto_negociacao_produto_id'] = $data->negociacao_produto_negociacao_produto_id;
            $negociacao_produto_negociacao_items[ $key ] = $fields;

            TSession::setValue('negociacao_produto_negociacao_items', $negociacao_produto_negociacao_items);

            $data->negociacao_produto_negociacao_id = '';
            $data->negociacao_produto_negociacao_produto_id = '';

            $this->form->setData($data);

            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());

            new TMessage('error', $e->getMessage());
        }
    }

    public function onEditNegociacaoProdutoNegociacao( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('negociacao_produto_negociacao_items');

        // get the session item
        $item = $items[$param['negociacao_produto_negociacao_id_row_id']];

        $data->negociacao_produto_negociacao_produto_id = $item['negociacao_produto_negociacao_produto_id'];

        $data->negociacao_produto_negociacao_id = $param['negociacao_produto_negociacao_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->onReload( $param );

    }

    public function onDeleteNegociacaoProdutoNegociacao( $param )
    {
        $data = $this->form->getData();

        $data->negociacao_produto_negociacao_produto_id = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('negociacao_produto_negociacao_items');

        // delete the item from session
        unset($items[$param['negociacao_produto_negociacao_id_row_id']]);
        TSession::setValue('negociacao_produto_negociacao_items', $items);

        // reload sale items
        $this->onReload( $param );

    }

    public function onReloadNegociacaoProdutoNegociacao( $param )
    {
        $items = TSession::getValue('negociacao_produto_negociacao_items'); 

        $this->negociacao_produto_negociacao_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteNegociacaoProdutoNegociacao')); 
                $action_del->setParameter('negociacao_produto_negociacao_id_row_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $action_edi = new TAction(array($this, 'onEditNegociacaoProdutoNegociacao'));  
                $action_edi->setParameter('negociacao_produto_negociacao_id_row_id', $key);  
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_del = new TButton('delete_negociacao_produto_negociacao'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm';
                $button_del->title = '';
                $button_del->setImage('far:trash-alt #dd5a43');

                $rowItem->delete = $button_del;

                $button_edi = new TButton('edit_negociacao_produto_negociacao'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                $button_edi->title = '';
                $button_edi->setImage('far:edit #478fca');

                $rowItem->edit = $button_edi;

                $rowItem->negociacao_produto_negociacao_produto_id = '';
                if(isset($item['negociacao_produto_negociacao_produto_id']) && $item['negociacao_produto_negociacao_produto_id'])
                {
                    TTransaction::open('base_banco');
                    $produto = Produto::find($item['negociacao_produto_negociacao_produto_id']);
                    if($produto)
                    {
                        $rowItem->negociacao_produto_negociacao_produto_id = $produto->render('{descricao}');
                    }
                    TTransaction::close();
                }

                $row = $this->negociacao_produto_negociacao_list->addItem($rowItem);

                $cont++;
            } 
        } 
    } 

    public function onShow($param = null)
    {

        TSession::setValue('negociacao_produto_negociacao_items', null);

        $this->onReload();

    } 

    public function onReload($params = null)
    {
        $this->loaded = TRUE;

        $this->onReloadNegociacaoProdutoNegociacao($params);
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

