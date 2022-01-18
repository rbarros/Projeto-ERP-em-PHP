<?php

class ProdutoBarCode extends TPage
{
    private static $database = 'base_banco';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'formBarcode_Produto';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Geração de etiquetas de código de barras de produtos");

        $descricao = new TEntry('descricao');
        $id = new TEntry('id');
        $tabela_preco = new TDBCombo('tabela_preco', 'base_banco', 'TabelaPreco', 'id', '{nome_tabela_preco}','nome_tabela_preco asc'  );
        $nome = new TDBCombo('nome', 'base_banco', 'ConfEtiquProduto', 'id', '{nome} ','nome asc'  );
        $caracteres = new TEntry('caracteres');
        $leftMargin = new TEntry('leftMargin');
        $topMargin = new TEntry('topMargin');
        $labelWidth = new TEntry('labelWidth');
        $labelHeight = new TEntry('labelHeight');
        $spaceBetween = new TEntry('spaceBetween');
        $rowsPerPage = new TEntry('rowsPerPage');
        $colsPerPage = new TEntry('colsPerPage');
        $fontSize = new TEntry('fontSize');
        $barcodeHeight = new TEntry('barcodeHeight');
        $imageMargin = new TEntry('imageMargin');
        $barcodeMethod = new TEntry('barcodeMethod');

        $nome->setDefaultOption(false);
        $tabela_preco->setDefaultOption(false);

        $id->setEditable(false);
        $descricao->setEditable(false);

        $caracteres->setValue('20');
        $barcodeMethod->setValue('EAN13');

        $id->setSize('100%');
        $nome->setSize('100%');
        $fontSize->setSize('100%');
        $descricao->setSize('100%');
        $topMargin->setSize('100%');
        $caracteres->setSize('100%');
        $leftMargin->setSize('100%');
        $labelWidth->setSize('100%');
        $labelHeight->setSize('100%');
        $rowsPerPage->setSize('100%');
        $colsPerPage->setSize('100%');
        $imageMargin->setSize('100%');
        $tabela_preco->setSize('100%');
        $spaceBetween->setSize('100%');
        $barcodeHeight->setSize('100%');
        $barcodeMethod->setSize('100%');

        $row1 = $this->form->addFields([new TFormSeparator("Gerador de etiquetas", '#000000', '20', '#ff0091')]);
        $row1->layout = [' col-sm-12'];

        $row2 = $this->form->addFields([new TLabel("Produto:", null, '14px', null, '100%'),$descricao],[new TLabel("ID:", null, '14px', null),$id]);
        $row2->layout = [' col-sm-11',' col-sm-1'];

        $row3 = $this->form->addFields([new TLabel("Tabela Preço:", null, '14px', null),$tabela_preco]);
        $row3->layout = [' col-sm-12 col-lg-12'];

        $row4 = $this->form->addFields([new TLabel("Perfil de configuração:", null, '14px', null),$nome]);
        $row4->layout = [' col-sm-3 col-lg-12'];

        $row5 = $this->form->addFields([new TLabel("Caractere por linha", null, '14px', null)],[$caracteres]);
        $row5->layout = [' col-sm-6','col-sm-6'];

        $row6 = $this->form->addFields([new TLabel("Margem esquerda:", null, '14px', null)],[$leftMargin]);
        $row6->layout = [' col-sm-6',' col-sm-6'];

        $row7 = $this->form->addFields([new TLabel("Margem Topo:", null, '14px', null, '100%')],[$topMargin]);
        $row7->layout = [' col-sm-6',' col-sm-6'];

        $row8 = $this->form->addFields([new TLabel("Largura:", null, '14px', null, '100%')],[$labelWidth]);
        $row8->layout = [' col-sm-6',' col-sm-6'];

        $row9 = $this->form->addFields([new TLabel("Altura:", null, '14px', null, '100%')],[$labelHeight]);
        $row9->layout = [' col-sm-6',' col-sm-6'];

        $row10 = $this->form->addFields([new TLabel("Espaço entre etiquetas:", null, '14px', null, '100%')],[$spaceBetween]);
        $row10->layout = [' col-sm-6',' col-sm-6'];

        $row11 = $this->form->addFields([new TLabel("Linhas por página:", null, '14px', null, '100%')],[$rowsPerPage]);
        $row11->layout = [' col-sm-6',' col-sm-6'];

        $row12 = $this->form->addFields([new TLabel("Colunas por página:", null, '14px', null, '100%')],[$colsPerPage]);
        $row12->layout = [' col-sm-6',' col-sm-6'];

        $row13 = $this->form->addFields([new TLabel("Tamanho da fonte:", null, '14px', null, '100%')],[$fontSize]);
        $row13->layout = [' col-sm-6',' col-sm-6'];

        $row14 = $this->form->addFields([new TLabel("Altura cód. barras:", null, '14px', null, '100%')],[$barcodeHeight]);
        $row14->layout = [' col-sm-6',' col-sm-6'];

        $row15 = $this->form->addFields([new TLabel("Margem:", null, '14px', null, '100%')],[$imageMargin]);
        $row15->layout = [' col-sm-6',' col-sm-6'];

        $row16 = $this->form->addFields([new TLabel("Tipo barcode:", null, '14px', null)],[$barcodeMethod]);
        $row16->layout = [' col-sm-6',' col-sm-6'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongenerate = $this->form->addAction("Gerar Etiqueta", new TAction([$this, 'onGenerate']), 'fas:barcode #000000');
        $this->btn_ongenerate = $btn_ongenerate;
        $btn_ongenerate->addStyleClass('btn-primary'); 

        $btn_ongenerateprice = $this->form->addAction("Gerar etiqueta de preço", new TAction([$this, 'onGeneratePrice']), 'fas:dollar-sign #e8f155');
        $this->btn_ongenerateprice = $btn_ongenerateprice;
        $btn_ongenerateprice->addStyleClass('btn-primary'); 

        $btn_onsalvar = $this->form->addAction("Salvar Config", new TAction([$this, 'onSalvar']), 'fas:cog #e4cbcb');
        $this->btn_onsalvar = $btn_onsalvar;
        $btn_onsalvar->addStyleClass('btn-success'); 

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        $style = new TStyle('right-panel');
        $style->width = '30% !important';   
        $style->show();
        parent::add($this->form);
    }

public function onGeneratePrice($param = null) //ETIQUETAS
    {
        try 
        {
             //obtem o objeto a partir do form
            $data = $this->form->getData();
            TTransaction::open(self::$database);
            $object = new Produto($data->id);
            if(isset($object->descricao)){
                    $limite         = $data->caracteres;
                    $descricao      = $object->descricao." ".$object->desc_variacao;
                    $tamanho        = strlen($descricao);
                    $quebra         = true;
                    $nova_descricao;
                    if($tamanho<=$limite){
                        $nova_descricao     = $object->descricao." ".$object->desc_variacao;
                        $object->descricao  = mb_strtolower($nova_descricao,'UTF-8');
                    }else{
                        $linha_1            = trim(substr($descricao,0,$limite));
                        $linha_2            = trim(substr($descricao,$limite,$limite));
                        $nova_descricao     = $linha_1."\n".$linha_2;
                        $object->descricao  = mb_strtolower($nova_descricao,'UTF-8');
                    }
            $precos = Preco::where('id_tabela','=',$data->tabela_preco)->where('id_produto','=',$object->id)->load();
                 if(isset($precos)){
                     foreach($precos as $preco){
                     $object->valor_custo     = $preco->preco_custo;
                     $object->valor_venda     = $preco->preco_venda;
                     $object->preco           = $preco->id;
                     }
                 }
            }
           if($object->cod_barras==""){
               $object->cod_barras = $object->SKU;
           }

           //configuração da etiqueta
            $properties = [];
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

            $label  =
"{descricao}
<b>R$  {valor_venda} </b>
";
            //inicio da etiqueta
            $bcgen = new AdiantiBarcodeDocumentGenerator('l', 'LETTER');
            $bcgen->setProperties($properties);
            $bcgen->setLabelTemplate($label);
            //envio de objetos para o form
                $bcgen->addObject($object); //coluna 1
                $bcgen->addObject($object); //coluna 2
                $bcgen->addObject($object); //coluna 3
            //cria o pdf da etiqueta
                $filename = 'tmp/barcode_'.uniqid().'.pdf';
                $bcgen->setBarcodeContent('cod_barras');
                $bcgen->generate();
                $bcgen->save($filename);
                parent::openFile($filename);
            TTransaction::close();
            $this->form->setData( $this->form->getData() );
        } 
        catch (Exception $e) 
        {
            $this->form->setData($data);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }//</end>

public function onGenerate($param) //COGIGO DE BARRAS
    {
        try 
        {
            TTransaction::open(self::$database);

         //obtem o objeto a partir do form
            $data = $this->form->getData();
            TTransaction::open(self::$database);
            $object = new Produto($data->id);
            if(isset($object->descricao)){
                    $limite         = $data->caracteres;
                    $descricao      = $object->descricao." ".$object->desc_variacao;
                    $tamanho        = strlen($descricao);
                    $quebra         = true;
                    $nova_descricao;
                    if($tamanho<=$limite){
                        $nova_descricao     = $object->descricao." ".$object->desc_variacao;
                        $object->descricao  = mb_strtolower($nova_descricao,'UTF-8');
                    }else{
                        $linha_1            = trim(substr($descricao,0,$limite));
                        $linha_2            = trim(substr($descricao,$limite,$limite));
                        $nova_descricao     = $linha_1."\n".$linha_2;
                        $object->descricao  = mb_strtolower($nova_descricao,'UTF-8');
                    }
            $precos = Preco::where('id_tabela','=',$data->tabela_preco)->where('id_produto','=',$object->id)->load();
                 if(isset($precos)){
                     foreach($precos as $preco){
                     $object->valor_custo     = $preco->preco_custo;
                     $object->valor_venda     = $preco->preco_venda;
                     $object->preco           = $preco->id;
                     }
                 }
            }
           if($object->cod_barras==""){
               $object->cod_barras = $object->SKU;
           }

            $properties = [];
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
            $label  =
            " #barcode#
    {cod_barras}
{descricao}
        <b>R$ {valor_venda}</b>
";

            $bcgen = new AdiantiBarcodeDocumentGenerator('l', 'LETTER');
            $bcgen->setProperties($properties);
            $bcgen->setLabelTemplate($label);

             $bcgen->addObject($object);
             $bcgen->addObject($object);
             $bcgen->addObject($object);

            $filename = 'tmp/barcode_'.uniqid().'.pdf';

                $bcgen->setBarcodeContent('cod_barras');
                $bcgen->generate();
                $bcgen->save($filename);

                parent::openFile($filename);

            TTransaction::close();

            $this->form->setData( $this->form->getData() );

        } 
        catch (Exception $e) 
        {
            $this->form->setData($data);

            // shows the exception error message
            new TMessage('error', $e->getMessage());

            // undo all pending operations
            TTransaction::rollback();
        }
    }
 public function onSalvar($param = null) 
    {
        try 
        {
        TTransaction::open(self::$database);
        $data = $this->form->getData();
            $config = new ConfEtiquProduto(1);
            $config->leftMargin     =$data->leftMargin;
            $config->topMargin      =$data->topMargin; 
            $config->labelWidth     =$data->labelWidth; 
            $config->labelHeight    =$data->labelHeight; 
            $config->spaceBetween   =$data->spaceBetween;  
            $config->rowsPerPage    =$data->rowsPerPage;  
            $config->colsPerPage    =$data->colsPerPage;  
            $config->fontSize       =$data->fontSize; 
            $config->barcodeHeight  =$data->barcodeHeight; 
            $config->imageMargin    =$data->imageMargin;
            $config->barcodeMethod  =$data->barcodeMethod;
            $config->store();
        TTransaction::close();
        $this->form->setData($data);
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }//</end>
/*

        parent::add($this->form);

        $style = new TStyle('right-panel');
        $style->width = '40% !important';   
        $style->show(true);

    }

    public function onGeneratePrice($param = null) 
    {

    }
    public function onSalvar($param = null) 
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

    public function onGenerate($param)
    {
        try 
        {
            TTransaction::open(self::$database);

            $data = $this->form->getData();
            $criteria = new TCriteria();

            if (isset($data->descricao) AND ( (is_scalar($data->descricao) AND $data->descricao !== '') OR (is_array($data->descricao) AND (!empty($data->descricao)) )) ) 
            {

                $criteria->add(new TFilter('id', '=', $data->descricao));
            }
            if (isset($data->tabela_preco) AND ( (is_scalar($data->tabela_preco) AND $data->tabela_preco !== '') OR (is_array($data->tabela_preco) AND (!empty($data->tabela_preco)) )) ) 
            {

                $criteria->add(new TFilter('tabela_preco', '=', $data->tabela_preco));
            }

            TSession::setValue(__CLASS__.'_filter_data', $data);

            $properties = [];

            $properties['leftMargin']    = 10; // Left margin
            $properties['topMargin']     = 0; // Top margin
            $properties['labelWidth']    = 60; // Label width in mm
            $properties['labelHeight']   = 20; // Label height in mm
            $properties['spaceBetween']  = 5;  // Space between labels
            $properties['rowsPerPage']   = 1;  // Label rows per page
            $properties['colsPerPage']   = 3;  // Label cols per page
            $properties['fontSize']      = 10; // Text font size
            $properties['barcodeHeight'] = 15; // Barcode Height
            $properties['imageMargin']   = 0;
            $properties['barcodeMethod'] = 'C39';

            $label  = "#barcode#
<b>{cod_barras}</b>
{descricao}
{desc_variacao} 
 ";

            $bcgen = new AdiantiBarcodeDocumentGenerator('p', 'LETTER');
            $bcgen->setProperties($properties);
            $bcgen->setLabelTemplate($label);

            $class = self::$activeRecord;

            $objects = $class::getObjects($criteria);

            if ($objects)
            {
                foreach ($objects as $object)
                {

                    $bcgen->addObject($object);
                }

                $filename = 'tmp/barcode_'.uniqid().'.pdf';

                $bcgen->setBarcodeContent('cod_barras');
                $bcgen->generate();
                $bcgen->save($filename);

                parent::openFile($filename);
                new TMessage('info', _t('Barcodes successfully generated'));
            }
            else
            {
                new TMessage('info', _t('No records found'));   
            }

            TTransaction::close();

            $this->form->setData($data);

        } 
        catch (Exception $e) 
        {
            $this->form->setData($data);

            // shows the exception error message
            new TMessage('error', $e->getMessage());

            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

                $object = new {builderModelName}($key); // instantiates the Active Record //</blockLine>
                $this->form->setData($object); // fill the form //</blockLine>
    }

*/
public function loadForm($param){
        try{
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key

                $data = $this->form->getData();
                TTransaction::open(self::$database); // open a transaction
                $object = new Produto($key); 
                $data->descricao        = $object-> SKU ." - ".
                                          $object->descricao." ".
                                          $object->desc_variacao." ".
                                          $object->referenecia;
                $data->id               = $object->id;

                $config                 = new ConfEtiquProduto(1);
                $data->nome             = $config-> nome;
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
                $precos = Preco::where('id_produto','=',$object->id)->
                                 where('id_tabela','=',1)->Load();
                if($precos){
                    $preco              = $precos[0];
                    $data->preco        = $preco->preco_venda;
                }
                if(strlen($object->SKU) <13){
                    $data->barcodeMethod    = "C39";
                }else{
                    $data->barcodeMethod    = $config-> barcodeMethod;
                }
                $data->caracteres       = 20;

                $this->form->setData($data); 
                TTransaction::close(); // close the transaction 

                $param =null;
            }
            else
            {

            }
            }catch(Exception $e){
            new TMessage('error', _t($e->getMessage())); // shows the exception error message
            }
}

}

