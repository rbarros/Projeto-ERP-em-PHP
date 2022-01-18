<?php

class TabelaPrecoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'TabelaPreco';
    private static $primaryKey = 'id';
    private static $formName = 'form_TabelaPreco';

    private $datagrid,$pageNavigation, $loaded  ;
    private $filter_criteria;

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
        $this->form->setFormTitle("Tabela de Preços");


        $id = new TEntry('id');
        $nome_tabela_preco = new TDBEntry('nome_tabela_preco', 'base_banco', 'TabelaPreco', 'nome_tabela_preco','nome_tabela_preco asc'  );

        $nome_tabela_preco->addValidation("Nome tabela preco", new TRequiredValidator()); 

        $nome_tabela_preco->setDisplayMask('{nome_tabela_preco}');
        $id->setEditable(false);

        $id->setSize(100);
        $nome_tabela_preco->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Nome tabela preco:", null, '14px', null, '100%'),$nome_tabela_preco]);
        $row1->layout = ['col-sm-3',' col-sm-9'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onrecarregar = $this->form->addAction("Recarregar", new TAction([$this, 'onRecarregar']), 'fas:redo #000000');
        $this->btn_onrecarregar = $btn_onrecarregar;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Estoque","Tabela de Preços"]));
        }
        $container->add($this->form);

         //CRIA A DATAGRID
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->width = "100%";
        //CRIA AS COLUNAS
        $col_id             = new TDataGridColumn('id_produto', 'Id', 'left', '10%');
        $col_SKU            = new TDataGridColumn('sku', 'SKU', 'left', '10%');
        $col_produto        = new TDataGridColumn('produto', 'Produto', 'left', '40%');
        $col_preco_custo    = new TDataGridColumn('preco_custo', 'preço custo', 'center', '10%');
        $col_preco_venda    = new TDataGridColumn('preco_venda', 'preco venda', 'center', '10%');
        //ADICIONA ACOES DA COLUNA
        $col_id->setAction                  ( new TAction([$this, 'onReload']), ['order' => 'id']);
        //FORMATACAO PARA TODA A COLUNA
        //ADICIONA AS COLUNAS NA DATAGRID
        $this->datagrid->addColumn($col_id);
        $this->datagrid->addColumn($col_SKU);
        $this->datagrid->addColumn($col_produto);
        $this->datagrid->addColumn($col_preco_custo);
        $this->datagrid->addColumn($col_preco_venda);
        //CRIA O MODELO DA DATAGRID NA MEMORIA
        $this->datagrid->createModel();
        //CMAPO DE BUSCA
        $input_search = new TEntry('input_search');
        $input_search->placeholder = _t('Search');
        $input_search->setSize('100%');
        //HABILITA A BUSC ANA DATAGRID
        $this->datagrid->enableSearch($input_search, 'id_produto, sku, produto');
        //CRIA UM PAGINADOR DE REGISTROS
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        //EMPACOTA TUDO USANDO UM CONTAINER

        $container->add(TPanelGroup::pack($input_search,$this->datagrid, $this->pageNavigation));
        //ADICIONA O CONTAINER A PAGINA

        $this->filter_criteria = new TCriteria;

        parent::add($container);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction
            $messageAction = null;
            $this->form->validate(); // validate form data
            $object = new TabelaPreco(); // create an empty object 
            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

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
    public function onRecarregar($param = null) 
    {
        try 
        {
            $this->onReload();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
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
                $object = new TabelaPreco($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                $var = [$key];
                $valor = TSession::setValue('tabela', $var);
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

function show()
 {
 //$this->onReload();
 parent::show();
 }
    public function onReload($param = null) 
    {
        try 
        {
        $data = $this->form->getData();
        $repository = new TRepository('Preco');
        $limit = 30;
        $itemRow = new stdClass();
        $itemRow->vendido =0;
        TTransaction::open(self::$database);
        // instancia um critério

         $valor = TSession::getValue('tabela');
         $criteria = new TCriteria();
         $criteria->add(new TFilter('id_tabela','=',$valor));

         // define uma ordem default
         if (empty($param['order']))
         {
         $param['order'] = 'id';
         $param['direction'] = 'desc';
         }
         // configura o critério com base nos parâmetros da URL
         $criteria->setProperties($param); // order, offset
         $criteria->setProperty('limit', $limit);
        $this->datagrid->clear();
        //array de produtos

        $objects = $repository->load($criteria);
        if($objects){
            foreach ($objects as $object){
                $produto                         = new Produto ($object->id_produto);
                $itemRow->sku                    = $produto->SKU;
                $itemRow->produto                = $produto->descricao." ".
                                                   $produto->desc_variacao." ".
                                                   $produto->referencia;
               $itemRow->preco_custo             = $object->preco_custo;
               $itemRow->preco_venda             = $object->preco_venda;
               $itemRow->id_produto              = $object->id_produto;
               $this->datagrid->addItem($itemRow);
             }
        }
        // reset nos critérios (limit, offset)
        $criteria->resetProperties();
        $count = $repository->count($criteria);
        $this->pageNavigation->setCount($count);        // qtde registros
        $this->pageNavigation->setProperties($param);   // order, page
        $this->pageNavigation->setLimit($limit);        // limit
        TTransaction::close();
        $this->loaded = true;
        $this->form->setData($data); 
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }//</end>

}

