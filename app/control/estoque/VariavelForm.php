<?php

use Google\client;
use Google\service;

class VariavelForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'form_Produto';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.60, null);
        parent::setTitle("Cadastro Produto");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro Produto");


        $id = new TEntry('id');
        $id_externo = new TNumeric('id_externo', '0', '', '' );
        $id_externo_promocao = new TNumeric('id_externo_promocao', '0', '', '' );
        $mestre_variavel = new TDBCombo('mestre_variavel', 'base_banco', 'MestreVariavel', 'id', '{tipo}','id asc'  );
        $id_familia = new TEntry('id_familia');
        $dt_cadastro = new TDate('dt_cadastro');
        $descricao = new TEntry('descricao');
        $desc_variacao = new TEntry('desc_variacao');
        $cod_barras = new TEntry('cod_barras');
        $SKU = new TNumeric('SKU', '0', '', '' );
        $button__ = new TButton('button__');
        $link_site = new TEntry('link_site');
        $situacao_prod = new TDBCombo('situacao_prod', 'base_banco', 'SituacaoProd', 'id', '{situacao_prod}','situacao_prod asc'  );
        $referencia = new TEntry('referencia');
        $deposito = new TDBCombo('deposito', 'base_banco', 'Deposito', 'id', '{nome_deposito}','loja asc'  );
        $nova_qtd = new TEntry('nova_qtd');
        $qtd = new TEntry('qtd');
        $qtd_max = new TEntry('qtd_max');
        $qtd_min = new TEntry('qtd_min');
        $tabela_preco = new TDBCombo('tabela_preco', 'base_banco', 'TabelaPreco', 'id', '{nome_tabela_preco}','nome_tabela_preco asc'  );
        $valor_custo = new TNumeric('valor_custo', '2', ',', '.' );
        $valor_venda = new TNumeric('valor_venda', '2', ',', '.' );
        $obs = new TText('obs');
        $unidade_id = new TDBCombo('unidade_id', 'base_banco', 'Unidade', 'id', '{nome}','nome asc'  );
        $tipo_cadastro = new TDBCombo('tipo_cadastro', 'base_banco', 'TipoCadastroProd', 'id', '{descricao}','descricao asc'  );
        $categoria_produto_id = new TDBCombo('categoria_produto_id', 'base_banco', 'CategoriaProduto', 'id', '{nome}','nome asc'  );
        $fornecedor_id = new TDBCombo('fornecedor_id', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc'  );
        $marca = new TDBCombo('marca', 'base_banco', 'Marca', 'id', '{marca}','marca asc'  );
        $sit_tribut = new TCombo('sit_tribut');
        $origem = new TCombo('origem');
        $ncm = new TEntry('ncm');
        $cest = new TEntry('cest');

        $deposito->setChangeAction(new TAction([$this,'onChangeDeposito']));
        $tabela_preco->setChangeAction(new TAction([$this,'onChangeTabela']));

        $cod_barras->setExitAction(new TAction([$this,'onEan']));

        $mestre_variavel->addValidation("Mestre variavel", new TRequiredValidator()); 
        $dt_cadastro->addValidation("Data de cadastro", new TRequiredValidator()); 
        $desc_variacao->addValidation("Nome", new TRequiredValidator()); 
        $SKU->addValidation("SKU", new TRequiredValidator()); 
        $valor_custo->addValidation("preço custo", new TRequiredValidator()); 
        $valor_venda->addValidation("Preço venda", new TRequiredValidator()); 
        $categoria_produto_id->addValidation("Tipo do produto", new TRequiredValidator()); 

        $dt_cadastro->setDatabaseMask('yyyy-mm-dd');
        $button__->setAction(new TAction([$this, 'onHiddenReload']), " ");
        $button__->addStyleClass('btn-default');
        $button__->setImage('fas:redo #faa700');
        $deposito->setValue('2');
        $SKU->setMaxLength(13);
        $desc_variacao->setMaxLength(40);

        $cod_barras->autofocus = 'autofocus';

        $sit_tribut->addItems(['1'=>'Substituição Tributaria 1','2'=>'Substituição Tributaria 2','3'=>'Substituição Tributaria 3','4'=>'Isento 1','5'=>'Isento 2','6'=>'Isento 3','7'=>'Não Tributado 1','8'=>'Não Tributado 2','9'=>'Não Tributado 3','10'=>'Isento ISSQN','11'=>'Tributada pelo ISSQN','12'=>'Tributada pelo ICMS']);
        $origem->addItems(['1'=>' 0 - Nacional, exceto as indicadas 3, 4, 5 e 8','2'=>' 1 - Estrangeira - importação direta, exceto indicada no código 6','3'=>' 2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7','4'=>' 3 - Nacional, mercadoria ou bem de consumo com conteúdo de importação superior a 40% e inferior ou igual a 70%','5'=>' 4 - nacional, cujo produção tenha sido feita em conformidade  com os processos produtivos básicos de que se tratam  as legislações citadas nos  Ajustes','6'=>' 5 - Nacional, mercadoria ou bem com conteúdo de importação inferior ou igual a 40%','7'=>' 6 - Estrangeira - Importação direta ou similar nacional, constante em lista CAMEX','8'=>' 7 - Estrangeira - adquirida no mercado interno, sem  similar nacional , constante em lista da CAMEX','9'=>' 8 - Nacional, mercadoria ou  bem com conteúdo de importação superior a 70%']);

        $cest->setMask('99.999.99', true);
        $ncm->setMask('9999.99.99', true);
        $dt_cadastro->setMask('dd/mm/yyyy');

        $descricao->forceUpperCase();
        $referencia->forceUpperCase();
        $desc_variacao->forceUpperCase();

        $deposito->setDefaultOption(false);
        $tabela_preco->setDefaultOption(false);
        $situacao_prod->setDefaultOption(false);

        $id->setEditable(false);
        $SKU->setEditable(false);
        $ncm->setEditable(false);
        $qtd->setEditable(false);
        $cest->setEditable(false);
        $marca->setEditable(false);
        $origem->setEditable(false);
        $descricao->setEditable(false);
        $id_familia->setEditable(false);
        $id_externo->setEditable(false);
        $sit_tribut->setEditable(false);
        $unidade_id->setEditable(false);
        $dt_cadastro->setEditable(false);
        $tipo_cadastro->setEditable(false);
        $fornecedor_id->setEditable(false);
        $mestre_variavel->setEditable(false);
        $id_externo_promocao->setEditable(false);
        $categoria_produto_id->setEditable(false);

        $id->setSize('100%');
        $SKU->setSize('100%');
        $ncm->setSize('100%');
        $qtd->setSize('100%');
        $cest->setSize('100%');
        $marca->setSize('100%');
        $origem->setSize('100%');
        $qtd_max->setSize('100%');
        $obs->setSize('100%', 70);
        $qtd_min->setSize('100%');
        $deposito->setSize('100%');
        $nova_qtd->setSize('100%');
        $link_site->setSize('100%');
        $descricao->setSize('100%');
        $cod_barras->setSize('100%');
        $sit_tribut->setSize('100%');
        $unidade_id->setSize('100%');
        $id_familia->setSize('100%');
        $referencia->setSize('100%');
        $id_externo->setSize('100%');
        $valor_custo->setSize('100%');
        $dt_cadastro->setSize('100%');
        $valor_venda->setSize('100%');
        $tabela_preco->setSize('100%');
        $situacao_prod->setSize('100%');
        $tipo_cadastro->setSize('100%');
        $fornecedor_id->setSize('100%');
        $desc_variacao->setSize('100%');
        $mestre_variavel->setSize('100%');
        $id_externo_promocao->setSize('100%');
        $categoria_produto_id->setSize('100%');

        $this->form->appendPage("Variação");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TFormSeparator("Informações Gerais", '#000000', '20', '#ff0091')]);
        $row1->layout = [' col-sm-12'];

        $row2 = $this->form->addFields([new TLabel("id:", null, '14px', null, '100%'),$id,new TLabel("ID do PDV:", null, '14px', null, '100%'),$id_externo,new TLabel("ID no PDV promoção:", null, '14px', null, '100%'),$id_externo_promocao],[],[new TLabel("Mestre variavel:", '#ff0000', '14px', null, '100%'),$mestre_variavel,new TLabel("Id familia:", null, '14px', null, '100%'),$id_familia,new TLabel("Data de cadastro:", '#ff0000', '14px', null, '100%'),$dt_cadastro]);
        $row2->layout = [' col-sm-3',' col-sm-4',' col-sm-5'];

        $row3 = $this->form->addFields([new TLabel("Descrição:", null, '14px', null),$descricao],[new TLabel("    Descrição da variação:", null, '14px', null, '100%'),$desc_variacao]);
        $row3->layout = [' col-sm-4',' col-sm-8'];

        $row4 = $this->form->addFields([new TLabel("Código EAN:", '#000000', '14px', null, '100%'),$cod_barras,new TLabel("SKU:", null, '14px', null, '100%'),$SKU,new TLabel(" ", null, '14px', null, '100%'),new TLabel(" ", null, '14px', null, '100%')],[new TLabel("  ", null, '14px', null, '100%'),$button__,new TLabel("  ", null, '14px', null, '100%'),new TLabel(" ", null, '14px', null, '100%'),new TLabel("  ", null, '14px', null, '100%'),new TLabel("  ", null, '14px', null, '100%')],[],[new TLabel("Link na loja:", null, '14px', null, '100%'),$link_site,new TLabel("Situacao prod:", null, '14px', null, '100%'),$situacao_prod,new TLabel("Ref. fornecedor:", null, '14px', null, '100%'),$referencia]);
        $row4->layout = [' col-sm-4',' col-sm-1',' col-sm-2',' col-sm-5'];

        $row5 = $this->form->addFields([new TFormSeparator("Estoque", '#000000', '20', '#ff0091')]);
        $row5->layout = [' col-sm-12'];

        $row6 = $this->form->addFields([new TLabel("Estoque atual:", null, '14px', null, '100%'),$deposito],[new TLabel("Adicionar Qtd.:", null, '14px', null),$nova_qtd],[],[new TLabel("Saldo:", null, '14px', null, '100%'),$qtd,new TLabel("Estoque máximo:", null, '14px', null),$qtd_max,new TLabel("Estoque mínimo:", null, '14px', null),$qtd_min]);
        $row6->layout = [' col-sm-4',' col-sm-2',' col-sm-1',' col-sm-5'];

        $row7 = $this->form->addFields([new TFormSeparator("Precificação", '#000000', '20', '#ff0091')]);
        $row7->layout = [' col-sm-12'];

        $row8 = $this->form->addFields([new TLabel("Tabela preco:", null, '14px', null, '100%'),$tabela_preco,new TLabel("  ", null, '14px', null, '100%'),new TLabel(" ", null, '14px', null, '100%')],[],[new TLabel("Preco custo:", null, '14px', null, '100%'),$valor_custo,new TLabel("Preço venda:", null, '14px', null, '100%'),$valor_venda]);
        $row8->layout = [' col-sm-4',' col-sm-3',' col-sm-5'];

        $row9 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$obs]);
        $row9->layout = [' col-sm-12'];

        $this->form->appendPage("Informação Produto Pai");
        $row10 = $this->form->addFields([new TFormSeparator("Informações gerais", '#000000', '20', '#ff0091')]);
        $row10->layout = [' col-sm-12'];

        $row11 = $this->form->addFields([new TLabel("Unidade de medida:", null, '14px', null, '100%'),$unidade_id],[new TLabel("Tipo cadastro:", null, '14px', null, '100%'),$tipo_cadastro],[new TLabel("Tipo do produto:", '#ff0000', '14px', null, '100%'),$categoria_produto_id]);
        $row11->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row12 = $this->form->addFields([new TLabel("Fornecedor:", null, '14px', null, '100%'),$fornecedor_id],[new TLabel("Marca:", null, '14px', null, '100%'),$marca]);
        $row12->layout = [' col-sm-7',' col-sm-5'];

        $row13 = $this->form->addFields([new TFormSeparator("Tributário", '#000000', '20', '#ff0091')]);
        $row13->layout = [' col-sm-12'];

        $row14 = $this->form->addFields([new TLabel("Situação tributária:", null, '14px', null, '100%'),$sit_tribut],[new TLabel("Origem do produto:", null, '14px', null, '100%'),$origem]);
        $row14->layout = [' col-sm-6',' col-sm-6'];

        $row15 = $this->form->addFields([new TLabel("NCM:", null, '14px', null, '100%'),$ncm],[new TLabel("CEST:", null, '14px', null, '100%'),$cest],[]);
        $row15->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onvoltar = $this->form->addAction("Voltar", new TAction([$this, 'onVoltar']), 'fas:arrow-left #000000');
        $this->btn_onvoltar = $btn_onvoltar;
        $btn_onvoltar->addStyleClass('btn-success'); 

            TScript::create("$(\"[name='preco']\").closest('.fb-inline-field-container').hide()");
            TScript::create("$(\"[name='estoque']\").closest('.fb-inline-field-container').hide()");

        parent::add($this->form);

    }

    public static function onEan($param = null) 
    {
        try 
        {
            $ean = $param['cod_barras'];
            if(isset($ean)){//valida se existe a variavel
                if($ean ==""){

                }else{
                if((strlen($ean) < 13)){//valida se ela possui 13 digitos
                throw new Exception('O código EAN13 mencionado não possui 13 caracteres numéricos');
                }else{
                    $digitos = str_split($ean);
                    $soma = 0;
                        for ($i=0;$i<12;$i++){
                            if (($i % 2) == 0) {
                            $soma += $digitos[$i] * 1;
                            }else{
                            $soma += $digitos[$i] * 3;
                            }
                        }
                    $resultado = floor($soma / 10) + 1;
                $resultado *= 10;
                $resultado -= $soma;

                    if (($resultado % 10) == 0) {
                    $resultado =  0;
                    }
                if($resultado != $digitos[12]){
                    throw new Exception('Código ean inválido');
                        }
                        TTransaction::open(self::$database);
                        $ean_13 = Produto::where('cod_barras','=', $ean)->load();
                        if(isset($ean_13[0])){
                            if($ean_13[0]->id == $param['id']){
                                }else{
                            throw new Exception ("Código de barras já cadastrado!");
                            }
                        }
                        TTransaction::close(); 
                    }
                }
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeDeposito($param = null) 
    {
        try 
        {
           $produto = $param['id'];
            $qtd_venda = 0;
            TTransaction::open(self::$database);
            $deposito = new Deposito($param['deposito']);   
            $lojas     = Loja::where('id','=',$deposito->nomeloja)->load();
            $loja;
            if($lojas){
                $loja = $lojas[0];
                $vendas = Venda::where('loja','=',$loja->nome_fantasia)->load();
                         foreach($vendas as $venda){
                             $itens = VendaItem::where('venda_id','=',$venda->id)
                                               ->where('produto_id','=',$produto)
                                               ->load();
                                foreach($itens as $item){
                                    $qtd_venda += $item->quantidade; 
                                }
                         }
            }
            $estoques = ProdEstoque::where('id_produto','=',$produto)
                                  ->where('id_deposito','=',$deposito->id)
                                  ->load();
            $object = new stdClass();
            if(isset($estoques[0])){
                $estoque = $estoques[0];
                     $object->qtd         = $estoque->quantidade;
                     $object->qtd_min     = $estoque->qtd_min;
                     $object->qtd_max     = $estoque->qtd_max;
                     $object->qtd        = $object->qtd - $qtd_venda;
            }else{
                $object->qtd = '';
            }
            TForm::sendData(self::$formName, $object);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeTabela($param = null) 
    {
        try 
        {
            $tabela_preco = $param['tabela_preco'];
            $id = $param['id'];
            TTransaction::open(self::$database);
                 //carrega os atributos de estoque
                 if($id){
                     $object = new stdClass();
                     $precos = Preco::where('id_tabela','=',$tabela_preco)
                                    ->where('id_produto','=',$id)
                                    ->load();
                     if($precos){
                         $preco = $precos[0];
                         $object->valor_custo     = $preco->preco_custo;
                         $object->valor_venda     = $preco->preco_venda;
                         $object->preco           = $preco->id;
                     }else{
                         $object->valor_custo     = "";
                         $object->valor_venda     = "";
                         $object->preco           = "";
                     }
                }
            TTransaction::close();
            TForm::sendData(self::$formName, $object);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onHiddenReload($param = null) 
    {
        try 
        {
            $this->form->setData( $this->form->getData() );

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
            TTransaction::open(self::$database); // open a transaction
            $messageAction = null;
            $this->form->validate(); // validate form data
            $object = new Produto(); // create an empty object 

            $data = $this->form->getData(); // get form data as array

//neste ponto é validado se o codigo de cod_barras já existe, corrigindo em caso de alteração apenas do ean
            $ean = $data->cod_barras;
            if(isset($ean)){//valida se existe a variavel
                if($ean ==""){

                }else{
                if((strlen($ean) < 13)){//valida se ela possui 13 digitos
                throw new Exception('O código EAN13 mencionado não possui 13 caracteres numéricos');
                }else{
                    $digitos = str_split($ean);
                    $soma = 0;
                        for ($i=0;$i<12;$i++){
                            if (($i % 2) == 0) {
                            $soma += $digitos[$i] * 1;
                            }else{
                            $soma += $digitos[$i] * 3;
                            }
                        }
                    $resultado = floor($soma / 10) + 1;
                $resultado *= 10;
                $resultado -= $soma;

                    if (($resultado % 10) == 0) {
                    $resultado =  0;
                    }
                if($resultado != $digitos[12]){
                    throw new Exception('Código ean inválido');
                        }
                        $ean_13 = Produto::where('cod_barras','=', $ean)->load();
                        if(isset($ean_13[0])){
                            if($ean_13[0]->id == $data->id){
                                }else{
                                    throw new Exception ("Código de barras já cadastrado!");
                                 }
                        }

                    }
                }
            }

            $data->mestre_variavel = 2;

//recupera o produto pai/mestre para que seja salvo seu id id_familia
            $object_pai = new Produto($data->id_familia);
            $object_pai->id_familia     = $data->id_familia;
            $object_pai->cod_barras     = "";
            $object_pai->store();

            $object->fromArray( (array) $data); 

            $object->store(); // save the object 
            if($object->SKU == 100000000000){
                $SKU = intval($object-> SKU) + intval($object-> id);

//digito verificador para SKU
                 $digitos = str_split($SKU);
                    $soma = 0;
                        for ($i=0;$i<12;$i++){
                            if (($i % 2) == 0) {
                            $soma += $digitos[$i] * 1;
                            }else{
                            $soma += $digitos[$i] * 3;
                            }
                        }
                    $resultado = floor($soma / 10) + 1;
                    $resultado *= 10;
                    $resultado -= $soma;
                        if (($resultado % 10) == 0) {
                            $resultado =  0;
                        }
                    $object->SKU = $SKU . $resultado;    

            }

//preenche o objeto preco com a quantidade e tabela informada
        $preco;
        $pesquisa_preco = Preco::where('id_tabela','=',$object->tabela_preco)
                      ->where('id_produto','=',$object->id)->load();
              if(isset($pesquisa_preco[0])){
                $preco = $pesquisa_preco[0];
                $preco-> preco_custo = $object-> valor_custo;
                $preco-> preco_venda = $object-> valor_venda;
                $preco-> id_produto  = $object-> id;
                $preco-> id_tabela   = $object-> tabela_preco;
                $preco-> store();
                $object-> valor_custo= "";
                $object-> valor_venda= "";
                $object->preco       = "";
              }else{                
                $preco = new Preco();
                $preco-> preco_custo = $object-> valor_custo;
                $preco-> preco_venda = $object-> valor_venda;
                $preco-> id_produto  = $object-> id;
                $preco-> id_tabela   = $object-> tabela_preco;
                $preco-> store();
                $object-> valor_custo= "";
                $object-> valor_venda= "";
                $object->preco       = "";
              }
        $preco->store();
//preenche o objeto deposito e adiciona seu id ao produto
    if($data->nova_qtd != null){
      $pesquisa_estoque = ProdEstoque::where('id_deposito','=',$object->deposito)
                            ->where('id_produto' ,'=',$object->id)->load();
                if(isset($pesquisa_estoque[0])){
                    $estoque = $pesquisa_estoque[0];
                    $estoque-> quantidade   = ($object-> qtd + $data->nova_qtd);
                    $estoque-> qtd_min      = $object-> qtd_min;
                    $estoque-> qtd_max      = $object-> qtd_max;
                    $estoque-> id_deposito  = $object-> deposito;
                    $estoque-> id_produto   = $object-> id;
                    $estoque-> store();
                    //trasnferencia
                    $transferencia                      = new TransferenciaEtq();
                    $transferencia->deposito_rec        = $data->deposito;
                    $transferencia->deposito_env        = $data->deposito;
                    $transferencia->estoque_id          = $estoque->id;
                    $transferencia->quantidade          = $data->nova_qtd;
                    $transferencia->dt_registro         = date('Y-m-d H:i:s');
                    $transferencia->usuario             = TSession::getValue('userid');
                    $transferencia->id_produto          = $object->id;
                    $transferencia->tipo_transferencia  = 'entrada';
                    $transferencia->store();
                    $object-> qtd           = "";
                    $object-> qtd_min       = "";
                    $object-> qtd_max       = "";
                    $object-> deposito      = "";
                }else{
                    $estoque = new ProdEstoque();
                    $estoque-> quantidade   = ($object-> qtd + $data->nova_qtd);
                    $estoque-> qtd_min      = $object-> qtd_min;
                    $estoque-> qtd_max      = $object-> qtd_max;
                    $estoque-> id_deposito  = $object-> deposito;
                    $estoque-> id_produto   = $object-> id;
                    $estoque-> store();
                    //trasnferencia
                    $transferencia                      = new TransferenciaEtq();
                    $transferencia->deposito_rec        = $data->deposito;
                    $transferencia->deposito_env        = $data->deposito;
                    $transferencia->estoque_id          = $estoque->id;
                    $transferencia->quantidade          = $data->nova_qtd;
                    $transferencia->dt_registro         = date('Y-m-d H:i:s');
                    $transferencia->usuario             = TSession::getValue('userid');
                    $transferencia->id_produto          = $object->id;
                    $transferencia->tipo_transferencia  = 'entrada';
                    $transferencia->store();
                    $object-> qtd           = "";
                    $object-> qtd_min       = "";
                    $object-> qtd_max       = "";
                    $object-> deposito      = "";
                }
            $cest = Cest::where('id','=',$object->cest)->load();
            if(isset($cest[0])){
            $object->cest = $cest[0]->n_cest;
            }
    }
            $object->store();

//neste ponto começa o envio deste produto para o WooPOS -----------------------------------------------------------------------------
$woocommerce =  ApiManager::getWooClient();
    //ajuste no sku
  $SKU;
   if($object->cod_barras==""){
        $SKU =$object->SKU;
   }else{
        $SKU =$object->cod_barras;
   }

    //ajuste categoria
    $categoria = new CategoriaProduto($object->categoria_produto_id);
    //ajuste Marca
    $marca = new Marca($object->marca);
    $description = json_encode(array("sit_tribut"=>$object->sit_tribut,"ncm"=>$object->ncm,"cest"=>$object->cest,"icms"=>$object->icms,"origem"=>$object->origem));
    if(!isset($categoria->id_externo)){
        $data = [
    'name' => $categoria->nome,
];
       $categoria_ext = $woocommerce->post('products/categories', $data);
       $categoria->id_externo = $categoria_ext->id;
       $categoria->store();
    }

//calculo de cadastro do produto
$precosPromo = Preco::where('id_produto','=',$object->id)->where('id_tabela','=','2')->load();
$precosProd  = Preco::where('id_produto','=',$object->id)->where('id_tabela','=','1')->load();

    if($precosProd){
        $preco_ = $precosProd[0];
        $woo_produto = [
            'name' => strtoupper($object->descricao." ".$object->desc_variacao." - ".$marca->marca),
            'type' => 'simple',
            'status' => 'publish',
            'featured'=> false,
            'catalog_visibility'=>'visible',
            'description'=> $description,
            'short_description'=> '',
            'sku'=> $SKU,
            'regular_price'=>str_replace(",",".",$preco_->preco_venda),
            'sale_price'=>'',
            'date_on_sale_from'=>'',
            'date_on_sale_from_gmt'=>'',
            'date_on_sale_to'=>'',
            'date_on_sale_to_gmt'=>'',
            'virtual'=>false,
            'downloadable'=>false,
            'external_url'=>$object->link_site,
            'categories'=>[ ['id'=>$categoria->id_externo]  ],
        ];

        $woocommerce =  ApiManager::getWooClient();
       if($object->id_externo !=""){
            //echo "1/1";
            $wooSave = $woocommerce->put(('products/'.$object->id_externo), $woo_produto);
        }else{
            //echo "1/2";
            $wooSave = $woocommerce->post('products', $woo_produto);
            $object->id_externo = $wooSave->id;
        } 
        if(!$precosPromo){
        $woocommerce =  ApiManager::getWooClient(1);
          if($object->id_externo_promocao !=""){
            //echo "2/1";
            $wooSave = $woocommerce->put(('products/'.$object->id_externo_promocao), $woo_produto);
          }else{
            //echo "2/2";
            $wooSave = $woocommerce->post('products', $woo_produto);
            $object->id_externo_promocao = $wooSave->id;
          }  
        }
    }
    if($precosPromo){
        $preco_ = $precosPromo[0];
        $woo_produto = [
            'name' => strtoupper($object->descricao." ".$object->desc_variacao." - ".$marca->marca),
            'type' => 'simple',
            'status' => 'publish',
            'featured'=> false,
            'catalog_visibility'=>'visible',
            'description'=> $description,
            'short_description'=> '',
            'sku'=> $SKU,
            'regular_price'=>str_replace(",",".",$preco_->preco_venda),
            'sale_price'=>'',
            'date_on_sale_from'=>'',
            'date_on_sale_from_gmt'=>'',
            'date_on_sale_to'=>'',
            'date_on_sale_to_gmt'=>'',
            'virtual'=>false,
            'downloadable'=>false,
            'external_url'=>$object->link_site,
            'categories'=>[ ['id'=>$categoria->id_externo]  ],
        ];
        $woocommerce =  ApiManager::getWooClient(1);
    if($object->id_externo_promocao !=""){
        //echo "3/1";
        $wooSave = $woocommerce->put(('products/'.$object->id_externo_promocao), $woo_produto);
    }else{
        //echo "3/2";
        $wooSave = $woocommerce->post('products', $woo_produto);
        $object->id_externo_promocao = $wooSave->id;
    } 
    }

$object->store();
//-----------------------------------------------------------------------------------------------------------------------    

            $data->id = $object->id; 
            $data->SKU = $object->SKU; 
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

            $page_param=['key'=>$object->id_familia];
            TApplication::loadPage('CadastroProdutoForm', 'onShow',$page_param);
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
    public function onVoltar($param = null) 
    {
        try 
        {

    $pageParam = ['key'=>$param['id_familia']]; // ex.: = ['key' => 10]

    TApplication::loadPage('CadastroProdutoForm', 'onShow', $pageParam);

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
                $object     = new Produto($key);
                //carrega os atributos de preco
                 $precos = Preco::where('id_produto','=',$object->id)->load();
                        if(!isset($precos[1])){
                            $preco = $precos[0];
                                $object->valor_custo     = $preco->preco_custo;
                                $object->valor_venda     = $preco->preco_venda;
                                $object->preco           = $preco->id;
                        }
                 //carrega os atributos de estoque
                 //estoque padrão ID = 2
                  $estoques = ProdEstoque::where('id_deposito','=','2')->where('id_produto','=',$object->id)->load();
                 if(isset($estoques)){
                     foreach($estoques as $estoque){
                     $object->qtd         = $estoque->quantidade;
                     $object->qtd_min     = $estoque->qtd_min;
                     $object->qtd_max     = $estoque->qtd_max;
                     $object->estoque     = $estoque->id;
                     }
                 }
                 $mestre = new Produto($object->id_familia);
                 if($object->id_externo == $mestre->id_externo){
                     $object->id_externo="";
                 }
                 $object->unidade_id            = $mestre->unidade_id;
                 $object->tipo_cadastro         = $mestre->tipo_cadastro;
                 $object->categoria_produto_id  = $mestre->categoria_produto_id;
                 $object->fornecedor_id         = $mestre->fornecedor_id;
                 $object->marca                 = $mestre->marca;
                 $object->sit_tribut            = $mestre->sit_tribut;
                 $object->origem                = $mestre->origem;
                 $object->ncm                   = $mestre->ncm;
                 $object->cest                  = $mestre->cest;

                 //consulta produto no PDV
                $sku;
                 if(isset($object->cod_barras)||$object->cod_barras != ""){
                     $sku = $object->cod_barras;
                 }else{
                     $sku = $object->SKU;
                 }
                 $woocommerce =  ApiManager::getWooClient();
                 $results = $woocommerce->get("products?sku=$sku");
                 if($results){
                     foreach($results as $result){
                     $object->id_externo = $result->id;
                     }
                 }else{
                     $object->id_externo ="";
                 }
                 $woocommerce =  ApiManager::getWooClient(1);
                 $results = $woocommerce->get("products?sku=$sku");
                 if($results){
                     foreach($results as $result){
                     $object->id_externo_promocao = $result->id;
                     }
                 }else{
                     $object->id_externo_promocao = "";
                 }
                $this->form->setData($object);
                TTransaction::close();
/*  TRECHO BUGADO NO ADIANTI
                $object = new Produto($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

*/
            }
        }catch (Exception $e) {
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

        try
        {
            if(isset($param['key'])){
                $key = $param['key'];
                TTransaction::open(self::$database);
                $object     = new Produto($key);
                //prepara o form para cadastro de uma nova variavel de produto.
                $object->cod_barras ="";
                $object->mestre_variavel= 2;     
                $object->id_externo="";
                $object->SKU = 100000000000;
                 $precos = Preco::where('id_produto','=',$object->id)->load();
                        if(!isset($precos[1])){
                            $preco = $precos[0];
                                $object->valor_custo     = $preco->preco_custo;
                                $object->valor_venda     = $preco->preco_venda;
                                $object->preco           = $preco->id;
                        }
                 //carrega os atributos de estoque
                 //estoque padrao ID = 2
                  $estoques = ProdEstoque::where('id_deposito','=','2')->where('id_produto','=',$object->id)->load();
                 if(isset($estoques)){
                     foreach($estoques as $estoque){
                     $object->qtd         = $estoque->quantidade;
                     $object->qtd_min     = $estoque->qtd_min;
                     $object->qtd_max     = $estoque->qtd_max;
                     $object->estoque     = $estoque->id;
                     }
                 }
                  //consulta produto no PDV
                $sku;
                 if(isset($object->cod_barras)||$object->cod_barras != ""){
                     $sku = $object->cod_barras;
                 }else{
                     $sku = $object->SKU;
                 }
                 $woocommerce =  ApiManager::getWooClient();
                 $results = $woocommerce->get("products?sku=$sku");
                 if($results){
                     foreach($results as $result){
                     $object->id_externo = $result->id;
                     }
                 }else{
                     $object->id_externo ="";
                 }
                 $woocommerce =  ApiManager::getWooClient(1);
                 $results = $woocommerce->get("products?sku=$sku");
                 if($results){
                     foreach($results as $result){
                     $object->id_externo_promocao = $result->id;
                     }
                 }else{
                     $object->id_externo_promocao = "";
                 }
                $object->id_familia = $key;
                $object->id = "";
                $this->form->setData($object);
                TTransaction::close();
            }

/*  TRECHO BUGADO NO ADIANTI
                $object = new {builderModelName}($key); // instantiates the Active Record //</blockLine>
                $this->form->setData($object); // fill the form //</blockLine>
*/
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    } 

}

