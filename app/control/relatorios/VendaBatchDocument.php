<?php

class VendaBatchDocument extends TPage
{
    private static $database = 'base_banco';
    private static $activeRecord = 'VendaAlt';
    private static $primaryKey = 'id';
    private static $htmlFile = 'app/documents/VendaDocumentTemplate.html';
    private static $formName = 'formDocument_VendaAlt';

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
        $this->form->setFormTitle("Geração de documento de venda em lote");

        $criteria_cliente_id = new TCriteria();
        $criteria_vendedor_id = new TCriteria();

        $filterVar = Grupo::clientes;
        $criteria_cliente_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 
        $filterVar = Grupo::vendedores;
        $criteria_vendedor_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_id in  (SELECT id FROM grupo WHERE id = '{$filterVar}') )")); 

        $id = new TEntry('id');
        $estado_venda_id = new TDBCombo('estado_venda_id', 'base_banco', 'EstadoVenda', 'id', '{nome}','nome asc'  );
        $cliente_id = new TDBUniqueSearch('cliente_id', 'base_banco', 'Pessoa', 'id', 'nome','nome asc' , $criteria_cliente_id );
        $vendedor_id = new TDBCombo('vendedor_id', 'base_banco', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_vendedor_id );
        $dt_venda = new TDate('dt_venda');
        $data_final = new TDate('data_final');
        $obs = new TDBCombo('obs', 'base_banco', 'CategoriaProduto', 'id', '{nome}','nome asc'  );
        $produto_id = new TDBCombo('produto_id', 'base_banco', 'Produto', 'id', '{descricao}','descricao asc'  );

        $cliente_id->setMinLength(2);
        $vendedor_id->enableSearch();

        $dt_venda->setDatabaseMask('yyyy-mm-dd');
        $data_final->setDatabaseMask('yyyy-mm-dd');

        $cliente_id->setMask('{id}');
        $dt_venda->setMask('dd/mm/yyyy');
        $data_final->setMask('dd/mm/yyyy');

        $id->setSize(100);
        $obs->setSize('100%');
        $dt_venda->setSize(100);
        $data_final->setSize(100);
        $cliente_id->setSize('100%');
        $produto_id->setSize('100%');
        $vendedor_id->setSize('100%');
        $estado_venda_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[new TLabel("Estado da venda:", null, '14px', null)],[$estado_venda_id]);
        $row2 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null)],[$cliente_id],[new TLabel("Vendedor:", null, '14px', null)],[$vendedor_id]);
        $row3 = $this->form->addFields([new TLabel("Data inicial:", null, '14px', null)],[$dt_venda],[new TLabel("Data final:", null, '14px', null)],[$data_final]);
        $row4 = $this->form->addFields([new TLabel("Tipo produto:", null, '14px', null)],[$obs],[new TLabel("Produto:", null, '14px', null)],[$produto_id]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongenerate = $this->form->addAction("Gerar", new TAction([$this, 'onGenerate']), 'fas:cog #ffffff');
        $this->btn_ongenerate = $btn_ongenerate;
        $btn_ongenerate->addStyleClass('btn-primary'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
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
            if (isset($data->estado_venda_id) AND ( (is_scalar($data->estado_venda_id) AND $data->estado_venda_id !== '') OR (is_array($data->estado_venda_id) AND (!empty($data->estado_venda_id)) )) ) 
            {

                $criteria->add(new TFilter('estado_venda_id', '=', $data->estado_venda_id));
            }
            if (isset($data->cliente_id) AND ( (is_scalar($data->cliente_id) AND $data->cliente_id !== '') OR (is_array($data->cliente_id) AND (!empty($data->cliente_id)) )) ) 
            {

                $criteria->add(new TFilter('cliente_id', '=', $data->cliente_id));
            }
            if (isset($data->vendedor_id) AND ( (is_scalar($data->vendedor_id) AND $data->vendedor_id !== '') OR (is_array($data->vendedor_id) AND (!empty($data->vendedor_id)) )) ) 
            {

                $criteria->add(new TFilter('vendedor_id', '=', $data->vendedor_id));
            }
            if (isset($data->dt_venda) AND ( (is_scalar($data->dt_venda) AND $data->dt_venda !== '') OR (is_array($data->dt_venda) AND (!empty($data->dt_venda)) )) ) 
            {

                $criteria->add(new TFilter('dt_venda', '>=', $data->dt_venda));
            }
            if (isset($data->data_final) AND ( (is_scalar($data->data_final) AND $data->data_final !== '') OR (is_array($data->data_final) AND (!empty($data->data_final)) )) ) 
            {

                $criteria->add(new TFilter('dt_venda', '<=', $data->data_final));
            }
            if (isset($data->obs) AND ( (is_scalar($data->obs) AND $data->obs !== '') OR (is_array($data->obs) AND (!empty($data->obs)) )) ) 
            {

                $criteria->add(new TFilter('id', 'in', "(SELECT venda_id FROM venda_item_alt WHERE produto_id in  (SELECT id FROM produto WHERE categoria_produto_id = '{$data->obs}') )"));
            }
            if (isset($data->produto_id) AND ( (is_scalar($data->produto_id) AND $data->produto_id !== '') OR (is_array($data->produto_id) AND (!empty($data->produto_id)) )) ) 
            {

                $criteria->add(new TFilter('id', 'in', "(SELECT venda_id FROM venda_item_alt WHERE produto_id in  (SELECT id FROM produto WHERE id = '{$data->produto_id}') )"));
            }

            $filterVar = TSession::getValue("userunitid");
            $criteria->add(new TFilter('system_unit_id', '=', $filterVar));

            $objects = VendaAlt::getObjects($criteria, FALSE);
            if ($objects)
            {
                $output = '';

                $count = 1;
                $count_records = count($objects);

                foreach ($objects as $object)
                {

                    $html = new AdiantiHTMLDocumentParser(self::$htmlFile);
                    $html->setMaster($object);

                    $objectsVendaItemAlt_venda_id = VendaItemAlt::where('venda_id', '=', $object->id)->load();

                    $html->setDetail('VendaItemAlt.venda_id', $objectsVendaItemAlt_venda_id);

                    $html->process();

                    if ($count < $count_records)
                    {
                        $html->addPageBreak();
                    }

                    $content = $html->getContents();
                    $dom = pQuery::parseStr($content);
                    $body = $dom->query('body');

                    if($body->count() > 0)
                    {
                        $output .= $body->html();    
                    }
                    else 
                    {
                        $output .= $content;    
                    }

                    $count ++;
                }

                $dom = pQuery::parseStr(file_get_contents(self::$htmlFile));
                $body = $dom->query('body');
                if($body->count() > 0)
                {
                    $body->html('<div>{$body}</div>');
                    $html = $dom->html();

                    $output = str_replace('<div>{$body}</div>', $output, $html);
                }

                $document = 'tmp/'.uniqid().'.pdf'; 
                $html = AdiantiHTMLDocumentParser::newFromString($output);
                $html->saveAsPDF($document, 'A4', 'portrait');

                parent::openFile($document);
                new TMessage('info', _t('Document successfully generated'));
            }
            else
            {
                new TMessage('info', _t('No records found'));   
            }

            TTransaction::close();

            TSession::setValue(__CLASS__.'_filter_data', $data);

            $this->form->setData($data);

        } 
        catch (Exception $e) 
        {
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

