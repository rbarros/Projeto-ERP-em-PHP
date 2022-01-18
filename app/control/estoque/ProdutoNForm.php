<?php

class ProdutoNForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'form_ProdutoNForm';

    use BuilderMasterDetailTrait;

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
        $this->form->setFormTitle("Produto");


        $id = new TEntry('id');
        $SKU = new TEntry('SKU');
        $dt_cadastro = new TDate('dt_cadastro');
        $id_externo_promocao = new TEntry('id_externo_promocao');
        $mestre_variavel = new TDBCombo('mestre_variavel', 'base_banco', 'MestreVariavel', 'id', '{tipo}','id asc'  );
        $id_familia = new TEntry('id_familia');
        $id_externo = new TEntry('id_externo');
        $descricao = new TEntry('descricao');
        $desc_variacao = new TEntry('desc_variacao');
        $cod_barras = new TEntry('cod_barras');
        $fornecedor_id = new TDBCombo('fornecedor_id', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc'  );
        $button__ = new TButton('button__');
        $marca = new TDBCombo('marca', 'base_banco', 'Marca', 'id', '{marca}','marca asc'  );
        $button__1 = new TButton('button__1');
        $categoria_produto_id = new TDBCombo('categoria_produto_id', 'base_banco', 'CategoriaProduto', 'id', '{nome}','nome asc'  );
        $button__2 = new TButton('button__2');
        $link_site = new TEntry('link_site');
        $tipo_cadastro = new TDBCombo('tipo_cadastro', 'base_banco', 'TipoCadastroProd', 'id', '{descricao}','descricao asc'  );
        $button__3 = new TButton('button__3');
        $situacao_prod = new TDBCombo('situacao_prod', 'base_banco', 'SituacaoProd', 'id', '{situacao_prod}','situacao_prod asc'  );
        $button__4 = new TButton('button__4');
        $referencia = new TEntry('referencia');
        $unidade_id = new TDBCombo('unidade_id', 'base_banco', 'Unidade', 'id', '{nome}','nome asc'  );
        $button__5 = new TButton('button__5');
        $origem = new TCombo('origem');
        $sit_tribut = new TCombo('sit_tribut');
        $ncm = new TEntry('ncm');
        $cest = new TCombo('cest');
        $button__6 = new TButton('button__6');
        $obs = new TText('obs');
        $Preco_produto_id_tabela = new TDBCombo('Preco_produto_id_tabela', 'base_banco', 'TabelaPreco', 'id', '{nome_tabela_preco}','nome_tabela_preco asc'  );
        $price = new THidden('price');
        $Preco_produto_preco_custo = new TNumeric('Preco_produto_preco_custo', '2', ',', '.' );
        $Preco_produto_preco_venda = new TNumeric('Preco_produto_preco_venda', '2', ',', '.' );
        $Preco_produto_id = new THidden('Preco_produto_id');
        $button_adicionar_preco_Preco_produto = new TButton('button_adicionar_preco_Preco_produto');
        $prod_estoque_produto_id_deposito = new TDBCombo('prod_estoque_produto_id_deposito', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $prod_estoque_produto_id = new THidden('prod_estoque_produto_id');
        $prod_estoque_produto_quantidade = new TNumeric('prod_estoque_produto_quantidade', '0', '', '' );
        $nova_qtd = new TNumeric('nova_qtd', '0', '', '' );
        $prod_estoque_produto_qtd_min = new TNumeric('prod_estoque_produto_qtd_min', '0', '', '' );
        $prod_estoque_produto_qtd_max = new TNumeric('prod_estoque_produto_qtd_max', '0', '', '' );
        $button_adicionar_estoque_prod_estoque_produto = new TButton('button_adicionar_estoque_prod_estoque_produto');

        $Preco_produto_id_tabela->setChangeAction(new TAction([$this,'onChangeTabela']));
        $prod_estoque_produto_id_deposito->setChangeAction(new TAction([$this,'onChangeEstoque']));

        $cod_barras->setExitAction(new TAction([$this,'onEanValidate']));
        $ncm->setExitAction(new TAction([$this,'onNcmValidate']));
        $nova_qtd->setExitAction(new TAction([$this,'onAddQtd']));

        $SKU->addValidation("SKU", new TRequiredValidator()); 
        $dt_cadastro->addValidation("Data de cadastro", new TRequiredValidator()); 
        $origem->addValidation("ORIGEM DO PRODUTO", new TRequiredValidator()); 
        $sit_tribut->addValidation("ST", new TRequiredValidator()); 
        $ncm->addValidation("NCM", new TRequiredValidator()); 
        $cest->addValidation("CEST", new TRequiredValidator()); 

        $dt_cadastro->setDatabaseMask('yyyy-mm-dd');
        $ncm->setMask('99999999');
        $dt_cadastro->setMask('dd/mm/yyyy');

        $sit_tribut->addItems(['102'=>'produto sem ST','500'=>'produto com ST']);
        $origem->addItems(['0'=>' 1 - Nacional, exceto as indicadas 3, 4, 5 e 8','1'=>' 2 - Estrangeira - importação direta, exceto indicada no código 6','2'=>' 3 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7','3'=>' 4 - Nacional, mercadoria ou bem de consumo com conteúdo de importação superior a 40% e inferior ou igual a 70%','4'=>' 5 - nacional, cujo produção tenha sido feita em conformidade  com os processos produtivos básicos de que se tratam  as legislações citadas nos  Ajustes','5'=>' 6 - Nacional, mercadoria ou bem com conteúdo de importação inferior ou igual a 40%','6'=>' 7 - Estrangeira - Importação direta ou similar nacional, constante em lista CAMEX','7'=>' 8 - Estrangeira - adquirida no mercado interno, sem  similar nacional , constante em lista da CAMEX','8'=>' 9 - Nacional, mercadoria ou  bem com conteúdo de importação superior a 70%']);

        $SKU->setMaxLength(20);
        $descricao->setMaxLength(60);
        $cod_barras->setMaxLength(13);
        $referencia->setMaxLength(30);
        $desc_variacao->setMaxLength(50);

        $cest->setDefaultOption(false);
        $origem->setDefaultOption(false);
        $unidade_id->setDefaultOption(false);
        $tipo_cadastro->setDefaultOption(false);
        $situacao_prod->setDefaultOption(false);
        $mestre_variavel->setDefaultOption(false);
        $categoria_produto_id->setDefaultOption(false);

        $price->setValue('false');
        $unidade_id->setValue('1');
        $tipo_cadastro->setValue('1');
        $situacao_prod->setValue('1');
        $Preco_produto_id_tabela->setValue('1');
        $prod_estoque_produto_qtd_min->setValue('0');
        $prod_estoque_produto_quantidade->setValue('0');
        $prod_estoque_produto_id_deposito->setValue('2');

        $id->setEditable(false);
        $SKU->setEditable(false);
        $id_familia->setEditable(false);
        $id_externo->setEditable(false);
        $dt_cadastro->setEditable(false);
        $desc_variacao->setEditable(false);
        $mestre_variavel->setEditable(false);
        $id_externo_promocao->setEditable(false);
        $prod_estoque_produto_quantidade->setEditable(false);

        $button__4->id = 'addSit';
        $button__3->id = 'addTipo';
        $button__1->id = 'addMarca';
        $button__5->id = 'addUnidade';
        $button__->id = 'addForncedor';
        $button__2->id = 'addCategoria';
        $button_adicionar_preco_Preco_produto->id = 'priceBT';
        $button_adicionar_estoque_prod_estoque_produto->id = 'estoqueBT';

        $button__1->setAction(new TAction(['MarcaForm', 'onEdit']), "  ");
        $button__->setAction(new TAction(['FornecedorForm', 'onEdit']), " ");
        $button__6->setAction(new TAction(['CestForm', 'onEdit']), "       ");
        $button__5->setAction(new TAction(['UnidadeForm', 'onEdit']), "      ");
        $button__4->setAction(new TAction(['SituacaoProdForm', 'onEdit']), "    ");
        $button__3->setAction(new TAction(['TipoCadastroProdForm', 'onEdit']), "  ");
        $button__2->setAction(new TAction(['CategoriaProdutoForm', 'onEdit']), "     ");
        $button_adicionar_preco_Preco_produto->setAction(new TAction([$this, 'onAddDetailPrecoProduto'],['static' => 1]), "Adicionar preço");
        $button_adicionar_estoque_prod_estoque_produto->setAction(new TAction([$this, 'onAddDetailProdEstoqueProduto'],['static' => 1]), "Adicionar Estoque");

        $button__->addStyleClass('btn-default');
        $button__1->addStyleClass('btn-default');
        $button__2->addStyleClass('btn-default');
        $button__3->addStyleClass('btn-default');
        $button__4->addStyleClass('btn-default');
        $button__5->addStyleClass('btn-default');
        $button__6->addStyleClass('btn-default');
        $button_adicionar_preco_Preco_produto->addStyleClass('btn-default');
        $button_adicionar_estoque_prod_estoque_produto->addStyleClass('btn-default');

        $button__->setImage('fas:plus #FF0091');
        $button__1->setImage('fas:plus #FF0091');
        $button__2->setImage('fas:plus #FF0091');
        $button__3->setImage('fas:plus #FF0091');
        $button__4->setImage('fas:plus #FF0091');
        $button__5->setImage('fas:plus #FF0091');
        $button__6->setImage('fas:plus #FF0091');
        $button_adicionar_preco_Preco_produto->setImage('fas:dollar-sign #2ecc71');
        $button_adicionar_estoque_prod_estoque_produto->setImage('fas:plus #2ecc71');

        $id->setSize(100);
        $price->setSize(200);
        $ncm->setSize('100%');
        $SKU->setSize('100%');
        $cest->setSize('100%');
        $nova_qtd->setSize(110);
        $marca->setSize('100%');
        $origem->setSize('100%');
        $obs->setSize('100%', 70);
        $link_site->setSize('100%');
        $descricao->setSize('100%');
        $sit_tribut->setSize('100%');
        $referencia->setSize('100%');
        $unidade_id->setSize('100%');
        $id_externo->setSize('100%');
        $cod_barras->setSize('100%');
        $id_familia->setSize('100%');
        $dt_cadastro->setSize('100%');
        $tipo_cadastro->setSize('100%');
        $situacao_prod->setSize('100%');
        $fornecedor_id->setSize('100%');
        $desc_variacao->setSize('100%');
        $Preco_produto_id->setSize(200);
        $mestre_variavel->setSize('100%');
        $id_externo_promocao->setSize('100%');
        $categoria_produto_id->setSize('100%');
        $prod_estoque_produto_id->setSize(200);
        $Preco_produto_id_tabela->setSize('100%');
        $Preco_produto_preco_custo->setSize('100%');
        $Preco_produto_preco_venda->setSize('100%');
        $prod_estoque_produto_qtd_min->setSize('70%');
        $prod_estoque_produto_qtd_max->setSize('70%');
        $prod_estoque_produto_quantidade->setSize('70%');
        $prod_estoque_produto_id_deposito->setSize('100%');

        $Preco_produto_id_tabela->id            = 'tabelaPreco';
        $prod_estoque_produto_id_deposito->id   = 'deposito';
        $cards      = new TCardView;
        $key        = $param['key'];
        TTransaction::open(self::$database);
        $produto    = new Produto($key);
        $familia    = $produto->id_familia; 
        if($produto->id_familia != null){
                $variacoes  = Produto::where('id_familia','=',$familia)->load();
                    $colors     = ['1'=>'#FF1493', '2'=>'#000000'];
                    $color      = '1';
                    if($variacoes){
                        foreach($variacoes as $variacao){
                            $sku    = $variacao->cod_barras == null ? $variacao->SKU : $variacao->cod_barras;
                            $img    = null;
                            $mestreVariavel =  new MestreVariavel($variacao->mestre_variavel);
                            //imagem
                            if($variacao->link_site == null){
                                 $categoria = $variacao->categoria_produto_id;
                                 $img = $categoria->iconeCategoria != null ? $categoria->iconeCategoria : "http://192.241.159.164/icons/noImage.png";
                            }
                            //precos
                            $precos     = Preco::where('id_produto','=',$variacao->id)->load();
                            $preco      = '';
                            if($precos){
                                foreach($precos as $preco_){
                                    $preco_->preco_venda = str_replace('.',',',$preco_->preco_venda);
                                    $preco .= "<p><b>Tabela $preco_->id_tabela</b> - R$ $preco_->preco_venda</p>";
                                }
                            }
                            //estoque
                            $estoques = ProdEstoque::where('id_produto','=',$variacao->id)->load();
                            $estoque  = '';
                            if($estoques){
                                foreach($estoques as $estoque_){
                                    $deposito = new Deposito($estoque_->id_deposito);
                                    $estoque .= "<p><b>$deposito->nome_deposito</b> - $estoque_->quantidade</p>";
                                }
                            }

                            //<img src="'.$img.'" class="card-img-top" alt="'."$sku - $variacao->descricao".'" title="sku: '.$sku.'"><br></img>
                            $item   = (object)[ 
                                'id'   => $variacao->id,
                                'title'     => $sku.'-'.$variacao->descricao,
                                'descricao' =>
                                '   <div class="CardBoxNoImage">
                                        <i class="fas fa-boxes" style="; color: '.$colors[$variacao->mestre_variavel].'"></i>
                                    </div>
                                    <div>
                                        <h5><b>'.$mestreVariavel->tipo.'</b> '.$variacao->desc_variacao.' '.$variacao->referencia.'</h5>
                                        '.$preco.$estoque.'
                                    </div>
                                    ',
                                'color'     => $colors[$variacao->mestre_variavel]
                                ];
                            $cards->addItem($item);
                        }
                    }
                    $cards->setTitleAttribute('title');
                    $cards->setColorAttribute('color');
                    $cards->setItemTemplate('{descricao}');

                    $gerar_pendencia    = new TAction([$this, 'onEdit'], ['id'=> '{id}','produto'=>$produto]);
                    $cards->addAction($gerar_pendencia  ,'Editar produto',     'fas: fa-edit #478fca');
                    $gerar_pendencia    = new TAction([$this, 'onPriceEdit'], ['id'=> '{id}','produto'=>$produto]);
                    $cards->addAction($gerar_pendencia  ,'Editar preco',     'fas: fa-dollar-sign #00FA9A');
                    $gerar_pendencia    = new TAction([$this, 'onEstoqueEdit'], ['id'=> '{id}','produto'=>$produto]);
                    $cards->addAction($gerar_pendencia  ,'Editar estoque',     'fas: fas fa-dolly #4B0082');
                    $gerar_pendencia    = new TAction([$this, 'onTransferencia'], ['id'=> '{id}','produto'=>$produto]);
                    $cards->addAction($gerar_pendencia  ,'Transferir produto',     'fas: fa-exchange-alt #00FA9A');
                    $gerar_pendencia    = new TAction([$this, 'onBarcode'], ['id'=> '{id}','produto'=>$produto]);
                    $cards->addAction($gerar_pendencia  ,'Gerar etiqueta',     'fas: fa-barcode #333333');
        }
        TTransaction::close();

        $this->form->appendPage("Geral");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("SKU:", null, '14px', null, '100%'),$SKU],[],[],[new TLabel("Data de cadastro:", null, '14px', null, '100%'),$dt_cadastro],[new TLabel("Id PDV Promoção", null, '14px', null, '100%'),$id_externo_promocao]);
        $row1->layout = [' col-sm-6 col-lg-1','col-sm-2',' col-sm-2 col-lg-3','col-sm-2',' col-sm-6 col-lg-2',' col-sm-6 col-lg-2'];

        $row2 = $this->form->addFields([],[new TLabel("Mestre variavel:", null, '14px', null, '100%'),$mestre_variavel],[],[],[new TLabel("Id familia:", null, '14px', null, '100%'),$id_familia],[new TLabel("Id PDV online", null, '14px', null, '100%'),$id_externo]);
        $row2->layout = [' col-sm-3 col-lg-1',' col-sm-6 col-lg-2',' col-sm-3 col-lg-3','col-sm-2','col-sm-2','col-sm-2'];

        $row3 = $this->form->addFields([new TLabel("Descrição do Produto:", null, '14px', null, '100%'),$descricao],[new TLabel("Descrição de variação:", null, '14px', null, '100%'),$desc_variacao],[new TLabel("Código de barras/ EAN13:", null, '14px', null, '100%'),$cod_barras]);
        $row3->layout = [' col-sm-6 col-lg-6',' col-sm-6 col-lg-3',' col-sm-2 col-lg-3'];

        $row4 = $this->form->addFields([new TLabel("Fornecedor:", null, '14px', null, '100%'),$fornecedor_id],[new TLabel("  ", null, '14px', null, '100%'),$button__],[new TLabel("Marca:", null, '14px', null, '100%'),$marca],[new TLabel("  ", null, '14px', null, '100%'),$button__1],[new TLabel("Categoria do produto:", null, '14px', null, '100%'),$categoria_produto_id],[new TLabel("  ", null, '14px', null, '100%'),$button__2],[new TLabel("Link no Site:", null, '14px', null, '100%'),$link_site]);
        $row4->layout = [' col-sm-6 col-lg-3',' col-sm-2 col-lg-1',' col-sm-2 col-lg-2',' col-sm-2 col-lg-1',' col-sm-6 col-lg-2',' col-sm-2 col-lg-1',' col-sm-2 col-lg-2'];

        $row5 = $this->form->addFields([new TLabel("Tipo cadastro:", null, '14px', null, '100%'),$tipo_cadastro],[new TLabel("  ", null, '14px', null, '100%'),$button__3],[new TLabel("situação do produto:", null, '14px', null, '100%'),$situacao_prod],[new TLabel("   ", null, '14px', null, '100%'),$button__4],[new TLabel("referência:", null, '14px', null, '100%'),$referencia],[new TLabel("Unidade de medida:", null, '14px', null, '100%'),$unidade_id],[new TLabel("   ", null, '14px', null, '100%'),$button__5]);
        $row5->layout = [' col-sm-6 col-lg-3',' col-sm-2 col-lg-1',' col-sm-6 col-lg-2',' col-sm-2 col-lg-1',' col-sm-6 col-lg-2',' col-sm-6 col-lg-2',' col-sm-2 col-lg-1'];

        $this->form->appendPage("Tributário");
        $row6 = $this->form->addFields([new TLabel("Origem do produto:", null, '14px', null, '100%'),$origem],[new TLabel("Situação Tributária (ST):", null, '14px', null, '100%'),$sit_tribut]);
        $row6->layout = [' col-sm-3 col-lg-6','col-sm-6'];

        $row7 = $this->form->addFields([new TLabel("Ncm:", null, '14px', null, '100%'),$ncm],[new TLabel("Cest:", null, '14px', null, '100%'),$cest],[new TLabel("   ", null, '14px', null, '100%'),$button__6],[new TLabel("Observação:", null, '14px', null, '100%'),$obs]);
        $row7->layout = [' col-sm-6 col-lg-2',' col-sm-2 col-lg-3',' col-sm-2 col-lg-1','col-sm-6'];

        $this->form->appendPage("Precificação");

        $this->detailFormPrecoProduto = new BootstrapFormBuilder('detailFormPrecoProduto');
        $this->detailFormPrecoProduto->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormPrecoProduto->setProperty('class', 'form-horizontal builder-detail-form');

        $row8 = $this->detailFormPrecoProduto->addFields([new TLabel("Tabela:", null, '14px', null, '100%'),$Preco_produto_id_tabela],[$price],[],[new TLabel("Preco custo:", null, '14px', null, '100%'),$Preco_produto_preco_custo],[new TLabel("Preco venda:", null, '14px', null, '100%'),$Preco_produto_preco_venda,$Preco_produto_id]);
        $row8->layout = ['col-lg-5','col-sm-2 col-lg-1','col-sm-2','col-sm-2','col-sm-2'];

        $row9 = $this->detailFormPrecoProduto->addFields([$button_adicionar_preco_Preco_produto]);
        $row9->layout = [' col-sm-12'];

        $row10 = $this->detailFormPrecoProduto->addFields([new THidden('Preco_produto__row__id')]);
        $this->Preco_produto_criteria = new TCriteria();

        $this->Preco_produto_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->Preco_produto_list->disableHtmlConversion();;
        $this->Preco_produto_list->generateHiddenFields();
        $this->Preco_produto_list->setId('Preco_produto_list');

        $this->Preco_produto_list->style = 'width:100%';
        $this->Preco_produto_list->class .= ' table-bordered';

        $column_Preco_produto_tabela_nome_tabela_preco = new TDataGridColumn('tabela->nome_tabela_preco', "Id tabela", 'left');
        $column_Preco_produto_preco_custo_transformed = new TDataGridColumn('preco_custo', "Preco custo", 'left');
        $column_Preco_produto_preco_venda_transformed = new TDataGridColumn('preco_venda', "Preco venda", 'left');

        $column_Preco_produto__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_Preco_produto__row__data->setVisibility(false);

        $action_onEditDetailPreco = new TDataGridAction(array('ProdutoNForm', 'onEditDetailPreco'));
        $action_onEditDetailPreco->setUseButton(false);
        $action_onEditDetailPreco->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailPreco->setLabel("Editar");
        $action_onEditDetailPreco->setImage('far:edit #478fca');
        $action_onEditDetailPreco->setFields(['__row__id', '__row__data']);

        $this->Preco_produto_list->addAction($action_onEditDetailPreco);
        $action_onDeleteDetailPreco = new TDataGridAction(array('ProdutoNForm', 'onDeleteDetailPreco'));
        $action_onDeleteDetailPreco->setUseButton(false);
        $action_onDeleteDetailPreco->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailPreco->setLabel("Excluir");
        $action_onDeleteDetailPreco->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailPreco->setFields(['__row__id', '__row__data']);

        $this->Preco_produto_list->addAction($action_onDeleteDetailPreco);

        $this->Preco_produto_list->addColumn($column_Preco_produto_tabela_nome_tabela_preco);
        $this->Preco_produto_list->addColumn($column_Preco_produto_preco_custo_transformed);
        $this->Preco_produto_list->addColumn($column_Preco_produto_preco_venda_transformed);

        $this->Preco_produto_list->addColumn($column_Preco_produto__row__data);

        $this->Preco_produto_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->Preco_produto_list);
        $this->detailFormPrecoProduto->addContent([$tableResponsiveDiv]);

        $column_Preco_produto_preco_custo_transformed->setTransformer(function($value, $object, $row) 
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_Preco_produto_preco_venda_transformed->setTransformer(function($value, $object, $row) 
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });        $row11 = $this->form->addFields([$this->detailFormPrecoProduto]);
        $row11->layout = [' col-sm-2 col-lg-12'];

        $this->form->appendPage("Estoque");

        $this->detailFormProdEstoqueProduto = new BootstrapFormBuilder('detailFormProdEstoqueProduto');
        $this->detailFormProdEstoqueProduto->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormProdEstoqueProduto->setProperty('class', 'form-horizontal builder-detail-form');

        $row12 = $this->detailFormProdEstoqueProduto->addFields([new TLabel("Deposito:", null, '14px', null, '100%'),$prod_estoque_produto_id_deposito,$prod_estoque_produto_id],[new TLabel("Quantidade:", null, '14px', null, '100%'),$prod_estoque_produto_quantidade],[new TLabel("Adicionar Quantidade:", null, '14px', null, '100%'),$nova_qtd],[new TLabel("Qtd min:", null, '14px', null, '100%'),$prod_estoque_produto_qtd_min],[new TLabel("Qtd max:", null, '14px', null, '100%'),$prod_estoque_produto_qtd_max]);
        $row12->layout = ['col-sm-6 col-lg-4',' col-sm-2  noProperty','col-sm-2',' col-sm-6 col-lg-2  noProperty',' col-sm-2  noProperty'];

        $row13 = $this->detailFormProdEstoqueProduto->addFields([$button_adicionar_estoque_prod_estoque_produto]);
        $row13->layout = [' col-sm-12'];

        $row14 = $this->detailFormProdEstoqueProduto->addFields([new THidden('prod_estoque_produto__row__id')]);
        $this->prod_estoque_produto_criteria = new TCriteria();

        $this->prod_estoque_produto_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->prod_estoque_produto_list->disableHtmlConversion();;
        $this->prod_estoque_produto_list->generateHiddenFields();
        $this->prod_estoque_produto_list->setId('prod_estoque_produto_list');

        $this->prod_estoque_produto_list->style = 'width:100%';
        $this->prod_estoque_produto_list->class .= ' table-bordered';

        $column_prod_estoque_produto_deposito_nome_deposito = new TDataGridColumn('deposito->nome_deposito', "Deposito:", 'left');
        $column_prod_estoque_produto_quantidade = new TDataGridColumn('quantidade', "Quantidade", 'left');
        $column_prod_estoque_produto_qtd_min = new TDataGridColumn('qtd_min', "Qtd min", 'left');
        $column_prod_estoque_produto_qtd_max = new TDataGridColumn('qtd_max', "Qtd max", 'left');
        $column_prod_estoque_produto_curva = new TDataGridColumn('curva', "Curva", 'left');

        $column_prod_estoque_produto__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_prod_estoque_produto__row__data->setVisibility(false);

        $action_onEditDetailProdEstoque = new TDataGridAction(array('ProdutoNForm', 'onEditDetailProdEstoque'));
        $action_onEditDetailProdEstoque->setUseButton(false);
        $action_onEditDetailProdEstoque->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailProdEstoque->setLabel("Editar");
        $action_onEditDetailProdEstoque->setImage('far:edit #478fca');
        $action_onEditDetailProdEstoque->setFields(['__row__id', '__row__data']);

        $this->prod_estoque_produto_list->addAction($action_onEditDetailProdEstoque);
        $action_onDeleteDetailProdEstoque = new TDataGridAction(array('ProdutoNForm', 'onDeleteDetailProdEstoque'));
        $action_onDeleteDetailProdEstoque->setUseButton(false);
        $action_onDeleteDetailProdEstoque->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailProdEstoque->setLabel("Excluir");
        $action_onDeleteDetailProdEstoque->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailProdEstoque->setFields(['__row__id', '__row__data']);

        $this->prod_estoque_produto_list->addAction($action_onDeleteDetailProdEstoque);

        $this->prod_estoque_produto_list->addColumn($column_prod_estoque_produto_deposito_nome_deposito);
        $this->prod_estoque_produto_list->addColumn($column_prod_estoque_produto_quantidade);
        $this->prod_estoque_produto_list->addColumn($column_prod_estoque_produto_qtd_min);
        $this->prod_estoque_produto_list->addColumn($column_prod_estoque_produto_qtd_max);
        $this->prod_estoque_produto_list->addColumn($column_prod_estoque_produto_curva);

        $this->prod_estoque_produto_list->addColumn($column_prod_estoque_produto__row__data);

        $this->prod_estoque_produto_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->prod_estoque_produto_list);
        $this->detailFormProdEstoqueProduto->addContent([$tableResponsiveDiv]);

        $row15 = $this->form->addFields([$this->detailFormProdEstoqueProduto]);
        $row15->layout = [' col-sm-2 col-lg-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onnewproduct = $this->form->addAction("Novo Produto", new TAction([$this, 'onNewProduct']), 'fas:plus #4CAF50');
        $this->btn_onnewproduct = $btn_onnewproduct;

        $btn_onvariation = $this->form->addAction("Adicionar Variação de produto", new TAction([$this, 'onVariation']), 'fas:plus #FF0091');
        $this->btn_onvariation = $btn_onvariation;

        $btn_onvoltar = $this->form->addAction("Voltar", new TAction([$this, 'onVoltar'],['static' => 1]), 'fas:arrow-left #FFFFFF');
        $this->btn_onvoltar = $btn_onvoltar;
        $btn_onvoltar->addStyleClass('btn-success'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Estoque","Produto"]));
        }
        $container->add($this->form);

        /*
        $row1= $this->form->addFields([$cards]);
        $row1->layout = [' col-sm-3 col-lg-12'];
        */
        $containerAlt = new TVBox;
        $containerAlt->style = 'width: 100%';
        $containerAlt->class = 'form-container';
        $containerAlt->add($cards);
        $container->add($containerAlt);

        /*
        //precisa por em loop até surgir o detailForm-472948-4139838-RemoveRowOnDeleteAutoCode
        forma A
        TScript::create(
        "document.getElementsByClassName('input-group spinner')[0].style.flexWrap = 'unset'
         document.getElementsByClassName('input-group spinner')[1].style.flexWrap = 'unset'
         document.getElementsByClassName('input-group spinner')[2].style.flexWrap = 'unset'
        "
        );
        forma B
        var spins = document.getElementsByClassName('input-group spinner')
            for (var i = 0; i < spins.length; i++){
                document.getElementsByClassName('input-group spinner')[i].style.flexWrap = 'unset'
            }
        */

        parent::add($container);

    }

    public static function onEanValidate($param = null) 
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

    public static function onNcmValidate($param = null) 
    {
        try 
        {
            $ncm = $param['ncm'];
            $ind = 0;
            $combo_cest =[];

            if($ncm!=""){
                TTransaction::open(self::$database);
                $ncm_busca = Ncm::where('n_ncm','=',$ncm)->load();
                if(isset($ncm_busca[0])){
                    foreach($ncm_busca as $ncm){
                        $cest = new Cest($ncm->cest);
                        $combo_cest[$cest->id]= $cest->n_cest." - ". $cest-> descricao;
                    }
            TCombo::reload(self::$formName, 'cest', $combo_cest, true);
                }else{
                    $object = new stdClass();
                    $object->ncm = '';
                    TForm::sendData(self::$formName, $object);
                    new TMessage ('info','verifique o NCM, não se encontra cadastrado.');
                }

                TTransaction::close();
            }

        // Código gerado pelo snippet: "Recarregar combo através de filtros" 

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onAddQtd($param = null) 
    {
        try 
        {   
            //SEÇÃO ESTOQUE id_produto->id_deposito->produto,depoisto,quantidade,qtd_adicionado
            if($param['nova_qtd'] != '' || $param['nova_qtd'] != null ){
                $quantidade  = TSession::getValue('estoque');
                $id_produto  = isset($param['id']) && $param['id'] != '' ? $param['id']: $param['sku'];
                $id_deposito = isset($param['prod_estoque_produto_id_deposito']) && $param['prod_estoque_produto_id_deposito'] != '' ? $param['prod_estoque_produto_id_deposito'] : false;
                if(isset($quantidade[$id_produto][$id_deposito])){
                    $quantidade[$id_produto][$id_deposito]['qtd_adicionado'] = $param['nova_qtd'];

                    $valor = TSession::setValue('estoque', $quantidade);
                    //update quantidade
                    $object = new stdClass();
                    $object->prod_estoque_produto_quantidade = $quantidade[$id_produto][$id_deposito]['quantidade'] + $param['nova_qtd'];
                    TForm::sendData(self::$formName, $object);
                }else{
                    $quantidade = array();
                    $quantidade[$id_produto][$id_deposito]['produto']        = $id_produto;
                    $quantidade[$id_produto][$id_deposito]['deposito']       = $id_deposito;
                    $quantidade[$id_produto][$id_deposito]['quantidade']     = $param['prod_estoque_produto_quantidade'];
                    $quantidade[$id_produto][$id_deposito]['qtd_adicionado'] = $param['nova_qtd'];
                    $valor = TSession::setValue('estoque', $quantidade);
                    //update quantidade
                    $object = new stdClass();
                    $object->prod_estoque_produto_quantidade = $quantidade[$id_produto][$id_deposito]['quantidade'] + $param['nova_qtd'];
                    TForm::sendData(self::$formName, $object);
                }
            }

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
           TTransaction::open(self::$database);
           $id                  = $param['id'];
           $tabela_preco        = $param['Preco_produto_id_tabela'];
           $precos              = Preco::where('id_tabela','=',$tabela_preco)
                                       ->where('id_produto','=',$id)
                                       ->load();
           if($precos){
                TScript::create(
                "
                var condicao = document.querySelector('#priceBT > span').textContent;
                if(condicao != 'Editar'){
                    document.getElementById('tabelaPreco').value = '';
                    alert('ATENÇÃO! você está cadastrando um preço em uma tabela já criada! utilize o botão EDITAR abaixo para alterar este preço.');
                }
                ");
           }
           TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeEstoque($param = null) 
    {
        try 
        {
           TTransaction::open(self::$database);
           $produto                 = $param['id'];
           $deposito                = $param['prod_estoque_produto_id_deposito'];   
           $estoques                = ProdEstoque::where('id_produto','=',$produto)
                                                ->where('id_deposito','=',$deposito)
                                                ->load();
           $object = new stdClass();
            if(isset($estoques[0])){
               TScript::create(
                "
                var condicao = document.querySelector('#estoqueBT > span').textContent;
                if(condicao != 'Editar'){
                    document.getElementById('deposito').value = '';
                    alert('ATENÇÃO! você está cadastrando um estoque em um depósito já cadastrado! utilize o botão EDITAR abaixo para alterar este estoque.');
                }
                ");
            }
            TForm::sendData(self::$formName, $object);
           TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailPrecoProduto($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields["Preco_produto_id_tabela"] = "Id tabela";
            $requiredFields["Preco_produto_preco_custo"] = "Preco custo";
            $requiredFields["Preco_produto_preco_venda"] = "Preco venda";
            foreach($requiredFields as $keyFieldName => $labelRequired)
            {
                try
                {
                    (new TRequiredValidator)->validate($labelRequired, $data->{$keyFieldName});
                }
                catch(Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
             }
             if(count($errors) > 0)
             {
                 throw new Exception(implode('<br>', $errors));
             }

            $__row__id = !empty($data->Preco_produto__row__id) ? $data->Preco_produto__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new Preco();
            $grid_data->__row__id = $__row__id;
            $grid_data->id_tabela = $data->Preco_produto_id_tabela;
            $grid_data->price = $data->price;
            $grid_data->preco_custo = $data->Preco_produto_preco_custo;
            $grid_data->preco_venda = $data->Preco_produto_preco_venda;
            $grid_data->id = $data->Preco_produto_id;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['id_tabela'] =  $param['Preco_produto_id_tabela'] ?? null;
            $__row__data['__display__']['price'] =  $param['price'] ?? null;
            $__row__data['__display__']['preco_custo'] =  $param['Preco_produto_preco_custo'] ?? null;
            $__row__data['__display__']['preco_venda'] =  $param['Preco_produto_preco_venda'] ?? null;
            $__row__data['__display__']['id'] =  $param['Preco_produto_id'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->Preco_produto_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('Preco_produto_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->Preco_produto_id_tabela = '1';
            $data->price = 'false';
            $data->Preco_produto_preco_custo = '';
            $data->Preco_produto_preco_venda = '';
            $data->Preco_produto_id = '';
            $data->Preco_produto__row__id = '';

            $data->Preco_produto_id_tabela = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#priceBT');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public  function onAddDetailProdEstoqueProduto($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields["prod_estoque_produto_id_deposito"] = "Id deposito";
            $requiredFields["prod_estoque_produto_quantidade"] = "Quantidade";
            foreach($requiredFields as $keyFieldName => $labelRequired)
            {
                try
                {
                    (new TRequiredValidator)->validate($labelRequired, $data->{$keyFieldName});
                }
                catch(Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
             }
             if(count($errors) > 0)
             {
                 throw new Exception(implode('<br>', $errors));
             }

            $__row__id = !empty($data->prod_estoque_produto__row__id) ? $data->prod_estoque_produto__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new ProdEstoque();
            $grid_data->__row__id = $__row__id;
            $grid_data->id_deposito = $data->prod_estoque_produto_id_deposito;
            $grid_data->id = $data->prod_estoque_produto_id;
            $grid_data->quantidade = $data->prod_estoque_produto_quantidade;
            $grid_data->nova_qtd = $data->nova_qtd;
            $grid_data->qtd_min = $data->prod_estoque_produto_qtd_min;
            $grid_data->qtd_max = $data->prod_estoque_produto_qtd_max;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['id_deposito'] =  $param['prod_estoque_produto_id_deposito'] ?? null;
            $__row__data['__display__']['id'] =  $param['prod_estoque_produto_id'] ?? null;
            $__row__data['__display__']['quantidade'] =  $param['prod_estoque_produto_quantidade'] ?? null;
            $__row__data['__display__']['nova_qtd'] =  $param['nova_qtd'] ?? null;
            $__row__data['__display__']['qtd_min'] =  $param['prod_estoque_produto_qtd_min'] ?? null;
            $__row__data['__display__']['qtd_max'] =  $param['prod_estoque_produto_qtd_max'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->prod_estoque_produto_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('prod_estoque_produto_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->prod_estoque_produto_id_deposito = '2';
            $data->prod_estoque_produto_id = '';
            $data->prod_estoque_produto_quantidade = '0';
            $data->nova_qtd = '';
            $data->prod_estoque_produto_qtd_min = '0';
            $data->prod_estoque_produto_qtd_max = '';
            $data->prod_estoque_produto__row__id = '';

            $data->prod_estoque_produto_id_deposito = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#estoqueBT');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public static function onEditDetailPreco($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->Preco_produto_id_tabela = $__row__data->__display__->id_tabela ?? null;
            $data->price = $__row__data->__display__->price ?? null;
            $data->Preco_produto_preco_custo = $__row__data->__display__->preco_custo ?? null;
            $data->Preco_produto_preco_venda = $__row__data->__display__->preco_venda ?? null;
            $data->Preco_produto_id = $__row__data->__display__->id ?? null;
            $data->Preco_produto__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#priceBT');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='far fa-edit' style='color:#478fca;padding-right:4px;'></i>Editar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailPreco($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->Preco_produto_id_tabela = '';
            $data->price = '';
            $data->Preco_produto_preco_custo = '';
            $data->Preco_produto_preco_venda = '';
            $data->Preco_produto_id = '';
            $data->Preco_produto__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('Preco_produto_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onEditDetailProdEstoque($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->prod_estoque_produto_id_deposito = $__row__data->__display__->id_deposito ?? null;
            $data->prod_estoque_produto_id = $__row__data->__display__->id ?? null;
            $data->prod_estoque_produto_quantidade = $__row__data->__display__->quantidade ?? null;
            $data->nova_qtd = $__row__data->__display__->nova_qtd ?? null;
            $data->prod_estoque_produto_qtd_min = $__row__data->__display__->qtd_min ?? null;
            $data->prod_estoque_produto_qtd_max = $__row__data->__display__->qtd_max ?? null;
            $data->prod_estoque_produto__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#estoqueBT');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='far fa-edit' style='color:#478fca;padding-right:4px;'></i>Editar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailProdEstoque($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->prod_estoque_produto_id_deposito = '';
            $data->prod_estoque_produto_id = '';
            $data->prod_estoque_produto_quantidade = '';
            $data->nova_qtd = '';
            $data->prod_estoque_produto_qtd_min = '';
            $data->prod_estoque_produto_qtd_max = '';
            $data->prod_estoque_produto__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('prod_estoque_produto_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;
            $valor = TSession::getValue('userid');
            if($valor == 1 ){
                echo'<pre>';
                var_dump($param['Preco_produto_list_tabela->nome_tabela_preco']);
                echo '<br>TODO PARAM <br>';
                var_dump($param);
                echo'</pre>';
            }
            if(!isset($param['Preco_produto_list_tabela->nome_tabela_preco']))throw new exception('necessário pelomenos um registro de preço para salvar o produto');
            if(!isset($param['prod_estoque_produto_list_deposito->nome_deposito']))throw new exception('necessário pelomenos um registro de de estoque para salvar o produto');
            //Preco_produto_list_tabela->nome_tabela_preco
            //prod_estoque_produto_list_deposito->nome_deposito
            $object = new Produto(); // create an empty object 

            $data = $this->form->getData(); // get form data as array

            $this->form->validate(); // validate form data

            //neste ponto é verificado se ouve alguma alteração em atributos que em variações não são alterados e realiza a alteração em todos os membros da familia
            /* são eles:
            Descricao;
            unidade;
            tipo cadastro;
            fornecedor;
            marca;
            categoria_produto_id;
            situação Tributaria
            Origem
            ncm
            cest
            */
            if($data->id !=""){ // se trata de uma edição ou produto novo
                $object = new Produto($data->id);
                if($data->id_familia!=""){ // se o produto possui variações
                    //inicio da comparação entre
                    if($data->descricao != $object-> descricao         || $data->unidade_id != $object-> unidade_id                       ||
                       $data->tipo_cadastro != $object-> tipo_cadastro || $data->fornecedor_id != $object-> fornecedor_id                 || 
                       $data->marca != $object-> marca                 || $data->categoria_produto_id != $object-> categoria_produto_id   ||
                       $data->sit_tribut != $object-> sit_tribut       || $data->origem != $object-> origem                               ||
                       $data->ncm != $object-> ncm                     || $data->cest != $object-> cest){

                         //recupera as viariações do produto  
                         if($data->id_familia!=""){
                         $variacoes = Produto::where('id_familia','=', $data->id_familia)->load();
                         if(isset($variacoes)){
                            foreach($variacoes as $variacao){
                                 $variacao-> descricao                      = $data-> descricao;
                                 $variacao-> unidade_id                     = $data-> unidade_id;
                                 $variacao-> tipo_cadastro                  = $data-> tipo_cadastro;
                                 $variacao-> fornecedor_id                  = $data-> fornecedor_id;
                                 $variacao-> marca                          = $data-> marca;
                                 $variacao-> categoria_produto_id           = $data-> categoria_produto_id;
                                 $variacao-> sit_tribut                     = $data-> sit_tribut;
                                 $variacao-> origem                         = $data-> origem;
                                 $variacao-> ncm                            = $data-> ncm;                             
                                 $variacao-> cest                           = $data-> cest;
                                 $variacao-> store();
                                }
                            }
                        }
                    }
                }
            }
            //fix Cest

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
            $object->fromArray( (array) $data);

            $object->store(); // save the object 
            //salva o objeto e agrega um SKU caso nao tenha
            if($object->SKU == 100000000000){
                $SKU = intval($object-> SKU) + intval($object-> id);
            //Adiciona digito verificador para SKU
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
            $variacao = TSession::getValue('newVariavel');
                        TSession::setValue('newVariavel',false);
            if(isset($variacao) && $variacao){
                $object->id_familia = $object->mestre_variavel == 1 ? $object->id : $object->id_familia;
            }

            //como salvar preços e estoques
            //cest
            //tratar categoria
            //salvar no PDV, e tratar o erro de Codbarras já salvo;

            //

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            //valida entrada de estoque
            $prod_estoque_produto_items = $this->storeMasterDetailItems('ProdEstoque', 'id_produto', 'prod_estoque_produto', $object, $param['prod_estoque_produto_list___row__data'] ?? [], $this->form, $this->prod_estoque_produto_list, function($masterObject, $detailObject){ 

            }); 

            $Preco_produto_items = $this->storeMasterDetailItems('Preco', 'id_produto', 'Preco_produto', $object, $param['Preco_produto_list___row__data'] ?? [], $this->form, $this->Preco_produto_list, function($masterObject, $detailObject){ 

                //code here

            }); 
            //preenche o objeto preco e salva no PDV Correspondente
            $quantidade = TSession::getValue('estoque');
            $id_qtd     = isset($data->id) && $data->id != null ? $data->id : $data->sku;
            foreach($quantidade[$id_qtd] as $deposito){
               if($deposito['qtd_adicionado'] != '' || $deposito['qtd_adicionado'] != null || $deposito['qtd_adicionado'] != 0){
                    $estoques                               = ProdEstoque::where('id_deposito','=',$deposito['deposito'])->where('id_produto','=',$deposito['produto'])->load();
                    if($estoques){
                        $estoque                            = $estoques[0];
                        $transferencia                      = new TransferenciaEtq();
                        $transferencia->deposito_rec        = $deposito['deposito'];
                        $transferencia->deposito_env        = $deposito['deposito'];
                        $transferencia->estoque_id          = $estoque->id;
                        $transferencia->quantidade          = $deposito['qtd_adicionado'];
                        $transferencia->dt_registro         = date('Y-m-d H:i:s');
                        $transferencia->usuario             = TSession::getValue('userid');
                        $transferencia->id_produto          = $deposito['produto'];
                        $transferencia->tipo_transferencia  = 'entrada';
                        $transferencia->store();
                    }
                }                 
            }
            TSession::setValue('estoque','');
              $SKU;
               if($object->cod_barras==""){
                    $SKU =$object->SKU;
               }else{
                    $SKU =$object->cod_barras;
               }
                //ajuste categoria
                $woocommerce                 = ApiManager::getWooClient();
                $categoria                   = new CategoriaProduto($object->categoria_produto_id);
                $wooSave                     = null;
                if(!$categoria->id_externo){
                    $data = ['name' => $categoria->nome];
                    $result = $woocommerce->post('products/categories', $data);
                    $categoria->id_externo = $result->id;
                    $categoria->store();
                }
                //ajuste Marca
                $marca = new Marca($object->marca);
                $description = json_encode(array("sit_tribut"=>$object->sit_tribut,
                                                 "ncm"=>$object->ncm,
                                                 "cest"=>$object->cest,
                                                 "origem"=>$object->origem));
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
                       echo' woo 1 ';
                        $wooSave = $woocommerce->put(('products/'.$data->id_externo), $woo_produto);
                    }else{
                        echo' woo 2 ';
                        $wooSave = $woocommerce->post('products', $woo_produto);
                        $object->id_externo = $wooSave->id;
                    } 
                    if(!$precosPromo){
                    $woocommerce =  ApiManager::getWooClient(1);
                      if($object->id_externo_promocao !=""){
                          echo' woo 3 ';
                        $wooSave = $woocommerce->put(('products/'.$data->id_externo_promocao), $woo_produto);
                      }else{
                          echo' woo 4 ';
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
                    echo' woo 5 ';
                    $wooSave = $woocommerce->put(('products/'.$object->id_externo_promocao), $woo_produto);
                }else{
                   echo' woo 6 ';
                    $wooSave = $woocommerce->post('products', $woo_produto);
                    $object->id_externo_promocao = $wooSave->id;
                } 
                }

             $object->store();
//-----------------------------------------------------------------------------------------------------------------------  
            $pageParam = null;
            if(isset($variacao) && $variacao){
                $pageParam = $object->mestre_variavel == 2 ? ['key'=>$object->id, 'id_familia'=>$object->id_familia] : ['key'=>$object->id, 'id_familia'=>$object->id];
            }else{
                $pageParam = ['key'=>$object->id];
            }

            TApplication::loadPage('ProdutoNForm', 'onEdit', $pageParam);

            $data->id = $object->id; 

            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

            return $object;

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 
            $pageParam = null;
            $id         = $param['id'];
            $id_familia  = $param ['id_familia'];
            $mestre_variavel = $param['mestre_variavel'];
            $variacao = TSession::getValue('newVariavel');
                        TSession::setValue('newVariavel',false);
            if(isset($variacao) && $variacao){
                $pageParam = $mestre_variavel == 2 ? ['key'=>$id, 'id_familia'=>$id_familia] : ['key'=>$id, 'id_familia'=>$id];
            }else{
                $pageParam = ['key'=>$id];
            }
            TApplication::loadPage('ProdutoNForm', 'onEdit', $pageParam);
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onNewProduct($param = null) 
    {
        try 
        {
           TApplication::loadPage('ProdutoNForm', 'onEdit');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onVariation($param = null) 
    {
        try 
        {
                TSession::setValue('newVariavel',true); 
                self::onSave($param);
                TToast::show("success", "Você está adicionando uma variação agora!", "bottomCenter", "fas fa-plus");

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());  

        }
    }
    public function onVoltar($param = null) 
    {
        try 
        {
            TApplication::loadPage('ProdutoList', 'onShow');

        }
        catch (Exception $e) 
        {
            //desativa
            TDBCombo::enableField(self::$formName, 'fornecedor_id');
            TDBCombo::enableField(self::$formName, 'marca');
            TDBCombo::enableField(self::$formName, 'categoria_produto_id');
            TDBCombo::enableField(self::$formName, 'tipo_cadastro');
            TDBCombo::enableField(self::$formName, 'situacao_prod');
            TDBCombo::enableField(self::$formName, 'unidade_id');
            TDBCombo::enableField(self::$formName, 'origem');
            TDBCombo::enableField(self::$formName, 'sit_tribut');
            TDBCombo::enableField(self::$formName, 'cest');
            TEntry::enableField(self::$formName, 'descricao');
            TEntry::enableField(self::$formName, 'ncm');
            TButton::enableField(self::$formName, 'addFornecedor');
            TButton::enableField(self::$formName, 'addTipo');
            TButton::enableField(self::$formName, 'addMarca');
            TButton::enableField(self::$formName, 'addSit');
            TButton::enableField(self::$formName, 'addCategoria');
            TButton::enableField(self::$formName, 'addUnidade');
            //ativa
            TEntry::disableField(self::$formName, 'desc_variacao');
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
                $object = new Produto($key); // instantiates the Active Record 

                if($object->mestre_variavel == 2 || isset($param['id_familia'])){
                    //tratamento do campo
                    //desativa
                    TDBCombo::disableField(self::$formName, 'fornecedor_id');
                    TDBCombo::disableField(self::$formName, 'marca');
                    TDBCombo::disableField(self::$formName, 'categoria_produto_id');
                    TDBCombo::disableField(self::$formName, 'tipo_cadastro');
                    TDBCombo::disableField(self::$formName, 'situacao_prod');
                    TDBCombo::disableField(self::$formName, 'unidade_id');
                    TDBCombo::disableField(self::$formName, 'origem');
                    TDBCombo::disableField(self::$formName, 'sit_tribut');
                    //TCombo::disableField(self::$formName, 'cest');
                    TEntry::disableField(self::$formName, 'descricao');
                    TEntry::disableField(self::$formName, 'ncm');
                    TButton::disableField(self::$formName, 'addFornecedor');
                    TButton::disableField(self::$formName, 'addTipo');
                    TButton::disableField(self::$formName, 'addMarca');
                    TButton::disableField(self::$formName, 'addSit');
                    TButton::disableField(self::$formName, 'addCategoria');
                    TButton::disableField(self::$formName, 'addUnidade');
                    //ativa
                    TEntry::enableField(self::$formName, 'desc_variacao');
                    //tratamento dos dados

                    $family                         = isset($param['id_familia'])? new Produto($param['id_familia']) : new Produto($object->id_familia);
                    $family_                        = new stdClass();
                    $family_->fornecedor_id         = $family->fornecedor_id;
                    $family_->marca                 = $family->marca;
                    $family_->categoria_produto_id  = $family->categoria_produto_id;
                    $family_->tipo_cadastro         = $family->tipo_cadastro;
                    $family_->situacao_prod         = $family->situacao_prod;
                    $family_->unidade_id            = $family->unidade_id;
                    $family_->origem                = $family->origem;
                    $family_->sit_tribut            = $family->sit_tribut;
                    $cest                           = new Cest($family->cest);
                    $combo_cest[$cest->id]          = $cest->n_cest." - ". $cest-> descricao;
                    TCombo::reload(self::$formName, 'cest', $combo_cest, true);
                    $family_->cest                  = $family->cest;
                    $family_->descricao             = $family->descricao;
                    $family_->ncm                   = $family->ncm;
                    if(isset($param['id_familia'])){
                        $family_->SKU                = 100000000000;
                        $family_->dt_cadastro        = (date('d/m/Y'));
                        $family_->mestre_variavel    = 2;
                        $family_->id_familia         = $param['id_familia'];
                        $object                      = new Produto();
                    }
                    //TForm::sendData(self::$formName, $family_);
                    $sku = null;
                    $sku2= null;
                    if(isset($param['id_familia'])){
                        $sku                            = $object->cod_barras != '' || $object->cod_barras != null ? $object->cod_barras : $object->SKU;
                        $sku2                           = $family->cod_barras != '' || $family->cod_barras != null ? $family->cod_barras : $family->SKU;
                    }else{
                        $sku2                           = $object->cod_barras != '' || $object->cod_barras != null ? $object->cod_barras : $object->SKU;
                        $sku                            = $family->cod_barras != '' || $family->cod_barras != null ? $family->cod_barras : $family->SKU;
                    }
                    $this->form->setData($family_); // 
                    TForm::sendData(self::$formName, $family_);
                    TToast::show("success", "Você está editanto a variação de SKU: $sku2 do SKU MESTRE: $sku", "bottomCenter", "far fa-edit ");
                }else{
                    //desativa
                    TDBCombo::enableField(self::$formName, 'fornecedor_id');
                    TDBCombo::enableField(self::$formName, 'marca');
                    TDBCombo::enableField(self::$formName, 'categoria_produto_id');
                    TDBCombo::enableField(self::$formName, 'tipo_cadastro');
                    TDBCombo::enableField(self::$formName, 'situacao_prod');
                    TDBCombo::enableField(self::$formName, 'unidade_id');
                    TDBCombo::enableField(self::$formName, 'origem');
                    TDBCombo::enableField(self::$formName, 'sit_tribut');
                    TCombo::enableField(self::$formName, 'cest');
                    TEntry::enableField(self::$formName, 'descricao');
                    TEntry::enableField(self::$formName, 'ncm');
                    TButton::enableField(self::$formName, 'addFornecedor');
                    TButton::enableField(self::$formName, 'addTipo');
                    TButton::enableField(self::$formName, 'addMarca');
                    TButton::enableField(self::$formName, 'addSit');
                    TButton::enableField(self::$formName, 'addCategoria');
                    TButton::enableField(self::$formName, 'addUnidade');
                    //ativa
                    TEntry::disableField(self::$formName, 'desc_variacao');

                    TForm::sendData(self::$formName, $object);
                    $sku                            = $object->cod_barras != '' || $object->cod_barras != null ? $object->cod_barras : $object->SKU;
                    TToast::show("info", "Você está editanto o SKU: $sku", "bottomCenter", "far fa-edit ");
                }

                $prod_estoque_produto_items = $this->loadMasterDetailItems('ProdEstoque', 'id_produto', 'prod_estoque_produto', $object, $this->form, $this->prod_estoque_produto_list, $this->prod_estoque_produto_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $Preco_produto_items = $this->loadMasterDetailItems('Preco', 'id_produto', 'Preco_produto', $object, $this->form, $this->Preco_produto_list, $this->Preco_produto_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $object = new stdClass();
                $object->SKU                = 100000000000;
                $object->dt_cadastro        = (date('d/m/Y'));
                $object->mestre_variavel    = 1;
                $this->form->setData($object);
            }
            if(isset($param['aba']))$this->form->setCurrentPage($param['aba']);

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

    public function onPriceEdit($param){
        TTransaction::open(self::$database);
        $id         = $param['id'];
        $produto    =  new Produto($id);
        $sku        = $produto->cod_barras == null || $produto->cod_barras == '' ? $produto->SKU : $produto->cod_barras;
        if($produto->mestre_variavel == 1){
            if($produto->id_familia == null){
                $pageParam = ['key' => $id, 'aba'=>2];
                TApplication::loadPage('ProdutoNForm', 'onEdit', $pageParam);
                TToast::show("success", "Editando preço do SKU : $sku", "bottomCenter", "far fa-edit ");
            }else{
                $pageParam = ['key' => $id];
                TApplication::loadPage('ProdutoNForm', 'onEdit', $pageParam);
                TApplication::loadPage('PrecoForm', 'onEdit', $pageParam);
                TToast::show("success", "Editando preço do SKU : $sku", "bottomCenter", "far fa-edit ");
            }
        }else{
            $pageParam = ['key' => $id, 'aba'=>2];
            TApplication::loadPage('ProdutoNForm', 'onEdit', $pageParam);
            TToast::show("success", "Editando preço do SKU : $sku", "bottomCenter", "far fa-edit ");
        }
        $this->form->setData($this->form->getData());
        TTransaction::close();
    }
    public function onBarcode($param){
        $id             = $param['id'];
        $pageParam      = ['key' => $id];
        TApplication::loadPage('ProdutoNForm'  , 'onEdit', $pageParam);
        TApplication::loadPage('ProdutoBarCode', 'loadForm', $pageParam);
    }
    public function onEstoqueEdit($param){
        TTransaction::open(self::$database);
        $id         = $param['id'];
        $produto    =  new Produto($id);
        $sku        = $produto->cod_barras == null || $produto->cod_barras == '' ? $produto->SKU : $produto->cod_barras;
        if($produto->mestre_variavel == 1){
            $pageParam = ['key' => $id, 'aba'=>3];
            TApplication::loadPage('ProdutoNForm', 'onEdit', $pageParam);
            TToast::show("info", "Editção de estoque em massa ainda não está disponivel", "bottomCenter", "far fa-edit ");
        }else{
            $pageParam = ['key' => $id, 'aba'=>3];
            TApplication::loadPage('ProdutoNForm', 'onEdit', $pageParam);
            TToast::show("success", "Editando estoque do SKU : $sku", "bottomCenter", "far fa-edit ");
        }
        $this->form->setData($this->form->getData());
        TTransaction::close();
    }
    public function onTransferencia($param){
        $id         = $param['id'];
        $pageParam  = ['key'=>$id]; 
        TApplication::loadPage('ProdutoNForm'  , 'onEdit', $pageParam);
        TApplication::loadPage('TransferenciaEtqForm', 'onShow', $pageParam);
        $this->form->setData($this->form->getData());
    }

}

