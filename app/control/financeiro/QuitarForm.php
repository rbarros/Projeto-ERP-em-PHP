<?php

class QuitarForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Conta';
    private static $primaryKey = 'id';
    private static $formName = 'form_Conta';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Cadastro de conta a pagar");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de conta a pagar");

        $criteria_loja = new TCriteria();

        $id = new TEntry('id');
        $loja = new TDBCombo('loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc' , $criteria_loja );
        $multa = new TDBCombo('multa', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc'  );
        $dt_vencimento = new TDate('dt_vencimento');
        $quitada = new TCombo('quitada');
        $juros = new TCombo('juros');
        $desconto = new TCombo('desconto');
        $valor = new TEntry('valor');
        $obs = new TText('obs');

        $juros->setChangeAction(new TAction([$this,'onChangeForma']));

        $loja->addValidation("Cliente", new TRequiredValidator()); 
        $dt_vencimento->addValidation("Data de vencimento", new TRequiredValidator()); 

        $dt_vencimento->setMask('dd/mm/yyyy');
        $dt_vencimento->setDatabaseMask('yyyy-mm-dd');
        $quitada->setValue('f');

        $quitada->addItems(['t'=>'SIM','f'=>'NÃO']);
        $juros->addItems(['deposito'=>' Depósito','transferencia'=>' Transferência','cheque'=>' Cheque','dinheiro'=>'Dinheiro','boleto'=>' Boleto']);
        $desconto->addItems(['ItauFlor'=>'Itaú Flor','ItauMimos'=>'Itaú Mimos','ItauTJ'=>'Itaú TJ','ItauDivvina'=>'Itaú Divvina','ItauFashion'=>'Itaú Fashion','ItauJade'=>'Itaú Jade','BBFashionBijoux'=>'BB Fashion Bijoux']);

        $id->setEditable(false);
        $loja->setEditable(false);
        $multa->setEditable(false);
        $valor->setEditable(false);
        $quitada->setEditable(false);
        $desconto->setEditable(false);
        $dt_vencimento->setEditable(false);

        $id->setSize('100%');
        $loja->setSize('100%');
        $multa->setSize('100%');
        $juros->setSize('100%');
        $valor->setSize('100%');
        $quitada->setSize('100%');
        $obs->setSize('100%', 68);
        $desconto->setSize('100%');
        $dt_vencimento->setSize('100%');

        $row1 = $this->form->addContent([new TFormSeparator("<b>Quitação</b>", '#333333', '14', '#ff0091')]);
        $row2 = $this->form->addFields([new TLabel("ID da conta:", null, '14px', null),$id],[new TLabel("Loja:", null, '14px', null, '100%'),$loja],[new TLabel("Fornecedor:", null, '14px', null, '100%'),$multa],[new TLabel("Vencimento:", null, '14px', null, '100%'),$dt_vencimento],[new TLabel("Quitada ?", null, '14px', 'B', '100%'),$quitada]);
        $row2->layout = [' col-sm-1',' col-sm-3',' col-sm-4','col-sm-2',' col-sm-2'];

        $row3 = $this->form->addFields([new TLabel("Forma de pagamento:", null, '14px', null),$juros],[new TLabel("Conta da Loja:", null, '14px', null, '100%'),$desconto],[new TLabel("Valor (R$):", null, '14px', null, '100%'),$valor],[new TLabel("Observação do pagamento:", null, '14px', null, '100%'),$obs]);
        $row3->layout = [' col-sm-5',' col-sm-5','col-sm-2',' col-sm-12'];

        // create the form actions
        $btn_onquitar = $this->form->addAction("Quitar", new TAction([$this, 'onQuitar']), 'far:check-square #ffffff');
        $this->btn_onquitar = $btn_onquitar;
        $btn_onquitar->addStyleClass('btn-success'); 

        parent::add($this->form);

    }

    public static function onChangeForma($param = null) 
    {
        try 
        {
          $pagamento = $param['juros'];
           if($pagamento == "transferencia"|| $pagamento == "boleto" || $pagamento == "cheque"){
            TCombo::enableField(self::$formName, 'desconto');
           }else{
              TCombo::disableField(self::$formName, 'desconto'); 
           }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onQuitar($param = null) 
    {
        try 
        {
            $data = $this->form->getData();
            if(isset($param['id']) && $param['id'] != ""){
                if(isset($param['quitada']) && $param['quitada'] != ""){
                    $pagamentos = $param['juros'];
                    $conta      = $param['desconto'];
                    if($pagamentos == "transferencia" && $conta == ""){
                        new TMessage('error','necessário informar uma conta bancária para quitação de transferencia');
                    }else{
                        TTransaction::open(self::$database);
                        $conta = new Conta($data->id); 
                        $conta->quitada = 't';
                        $data->quitada = 't';
                        $conta->juros = $data->juros;
                        $conta->obs = $data->obs;
                        $conta->store();
                        TTransaction::close();
                    TToast::show("success", "Conta quitada!", "bottomCenter", "fas fa-check");
                    TApplication::loadPage('ContaAPagarAdminList', 'onReload');
                    }
                }else{
                    new TMessage('error','conta já quitada');
                }
            }else{
                new TMessage('error','salve esta conta primeiro');
            }
            $this->form->setData($data);

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

                $object = new Conta($key); // instantiates the Active Record 

               if($object->juros =="transferencia" || $object->juros == "transferencia"|| $object->juros == "boleto" || $object->juros == "cheque"){
                    TCombo::enableField(self::$formName, 'desconto');
                }
                if($object->quitada =='t'){
                    TCombo::disableField(self::$formName, 'system_unit_id');
                    TCombo::disableField(self::$formName, 'natureza_id');
                    TDBUniqueSearch::disableField(self::$formName, 'multa');
                    TDate::disableField(self::$formName, 'dt_emissao');
                    TDate::disableField(self::$formName, 'dt_vencimento');
                    TNumeric::disableField(self::$formName, 'valor');
                    TCombo::disableField(self::$formName, 'juros');
                    TText::disableField(self::$formName, 'obs');
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

        TCombo::disableField(self::$formName, 'desconto');

    }

    public function onShow($param = null)
    {

    } 

}

