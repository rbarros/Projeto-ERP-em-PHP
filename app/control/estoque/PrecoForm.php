<?php

class PrecoForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Preco';
    private static $primaryKey = 'id';
    private static $formName = 'form_Preco';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("Alterar Preços da variável");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Alterar Preços da variável");


        $id_tabela = new TDBCombo('id_tabela', 'base_banco', 'TabelaPreco', 'id', '{nome_tabela_preco}','nome_tabela_preco asc'  );
        $preco_custo = new TNumeric('preco_custo', '2', ',', '.' );
        $preco_venda = new TNumeric('preco_venda', '2', ',', '.' );
        $id_produto = new TDBCombo('id_produto', 'base_banco', 'Produto', 'id', '{SKU} - {descricao} {desc_variacao} {referencia} ','descricao asc'  );

        $id_tabela->setChangeAction(new TAction([$this,'onChangeTabela']));

        $id_tabela->addValidation("Id tabela", new TRequiredValidator()); 
        $preco_custo->addValidation("Preco custo", new TRequiredValidator()); 
        $preco_venda->addValidation("Preco venda", new TRequiredValidator()); 
        $id_produto->addValidation("Id produto", new TRequiredValidator()); 

        $id_produto->setEditable(false);
        $id_tabela->setDefaultOption(false);

        $id_tabela->setSize('100%');
        $id_produto->setSize('100%');
        $preco_custo->setSize('100%');
        $preco_venda->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Tabela Preço:", '#000000', '14px', null, '100%'),$id_tabela]);
        $row1->layout = [' col-sm-12'];

        $row2 = $this->form->addFields([new TLabel("Preco custo:", '#000000', '14px', null, '100%'),$preco_custo],[new TLabel("Preco venda:", '#000000', '14px', null, '100%'),$preco_venda]);
        $row2->layout = [' col-sm-6',' col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Id produto:", '#000000', '14px', null, '100%'),$id_produto]);
        $row3->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Aplicar a todas as variações", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public static function onChangeTabela($param = null) 
    {
        try 
        {
            $tabela_preco = $param['id_tabela'];
            $id = $param['id_produto'];
            TTransaction::open(self::$database);
                 //carrega os atributos de estoque
                 if($id){
                     $object = new stdClass();
                     $precos = Preco::where('id_tabela','=',$tabela_preco)
                                    ->where('id_produto','=',$id)
                                    ->load();
                     if($precos){
                         $preco = $precos[0];
                         $object->preco_custo     = str_replace(".",",",$preco->preco_custo);
                         $object->preco_venda     = str_replace(".",",",$preco->preco_venda);
                     }else{
                         $object->valor_custo     = "novo preço";
                         $object->valor_venda     = "novo preço";
                     }
                 TForm::sendData(self::$formName, $object);
                }
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onSave($param = null) 
    {
        try
        {
            $messageAction      = null;
            TTransaction::open(self::$database); 
            $this->form->validate(); 
            $data               = $this->form->getData();
            $variacoes          = Produto::where('id_familia','=',$data->id_produto)->load();
            $hasTwoTable        = false;
            if($variacoes){
                foreach($variacoes as $variacao){
                    $sku = isset($variacao->cod_barras)?$variacao->cod_barras:$variacao->SKU;
                    //ajuste nas variaveis
                    $precos  = Preco::where('id_produto','=',$variacao->id)
                                    ->where('id_tabela','=',$data->id_tabela)
                                    ->load();
                    $marca = new Marca($variacao->marca);
                    if($precos){
                        $hasTwoTable            = isset($precos[1]) ? true: false;
                        $preco  = $precos[0];
                        $preco->preco_custo     = $data->preco_custo;
                        $preco->preco_venda     = $data->preco_venda;
                        $preco->store();
                    }else{
                        $preco = new Preco();
                        $preco->fromArray( (array) $data);
                        $preco->preco_custo     = $data->preco_custo;
                        $preco->preco_venda     = $data->preco_venda;
                        $preco->id_tabela       = $data->id_tabela;
                        $preco->id_produto      = $variacao->id;
                        $preco->store();
                    }
                    //envia ao PDV
                    $woo_preco = [
                            'regular_price'=>str_replace(",",".",$preco->preco_venda)
                        ];
                    $woo_produto = [
                                'name' => strtoupper($variacao->descricao." ".$variacao->desc_variacao." - ".$marca->marca),
                                'type' => 'simple',
                                'status' => 'publish',
                                'featured'=> false,
                                'catalog_visibility'=>'visible',
                                'description'=> '',
                                'short_description'=> '',
                                'sku'=> $sku,
                                'regular_price'=>str_replace(",",".",$preco->preco_venda),
                                'sale_price'=>'',
                                'date_on_sale_from'=>'',
                                'date_on_sale_from_gmt'=>'',
                                'date_on_sale_to'=>'',
                                'date_on_sale_to_gmt'=>'',
                                'virtual'=>false,
                                'downloadable'=>false,
                                'external_url'=>$variacao->link_site
                            ];
                    if($data->id_tabela == 1){
                         //obtem id de PDV produção
                        $woocommerce =  ApiManager::getWooClient();
                        $results = $woocommerce->get("products?sku=$sku");
                        if($results){
                             foreach($results as $result){
                             $variacao->id_externo = $result->id;
                             }
                        }else{
                            $variacao->id_externo ="";
                        }
                        if($variacao->id_externo_promocao !=""){
                            $wooSave = $woocommerce->put(('products/'.$variacao->id_externo), $woo_preco);

                        }else{
                            $wooSave = $woocommerce->post('products', $woo_produto);
                            $variacao->id_externo_promocao = $wooSave->id;

                            $variacao->store();
                        }
                        if(!$hasTwoTable){//quando só tem uma tabela de preço
                            $woocommerce        =  ApiManager::getWooClient(1);
                            $results            = $woocommerce->get("products?sku=$sku");
                            if($results){
                                 foreach($results as $result){
                                    $variacao->id_externo = $result->id;
                                 }
                            }else{
                                $variacao->id_externo ="";
                            }
                            if($variacao->id_externo_promocao !=""){
                                $wooSave = $woocommerce->put(('products/'.$variacao->id_externo), $woo_preco);

                            }else{
                                $wooSave = $woocommerce->post('products', $woo_produto);
                                $variacao->id_externo_promocao = $wooSave->id;
                                $variacao->store();
                            }
                        }

                    }else{
                        //obtem id de pdv promoção
                        $woocommerce =  ApiManager::getWooClient(1);
                        $results = $woocommerce->get("products?sku=$sku");
                        if($results){
                            foreach($results as $result){
                            $variacao->id_externo_promocao = $result->id; 
                            }
                        }else{
                             $variacao->id_externo_promocao = "";
                        }
                        if($variacao->id_externo_promocao !=""){
                            $wooSave = $woocommerce->put(('products/'.$variacao->id_externo_promocao), $woo_preco);

                        }else{
                            $wooSave = $woocommerce->post('products', $woo_produto);
                            $variacao->id_externo_promocao = $wooSave->id;

                            $variacao->store();
                        } 
                    }
                }
            }else{
                throw new Exception('Produto sem variavel cadastrada');
            }
            TTransaction::close();
/*
            TTransaction::open(self::$database); // open a transaction
            $this->form->validate(); // validate form data
            $object = new Preco(); // create an empty object 
            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $data->id = $object->id; 
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
*/

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
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction
                $objects = Preco::where('id_produto','=',$key)
                                ->where('id_tabela','=',1)
                                ->load();
                 if($objects){
                     $object = $objects[0];
                     $this->form->setData($object); 
                 }else{
                     throw new Exception("não foi encontrado nenhum preço para este produto");
                 }             

/*
                $object = new Preco($key); // instantiates the Active Record 

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

