<?php

class VendaItemForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'vendas_base';
    private static $activeRecord = 'VendaItem';
    private static $primaryKey = 'id';
    private static $formName = 'form_VendaItem';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.60, null);
        parent::setTitle("Venda Item");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Venda Item");


        $id = new TEntry('id');
        $loja_id = new TEntry('loja_id');
        $produto_id = new TEntry('produto_id');
        $deposito = new TEntry('deposito');
        $venda_id = new TDBCombo('venda_id', 'vendas_base', 'Venda', 'id', '{id}','id asc'  );
        $dt_venda = new TDate('dt_venda');
        $name = new TEntry('name');
        $SKU = new TEntry('SKU');
        $valor_unitario = new TNumeric('valor_unitario', '2', ',', '.' );
        $quantidade = new TEntry('quantidade');
        $valor_desconto = new TNumeric('valor_desconto', '2', ',', '.' );
        $valor_total = new TNumeric('valor_total', '2', ',', '.' );
        $cest = new TDBEntry('cest', 'base_banco', 'Cest', 'id','id asc'  );
        $ncm = new TDBEntry('ncm', 'base_banco', 'Ncm', 'id','id asc'  );
        $cfop = new TEntry('cfop');
        $percentual = new TEntry('percentual');
        $unidadeMedida = new TEntry('unidadeMedida');
        $situacaoTributaria = new TEntry('situacaoTributaria');
        $origem = new TEntry('origem');

        $produto_id->addValidation("Produto", new TRequiredValidator()); 
        $venda_id->addValidation("Código da venda", new TRequiredValidator()); 
        $dt_venda->addValidation("Dt venda", new TRequiredValidator()); 
        $valor_unitario->addValidation("Valor", new TRequiredValidator()); 
        $quantidade->addValidation("Quantidade", new TRequiredValidator()); 

        $dt_venda->setMask('dd/mm/yyyy');
        $dt_venda->setDatabaseMask('yyyy-mm-dd');

        $ncm->setDisplayMask('{n_ncm}  {cest} ');
        $cest->setDisplayMask('{n_cest}  {descricao} ');

        $SKU->setMaxLength(50);
        $name->setMaxLength(200);
        $unidadeMedida->setMaxLength(4);

        $id->setEditable(false);
        $SKU->setEditable(false);
        $name->setEditable(false);
        $loja_id->setEditable(false);
        $deposito->setEditable(false);
        $venda_id->setEditable(false);
        $dt_venda->setEditable(false);
        $produto_id->setEditable(false);
        $valor_total->setEditable(false);
        $unidadeMedida->setEditable(false);

        $id->setSize(100);
        $ncm->setSize('100%');
        $SKU->setSize('100%');
        $name->setSize('100%');
        $cfop->setSize('100%');
        $cest->setSize('100%');
        $origem->setSize('100%');
        $loja_id->setSize('100%');
        $dt_venda->setSize('100%');
        $venda_id->setSize('100%');
        $deposito->setSize('100%');
        $produto_id->setSize('100%');
        $percentual->setSize('100%');
        $quantidade->setSize('100%');
        $valor_total->setSize('100%');
        $unidadeMedida->setSize('100%');
        $valor_unitario->setSize('100%');
        $valor_desconto->setSize('100%');
        $situacaoTributaria->setSize('100%');

        $this->form->appendPage("Produto");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[],[new TLabel("Loja id:", null, '14px', null, '100%'),$loja_id],[new TLabel("Produto ID:", '#000000', '14px', null, '100%'),$produto_id],[new TLabel("Deposito:", null, '14px', null, '100%'),$deposito],[new TLabel("Código da venda:", '#000000', '14px', null, '100%'),$venda_id],[new TLabel("Data da venda:", '#000000', '14px', null, '100%'),$dt_venda]);
        $row1->layout = [' col-sm-6 col-lg-1',' col-sm-2 col-lg-1',' col-sm-2 col-lg-2',' col-sm-6 col-lg-2',' col-sm-2 col-lg-2',' col-sm-2 col-lg-2',' col-sm-2 col-lg-2'];

        $row2 = $this->form->addFields([new TLabel("Descrição do produto:", null, '14px', null, '100%'),$name],[new TLabel("SKU:", null, '14px', null, '100%'),$SKU]);
        $row2->layout = [' col-lg-6',' col-lg-6'];

        $row3 = $this->form->addFields([new TLabel("Valor:", '#000000', '14px', null, '100%'),$valor_unitario],[new TLabel("Quantidade:", '#000000', '14px', null, '100%'),$quantidade],[new TLabel("Desconto:", null, '14px', null, '100%'),$valor_desconto],[new TLabel("Valor total:", null, '14px', null, '100%'),$valor_total]);
        $row3->layout = ['col-sm-3','col-sm-3',' col-sm-6 col-lg-3',' col-sm-2 col-lg-3'];

        $this->form->appendPage("fiscal");
        $row4 = $this->form->addFields([new TLabel("Cest:", null, '14px', null, '100%'),$cest],[new TLabel("Ncm:", null, '14px', null, '100%'),$ncm]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([new TLabel("Cfop:", null, '14px', null, '100%'),$cfop],[new TLabel("Percentual:", null, '14px', null, '100%'),$percentual]);
        $row5->layout = ['col-sm-6','col-sm-6'];

        $row6 = $this->form->addFields([new TLabel("UnidadeMedida:", null, '14px', null, '100%'),$unidadeMedida],[new TLabel("SituacaoTributaria:", null, '14px', null, '100%'),$situacaoTributaria]);
        $row6->layout = ['col-sm-6','col-sm-6'];

        $row7 = $this->form->addFields([new TLabel("Origem:", null, '14px', null, '100%'),$origem]);
        $row7->layout = ['col-sm-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new VendaItem(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

                TWindow::closeWindow(parent::getId()); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

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
                $key = $param['key'];
                TTransaction::open(self::$database); 

                $object = new VendaItem($key); // instantiates the Active Record 

                TTransaction::open('base_banco');
                $produtos = Produto::where('id','=',$object->produto_id)->load();
                TTransaction::close();
                if($produtos){
                    $produto = $produtos[0];
                    $object->ncm                = $produto->ncm;
                    $object->cest               = $produto->cest;
                    $object->situacaoTributaria = $produto->sit_tribut;
                    $object->origem             = $produto->origem;
                }

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
            //TTransaction::rollback(); // undo all pending operations
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

