<?php

class VendaDocument extends TPage
{
    private static $database = 'base_banco';
    private static $activeRecord = 'VendaAlt';
    private static $primaryKey = 'id';
    private static $htmlFile = 'app/documents/VendaDocumentTemplate.html';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {

    }

    public static function onGenerate($param)
    {
        try 
        {
            TTransaction::open(self::$database);

            $class = self::$activeRecord;
            $object = new $class($param['key']);

            $html = new AdiantiHTMLDocumentParser(self::$htmlFile);
            $html->setMaster($object);

            $objectsVendaItemAlt_venda_id = VendaItemAlt::where('venda_id', '=', $param['key'])->load();
            $html->setDetail('VendaItemAlt.venda_id', $objectsVendaItemAlt_venda_id);

            $html->process();

            $document = 'tmp/'.uniqid().'.pdf'; 
            $html->saveAsPDF($document, 'A4', 'portrait');

            TTransaction::close();

            if(empty($param['returnFile']))
            {
                parent::openFile($document);

                new TMessage('info', _t('Document successfully generated'));    
            }
            else
            {
                return $document;
            }
        } 
        catch (Exception $e) 
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());

            // undo all pending operations
            TTransaction::rollback();
        }
    }

}

