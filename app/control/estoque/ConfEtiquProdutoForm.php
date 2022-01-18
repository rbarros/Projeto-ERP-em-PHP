<?php

class ConfEtiquProdutoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'ConfEtiquProduto';
    private static $primaryKey = 'id';
    private static $formName = 'form_ConfEtiquProduto';

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
        $this->form->setFormTitle("Etiqueta");


        $produto_id = new TEntry('produto_id');
        $tabela_preco = new TDBCombo('tabela_preco', 'base_banco', 'TabelaPreco', 'id', '{nome_tabela_preco}','nome_tabela_preco asc'  );
        $sku_barras = new TEntry('sku_barras');
        $linha1 = new TEntry('linha1');
        $linha2 = new TEntry('linha2');
        $preco = new TNumeric('preco', '2', ',', '.' );
        $perfil = new TDBCombo('perfil', 'base_banco', 'ConfEtiquProduto', 'id', '{nome}','id asc'  );
        $nome = new TEntry('nome');
        $caracteres = new TNumeric('caracteres', '0', '', '' );
        $leftMargin = new TNumeric('leftMargin', '0', '', '' );
        $topMargin = new TNumeric('topMargin', '0', '', '' );
        $labelWidth = new TNumeric('labelWidth', '0', '', '' );
        $labelHeight = new TNumeric('labelHeight', '0', '', '' );
        $spaceBetween = new TNumeric('spaceBetween', '0', '', '' );
        $rowsPerPage = new TNumeric('rowsPerPage', '0', '', '' );
        $colsPerPage = new TNumeric('colsPerPage', '0', '', '' );
        $fontSize = new TNumeric('fontSize', '0', '', '' );
        $barcodeHeight = new TNumeric('barcodeHeight', '0', '', '' );
        $imageMargin = new TNumeric('imageMargin', '0', '', '' );
        $barcodeMethod = new TEntry('barcodeMethod');

        $tabela_preco->setChangeAction(new TAction([$this,'onChangeTabela']));
        $perfil->setChangeAction(new TAction([$this,'changePerfil']));

        $leftMargin->addValidation("LeftMargin", new TRequiredValidator()); 
        $topMargin->addValidation("TopMargin", new TRequiredValidator()); 
        $labelWidth->addValidation("LabelWidth", new TRequiredValidator()); 
        $labelHeight->addValidation("LabelHeight", new TRequiredValidator()); 
        $spaceBetween->addValidation("SpaceBetween", new TRequiredValidator()); 
        $rowsPerPage->addValidation("RowsPerPage", new TRequiredValidator()); 
        $colsPerPage->addValidation("ColsPerPage", new TRequiredValidator()); 
        $fontSize->addValidation("FontSize", new TRequiredValidator()); 
        $barcodeHeight->addValidation("BarcodeHeight", new TRequiredValidator()); 
        $imageMargin->addValidation("ImageMargin", new TRequiredValidator()); 
        $barcodeMethod->addValidation("BarcodeMethod", new TRequiredValidator()); 

        $caracteres->setAllowNegative(false);
        $tabela_preco->setDefaultOption(false);

        $perfil->autofocus = 'autofocus';

        $preco->setEditable(false);
        $produto_id->setEditable(false);
        $sku_barras->setEditable(false);

        $perfil->setValue('1');
        $caracteres->setValue('20');
        $tabela_preco->setValue('1');

        $linha1->setMaxLength(23);
        $linha2->setMaxLength(23);
        $barcodeMethod->setMaxLength(20);

        $nome->setSize('100%');
        $preco->setSize('100%');
        $linha1->setSize('100%');
        $linha2->setSize('100%');
        $perfil->setSize('100%');
        $fontSize->setSize('100%');
        $topMargin->setSize('100%');
        $labelWidth->setSize('100%');
        $produto_id->setSize('100%');
        $leftMargin->setSize('100%');
        $caracteres->setSize('100%');
        $sku_barras->setSize('100%');
        $labelHeight->setSize('100%');
        $rowsPerPage->setSize('100%');
        $colsPerPage->setSize('100%');
        $imageMargin->setSize('100%');
        $tabela_preco->setSize('100%');
        $spaceBetween->setSize('100%');
        $barcodeHeight->setSize('100%');
        $barcodeMethod->setSize('100%');

        $row1 = $this->form->addContent([new TFormSeparator("Informação do produto", '#333333', '18', '#ff0091')]);
        $row2 = $this->form->addFields([new TLabel("Produto ID:", null, '14px', null)],[$produto_id]);
        $row2->layout = [' col-sm-2 col-lg-4',' col-sm-2 col-lg-2'];

        $row3 = $this->form->addFields([new TLabel("Tabela preço:", null, '14px', null, '100%')],[$tabela_preco]);
        $row3->layout = [' col-sm-3 col-lg-4',' col-sm-6 col-lg-8'];

        $row4 = $this->form->addFields([new TLabel("SKU / Cód. barras :", null, '14px', null)],[$sku_barras]);
        $row4->layout = [' col-sm-6 col-lg-4',' col-sm-2 col-lg-8'];

        $row5 = $this->form->addFields([new TLabel("Linha1:", null, '14px', null)],[$linha1]);
        $row5->layout = [' col-sm-2 col-lg-4',' col-sm-2 col-lg-8'];

        $row6 = $this->form->addFields([new TLabel("Linha2:", null, '14px', null)],[$linha2]);
        $row6->layout = [' col-sm-3 col-lg-4',' col-sm-6 col-lg-8'];

        $row7 = $this->form->addFields([new TLabel("Preço (R$):", null, '14px', null)],[$preco]);
        $row7->layout = [' col-sm-3 col-lg-4',' col-sm-6 col-lg-8'];

        $row8 = $this->form->addContent([new TFormSeparator("Configuração da etiqueta do produto", '#333333', '18', '#ff0091')]);
        $row9 = $this->form->addFields([new TLabel("Perfil de impressão:", null, '14px', null, '100%'),$perfil]);
        $row9->layout = [' col-sm-2 col-lg-12'];

        $row10 = $this->form->addFields([new TLabel("Descrição :", null, '14px', null)],[$nome]);
        $row10->layout = [' col-sm-3 col-lg-4',' col-sm-6 col-lg-8'];

        $row11 = $this->form->addFields([new TLabel("Caracteres por linha :", null, '14px', null)],[$caracteres]);
        $row11->layout = [' col-sm-3 col-lg-4',' col-sm-6 col-lg-8'];

        $row12 = $this->form->addFields([new TLabel("Margem da esquerda:", null, '14px', null, '100%')],[$leftMargin]);
        $row12->layout = [' col-sm-3 col-lg-4',' col-sm-6 col-lg-8'];

        $row13 = $this->form->addFields([new TLabel("Margem superior:", null, '14px', null, '100%')],[$topMargin]);
        $row13->layout = [' col-sm-6 col-lg-4',' col-sm-6 col-lg-8'];

        $row14 = $this->form->addFields([new TLabel("Largura da etiqueta:", null, '14px', null, '100%')],[$labelWidth]);
        $row14->layout = [' col-sm-3 col-lg-4',' col-sm-2 col-lg-8'];

        $row15 = $this->form->addFields([new TLabel("Altura da etiqueta:", null, '14px', null, '100%')],[$labelHeight]);
        $row15->layout = [' col-sm-3 col-lg-4',' col-sm-6 col-lg-8'];

        $row16 = $this->form->addFields([new TLabel("Espaço entre etiquetas:", null, '14px', null, '100%')],[$spaceBetween]);
        $row16->layout = [' col-sm-3 col-lg-4',' col-sm-6 col-lg-8'];

        $row17 = $this->form->addFields([new TLabel("Linhas por página:", null, '14px', null, '100%')],[$rowsPerPage]);
        $row17->layout = [' col-2 col-sm-3 col-lg-4',' col-sm-6 col-lg-8'];

        $row18 = $this->form->addFields([new TLabel("Colunas por página:", null, '14px', null, '100%')],[$colsPerPage]);
        $row18->layout = [' col-sm-6 col-lg-4',' col-sm-6 col-lg-8'];

        $row19 = $this->form->addFields([new TLabel("Tamanho da fonte:", null, '14px', null, '100%')],[$fontSize]);
        $row19->layout = [' col-sm-6 col-lg-4',' col-sm-6 col-lg-8'];

        $row20 = $this->form->addFields([new TLabel("Altura do código de barras:", null, '14px', null, '100%')],[$barcodeHeight]);
        $row20->layout = [' col-sm-6 col-lg-4',' col-sm-6 col-lg-8'];

        $row21 = $this->form->addFields([new TLabel("Margem do código de barras:", null, '14px', null, '100%')],[$imageMargin]);
        $row21->layout = [' col-sm-6 col-lg-4',' col-sm-6 col-lg-8'];

        $row22 = $this->form->addFields([new TLabel("Tipo de código de barras:", null, '14px', null, '100%')],[$barcodeMethod]);
        $row22->layout = [' col-sm-3 col-lg-4',' col-sm-6 col-lg-8'];

        // create the form actions
        $GerarEtiqueta = $this->form->addAction("Gerar etiqueta", new TAction([$this, 'onEtiqueta']), 'fas:barcode #000000');
        $this->GerarEtiqueta = $GerarEtiqueta;
        $GerarEtiqueta->addStyleClass('btn-primary'); 

        $GerarEtiquetaPreco = $this->form->addAction("Gerar etiqueta de preço", new TAction([$this, 'onEtiquetaPreco']), 'fas:dollar-sign #e8f155');
        $this->GerarEtiquetaPreco = $GerarEtiquetaPreco;
        $GerarEtiquetaPreco->addStyleClass('btn-primary'); 

        $SalvarConfiguracao = $this->form->addAction("Salvar Configuração", new TAction([$this, 'onSave']), 'fas:cog #ffffff');
        $this->SalvarConfiguracao = $SalvarConfiguracao;
        $SalvarConfiguracao->addStyleClass('btn-success'); 

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

        $style = new TStyle('right-panel');
        $style->width = '35% !important';   
        $style->show(true);

    }

    public static function onChangeTabela($param = null) 
    {
        try 
        {
            $id_produto         = $param['produto_id'];
            $id_tabela          = $param['tabela_preco'];
            TTransaction::open(self::$database);
            $precos = Preco::where('id_tabela','=',$id_tabela)
                           ->where('id_produto','=',$id_produto)
                           ->load();
            TTransaction::close();
            $object = new stdClass();
            if($precos){
                $object->preco =str_replace('.',',',$precos[0]->preco_venda);
            }else{
               $object->preco = "não há preço deste produto nesta tabela";
            }

            TForm::sendData(self::$formName, $object);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function changePerfil($param = null) 
    {
        try 
        {
            $id = $param['perfil'];
            TTransaction::open(self::$database);
            $object_                    = new ConfEtiquProduto($id);
            $object                     = new stdClass();
            $object->leftMargin         = $object_->leftMargin;
            $object->topMargin          = $object_->topMargin;
            $object->labelWidth         = $object_->labelWidth;
            $object->labelHeight        = $object_->labelHeight;
            $object->spaceBetween       = $object_->spaceBetween;
            $object->rowsPerPage        = $object_->rowsPerPage;
            $object->colsPerPage        = $object_->colsPerPage;
            $object->fontSize           = $object_->fontSize;
            $object->barcodeHeight      = $object_->barcodeHeight;
            $object->imageMargin        = $object_->imageMargin;
            $object->barcodeMethod      = $object_->barcodeMethod;
            $object->nome               = $object_->nome;

            $object->id         = $id;
            TForm::sendData(self::$formName, $object);
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onEtiqueta($param = null) 
    {
        try 
        {
           $label  =
            " #barcode#
    {cod_barras}
{descricao}
        <b>R$ {valor_venda}</b>
";
        self::gerarEtiqueta($param, $label);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onEtiquetaPreco($param = null) 
    {
        try 
        {
          $label  =
"{descricao}
<b>R$  {valor_venda} </b>
";
        self::gerarEtiqueta($param, $label);

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

            $object = new ConfEtiquProduto(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

                                TScript::create("Template.closeRightPanel();"); 

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
                $data                   = $this->form->getData();
                $key_produto            = $param['key'];  // get the parameter $key
                $key                    = 1;
                $linha_1                = "";
                $linha_2                = "";

                TTransaction::open(self::$database); // open a transaction
                $produto                = new Produto($key_produto);
                $config                 = new ConfEtiquProduto($key);
                //Produto
                $data->caracteres       = 20;
                $data->tabela_preco     = 1;
                $data->produto_id       = $produto->id;
                $data->sku_barras       = $produto->cod_barras != null? $produto->cod_barras:$produto->SKU;
                if(isset($produto->descricao)){
                    $limite         = 20;
                    $descricao      = $produto->descricao." ".$produto->desc_variacao;
                    $tamanho        = strlen($descricao);
                    $quebra         = true;
                    $nova_descricao;
                    if($tamanho<=$limite){
                        $nova_descricao     = $produto->descricao." ".$produto->desc_variacao;
                        $data->linha1       = mb_strtolower($nova_descricao,'UTF-8');
                        $data->Linha2       = null;
                    }else{
                        $linha_1            = trim(substr($descricao,0,$limite));
                        $linha_2            = trim(substr($descricao,$limite,$limite));
                        $data->linha1       = mb_strtolower($linha_1,'UTF-8');
                        $data->linha2       = mb_strtolower($linha_2,'UTF-8');
                    }
                $precos = Preco::where('id_tabela','=',$data->tabela_preco)
                               ->where('id_produto','=',$produto->id)
                               ->load();
                     if($precos){
                         $preco             = $precos[0];
                         $data->preco       = $preco->preco_venda;
                     }else{
                         $data->preco       = "sem preço nesta tabela";
                     }
                }

                //etiqueta
                if(strlen($produto->SKU) <13){
                    $data->barcodeMethod    = "C39";
                }else{
                    $data->barcodeMethod    = $config-> barcodeMethod;
                }
                $data->id               = $config-> id;
                $data->leftMargin       = $config-> leftMargin;
                $data->topMargin        = $config-> topMargin;
                $data->labelWidth       = $config-> labelWidth;
                $data->labelHeight      = $config-> labelHeight;
                $data->spaceBetween     = $config-> spaceBetween;
                $data->rowsPerPage      = $config-> rowsPerPage;
                $data->colsPerPage      = $config-> colsPerPage;
                $data->fontSize         = $config-> fontSize;
                $data->barcodeHeight    = $config-> barcodeHeight;
                $data->imageMargin      = $config-> imageMargin;
                $data->nome             = $config-> nome;

                $this->form->setData($data); 

/*             
                $object = new ConfEtiquProduto($key); // instantiates the Active Record 

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

    public function gerarEtiqueta($param = null,$label){
        try{

            $data = $this->form->getData();
            TTransaction::open(self::$database);
            $produto                     = new Produto($data->produto_id); 
            //ajustes do produto
            $produto->descricao          = $data->linha2 != null?$data->linha1."\n".$data->linha2:$data->linha1;
            var_dump(intval($data->sku_barras));
            $produto->cod_barras         = $data->sku_barras;
            $produto->valor_venda        = $data->preco;
            //config co barras
            $properties                  = [];
            $properties['leftMargin']    = $data->leftMargin; // Left margin
            $properties['topMargin']     = $data->topMargin; // Top margin
            $properties['labelWidth']    = $data->labelWidth; // Label width in mm
            $properties['labelHeight']   = $data->labelHeight; // Label height in mm
            $properties['spaceBetween']  = $data->spaceBetween;  // Space between labels
            $properties['rowsPerPage']   = $data->rowsPerPage;  // Label rows per page
            $properties['colsPerPage']   = $data->colsPerPage;  // Label cols per page
            $properties['fontSize']      = $data->fontSize; // Text font size
            $properties['barcodeHeight'] = $data->barcodeHeight; // Barcode Height
            $properties['imageMargin']   = $data->imageMargin;
            $properties['barcodeMethod'] = $data->barcodeMethod;
            //inicio da etiqueta
            $bcgen = new AdiantiBarcodeDocumentGenerator('l', 'LETTER');
            $bcgen->setProperties($properties);
            $bcgen->setLabelTemplate($label);
            //envio de objetos para o form
            $bcgen->addObject($produto); //coluna 1
            $bcgen->addObject($produto); //coluna 1
            $bcgen->addObject($produto); //coluna 1
            //cria o pdf da etiqueta
            $filename = 'tmp/barcode_'.uniqid().'.pdf';
            $bcgen->setBarcodeContent('cod_barras');
            $bcgen->generate();
            $bcgen->save($filename);
            parent::openFile($filename);
            TTransaction::close();
            $this->form->setData( $this->form->getData() );
        }catch (Exception $e) 
        {
            $this->form->setData($data);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

}

