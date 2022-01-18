<?php

class NfceForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'vendas_base';
    private static $activeRecord = 'Nfce';
    private static $primaryKey = 'id';
    private static $formName = 'form_Nfce';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.60, null);
        parent::setTitle("NfceForm");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("NfceForm");


        $id = new TEntry('id');
        $numVenda = new TEntry('numVenda');
        $n_nfce = new TEntry('n_nfce');
        $dt_nfce = new TDate('dt_nfce');
        $venda_id = new TEntry('venda_id');
        $status = new TEntry('status');
        $status_venda = new TEntry('status_venda');
        $serie = new TEntry('serie');
        $prox_numero = new TEntry('prox_numero');
        $id_loja = new TEntry('id_loja');
        $ambienteEmissao = new TEntry('ambienteEmissao');
        $presencaConsumidor = new TEntry('presencaConsumidor');
        $forma_pagamento = new TEntry('forma_pagamento');
        $link_cupom = new TEntry('link_cupom');
        $informacoesAdicionais = new TText('informacoesAdicionais');

        $numVenda->addValidation("NumVenda", new TRequiredValidator()); 

        $id->setEditable(false);
        $dt_nfce->setMask('dd/mm/yyyy');
        $dt_nfce->setDatabaseMask('yyyy-mm-dd');

        $status->setMaxLength(50);
        $numVenda->setMaxLength(30);
        $link_cupom->setMaxLength(1000);
        $ambienteEmissao->setMaxLength(30);
        $presencaConsumidor->setMaxLength(20);

        $id->setSize(100);
        $serie->setSize('100%');
        $n_nfce->setSize('100%');
        $status->setSize('100%');
        $dt_nfce->setSize('100%');
        $id_loja->setSize('100%');
        $numVenda->setSize('100%');
        $venda_id->setSize('100%');
        $link_cupom->setSize('100%');
        $prox_numero->setSize('100%');
        $status_venda->setSize('100%');
        $ambienteEmissao->setSize('100%');
        $forma_pagamento->setSize('100%');
        $presencaConsumidor->setSize('100%');
        $informacoesAdicionais->setSize('100%', 70);

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Venda:", null, '14px', null, '100%'),$numVenda],[new TLabel("nº Nfce:", null, '14px', null, '100%'),$n_nfce],[],[new TLabel("Data Emissão:", null, '14px', null, '100%'),$dt_nfce]);
        $row1->layout = [' col-sm-6 col-lg-1','col-sm-2','col-sm-2',' col-sm-2 col-lg-5','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Venda id:", null, '14px', null, '100%'),$venda_id],[],[new TLabel("Status Nfce:", null, '14px', null, '100%'),$status],[new TLabel("Status venda:", null, '14px', null, '100%'),$status_venda]);
        $row2->layout = ['col-sm-3',' col-sm-3 col-lg-5',' col-sm-6 col-lg-2','col-sm-2'];

        $row3 = $this->form->addContent([new TFormSeparator("  ", '#333', '18', '#FF0091')]);
        $row4 = $this->form->addFields([new TLabel("Série:", null, '14px', null, '100%'),$serie],[new TLabel("Próximo número da Nfce", null, '14px', null),$prox_numero],[new TLabel("Id loja:", null, '14px', null, '100%'),$id_loja]);
        $row4->layout = ['col-sm-3','col-sm-3','col-sm-6'];

        $row5 = $this->form->addContent([new TFormSeparator("   ", '#333', '18', '#FF0091')]);
        $row6 = $this->form->addFields([new TLabel("AmbienteEmissao:", null, '14px', null, '100%'),$ambienteEmissao],[new TLabel("PresencaConsumidor:", null, '14px', null, '100%'),$presencaConsumidor],[],[new TLabel("Forma pagamento:", null, '14px', null, '100%'),$forma_pagamento]);
        $row6->layout = [' col-sm-6 col-lg-2',' col-sm-6 col-lg-2',' col-sm-2 col-lg-6','col-sm-2'];

        $row7 = $this->form->addFields([new TLabel("Link cupom:", null, '14px', null, '100%'),$link_cupom]);
        $row7->layout = [' col-sm-6 col-lg-12'];

        $row8 = $this->form->addFields([new TLabel("InformacoesAdicionais:", null, '14px', null, '100%'),$informacoesAdicionais]);
        $row8->layout = [' col-sm-6 col-lg-12'];

        // create the form actions
        $tbutton_btn_emitirReemitir = $this->form->addAction("Emitir / Reemitir Nfc-e", new TAction([$this, 'onEmitir']), 'fas:check #FFFFFF');
        $this->tbutton_btn_emitirReemitir = $tbutton_btn_emitirReemitir;
        $tbutton_btn_emitirReemitir->addStyleClass('btn-success'); 

        $tbutton_btn_cancelar_nfce = $this->form->addAction("Cancelar Nfc-e", new TAction([$this, 'onCancelar']), 'fas:ban #E91E63');
        $this->tbutton_btn_cancelar_nfce = $tbutton_btn_cancelar_nfce;

        $tbutton_btn_imprimir_cupom = $this->form->addAction("Imprimir Cupom", new TAction([$this, 'onImprimir']), 'fas:print #3F51B5');
        $this->tbutton_btn_imprimir_cupom = $tbutton_btn_imprimir_cupom;

        $tbutton_btn_baixar_xml = $this->form->addAction("Baixar Xml", new TAction([$this, 'onBaixarXml']), 'fas:code #F44336');
        $this->tbutton_btn_baixar_xml = $tbutton_btn_baixar_xml;

        parent::add($this->form);

    }

    public function onEmitir($param = null) 
    {
        try 
        {
            $data= $this->form->getData();
            TTransaction::open(self::$database);
            $venda              = new Venda($data->venda_id);
            $param              = array();
            $param['usuario']   = TSession::getValue('username');
            $param['origem']    = "ERP";
            $param['fiscal']    = 1;
            $retorno = VendaService::isFiscal($venda,$param);
            new TMessage('info',"NFC-e Gerada, cupom: $retorno");
            $this->form->setData($data);
            TTransaction::close();

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
                VendaService::CancelarVenda($param['venda_id']);
            }
            catch (Exception $e) // in case of exception
            {
                new TMessage('error', $e->getMessage());
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
            TTransaction::rollback(); 
        }
    }
    public function onBaixarXml($param = null) 
    {
        try 
        {
           $data = $this->form->getData();
           nfceWebhook::obterRetornoPorID($data->numVenda);
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
              if (isset($param))
            {
                if(isset($param['nfce'])){// tratamento vindo da lista de Requisição NFCE gerada
                    TTransaction::open(self::$database);
                    $id                         = $param['nfce'];
                    $nfce                       = new Nfce($id);
                    $venda                      = new Venda($nfce->venda_id);
                    TTransaction::open('base_banco');
                    $loja                       = new Loja($nfce->id_loja);
                    TTransaction::close();
                    $data                       = $this->form->getData();
                    //venda
                    $data->forma_pagamento      = $venda->forma_pagamento;
                    $data->numVenda             = $venda->id_interno;
                    $data->venda_id             = $venda->id;
                    $data->status_venda         = $venda->status;
                    //loja
                    $data->serie                = $nfce->ambienteEmissao=='Producao'?$loja->serie_nf_producao:$loja->serie_nf_homologacao;//alterar para venda em produção, caso não tenha NFCE
                    $data->prox_numero          = $nfce->ambienteEmissao=='Producao'?$loja->seq_nf_producao:$loja->seq_nf_homologacao;
                    $data->id_loja              = $loja->nome_fantasia.' - '.$loja->cnpj;
                    //nfce
                    $data->id                   = $nfce->id;
                    $data->n_nfce               = $nfce->n_nfce;
                    $data->dt_nfce              = $nfce->dt_nfce;
                    $data->status               = $nfce->status;
                    $data->ambienteEmissao      = $nfce->ambienteEmissao;
                    $data->presencaConsumidor   = $nfce->presencaConsumidor;
                    $data->link_cupom           = $nfce->link_cupom;
                    $data->InformacoesAdicionais= $nfce->informacoesAdicionais;
                    $this->form->setData($data);
                    TTransaction::close();
                }
                if(isset($param['venda'])){// tratamento vindo da lista de vendas recebidas
                    TTransaction::open(self::$database);
                    $id                         = $param['venda'];
                    $venda                      = new Venda($id);
                    $nfces                      = Nfce::where('venda_id','=',$venda->id)->load();
                    if($nfces){//possui Nfce
                        $nfce                       = $nfces[0];
                        TTransaction::open('base_banco');
                        $loja                       = new Loja($nfce->id_loja);
                        TTransaction::close();
                        $data                       = $this->form->getData();
                        //venda
                        $data->forma_pagamento      = $venda->forma_pagamento;
                        $data->numVenda             = $venda->id_interno;
                        $data->venda_id             = $venda->id;
                        $data->status_venda         = $venda->status;
                        //loja
                        $data->serie                = $nfce->ambienteEmissao=='Producao'?$loja->serie_nf_producao:$loja->serie_nf_homologacao;//alterar para venda em produção, caso não tenha NFCE
                        $data->prox_numero          = $nfce->ambienteEmissao=='Producao'?$loja->seq_nf_producao:$loja->seq_nf_homologacao;
                        $data->id_loja              = $loja->nome_fantasia.' - '.$loja->cnpj;
                        //nfce
                        $data->id                   = $nfce->id;
                        $data->n_nfce               = $nfce->n_nfce;
                        $data->dt_nfce              = $nfce->dt_nfce;
                        $data->status               = $nfce->status;
                        $data->ambienteEmissao      = $nfce->ambienteEmissao;
                        $data->presencaConsumidor   = $nfce->presencaConsumidor;
                        $data->link_cupom           = $nfce->link_cupom;
                        $data->InformacoesAdicionais= $nfce->informacoesAdicionais;
                        $this->form->setData($data);

                    }else{
                         TTransaction::open('base_banco');
                        $loja                       = new Loja($venda->loja);
                        TTransaction::close();
                        $data                       = $this->form->getData();
                        //venda
                        $data->forma_pagamento      = $venda->forma_pagamento;
                        $data->numVenda             = $venda->id_interno;
                        $data->venda_id             = $venda->id;
                        $data->status_venda         = $venda->status;
                        //loja
                        $data->serie                = $loja->serie_nf_producao;//alterar para venda em produção, caso não tenha NFCE
                        $data->prox_numero          = $loja->seq_nf_producao;
                        $data->id_loja              = $loja->nome_fantasia.' - '.$loja->cnpj;
                        //nfce
                        $data->status               = 'não possui nfce';
                        $this->form->setData($data);
                    }
                    TTransaction::close();
                }
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

}

