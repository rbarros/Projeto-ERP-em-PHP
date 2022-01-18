<?php

class EnviarDepositoForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'SubparcelasParcela';
    private static $primaryKey = 'id';
    private static $formName = 'form_SubparcelasParcela';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.40, null);
        parent::setTitle("EnviarDeposito");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("EnviarDeposito");


        $id = new TEntry('id');
        $id_parcela_mestre = new TEntry('id_parcela_mestre');
        $loja_id = new TDBCombo('loja_id', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $message = new TText('message');

        $id->setEditable(false);
        $loja_id->setEditable(false);
        $id_parcela_mestre->setEditable(false);

        $id->setSize('100%');
        $loja_id->setSize('100%');
        $message->setSize('100%', 400);
        $id_parcela_mestre->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("ID subparcela:", null, '14px', null, '100%'),$id],[],[new TLabel("Parcela principal:", null, '14px', null, '100%'),$id_parcela_mestre],[new TLabel("Loja:", null, '14px', null, '100%'),$loja_id]);
        $row1->layout = ['col-sm-3 col-lg-2',' col-sm-3 col-lg-4','col-sm-2','col-sm-6 col-lg-4'];

        $row2 = $this->form->addFields([new TLabel("Texto apra envio do depósito:", null, '14px', null),$message]);
        $row2->layout = [' col-sm-3 col-lg-12'];

        // create the form actions
        $btn_onenviarunico = $this->form->addAction("Enviar Deposito", new TAction([$this, 'onEnviarUnico']), 'fab:telegram-plane #ffffff');
        $this->btn_onenviarunico = $btn_onenviarunico;
        $btn_onenviarunico->addStyleClass('btn-primary'); 

        $btn_onenviartodos = $this->form->addAction("Enviar para todos", new TAction([$this, 'onEnviarTodos']), 'fab:telegram #ffffff');
        $this->btn_onenviartodos = $btn_onenviartodos;
        $btn_onenviartodos->addStyleClass('btn-success'); 

        parent::add($this->form);

    }

    public function onEnviarUnico($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $data           = $this->form->getData();
            $chatapi        = Chatapi::where('loja','=',$data->loja_id)->load();
            if($chatapi){
                $chat       = $chatapi[0];
                ApiManager::sendMessage2($data->message,$chat->id);
            }
            $subparcela = new SubparcelasParcela($data->id);
            $agora = date('d/m/Y H:i:s');
            $subparcela->obs = $subparcela->obs."\nEnviado pelo Telegram em: $agora";
            $subparcela->store();
            TTransaction::close();
            $this->form->setData($data);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onEnviarTodos($param = null) 
    {
        try 
        {
           $data                = $this->form->getData();
           $parcela_mestre      = $data->id_parcela_mestre;
           $hoje                = date('d/m/Y');
           TTransaction::open(self::$database);
           $subparcelas         = SubparcelasParcela::where('id_parcela_mestre','=',$parcela_mestre)->load();
           foreach($subparcelas as $subparcela){
                $message        = "Olá! \n\nSegue abaixo o depósito a ser realizado hoje $hoje. ";
                $fornecedor     = new Fornecedor($subparcela->fornecedor_id);
                $message        = $message."\n\nFavorecido: ".$fornecedor->nome_fantasia;
                $message        = $message."\nCNPJ: ".$fornecedor->cnpj;
                $conta_bancaria = new ContaBancaria($subparcela->conta_bancaria_fornecedor);
                $message        = $message."\nNúmero da conta: ".$conta_bancaria->numero_conta;
                $message        = $message."\nAgencia: ".$conta_bancaria->agencia;
                $message        = $message."\nBanco: ".$conta_bancaria->fk_banco->cod.' - '.$conta_bancaria->fk_banco->nome;
                $object->valor  = doubleval($object->valor);
                $message        = $message."\n\n Valor(R$): $subparcela->valor";
                $message        = $message."\n\nFavor realizar o depósito e enviar o comprovante através do link abaixo: ";
                $message        = $message."\n http://192.241.159.164/index.php?class=ComprovanteForm&method=onEdit&key=$subparcela->id&id=$subparcela->id"; 
                $chatapi        = Chatapi::where('loja','=',$subparcela->loja_id)->load();
                if($chatapi){
                    $chat       = $chatapi[0];
                    ApiManager::sendMessage2($message,$chat->id);
                    $agora = date('d/m/Y H:i:s');
                    $subparcela->obs = $subparcela->obs."\nEnviado pelo Telegram em: $agora";
                    $subparcela->store();
                }else{
                    $data=$this->form->GetData();
                    throw new Exception("ID do Telegram não localizado para a loja $data->loja_id, entre em contato com o suporte.");
                    $this->form->setData($data);
                }

           }
           TTransaction::close();
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

                $object = new SubparcelasParcela($key); // instantiates the Active Record 
                $data           = $this->form->getData();
                $hoje           = date('d/m/Y');
                $message        = "Olá! \n\nSegue abaixo o depósito a ser realizado hoje $hoje. ";
                $fornecedor     = new Fornecedor($object->fornecedor_id);
                $message        = $message."\n\nFavorecido: ".$fornecedor->nome_fantasia;
                $message        = $message."\nCNPJ: ".$fornecedor->cnpj;
                $conta_bancaria = new ContaBancaria($object->conta_bancaria_fornecedor);
                $message        = $message."\nNúmero da conta: ".$conta_bancaria->numero_conta;
                $message        = $message."\nAgencia: ".$conta_bancaria->agencia;
                $message        = $message."\nBanco: ".$conta_bancaria->fk_banco->cod.' - '.$conta_bancaria->fk_banco->nome;
                $object->valor  = doubleval($object->valor);
                $message        = $message."\n\n Valor(R$): $object->valor";
                $message        = $message."\n\nFavor realizar o depósito e enviar o comprovante através do link abaixo: ";
                $message        = $message."\n http://192.241.159.164/index.php?class=ComprovanteForm&method=onEdit&key=$object->id&id=$object->id";

                $data->message              = $message;
                $data->loja_id              = $object->loja_id;
                $data->id                   = $object->id;
                $data->id_parcela_mestre    = $object->id_parcela_mestre;
                $this->form->setData($data);

/*
                $this->form->setData($object); // fill the form 
*/

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

