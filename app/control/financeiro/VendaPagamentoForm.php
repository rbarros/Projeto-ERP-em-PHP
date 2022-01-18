<?php

class VendaPagamentoForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'vendas_base';
    private static $activeRecord = 'VendaPagamento';
    private static $primaryKey = 'id';
    private static $formName = 'form_VendaPagamento';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.60, null);
        parent::setTitle("Parcela");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Parcela");


        $id = new TEntry('id');
        $venda_id = new TDBCombo('venda_id', 'vendas_base', 'Venda', 'id', '{id_interno}','id asc'  );
        $id_loja = new TDBCombo('id_loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $dt_venda = new TDate('dt_venda');
        $metodo_pgto = new TCombo('metodo_pgto');
        $valor_pgto = new TNumeric('valor_pgto', '2', ',', '.' );

        $venda_id->addValidation("Venda id", new TRequiredValidator()); 
        $dt_venda->addValidation("Dt venda", new TRequiredValidator()); 
        $metodo_pgto->addValidation("Metodo pgto", new TRequiredValidator()); 
        $valor_pgto->addValidation("Valor pgto", new TRequiredValidator()); 

        $dt_venda->setMask('dd/mm/yyyy');
        $dt_venda->setDatabaseMask('yyyy-mm-dd');
        $metodo_pgto->addItems(['Dinheiro'=>'Dinheiro','Cartão Débito'=>'Cartão Débito','Cartão Credito à Vista'=>'Cartão Credito à Vista','Cartão Credito parcelado'=>'Cartão Credito parcelado']);

        $id->setEditable(false);
        $id_loja->setEditable(false);
        $venda_id->setEditable(false);
        $dt_venda->setEditable(false);

        $id->setSize(100);
        $id_loja->setSize('100%');
        $venda_id->setSize('100%');
        $dt_venda->setSize('100%');
        $valor_pgto->setSize('100%');
        $metodo_pgto->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[],[new TLabel("Nº Venda:", null, '14px', null, '100%'),$venda_id],[new TLabel("Loja:", null, '14px', null, '100%'),$id_loja],[new TLabel("Data Pagamento:", null, '14px', null, '100%'),$dt_venda]);
        $row1->layout = [' col-sm-6 col-lg-1',' col-sm-2 col-lg-2',' col-sm-2 col-lg-3',' col-sm-2 col-lg-3',' col-sm-6 col-lg-3'];

        $row2 = $this->form->addFields([new TLabel("Forma de pagamento:", null, '14px', null, '100%'),$metodo_pgto],[new TLabel("Valor pago:", null, '14px', null, '100%'),$valor_pgto]);
        $row2->layout = ['col-sm-6','col-sm-6'];

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

            $object = new VendaPagamento(); // create an empty object 

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
                $__row__data = unserialize(base64_decode($param['__row__data']));
                $key = $__row__data->id;
                TTransaction::open(self::$database); // open a transaction

                $object = new VendaPagamento($key); // instantiates the Active Record 

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

