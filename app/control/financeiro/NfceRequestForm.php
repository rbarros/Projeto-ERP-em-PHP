<?php

class NfceRequestForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'NfceRequestAlt';
    private static $primaryKey = 'id';
    private static $formName = 'form_NfceRequestAlt';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.70, null);
        parent::setTitle("NFCE");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("NFCE");


        $id = new TEntry('id');
        $numVenda = new TEntry('numVenda');
        $n_nfce = new TEntry('n_nfce');
        $link_cupom = new TEntry('link_cupom');
        $status = new TEntry('status');
        $status_venda = new TEntry('status_venda');
        $serie = new TEntry('serie');
        $prox_nfce = new TEntry('prox_nfce');
        $id_loja = new TEntry('id_loja');
        $ambienteEmissao = new TCombo('ambienteEmissao');
        $presencaConsumidor = new TCombo('presencaConsumidor');
        $fPagamento = new TEntry('fPagamento');
        $informacoesAdicionais = new TText('informacoesAdicionais');

        $ambienteEmissao->setChangeAction(new TAction([$this,'onChangeMode']));

        $ambienteEmissao->addItems(['Homologacao'=>' Homologação','Producao'=>' Produção']);
        $presencaConsumidor->addItems(['PagamentoAVista'=>' Pagamento à Vista','PagamentoAPrazo'=>' Pagamento a prazo']);

        $ambienteEmissao->setValue('Homologacao');
        $informacoesAdicionais->setValue('Documento emitido por ME ou EPP optante pelo Simples Nacional. Não gera direito a crédito fiscal de IPI.');

        $ambienteEmissao->setDefaultOption(false);
        $presencaConsumidor->setDefaultOption(false);

        $id->setEditable(false);
        $serie->setEditable(false);
        $n_nfce->setEditable(false);
        $status->setEditable(false);
        $id_loja->setEditable(false);
        $numVenda->setEditable(false);
        $prox_nfce->setEditable(false);
        $link_cupom->setEditable(false);
        $fPagamento->setEditable(false);
        $status_venda->setEditable(false);

        $id->setSize(100);
        $serie->setSize('100%');
        $n_nfce->setSize('100%');
        $status->setSize('100%');
        $id_loja->setSize('100%');
        $numVenda->setSize('100%');
        $prox_nfce->setSize('100%');
        $link_cupom->setSize('100%');
        $fPagamento->setSize('100%');
        $status_venda->setSize('100%');
        $ambienteEmissao->setSize('100%');
        $presencaConsumidor->setSize('100%');
        $informacoesAdicionais->setSize('100%', 70);

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Venda:", null, '14px', null, '100%'),$numVenda],[new TLabel("nº Nfce", null, '14px', null, '100%'),$n_nfce],[new TLabel("Link cupom:", null, '14px', null, '100%'),$link_cupom],[new TLabel("Status Nfc-e:", null, '14px', null, '100%'),$status],[new TLabel("Status venda:", null, '14px', null),$status_venda]);
        $row1->layout = [' col-sm-2',' col-sm-2','col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("série:", null, '14px', null),$serie],[new TLabel("Próximo número da NF:", null, '14px', null),$prox_nfce],[new TLabel("Loja:", null, '14px', null, '100%'),$id_loja]);
        $row2->layout = ['col-sm-3','col-sm-3','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Ambiente de Emissão:", null, '14px', null, '100%'),$ambienteEmissao],[new TLabel("Presenca do Consumidor:", null, '14px', null, '100%'),$presencaConsumidor],[new TLabel("Forma Pagamento:", null, '14px', null),$fPagamento]);
        $row3->layout = [' col-sm-5',' col-sm-5','col-sm-2'];

        $row4 = $this->form->addFields([new TLabel("Informações Adicionais:", null, '14px', null, '100%'),$informacoesAdicionais]);
        $row4->layout = [' col-sm-12'];

        // create the form actions
        $btn_onemitir = $this->form->addAction("Emitir / Reemitir Nfc-e", new TAction([$this, 'onEmitir']), 'fas:check #ffffff');
        $this->btn_onemitir = $btn_onemitir;
        $btn_onemitir->addStyleClass('btn-success'); 

        $btn_oncancelar = $this->form->addAction("Cancelar NFce", new TAction([$this, 'onCancelar']), 'fas:ban #ff0000');
        $this->btn_oncancelar = $btn_oncancelar;

        $btn_onimprimir = $this->form->addAction("Imprimir Cupom", new TAction([$this, 'onImprimir']), 'fas:print #6184f1');
        $this->btn_onimprimir = $btn_onimprimir;

        $btn_onconsulta = $this->form->addAction("Baixar Xml", new TAction([$this, 'onConsulta']), 'fas:code #df6b6b');
        $this->btn_onconsulta = $btn_onconsulta;

        parent::add($this->form);

    }

    public static function onChangeMode($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);
            $id_venda = $param['id_venda'];
            $nfces = Nfce::where('id_venda','=',$id_venda)->load();
             if($nfces){
                        $nfce = $nfces[0];
                        $object             = new stdClass();
                        $object->id         = $nfce->id;
                        $object->status     = $nfce->status;
                        $object->n_nfce     = $nfce->n_nfce;
                        $object->link_cupom = $nfce->link_cupom;
                        TCombo::disableField    (self::$formName, 'presencaConsumidor');
                        TText::disableField     (self::$formName, 'informacoesAdicionais');
                        TButton::disableField   (self::$formName, 'btn_gerar_nfce');
                        TButton::enableField    (self::$formName, 'btn_imprimir');
                        TButton::enableField    (self::$formName, 'btn_consultar_status');
                        TButton::enableField    (self::$formName, 'btn_cancelar_nfce');
                        TForm::sendData(self::$formName, $object);
                    }else{
                        TCombo::enableField     (self::$formName, 'presencaConsumidor');
                        TText::enableField      (self::$formName, 'informacoesAdicionais');
                        TButton::enableField    (self::$formName, 'btn_gerar_nfce');
                        TButton::disableField   (self::$formName, 'btn_imprimir');
                        TButton::disableField   (self::$formName, 'btn_consultar_status');
                        TButton::disableField   (self::$formName, 'btn_cancelar_nfce');
                    }
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onEmitir($param = null) 
    {
        try
        {
            $data= $this->form->getData();
            TTransaction::open(self::$database);
            $vendas = Venda::where('id_interno','=',$data->id_venda)->load();
            if($vendas){
                $venda = $vendas[0];
                $vigencia= false;
                $mes_venda = date( 'm', strtotime( $venda->dt_venda ) );
                $mes_venda = $mes_venda;
                $ano_venda = date( 'Y', strtotime( $venda->dt_venda ) );
                $hoje = getdate();
                $mes_atual  = '0'.$hoje['mon'];
                $ano_atual  = $hoje['year'];
                if($mes_venda == $mes_atual && $ano_venda == $ano_atual){ 
                    $vigencia= true;
                    $usuário = TSession::getValue('login');
                    VendaService::emitirNfce($venda,null,"Erp fashion Biju",$usuário);
                }else{
                    $vigencia =false;
                    new TMessage('error','Emissão de venda fora do mês de vigência');
                }

            }
            $this->form->setData($data);
            TTransaction::close();
/*
            $object = new Nfce(); // create an empty object 

            $object->store(); // save the object 

            $data->id = $object->id; 

            new TMessage('info', "Registro salvo", $messageAction); 

                TWindow::closeWindow(parent::getId()); 

*/
        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onCancelar($param = null) 
    {
        try 
        {
            $data = $this->form->getData();
            if(isset($data->n_nfce)){
if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {

                // open a transaction with database
                TTransaction::open(self::$database);

                if(isset($data->id)){
                $key = $param['id'];
                TTransaction::open(self::$database);
                $object = new Nfce($key, FALSE);
                TTransaction::close();

                Enotas_client::cancelarNota($key);

                TTransaction::open(self::$database);
                $pagamentos         = FormasPgto::where('nfce_id','=',$object->id)->load();
                if($pagamentos){
                    foreach($pagamentos as $pagamento){
                        $pagamento->delete();
                    }
                }
                $itensVenda         = Itens::where('nfce_id','=',$object->id)->load();
                if($itensVenda){
                    foreach($itensVenda as $itemVenda){
                        $itemVenda->delete();
                    }
                }
                $vendas              = Venda::where('id','=',$object->id_venda)->load();
                if($vendas){
                    $venda = $vendas[0];
                    $venda->status = "NFC-e Cancelada";
                    $venda->store();
                }
                // deletes the object from the database
                $object->delete();
                }else{
                    new TMessage ('info',' Vendão não possui NFCE "autorizada"');
                }
                // close the transaction
                TTransaction::close();

                // reload the listing

                $this->form->setData($data);
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
            $action = new TAction(array($this, 'onCancelar'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }

        }else{
            throw new Exception ("Venda sem uma NFCE 'Autorizada");
        }
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onImprimir($param = null) 
    {
        try 
        {
           $data = $this->form->getData();
           if(isset($data->n_nfce)){
        TTransaction::open(self::$database);
            $pasta = 'tmp/';
            if(!is_dir($pasta)){
                mkdir($pasta,755);
            }
            $filename = $pasta.$data->n_nfce.'.pdf';
            $fp = fopen ($filename, 'w+');
            $ch = curl_init($data->link_cupom);
            curl_setopt($ch, CURLOPT_TIMEOUT, 50);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            TPage::openFile($filename);
        TTransaction::close();
        $this->form->setData($data);
        }else{
            throw new Exception (" Venda não possui uma NFC-E 'Autorizada'");
        }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onConsulta($param = null) 
    {
        try 
        {
            //code here

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

            if ($param)
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction
                $data=$this->form->getData();
                $venda = new Venda($key);
                $data->id_venda = $venda->id_interno;
                $loja = new Loja($venda->loja);
                $ambienteEmissao = VendaService::AMBIENTE_EMISSAO;
                if($ambienteEmissao == 'Homologacao'){
                    $data->serie        = $loja-> serie_nf_homologacao;
                    $data->prox_nfce    = $loja-> seq_nf_homologacao;
                    $data->id_loja      = $loja-> nome_fantasia;
                }else{
                    $data->serie        = $loja-> serie_nf_producao;
                    $data->prox_nfce    = $loja-> seq_nf_producao;
                    $data->id_loja      = $loja-> nome_fantasia; 
                }
                $data->fPagamento  = $venda->forma_pagamento;
                $data->status_venda = $venda->status;
                $data->observacao   = $venda->obs;
                $data->Status       = "venda não fiscal";
                $nfces =  Nfce::where('id_venda','=',$venda->id_interno)
                                     ->load();  
                    if($nfces){
                        $nfce = $nfces[0];
                        $data->id               = $nfce->id;
                        $data->status           = $nfce->status;
                        $data->n_nfce           = $nfce->n_nfce;
                        $data->link_cupom       = $nfce->link_cupom;
                        TCombo::disableField    (self::$formName, 'ambienteEmissao');
                        TCombo::disableField    (self::$formName, 'presencaConsumidor');
                        TText::disableField     (self::$formName, 'informacoesAdicionais');
                        TButton::disableField   (self::$formName, 'tbutton_btn_emitir_/_reemitir_nfc-e');
                        TButton::enableField    (self::$formName, 'tbutton_btn_imprimir_cupom');
                        TButton::enableField    (self::$formName, 'tbutton_btn_baixar_xml');
                        TButton::enableField    (self::$formName, 'tbutton_btn_cancelar_nfce');

                    }else{

                        TCombo::enableField     (self::$formName, 'ambienteEmissao');
                        TCombo::enableField     (self::$formName, 'presencaConsumidor');
                        TText::enableField      (self::$formName, 'informacoesAdicionais');
                        TButton::enableField   (self::$formName, 'tbutton_btn_emitir_/_reemitir_nfc-e');
                        TButton::disableField    (self::$formName, 'tbutton_btn_imprimir_cupom');
                        TButton::disableField    (self::$formName, 'tbutton_btn_baixar_xml');
                        TButton::disableField    (self::$formName, 'tbutton_btn_cancelar_nfce');

                    }
                $this->form->setData($data); 
            }else{
                $this->form->clear();
            }

/*
                $object = new Nfce($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

*/             
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

public function onEditNfce($param){
    TTransaction::open(self::$database);
    $key = $param['key'];
    $nfce = new Nfce($key);
    $vendas = Venda::where('id_interno','=',$nfce->id_venda)->load();
    $venda = $vendas[0];
    $page_param = ['key' => $venda->id];
    self::onEdit($page_param);
}

}

