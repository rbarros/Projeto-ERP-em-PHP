<?php

class ComprovanteForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'SubparcelasParcela';
    private static $primaryKey = 'id';
    private static $formName = 'form_SubparcelasParcela';

    use Adianti\Base\AdiantiFileSaveTrait;

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
        $this->form->setFormTitle("Envio de Comprovante");


        $id = new TEntry('id');
        $id_parcela_mestre = new TEntry('id_parcela_mestre');
        $conta_origem = new TDBCombo('conta_origem', 'base_banco', 'Conta', 'id', '{id}','id asc'  );
        $loja_id = new TDBCombo('loja_id', 'base_banco', 'Loja', 'id', '{razao_social}','razao_social asc'  );
        $fornecedor_id = new TDBCombo('fornecedor_id', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc'  );
        $valor = new TNumeric('valor', '2', ',', '.' );
        $conta_bancaria_fornecedor = new TDBCombo('conta_bancaria_fornecedor', 'base_banco', 'ContaBancaria', 'id', '{nome}','id asc'  );
        $agencia = new TEntry('agencia');
        $conta = new TEntry('conta');
        $banco = new TEntry('banco');
        $caminho = new TFile('caminho');
        $obs = new TText('obs');

        $conta_origem->addValidation("Conta origem", new TRequiredValidator()); 
        $loja_id->addValidation("Loja id", new TRequiredValidator()); 
        $valor->addValidation("Valor", new TRequiredValidator()); 

        $caminho->enableFileHandling();
        $caminho->enableImageGallery('0', NULL);

        $id->setEditable(false);
        $valor->setEditable(false);
        $conta->setEditable(false);
        $banco->setEditable(false);
        $loja_id->setEditable(false);
        $agencia->setEditable(false);
        $conta_origem->setEditable(false);
        $fornecedor_id->setEditable(false);
        $id_parcela_mestre->setEditable(false);
        $conta_bancaria_fornecedor->setEditable(false);

        $id->setSize(100);
        $valor->setSize('100%');
        $conta->setSize('100%');
        $banco->setSize('100%');
        $loja_id->setSize('100%');
        $agencia->setSize('100%');
        $caminho->setSize('100%');
        $obs->setSize('100%', 70);
        $conta_origem->setSize('100%');
        $fornecedor_id->setSize('100%');
        $id_parcela_mestre->setSize('100%');
        $conta_bancaria_fornecedor->setSize('100%');

        $row1 = $this->form->addContent([new TFormSeparator("Depósito", '#333333', '18', '#ff0091')]);
        $row2 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[],[new TLabel("Parcela principal:", null, '14px', null, '100%'),$id_parcela_mestre],[new TLabel("Conta origem:", null, '14px', null, '100%'),$conta_origem]);
        $row2->layout = [' col-sm-6 col-lg-1',' col-sm-2 col-lg-7','col-sm-2',' col-sm-6 col-lg-2'];

        $row3 = $this->form->addFields([new TLabel("Loja a realizar o depósito:", null, '14px', null, '100%'),$loja_id]);
        $row3->layout = [' col-sm-6 col-lg-12'];

        $row4 = $this->form->addFields([new TLabel("Fornecedor a realizar o depósito:", null, '14px', null, '100%'),$fornecedor_id]);
        $row4->layout = [' col-sm-3 col-lg-12'];

        $row5 = $this->form->addFields([new TLabel("Valor (R$):", null, '14px', null, '100%'),$valor]);
        $row5->layout = [' col-sm-6 col-lg-12'];

        $row6 = $this->form->addFields([new TLabel("Conta bancaria fornecedor:", null, '14px', null, '100%'),$conta_bancaria_fornecedor],[new TLabel("Agência:", null, '14px', null, '100%'),$agencia],[new TLabel("Conta:", null, '14px', null, '100%'),$conta],[new TLabel("Banco:", null, '14px', null, '100%'),$banco]);
        $row6->layout = [' col-sm-6 col-lg-4','col-sm-2','col-sm-2',' col-sm-2 col-lg-4'];

        $row7 = $this->form->addContent([new TFormSeparator("Comprovante", '#333333', '18', '#ff0091')]);
        $row8 = $this->form->addFields([new TLabel("Comprovante", null, '14px', null, '100%'),$caminho]);
        $row8->layout = [' col-sm-6 col-lg-12'];

        $row9 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$obs]);
        $row9->layout = [' col-sm-3 col-lg-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Enviar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Loja","Envio de Comprovante"]));
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

            $object = new SubparcelasParcela(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $caminho_dir = '/tmp';  

              if($data->caminho != null){
                $config                      = array();
                $config['file_path']         = $data->caminho;
                $config['user']              = TSession::getValue('username');
                $config['vencimento']        = $data->vencimento;
                $config['valor']             = $data->valor;
                $fornecedor                  = new Fornecedor($data->fornecedor_id);
                $config['fornecedor']        = $fornecedor->nome_fantasia;
                $loja                        = new Loja($data->loja_id);
                $config['loja']              = $loja->abreviacao;
                $return                      = ApiManager::sendGoogleFile($config);
                $object->link_comprovante    = $return;
            }

            $object->quitada = 't';
            $object->store(); // save the object 
            $parcela            = new ParcelasConta($data->id_parcela_mestre);
            $conta              = new Conta($data->conta_origem);
            $subparcelas        = SubparcelasParcela::where('id_parcela_mestre','=',$parcela->id)->load();
            $total_subparcelas  = 0;
            if($subparcelas){
                foreach($subparcelas as $subparcela){
                    if($subparcela->quitada='t'){
                        $total_subparcelas += $subparcela->valor;
                    }
                }
            }
            //quita a parcela principal
            if($total_subparcelas == $parcela->valor){
                $parcela->quitada = 't';
                $parcela->store();
            }
            //quita a conta
            if($total_subparcelas == $conta->valor){
                $conta->quitada = 't';
                $conta->store();
            }

/*

            $this->saveFile($object, $data, 'caminho', $caminho_dir); 

*/
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

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new SubparcelasParcela($key); // instantiates the Active Record 

                $conta_bancaria = new ContaBancaria($object->conta_bancaria_fornecedor);
                $object->agencia = $conta_bancaria->agencia;
                $object->conta   = $conta_bancaria->numero_conta;
                $object->banco   = $conta_bancaria->fk_banco->cod." - ".$conta_bancaria->fk_banco->nome;

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

