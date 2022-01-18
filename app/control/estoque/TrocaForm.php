<?php

class TrocaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_TrocaForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Trocas");


        $loja = new TDBCombo('loja', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $skuProduto = new TDBUniqueSearch('skuProduto', 'base_banco', 'Produto', 'id', 'SKU','descricao asc'  );
        $codigoProduto = new TDBUniqueSearch('codigoProduto', 'base_banco', 'Produto', 'id', 'cod_barras','descricao asc'  );
        $descricaoProduto = new TDBUniqueSearch('descricaoProduto', 'base_banco', 'Produto', 'id', 'descricao','descricao asc'  );
        $Motivo = new TEntry('Motivo');
        $quantidadeProduto = new TNumeric('quantidadeProduto', '0', ',', '.' );

        $loja->setChangeAction(new TAction([$this,'onSelectStore']));
        $skuProduto->setChangeAction(new TAction([$this,'onSKUSearch']));
        $codigoProduto->setChangeAction(new TAction([$this,'onBarSearch']));
        $descricaoProduto->setChangeAction(new TAction([$this,'onDescSearch']));

        $loja->addValidation("Loja", new TRequiredValidator()); 

        $quantidadeProduto->setAllowNegative(false);
        $quantidadeProduto->setValue('1');
        $Motivo->setValue('Troca de produto avariado');

        $skuProduto->setEditable(false);
        $codigoProduto->setEditable(false);
        $descricaoProduto->setEditable(false);

        $skuProduto->setMinLength(2);
        $codigoProduto->setMinLength(2);
        $descricaoProduto->setMinLength(2);

        $skuProduto->setMask('{SKU}  {cod_barras}  {descricao}  {desc_variacao} ');
        $codigoProduto->setMask('{SKU}  {cod_barras}  {descricao}  {desc_variacao} ');
        $descricaoProduto->setMask('{SKU}  {cod_barras}  {descricao}  {desc_variacao} ');

        $loja->setSize('100%');
        $Motivo->setSize('100%');
        $skuProduto->setSize('100%');
        $codigoProduto->setSize('100%');
        $descricaoProduto->setSize('100%');
        $quantidadeProduto->setSize('100%');


        $row1 = $this->form->addContent([new TFormSeparator("<b>Gerador de Cupom de Troca</b>", '#333', '18', '#eee')]);
        $row2 = $this->form->addFields([new TLabel("Informe sua Loja:", null, '14px', null)],[$loja]);
        $row2->layout = ['col-sm-3',' col-sm-6 col-lg-9'];

        $row3 = $this->form->addFields([new TLabel("por SKU do produto:", null, '14px', null, '100%'),$skuProduto],[new TLabel("<b> OU </b>", null, '14px', null)],[new TLabel("por código de barras do produto:", null, '14px', null, '100%'),$codigoProduto],[new TLabel("<b> OU </b>", null, '14px', null)],[new TLabel("por descrição do produto:", null, '14px', null, '100%'),$descricaoProduto]);
        $row3->layout = [' col-sm-6 col-lg-3',' col-sm-2 col-lg-1  centralizar',' col-sm-2 col-lg-4  ou',' col-sm-2 col-lg-1  centralizar',' col-sm-6 col-lg-3'];

        $row4 = $this->form->addFields([new TLabel("Motivo:", null, '14px', null, '100%'),$Motivo],[new TLabel("Quantidade:", null, '14px', null, '100%'),$quantidadeProduto]);
        $row4->layout = [' col-sm-3 col-lg-8',' col-sm-2 col-lg-4'];

        // create the form actions
        $btn_ongenerate = $this->form->addAction("GERAR CUPOM DE TROCA", new TAction([$this, 'onGenerate']), 'fas:exchange-alt #FFFFFF');
        $this->btn_ongenerate = $btn_ongenerate;
        $btn_ongenerate->addStyleClass('btn-success'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Estoque","Trocas"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onSelectStore($param = null) 
    {
        try 
        {
            if($param['loja'] != '' || $param['loja'] != null){
                self::blockEntry(0);
            }else{
                self::blockEntry(4);
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onSKUSearch($param = null) 
    {
        try 
        {
            if($param['skuProduto'] != '' || $param['skuProduto'] != null){
                self::blockEntry(1);
            }else{
                self::blockEntry(0);
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onBarSearch($param = null) 
    {
        try 
        {
            if($param['codigoProduto'] != '' || $param['codigoProduto'] != null){
                self::blockEntry(2);
            }else{
                self::blockEntry(0);
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onDescSearch($param = null) 
    {
        try 
        {
            if($param['descricaoProduto'] != '' || $param['descricaoProduto'] != null){
                self::blockEntry(3);
            }else{
                self::blockEntry(0);
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onGenerate($param = null) 
    {
        try 
        {
            $data = $this->form->getData();
            $result = null;
            //is promo (?) lojas que estão no PDV promoção
            $stores = array(
                1=>'S',
                2=>'N',
                3=>'N',
                4=>'N',
                5=>'S',
                6=>'N',
                7=>'S',
                8=>'N',
                9=>'N',
                10=>'N',
                11=>'N',
                12=>'N',
                13=>'S',
                14=>'N',
                15=>'S',
                16=>'N',
                17=>'S',
                18=>'N',
                19=>'S',
                20=>'S',
                21=>'S'
                );
            TTransaction::open('base_banco');
            $tabela         = $stores[$data->loja]=='S' ? true : false;
            $idTabela       = array(true => 2, false => 1);
            $woocommerce    = $tabela ? ApiManager::getWooClient() : ApiManager::getWooClient(1);
            $idProduto      = null;
            $idProduto      = $data->skuProduto != null         || $data->skuProduto != ''          ?  $data->skuProduto        : $idProduto;    
            $idProduto      = $data->codigoProduto != null      || $data->codigoProduto != ''       ?  $data->codigoProduto     : $idProduto;    
            $idProduto      = $data->descricaoProduto != null   || $data->descricaoProduto != ''    ?  $data->descricaoProduto  : $idProduto;    

            if($idProduto == null){
                throw new Exception('id do produto não informado');
            }

            $produto        = new Produto($idProduto);
            $loja           = new Loja($data->loja);
            $precos         = Preco::where('id_produto','=',$idProduto)
                                   ->where('id_tabela','=',$idTabela[$tabela])
                                   ->load();
            $produtoPDV     = $tabela ? $produto->id_externo : $produto->id_externo_promocao;
            if($precos){
                $preco = $precos[0];
                $preco->preco_venda = $preco->preco_venda*$data->quantidadeProduto;
                var_dump($preco->preco_venda);
                $data = [
                    'code' => $produto->id.strtoupper($loja->abreviacao),
                    'discount_type' => 'fixed_product',
                    'amount' => "$preco->preco_venda",
                    'exclude_sale_items' => true,
                    'product_ids' => [$produtoPDV],
                    'usage_limit' => 1
                ];
                $woocommerce->post('coupons', $data);
            }else{
                $tabela = !$tabela;
                $precos         = Preco::where('id_produto','=',$idProduto)
                                       ->where('id_tabela','=',$idTabela[$tabela])
                                       ->load();
                if($precos){
                    $preco = $precos[0];
                    $preco->preco_venda = $preco->preco_venda*$data->quantidadeProduto;
                    var_dump($preco->preco_venda);
                    $data = [
                        'code' => $produto->id.strtoupper($loja->abreviacao),
                        'discount_type' => 'fixed_product',
                        'amount' => "$preco->preco_venda",
                        'exclude_sale_items' => true,
                        'product_ids' => [$produtoPDV],
                        'usage_limit' => 1
                    ];
                $result = $woocommerce->post('coupons', $data); 
                $this->form->setData($data);
                }else{
                    throw new Exception(' este produto não possui preço nesta tabela');
                }
            }
            TTransaction::close();
            new TMessage('info','CUPOM GERADO CÓD :'.$result->code);
            echo "<h5>CUPOM GERADO$result->code</h5>";

        }
        catch (Exception $e) 
        {
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onShow($param = null)
    {               

    } 

    public function blockEntry($entry){
        switch($entry){
            case 0:
                TDBUniqueSearch::enableField(self::$formName, 'skuProduto');
                TDBUniqueSearch::enableField(self::$formName, 'codigoProduto');
                TDBUniqueSearch::enableField(self::$formName, 'descricaoProduto');
                $object = new stdClass();
                $object->skuProduto         = null;
                $object->codigoProduto      = null;
                $object->descricaoProduto   = null;
                TForm::sendData(self::$formName, $object);
                break;
            case 1:
                TDBUniqueSearch::enableField(self::$formName, 'skuProduto');
                TDBUniqueSearch::disableField(self::$formName, 'codigoProduto');
                TDBUniqueSearch::disableField(self::$formName, 'descricaoProduto');
                break;
            case 2:
                TDBUniqueSearch::disableField(self::$formName, 'skuProduto');
                TDBUniqueSearch::enableField(self::$formName, 'codigoProduto');
                TDBUniqueSearch::disableField(self::$formName, 'descricaoProduto');
                break;
            case 3:
                TDBUniqueSearch::disableField(self::$formName, 'skuProduto');
                TDBUniqueSearch::disableField(self::$formName, 'codigoProduto');
                TDBUniqueSearch::enableField(self::$formName, 'descricaoProduto');
                break;
            case 4:
                TDBUniqueSearch::disableField(self::$formName, 'skuProduto');
                TDBUniqueSearch::disableField(self::$formName, 'codigoProduto');
                TDBUniqueSearch::disableField(self::$formName, 'descricaoProduto');
                break;
        }
    }

}

