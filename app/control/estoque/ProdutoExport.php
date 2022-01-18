<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Google\client;
use Google\service;

class ProdutoExport extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'form_ProdutoExport';

    use Adianti\Base\AdiantiFileSaveTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("exportar");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("exportar");


        $button_obter_planilha = new TButton('button_obter_planilha');
        $caminho = new TFile('caminho');
        $button_importar_planilha = new TButton('button_importar_planilha');

        $caminho->addValidation("caminho", new TRequiredValidator()); 

        $caminho->setSize('100%');
        $caminho->enableFileHandling();

        $button_obter_planilha->setAction(new TAction([$this, 'onExport']), "Obter planilha");
        $button_importar_planilha->setAction(new TAction([$this, 'onImport']), "Importar planilha");

        $button_obter_planilha->addStyleClass('btn-success');
        $button_importar_planilha->addStyleClass('btn-info');

        $button_obter_planilha->setImage('fas:arrow-down #000000');
        $button_importar_planilha->setImage('fas:arrow-up #000000');

        $row1 = $this->form->addContent([new TFormSeparator("Parte 01:", '#333333', '18', '#FF0091')]);
        $row2 = $this->form->addFields([new TLabel("é necessário realizar o download da planilha de modelo abaixo  para que seja modificado e importado novamente para o sistema:", null, '14px', null)],[$button_obter_planilha]);
        $row2->layout = [' col-sm-3 col-lg-9',' col-sm-6 col-lg-3'];

        $row3 = $this->form->addContent([new TFormSeparator("Parte 02:", '#333333', '18', '#FF0091')]);
        $row4 = $this->form->addFields([new TLabel("selecione abaixo a planilha de modelo editada:", null, '14px', null)],[$caminho],[$button_importar_planilha]);
        $row4->layout = [' col-sm-3 col-lg-3',' col-sm-2 col-lg-6',' col-sm-6 col-lg-3'];

        // create the form actions


        parent::add($this->form);

    }

    public  function onExport($param = null) 
    {
        try 
        {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            //precificação
            $sheet->setCellValue('A1', 'ID PRODUTO');
            $sheet->setCellValue('B1', 'SKU');
            $sheet->setCellValue('C1', 'COD. BARRAS');
            $sheet->setCellValue('D1', 'DESCRIÇÃO');
            $sheet->setCellValue('E1', 'DESC. VARIAÇÃO');
            $sheet->setCellValue('F1', 'NCM');
            $sheet->setCellValue('G1', 'CEST');
            //$col = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            $lin = 2;
            TTransaction::open(self::$database);
            $objects = Produto::getObjects();
            TTransaction::close();
            foreach($objects as $object){
                $sheet->setCellValue("A$lin", $object->id);
                $sheet->setCellValue("B$lin", intval($object->SKU));
                $sheet->setCellValue("C$lin", intval($object->cod_barras));
                $sheet->setCellValue("D$lin", $object->descricao);
                $sheet->setCellValue("E$lin", $object->desc_variacao);
                $sheet->setCellValue("F$lin", $object->ncm);
                $sheet->setCellValue("G$lin", $object->cest);
                $lin++;
            }       
            $writer = new Xlsx($spreadsheet);
            $writer->save('tmp/Produtos.xlsx');
            TPage::openfile('tmp/Produtos.xlsx');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImport($param = null) 
    {
        try 
        {
            $arquivo = json_decode(urldecode($param['caminho']))->fileName;
            if(isset($arquivo)){
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadsheet = $reader->load($arquivo);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                $inicio_dados = 2;//variavel onde indica o inicio dos dados de fato.
                $cont=0;//contador que percorre as linhas para iniciar o salvamento somente nas linhas com dados;
                TTransaction::open(self::$database);
                foreach($sheetData as $linhas){//foreach que percorre as linhas existentes na planilha
                    $cont++;
                    if($cont >= $inicio_dados){
                         //inicio de apontamento de dados para o respectivo objeto
                         $object = new Produto($linhas['A']);
                         //apontamento dados continuação
                         $object->ncm             = $linhas['F'];
                         $object->cest            = $linhas['G'];
                         $object->store();
                    }
                }
                TTransaction::close(); 
                TApplication::loadPage('ProdutoList', 'onReload');//list do objeto em especifico
            }

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

                $object = new Produto($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

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

