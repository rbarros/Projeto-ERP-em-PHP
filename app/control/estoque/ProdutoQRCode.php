<?php

class ProdutoQRCode extends TPage
{
    private static $database = 'base_banco';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'formQRCode_Produto';

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
        $this->form->setFormTitle("Geração de QRCode de produtos");

        $criteria_fornecedor_id = new TCriteria();

        $id = new TEntry('id');
        $cod_barras = new TEntry('cod_barras');
        $descricao = new TEntry('descricao');
        $categoria_produto_id = new TDBCombo('categoria_produto_id', 'base_banco', 'CategoriaProduto', 'id', '{nome}','nome asc'  );
        $unidade_id = new TDBCombo('unidade_id', 'base_banco', 'Unidade', 'id', '{nome}','nome asc'  );
        $fornecedor_id = new TDBCombo('fornecedor_id', 'base_banco', 'Fornecedor', 'id', '{razao_social}','razao_social asc' , $criteria_fornecedor_id );

        $id->setSize(100);
        $descricao->setSize('100%');
        $cod_barras->setSize('100%');
        $unidade_id->setSize('100%');
        $fornecedor_id->setSize('100%');
        $categoria_produto_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[new TLabel("Código de barras:", null, '14px', null)],[$cod_barras]);
        $row2 = $this->form->addFields([new TLabel("Nome:", null, '14px', null)],[$descricao],[new TLabel("Tipo do produto:", null, '14px', null)],[$categoria_produto_id]);
        $row3 = $this->form->addFields([new TLabel("Unidade de medida:", null, '14px', null)],[$unidade_id],[new TLabel("Fornecedor:", null, '14px', null)],[$fornecedor_id]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongenerate = $this->form->addAction("Gerar", new TAction([$this, 'onGenerate']), 'fas:cog #ffffff');
        $this->btn_ongenerate = $btn_ongenerate;
        $btn_ongenerate->addStyleClass('btn-primary'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(TBreadCrumb::create(["Estoque","QRCode do produto"]));
        $container->add($this->form);

        parent::add($container);

    }

    public function onGenerate($param)
    {
        try 
        {
            TTransaction::open(self::$database);

            $data = $this->form->getData();
            $criteria = new TCriteria();

            if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
            {

                $criteria->add(new TFilter('id', '=', $data->id));
            }
            if (isset($data->cod_barras) AND ( (is_scalar($data->cod_barras) AND $data->cod_barras !== '') OR (is_array($data->cod_barras) AND (!empty($data->cod_barras)) )) )
            {

                $criteria->add(new TFilter('cod_barras', 'like', "%{$data->cod_barras}%"));
            }
            if (isset($data->descricao) AND ( (is_scalar($data->descricao) AND $data->descricao !== '') OR (is_array($data->descricao) AND (!empty($data->descricao)) )) )
            {

                $criteria->add(new TFilter('descricao', 'like', "%{$data->descricao}%"));
            }
            if (isset($data->categoria_produto_id) AND ( (is_scalar($data->categoria_produto_id) AND $data->categoria_produto_id !== '') OR (is_array($data->categoria_produto_id) AND (!empty($data->categoria_produto_id)) )) )
            {

                $criteria->add(new TFilter('categoria_produto_id', '=', $data->categoria_produto_id));
            }
            if (isset($data->unidade_id) AND ( (is_scalar($data->unidade_id) AND $data->unidade_id !== '') OR (is_array($data->unidade_id) AND (!empty($data->unidade_id)) )) )
            {

                $criteria->add(new TFilter('unidade_id', '=', $data->unidade_id));
            }
            if (isset($data->fornecedor_id) AND ( (is_scalar($data->fornecedor_id) AND $data->fornecedor_id !== '') OR (is_array($data->fornecedor_id) AND (!empty($data->fornecedor_id)) )) )
            {

                $criteria->add(new TFilter('fornecedor_id', '=', $data->fornecedor_id));
            }

            TSession::setValue(__CLASS__.'_filter_data', $data);

            $properties = [];

            $properties['leftMargin']    = 10; // Left margin
            $properties['topMargin']     = 10; // Top margin
            $properties['labelWidth']    = 64; // Label width in mm
            $properties['labelHeight']   = 28; // Label height in mm
            $properties['spaceBetween']  = 4;  // Space between labels
            $properties['rowsPerPage']   = 10;  // Label rows per page
            $properties['colsPerPage']   = 3;  // Label cols per page
            $properties['fontSize']      = 12; // Text font size
            $properties['barcodeHeight'] = 17; // Barcode Height
            $properties['imageMargin']   = 0;

            $label  = "{descricao}
{entity_column_id:4139867->entity_column_id:4139924} 
#qrcode#";

            $bcgen = new AdiantiBarcodeDocumentGenerator('p', 'A4');
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

                $bcgen->setBarcodeContent('Nome: {descricao}
Cód: {cod_barras} 
 {entity_column_id:4139867->entity_column_id:4139924} ');
                $bcgen->generate();
                $bcgen->save($filename);

                parent::openFile($filename);
                new TMessage('info', _t('QR Codes successfully generated'));
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

    }


}

