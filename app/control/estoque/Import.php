<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Google\client;
use Google\service;

class Import extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_Import';

/*

class Import extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_Import';

*/

    use Adianti\Base\AdiantiFileSaveTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();
        parent::setSize(0.40, null);
        parent::setTitle("Importar produtos");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Importar produtos");


        $tipoImport = new TEntry('tipoImport');
        $button_baixar_planilha_de_modelo = new TButton('button_baixar_planilha_de_modelo');
        $caminho = new TFile('caminho');
        $button_importar = new TButton('button_importar');

        $tipoImport->setEditable(false);
        $caminho->enableFileHandling();

        $caminho->setSize('100%');
        $tipoImport->setSize('100%');

        $button_importar->setAction(new TAction([$this, 'onImport']), "importar");
        $button_baixar_planilha_de_modelo->setAction(new TAction([$this, 'onGenerateModelo']), "Baixar planilha de modelo");

        $button_importar->addStyleClass('btn-primary');
        $button_baixar_planilha_de_modelo->addStyleClass('btn-success');

        $button_importar->setImage('fas:arrow-down #000000');
        $button_baixar_planilha_de_modelo->setImage('fas:arrow-down #000000');


        $row1 = $this->form->addFields([],[new TLabel("Importação de:", null, '14px', null)],[$tipoImport]);
        $row1->layout = [' col-sm-5 control-label',' col-sm-3 control-label',' col-sm-4'];

        $row2 = $this->form->addFields([new TFormSeparator("Parte 01:", '#000000', '15', '#ff0091')]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("é necessário realizar o download da planilha de modelo abaixo  para que seja modificado e importado novamente para o sistema:", null, '14px', null, '100%')]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([],[$button_baixar_planilha_de_modelo],[]);
        $row4->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row5 = $this->form->addFields([new TFormSeparator("Parte 02:", '#000000', '15', '#ff0091')]);
        $row5->layout = [' col-sm-12'];

        $row6 = $this->form->addFields([new TLabel("selecione abaixo a planilha de modelo editada:", null, '14px', null, '100%')]);
        $row6->layout = [' col-sm-12'];

        $row7 = $this->form->addFields([$caminho]);
        $row7->layout = [' col-sm-12'];

        $row8 = $this->form->addFields([],[$button_importar],[]);
        $row8->layout = [' col-sm-5 col-lg-4',' col-sm-2 col-lg-4',' col-sm-5 col-lg-4'];

        // create the form actions


        parent::add($this->form);

    }

    public  function onGenerateModelo($param = null) 
    {
    try {

       $dados = $this->form->getData();
       $tipoForm = $dados->tipoImport;
       switch($tipoForm){
           case "Produto":
                        $this->form->setData($dados);
                          break;

           case "Fornecedor":
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();

                        //DESENHO DA PLANILHA
                        $sheet->setCellValue('A1', 'IMPORTAÇÃO DE FORNECEDORES');
                        $sheet->setCellValue('H1', 'colunas assinaladas com * são opcionais e podem ser geradas automaticamente pelo sistema');
                        //informações especificas
                        //informação tributária
                        $sheet->setCellValue('A3', 'tabela de codigo para informar o regime tributário');
                        $sheet->setCellValue('A4', '1');
                        $sheet->setCellValue('A5', '2');
                        $sheet->setCellValue('A6', '3');
                        $sheet->setCellValue('A7', '4');

                        $sheet->setCellValue('B4', 'Não definido');
                        $sheet->setCellValue('B5', 'Simples nacional');
                        $sheet->setCellValue('B6', 'Simples nacional - Excesso de sublimite de receita bruta');
                        $sheet->setCellValue('B7', 'Regime normal');

                        //informação ICMS
                        $sheet->setCellValue('I3', 'tabela de codigo para informar o ICMS fornecedor');
                        $sheet->setCellValue('I4', '1');
                        $sheet->setCellValue('I5', '2');
                        $sheet->setCellValue('I6', '3');

                        $sheet->setCellValue('J4', 'Contribuinte ICMS');
                        $sheet->setCellValue('J5', 'Contribuinte isento de inscrição no cadastro de Contribuintes');
                        $sheet->setCellValue('J6', 'Não Contribuinte, que pode ou não possuir inscrição estadual no cadastro de contribuinte');

                        $sheet->setCellValue('A9', 'razão social');
                        $sheet->setCellValue('B9', 'nome fantasia');
                        $sheet->setCellValue('C9', 'cnpj');
                        $sheet->setCellValue('D9', 'observação*');
                        $sheet->setCellValue('E9', 'cod regime tributario');
                        $sheet->setCellValue('F9', 'inscrição estadual*');
                        $sheet->setCellValue('G9', 'possui I.E.');
                        $sheet->setCellValue('H9', 'Incrição municipal');
                        $sheet->setCellValue('I9', 'cod. contribuinte icms');
                        $sheet->setCellValue('J9', 'estado');
                        $sheet->setCellValue('K9', 'cidade');
                        $sheet->setCellValue('L9', 'rua*');
                        $sheet->setCellValue('M9', 'numero*');
                        $sheet->setCellValue('N9', 'bairro*');
                        $sheet->setCellValue('O9', 'complemento*');
                        $sheet->setCellValue('P9', 'telefone*');
                        $sheet->setCellValue('Q9', 'email*');
                        $sheet->setCellValue('R9', 'nome contato*');
                        $sheet->setCellValue('S9', 'telefone contato*');
                        $sheet->setCellValue('T9', 'email contato*');
                        $sheet->setCellValue('U9', 'observação contato*');

                        $writer = new Xlsx($spreadsheet);
                        $writer->save('tmp/Modelo fornecedor.xlsx');
                        TPage::openfile('tmp/Modelo fornecedor.xlsx'); 
                        $this->form->setData($dados);
                          break;

           case "Marca": 
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();

                        //DESENHO DA PLANILHA
                        $sheet->setCellValue('A1', 'IMPORTAÇÃO DE MARCA');
                        $sheet->setCellValue('A3', 'nome da marca');

                        $writer = new Xlsx($spreadsheet);
                        $writer->save('tmp/Modelo marca.xlsx');
                        TPage::openfile('tmp/Modelo marca.xlsx'); 
                        $this->form->setData($dados);
                          break;

           case "Categoria Produto":
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();

                        //DESENHO DA PLANILHA
                        $sheet->setCellValue('A1', 'IMPORTAÇÃO DE CATEGORIA DE PRODUTO');
                        $sheet->setCellValue('A3', 'nome da categoria do produto');

                        $writer = new Xlsx($spreadsheet);
                        $writer->save('tmp/Modelo categoria produto.xlsx');
                        TPage::openfile('tmp/Modelo categoria produto.xlsx'); 
                        $this->form->setData($dados);
                          break;

          case "ncm_cest":
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();

                        //DESENHO DA PLANILHA
                        $sheet->setCellValue('A1', 'IMPORTAÇÃO CEST E NCM');
                        $sheet->setCellValue('e1', 'Link - "https://www.confaz.fazenda.gov.br/legislacao/convenios/2018/CV142_18"');
                        $sheet->setCellValue('A3', 'CEST');
                        $sheet->setCellValue('B3', 'NCM');
                        $sheet->setCellValue('C3', 'Descrição CEST');

                        $writer = new Xlsx($spreadsheet);
                        $writer->save('tmp/Modelo modelo de ncm e cest.xlsx');
                        TPage::openfile('tmp/Modelo modelo de ncm e cest.xlsx'); 
                        $this->form->setData($dados);
                          break;
           case "preco":
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();

                        //DESENHO DA PLANILHA
                        $sheet->setCellValue('A1', 'TABELA PREÇO');
                        $sheet->setCellValue('A3', 'ID');
                        $sheet->setCellValue('B3', 'PREÇO VENDA');
                        $sheet->setCellValue('C3', 'PREÇO CUSTO');
                        $sheet->setCellValue('D3', 'ID DO PRODUTO');
                        $sheet->setCellValue('E3', 'ID DA TABELA');

                        $writer = new Xlsx($spreadsheet);
                        $writer->save('tmp/preco.xlsx');
                        TPage::openfile('tmp/preco.xlsx'); 
                        $this->form->setData($dados);
                          break;
            case "deposito":
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();

                        //DESENHO DA PLANILHA
                        $sheet->setCellValue('A1', 'ESTOQUE');
                        $sheet->setCellValue('A3', 'ID');
                        $sheet->setCellValue('B3', 'QUANTIDADE');
                        $sheet->setCellValue('C3', 'QTD. MINIMA');
                        $sheet->setCellValue('D3', 'QTD. MAXIMA');
                        $sheet->setCellValue('E3', 'ID DO DEPOSITO');
                        $sheet->setCellValue('F3', 'ID DO PRODUTO');

                        $writer = new Xlsx($spreadsheet);
                        $writer->save('tmp/deposito.xlsx');
                        TPage::openfile('tmp/deposito.xlsx'); 
                        $this->form->setData($dados);
                          break;

           default:
                          new TMessage('error','Nenhum tipo de importação existente ou chamada de pagina incorreta');     
       }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onImport($param = null) 
    {
     try{
       $dados = $this->form->getData();
       $tipoForm = $dados->tipoImport;

       //recupera um array a partir do xlsx
       $arquivo = json_decode(urldecode($param['caminho']))->fileName;
       if(isset($arquivo)){
           $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
           $spreadsheet = $reader->load($arquivo);
           $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        switch($tipoForm){
           case "Produto":
                            $inicio_dados = 18;//variavel onde indica o inicio dos dados de fato.
                            $cont=0;//contador que percorre as linhas para iniciar o salvamento somente nas linhas com dados;
                            TTransaction::open(self::$database);
                           if($sheetData[1]['A']== 'IMPORTAÇÃO DE PRODUTOS'){ //valida se é a planilha para o objeto atual
                                foreach($sheetData as $linhas){//foreach que percorre as linhas existentes na planilha
                                $cont++;
                                    if($cont >= $inicio_dados){

                                         //inicio de apontamento de dados para o respectivo objeto
                                         $object = new Produto();
                                         $object->dt_cadastro           = date("m.d.yy");
                                         if(isset($linhas['A']))
                                         $object->unidade_id            = $linhas['A'];
                                         $object->unidade_id            = '1';

                                         //procurar categoria
                                         $object_categoria              = CategoriaProduto::where('nome', '=', $linhas['B'])->load();
                                         if($object_categoria){
                                         $object->categoria_produto_id  = $object_categoria[0]->id;
                                         }else{
                                         $object_categoria              = new CategoriaProduto();
                                         $object_categoria->nome        = $linhas['J']; 
                                         $object_categoria->store();
                                         $object->categoria_produto_id  = $object_categoria->id;
                                         }

                                         //procurar fornecedor
                                         $object_fornecedor             = Fornecedor::where('razao_social', '=', $linhas['B'])->load();
                                         if($object_fornecedor){
                                         $object->fornecedor            = $object_fornecedor[0]->id;
                                         }else{
                                         throw new Exeption("fornecedor na linha {$cont}  não se encontra cadastrado, realize o cadastro antes ou importe fornecedores primeiro");
                                         }

                                         //apontamento dados continuação
                                         $object->descricao             = $linhas['D'];
                                         $object->desc_variacao         = $linhas['E'];
                                         $object->SKU                   = $linhas['F'];
                                         $object->cod_barras            = $linhas['G'];
                                         $object->obs                   = $linhas['H'];

                                         //procurar tributário 
                                         $object_tributario             = new Tributario();
                                         $object_tributario->origem     = $linhas['I'];
                                         $object_tributario->s_tribut   = $linhas['J'];
                                         $object_tributario->ncm        = $linhas['K'];
                                         $object_tributario->cest       = $linhas['L'];
                                         $object_tributario->icms       = $linhas['M'];
                                         $object_tributario->store();
                                         $object->tributario            = $object_tributario->id;

                                         //procurar MARCA
                                         $object_marca                  = Marca::where('marca', '=', $linhas['N'])->load();
                                         if($object_marca){
                                            $object->marca                 = $object_marca[0]->id;
                                         }else{
                                             $object_marca                  = new Marca();
                                             $object_marca->marca           = $linhas['J']; 
                                             $object_marca->store();
                                             $object->marca                 = $object_categoria->id;
                                         }

                                         //apontamento dados continuação
                                         $object->situacao_prod         = '1';  
                                         $object->id_familia            = $linhas['P'];
                                         if(isset($object->desc_variacao)){
                                            $object->mestre_variavel    ="variacão";
                                         }else{
                                            $object->mestre_variavel    ="mestre";
                                         }
                                         $object->referencia            = $linhas['R'];

                                         //quantidade e estoque
                                         $estoque                       = new estoque();
                                         $estoque->id_deposito          = $linhas['S'];
                                         $estoque->quantidade           = $linhas['T'];
                                         $estoque->qtd_min              = $linhas['U'];
                                         $estoque->qtd_max              = $linhas['V'];
                                         $estoque-store();
                                         $object->estoque               = $estoque->id;
                                         $object->Deposito              = $estoque->id_deposito;

                                         //precificação
                                         $preco                         =new Preco();
                                         $preco->id_tabela              = $linhas['W'];
                                         $preco->preco_custo            = $linhas['X'];
                                         $preco->preco_venda            = $linhas['Y'];
                                         $preco->store();
                                         $object->preco                 = $preco->id;

                                         //salva o objeto produto
                                         $object->store(); 

                                    }
                                }
                            TTransaction::close(); 
                            TApplication::loadPage('ProdutoList', 'onReload');//list do objeto em especifico

                           }else{
                               new TMessage('error',"Planilha importada é diferente ao formulário atual,
                               informe  aplanilha referente a {$tipoForm}"); 
                           }

                            break;

           case "Fornecedor":
                            $inicio_dados = 10;//variavel onde indica o inicio dos dados de fato.
                            $cont=0;//contador que percorre as linhas para iniciar o salvamento somente nas linhas com dados;
                            TTransaction::open(self::$database);
                           if($sheetData[1]['A']== 'IMPORTAÇÃO DE FORNECEDORES'){ //valida se é a planilha para o objeto atual
                                foreach($sheetData as $linhas){//foreach que percorre as linhas existentes na planilha
                                $cont++;
                                    if($cont >= $inicio_dados){

                                         //inicio de apontamento de dados para o respectivo objeto
                                         $object = new Fornecedor();
                                         $object->dt_ativacao           = date("m.d.yy");
                                         $object->razao_social          = $linhas['A'];
                                         $object->nome_fantasia         = $linhas['B'];
                                         $object->cnpj                  = $linhas['C'];
                                         $object->observacao            = $linhas['D'];
                                         $object->regime_tributario     = $linhas['E'];
                                         $object->inscr_estadual        = $linhas['F'];
                                         $object->possui_ie             = $linhas['G'];
                                         $object->inscr_municipal       = $linhas['H'];
                                         $object->icms                  = $linhas['I'];
                                         //salvar estado na tabela estado
                                         $object_estado                 = Estado::where('nome', '=', $linhas['J'])->load();
                                         if($object_estado){
                                         $object->estado                = $object_estado[0]->id;
                                         }else{
                                         $object_estado                 = new Estado;
                                         $object_estado->nome           = $linhas['J']; 
                                         $object_estado->store();
                                         $object->estado                = $object_estado->id;
                                         }
                                         //salvar cidade na tabela cidade
                                         $object_cidade                 = Cidade::where('nome', '=', $linhas['K'])->load();
                                         if($object_cidade){
                                             $object->cidade            = $object_cidade[0]->id;
                                         }else{
                                         $object_cidade                 = new Cidade();
                                         $object_cidade->nome           = $linhas['K'];
                                         $object_cidade->estado_id      = $object_estado[0]->id;
                                         $object_cidade->store();
                                         $object->cidade                = $object_cidade->id;
                                         }
                                         $object->rua                   = $linhas['L'];
                                         $object->numero                = $linhas['N'];
                                         $object->bairro                = $linhas['M'];
                                         $object->complemento           = $linhas['O'];
                                         $object->fone                  = $linhas['P'];
                                         $object->email                 = $linhas['Q'];
                                         $object->store();
                                         //salvar cidade na tabela cidade
                                         $object_contato                = new Contato();
                                         $object_contato->email         = $linhas['T'];
                                         $object_contato->nome          = $linhas['R'];
                                         $object_contato->telefone      = $linhas['S'];
                                         $object_contato->obs           = $linhas['U'];
                                         $object_contato->fornecedor_id = $object->id;
                                         $object_contato->store();
                                         $object->contato               = $linhas['R'];
                                         $object->store();

                                    }
                                }
                            TTransaction::close(); 
                            TApplication::loadPage('FornecedorList', 'onReload');//list do objeto em especifico

                           }else{
                               new TMessage('error',"Planilha importada é diferente ao formulário atual,
                               informe  aplanilha referente a {$tipoForm}"); 
                           }

                            break;

           case "Marca":    
                            $inicio_dados = 4;//variavel onde indica o inicio dos dados de fato.
                            $cont=0;//contador que percorre as linhas para iniciar o salvamento somente nas linhas com dados;
                            TTransaction::open(self::$database);
                           if($sheetData[1]['A']== 'IMPORTAÇÃO DE MARCA'){ //valida se é a planilha para o objeto atual
                                foreach($sheetData as $linhas){//foreach que percorre as linhas existentes na planilha
                                $cont++;
                                    if($cont >= $inicio_dados){

                                         //inicio de apontamento de dados para o respectivo objeto
                                         $object = new Marca();
                                         $object->marca = $linhas['A'];
                                         $object->store();

                                    }
                                }
                            TTransaction::close(); 
                            TApplication::loadPage('MarcaList', 'onReload');//list do objeto em especifico

                           }else{
                               new TMessage('error',"Planilha importada é diferente ao formulário atual,
                               informe  aplanilha referente a {$tipoForm}"); 
                           }

                            break;

           case "Categoria Produto":
                            $inicio_dados = 4;//variavel onde indica o inicio dos dados de fato.
                            $cont=0;//contador que percorre as linhas para iniciar o salvamento somente nas linhas dados;
                            TTransaction::open(self::$database);
                           if($sheetData[1]['A']== 'IMPORTAÇÃO DE CATEGORIA DE PRODUTO'){ //valida se é a planilha para o objeto atual
                                foreach($sheetData as $linhas){//foreach que percorre as linhas existentes na planilha
                                $cont++;
                                    if($cont >= $inicio_dados){

                                         //inicio de apontamento de dados para o respectivo objeto
                                         $object = new CategoriaProduto();
                                         $object->nome = $linhas['A'];
                                         $object->store();

                                    }
                                }
                            TTransaction::close(); 
                            TApplication::loadPage('CategoriaProdutoList', 'onReload');//list do objeto em especifico

                           }else{
                               new TMessage('error',"Planilha importada é diferente ao formulário atual,
                               informe  aplanilha referente a {$tipoForm}"); 
                           }

                            break;
            case "ncm_cest":
                        $inicio_dados = 4;//variavel onde indica o inicio dos dados de fato.
                            $cont=0;//contador que percorre as linhas para iniciar o salvamento somente nas linhas dados;
                            TTransaction::open(self::$database);
                           if($sheetData[1]['A']== 'IMPORTAÇÃO CEST E NCM'){ //valida se é a planilha para o objeto atual

                                TTransaction::open(self::$database);
                                foreach($sheetData as $linhas){//foreach que percorre as linhas existentes na planilha
                                $cont++;
                                        if($cont >= $inicio_dados){
                                        $cests = Cest::where('n_cest','=',$linhas['A'])->load();
                                        var_dump($cests);
                                            if(isset($cests[0])){
                                                foreach($cests as $cest_un){
                                                    $ncm = new Ncm();
                                                    $ncm->n_ncm = $linhas['B'];
                                                    $ncm->cest = $cest_un->id;
                                                    $ncm->store();
                                                }
                                            }else{
                                                    $cest = new Cest();
                                                    $cest->n_cest =$linhas['A'];
                                                    $cest->descricao = $linhas['C'];
                                                    $cest->store();

                                                    $ncm = new Ncm();
                                                    $ncm->n_ncm = $linhas['B'];
                                                    $ncm->cest = $cest->id;
                                                    $ncm->store();

                                            }
                                        }
                                }
                            TTransaction::close(); 
                            TApplication::loadPage('CestList', 'onReload');//list do objeto em especifico

                           }else{
                               new TMessage('error',"Planilha importada é diferente ao formulário atual,
                               informe  aplanilha referente a {$tipoForm}"); 
                           }
            case "preco":
                        $inicio_dados = 4;//variavel onde indica o inicio dos dados de fato.
                            $cont=0;//contador que percorre as linhas para iniciar o salvamento somente nas linhas dados;
                            TTransaction::open(self::$database);
                           if($sheetData[1]['A']== 'TABELA PREÇO'){ //valida se é a planilha para o objeto atual

                                TTransaction::open(self::$database);
                                foreach($sheetData as $linhas){//foreach que percorre as linhas existentes na planilha
                                $cont++;
                                        if($cont >= $inicio_dados){

                                             $object = new Preco();
                                             $object->id                      = $linhas['A'];
                                             $object->preco_venda             = $linhas['B'];
                                             $object->preco_custo             = $linhas['C'];
                                             $object->id_produto              = $linhas['D'];
                                             $object->id_tabela               = $linhas['E'];
                                             $object->store();

                                        }
                                }
                            TTransaction::close(); 
                            TApplication::loadPage('TabelaPrecoList', 'onReload');//list do objeto em especifico

                           }else{
                               new TMessage('error',"Planilha importada é diferente ao formulário atual,
                               informe  aplanilha referente a {$tipoForm}"); 
                           }
             case "deposito":
                        $inicio_dados = 4;//variavel onde indica o inicio dos dados de fato.
                            $cont=0;//contador que percorre as linhas para iniciar o salvamento somente nas linhas dados;
                            TTransaction::open(self::$database);
                           if($sheetData[1]['A']== 'ESTOQUE'){ //valida se é a planilha para o objeto atual

                                TTransaction::open(self::$database);
                                foreach($sheetData as $linhas){//foreach que percorre as linhas existentes na planilha
                                $cont++;
                                        if($cont >= $inicio_dados){

                                             $object = new ProdEstoque();
                                             $object->id                         = $linhas['A'];
                                             $object->quantidade                 = $linhas['B'];
                                             $object->qtd_min                    = $linhas['C'];
                                             $object->qtd_max                    = $linhas['D'];
                                             $deposito = Deposito::where('id','=',$linhas['E'])->load();
                                        if(isset($deposito[0])){
                                             $object->id_deposito                = $linhas['E'];
                                        }else{
                                            $deposito = new Deposito();
                                            $deposito->id = $linhas['E'];
                                            $deposito->nome_deposito = "novo deposito {$cont}";
                                            $deposito->store();
                                            $object->id_deposito = $deposito->id;
                                        }
                                            $object->id_produto                   = $linhas['F'];
                                            $object->store();
                                    }
                                }
                            TTransaction::close(); 
                            TApplication::loadPage('DepositoFormList', 'onReload');//list do objeto em especifico

                           }else{
                               new TMessage('error',"Planilha importada é diferente ao formulário atual,
                               informe  aplanilha referente a {$tipoForm}"); 
                           }

                            break;
           default:
                            new TMessage('error','Nenhum tipo de importação existente ou chamada de pagina incorreta');    
                }

                     }else{
                            new TMessage('error', 'informe um arquivo para que seja aberto');
                          }
      $this->form->setData($dados);
      }catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
             $this->form->setData($dados);
        }

    }

    public function onShow($param = null)
    {               

        try{
            if(isset($param['tipoImport'])){
                $object = new stdClass();
                $object->tipoImport = $param['tipoImport'];
                TTransaction::open(self::$database);
                $links = new LinksImport(1);
                TTransaction::close();
                switch($object->tipoImport){
           case "Produto":
                            $object->linkImport = $links->produto;
                            break;

           case "Fornecedor":
                            $object->linkImport = $links->fornecedores;
                            break;

           case "Marca":    
                            $object->linkImport = $links->marcas;
                            break;

           case "Categoria Produto":
                            $object->linkImport = $links->categoria_prod;
                            break;
            case "ncm_cest":
                            $object->linkImport = $links->ncm_cest;
                            break;
            case "preco":
                            $object->linkImport = $links->preco;
                            break;
            case "deposito":
                            $object->linkImport = $links->estoque;
                            break;
           default:
                            new TMessage('error','Nenhum tipo de importação existente ou chamada de pagina incorreta');    
                }
                 $this->form->setData($object);
            }else{
               new TMessage('error', "Necessário informar o tipo de importação"); 
            }
        }catch(Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // undo all pending operations
        }
    } 

}

