<?php

use eNotasGW\Api\Exceptions as Exceptions;
use eNotasGW\Api\fileParameter as fileParameter;

class LojaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Loja';
    private static $primaryKey = 'id';
    private static $formName = 'form_Loja';

    use Adianti\Base\AdiantiFileSaveTrait;

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
        $this->form->setFormTitle("Cadastro Loja");


        $id = new TEntry('id');
        $grupo = new TDBCombo('grupo', 'base_banco', 'GrupoLojas', 'id', '{nome}','nome asc'  );
        $button__ = new TButton('button__');
        $dt_ativacao = new TDate('dt_ativacao');
        $e_notas = new TCombo('e_notas');
        $razao_social = new TEntry('razao_social');
        $unidade = new TDBCombo('unidade', 'permission', 'SystemUnit', 'id', '{name}','name asc'  );
        $abreviacao = new TEntry('abreviacao');
        $cnpj = new TEntry('cnpj');
        $observacao = new TText('observacao');
        $fk_cidade_estado_id = new TDBCombo('fk_cidade_estado_id', 'base_banco', 'Estado', 'id', '{nome}','nome asc'  );
        $button__1 = new TButton('button__1');
        $cidade = new TDBCombo('cidade', 'base_banco', 'Cidade', 'id', '{nome}','nome asc'  );
        $button__2 = new TButton('button__2');
        $rua = new TEntry('rua');
        $numero = new TEntry('numero');
        $bairro = new TEntry('bairro');
        $complemento = new TEntry('complemento');
        $lat = new TEntry('lat');
        $lon = new TEntry('lon');
        $fone = new TEntry('fone');
        $email = new TEntry('email');
        $cep = new TEntry('cep');
        $deposito = new TDBCombo('deposito', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $button__3 = new TButton('button__3');
        $button_acessar_deposito = new TButton('button_acessar_deposito');
        $regime_tribut = new TCombo('regime_tribut');
        $inscr_estadual = new TEntry('inscr_estadual');
        $inscr_municipal = new TEntry('inscr_municipal');
        $icms = new TCombo('icms');
        $idEmpresa = new TEntry('idEmpresa');
        $tipo_emissao = new TCombo('tipo_emissao');
        $caminho = new TFile('caminho');
        $senha_certificado = new TPassword('senha_certificado');
        $serie_nf_producao = new TEntry('serie_nf_producao');
        $seq_nf_producao = new TEntry('seq_nf_producao');
        $id_csc_producao = new TEntry('id_csc_producao');
        $csc_producao = new TEntry('csc_producao');
        $serie_nf_homologacao = new TEntry('serie_nf_homologacao');
        $seq_nf_homologacao = new TEntry('seq_nf_homologacao');
        $id_csc_homologacao = new TEntry('id_csc_homologacao');
        $csc_homologacao = new TEntry('csc_homologacao');

        $grupo->addValidation("Grupo da loja", new TRequiredValidator()); 
        $razao_social->addValidation("Razao social", new TRequiredValidator()); 
        $cnpj->addValidation("Cnpj", new TRequiredValidator()); 
        $cidade->addValidation("Cidade", new TRequiredValidator()); 

        $abreviacao->setMaxLength(4);
        $dt_ativacao->setDatabaseMask('yyyy-mm-dd');
        $idEmpresa->forceUpperCase();
        $caminho->enableFileHandling();

        $id->setEditable(false);
        $dt_ativacao->setEditable(false);

        $e_notas->setDefaultOption(false);
        $tipo_emissao->setDefaultOption(false);

        $email->setTip("Inserir o e-mail do financeiro/ ADM");
        $fone->setTip("Inserir o telefone do financeiro/ ADM");

        $e_notas->setValue('1');
        $tipo_emissao->setValue('1');
        $dt_ativacao->setValue(date('d/m/Y'));

        $e_notas->addItems(['1'=>'Não','2'=>'Sim']);
        $tipo_emissao->addItems(['1'=>'Parcial','2'=>'Completa']);
        $regime_tribut->addItems(['true'=>' Simples nacional','false'=>' Regime normal']);
        $icms->addItems(['1'=>' 1- Contribuinte ICMS','2'=>' 2- Contribuinte isento de inscrição no cadastro de Contribuintes','3'=>' 9- Não Contribuinte, que pode ou não possuir inscrição estadual no cadastro de contribuinte']);

        $button__->setAction(new TAction([$this, 'onAddGrupo']), "  ");
        $button__1->setAction(new TAction(['EstadoForm', 'onShow']), "  ");
        $button__2->setAction(new TAction(['CidadeForm', 'onShow']), "  ");
        $button__3->setAction(new TAction(['DepositoFormjanela', 'onShow']), "  ");
        $button_acessar_deposito->setAction(new TAction(['DepositoForm', 'onEdit']), "Acessar Depósito");

        $button__->addStyleClass('btn-default');
        $button__1->addStyleClass('btn-default');
        $button__2->addStyleClass('btn-default');
        $button__3->addStyleClass('btn-default');
        $button_acessar_deposito->addStyleClass('btn-primary');

        $button__->setImage('fas:plus #ff0091');
        $button__1->setImage('fas:plus #ff0091');
        $button__2->setImage('fas:plus #ff0091');
        $button__3->setImage('fas:plus #ff0091');
        $button_acessar_deposito->setImage('fas:external-link-alt #ffffff');

        $abreviacao->setMask('AAAA');
        $dt_ativacao->setMask('dd/mm/yyyy');
        $id_csc_producao->setMask('000000');
        $id_csc_homologacao->setMask('000000');
        $inscr_municipal->setMask('000000000000');
        $inscr_estadual->setMask('0000000000000000');

        $id->setSize(100);
        $rua->setSize('100%');
        $cep->setSize('100%');
        $lon->setSize('100%');
        $lat->setSize('100%');
        $icms->setSize('100%');
        $fone->setSize('100%');
        $cnpj->setSize('100%');
        $grupo->setSize('100%');
        $email->setSize('100%');
        $cidade->setSize('100%');
        $numero->setSize('100%');
        $bairro->setSize('100%');
        $unidade->setSize('100%');
        $caminho->setSize('100%');
        $e_notas->setSize('100%');
        $dt_ativacao->setSize(170);
        $deposito->setSize('100%');
        $idEmpresa->setSize('100%');
        $abreviacao->setSize('100%');
        $complemento->setSize('100%');
        $razao_social->setSize('100%');
        $csc_producao->setSize('100%');
        $tipo_emissao->setSize('100%');
        $regime_tribut->setSize('100%');
        $observacao->setSize('100%', 70);
        $inscr_estadual->setSize('100%');
        $seq_nf_producao->setSize('100%');
        $id_csc_producao->setSize('100%');
        $inscr_municipal->setSize('100%');
        $csc_homologacao->setSize('100%');
        $serie_nf_producao->setSize('100%');
        $senha_certificado->setSize('100%');
        $seq_nf_homologacao->setSize('100%');
        $id_csc_homologacao->setSize('100%');
        $fk_cidade_estado_id->setSize('100%');
        $serie_nf_homologacao->setSize('100%');

        $this->form->appendPage("Dados Cadastrais");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addContent([new TFormSeparator("Dados Cadastrais", '#000000', '20', '#ff0091')]);
        $row2 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Grupo:", null, '14px', null, '100%'),$grupo],[new TLabel("  ", null, '14px', null, '100%'),$button__],[new TLabel("Dt ativacao:", null, '14px', null, '100%'),$dt_ativacao],[new TLabel("Enviar E-Notas:", null, '14px', null),$e_notas]);
        $row2->layout = [' col-sm-6','col-sm-2',' col-sm-1',' col-sm-2',' col-sm-1'];

        $row3 = $this->form->addFields([new TLabel("Razao social:", '#ff0000', '14px', null, '100%'),$razao_social],[new TLabel("Nome fantasia:", null, '14px', null, '100%'),$unidade],[new TLabel("Abreviação:", null, '14px', null),$abreviacao]);
        $row3->layout = [' col-sm-5',' col-sm-5','col-sm-2'];

        $row4 = $this->form->addFields([new TLabel("Cnpj:", '#ff0000', '14px', null, '100%'),$cnpj],[new TLabel("Observacao:", null, '14px', null, '100%'),$observacao]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addContent([new TFormSeparator("Endereço e Contato", '#000000', '20', '#ff0091')]);
        $row6 = $this->form->addFields([new TLabel("Estado:", '#ff0000', '14px', null, '100%'),$fk_cidade_estado_id],[$button__1],[new TLabel("Cidade:", '#ff0000', '14px', null, '100%'),$cidade],[$button__2]);
        $row6->layout = [' col-sm-5',' col-sm-1',' col-sm-5',' col-sm-1'];

        $row7 = $this->form->addFields([new TLabel("Rua:", null, '14px', null, '100%'),$rua],[new TLabel("Numero:", null, '14px', null, '100%'),$numero],[new TLabel("Bairro:", null, '14px', null, '100%'),$bairro],[new TLabel("Complemento:", null, '14px', null, '100%'),$complemento]);
        $row7->layout = [' col-sm-3',' col-sm-1','col-sm-2',' col-sm-6'];

        $row8 = $this->form->addFields([new TLabel("Latitude:", null, '14px', null),$lat],[new TLabel("Longitude:", null, '14px', null),$lon]);
        $row8->layout = [' col-sm-6',' col-sm-6'];

        $row9 = $this->form->addFields([new TLabel("Fone:", null, '14px', null, '100%'),$fone],[new TLabel("Email:", null, '14px', null, '100%'),$email],[new TLabel("CEP:", null, '14px', null, '100%'),$cep]);
        $row9->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $this->form->appendPage("Funcionários");
        $row10 = $this->form->addContent([new TFormSeparator("Funcionários", '#000000', '20', '#ff0091')]);

        $this->form->appendPage("Depósito");
        $row11 = $this->form->addContent([new TFormSeparator("Depósito", '#000000', '20', '#ff0091')]);
        $row12 = $this->form->addFields([new TLabel("Depósito:", null, '14px', null, '100%'),$deposito],[new TLabel(" ", null, '14px', null, '100%'),$button__3],[$button_acessar_deposito]);
        $row12->layout = ['col-sm-6',' col-sm-1','col-sm-2'];

        $this->form->appendPage("Preço");
        $row13 = $this->form->addContent([new TFormSeparator("Tebela de preço", '#000000', '20', '#ff0091')]);

        $this->form->appendPage("Vendas");
        $row14 = $this->form->addContent([new TFormSeparator("Vendas", '#000000', '20', '#ff0091')]);

        $this->form->appendPage("Fiscal");
        $row15 = $this->form->addContent([new TFormSeparator("Fiscal", '#000000', '20', '#ff0091')]);
        $row16 = $this->form->addFields([new TLabel("Regime tributário:", null, '14px', null, '100%'),$regime_tribut],[new TLabel("Inscrição estadual:", null, '14px', null, '100%'),$inscr_estadual],[new TLabel("Inscrição municipal:", null, '14px', null, '100%'),$inscr_municipal]);
        $row16->layout = [' col-sm-4',' col-sm-3',' col-sm-5'];

        $row17 = $this->form->addFields([new TLabel("Contribuinte ICMS:", null, '14px', null, '100%'),$icms],[new TLabel("id da empresa no E-notas:", null, '14px', null, '100%'),$idEmpresa],[new TLabel("Tipo Emissão:", null, '14px', null, '100%'),$tipo_emissao]);
        $row17->layout = [' col-sm-6',' col-sm-4','col-sm-2'];

        $row18 = $this->form->addFields([new TLabel("Certificado Digital:", null, '14px', null)],[$caminho]);
        $row18->layout = [' col-sm-4',' col-sm-8'];

        $row19 = $this->form->addFields([new TLabel("Senha do certificado:", null, '14px', null)],[$senha_certificado]);
        $row19->layout = [' col-sm-4',' col-sm-4'];

        $row20 = $this->form->addContent([new TFormSeparator("Produção:", '#333333', '14', '#ff0091')]);
        $row21 = $this->form->addFields([new TLabel("Série:", null, '14px', null, '100%'),$serie_nf_producao],[new TLabel("Próximo número de nota:", null, '14px', null, '100%'),$seq_nf_producao]);
        $row21->layout = [' col-sm-4',' col-sm-8'];

        $row22 = $this->form->addFields([new TLabel("ID Fiscal:", null, '14px', null, '100%'),$id_csc_producao],[new TLabel("CSC:", null, '14px', null, '100%'),$csc_producao]);
        $row22->layout = [' col-sm-4',' col-sm-8'];

        $row23 = $this->form->addContent([new TFormSeparator("Homologação", '#333333', '14', '#ff0091')]);
        $row24 = $this->form->addFields([new TLabel("Série:", null, '14px', null),$serie_nf_homologacao],[new TLabel("Próximo número de nota:", null, '14px', null),$seq_nf_homologacao]);
        $row24->layout = [' col-sm-4',' col-sm-8'];

        $row25 = $this->form->addFields([new TLabel("ID Fiscal:", null, '14px', null),$id_csc_homologacao],[new TLabel("CSC:", null, '14px', null),$csc_homologacao]);
        $row25->layout = [' col-sm-4',' col-sm-8'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onvoltar = $this->form->addAction("Voltar", new TAction([$this, 'onVoltar']), 'fas:arrow-left #fdfdfd');
        $this->btn_onvoltar = $btn_onvoltar;
        $btn_onvoltar->addStyleClass('btn-success'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Desenvolvedor","Cadastro Loja"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public  function onAddGrupo($param = null) 
    {
        try 
        {

    TApplication::loadPage('GrupoLojasFormList', 'onShow');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
        }
        /*
    }

    public  function ($param = null) 
    {

        try 
        {
           Application::loadPage('depositoForm', 'onShow');

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public function onSave($param = null) 
    {
 */     
    public function onSave($param = null) 
    {

       require '/var/www/html/vendor/enotas/php-client-v2/src/eNotasGW.php';
       eNotasGW::configure(array('apiKey' => 'NWZiZGM0YWUtMDdmYi00NTEzLThjYjMtNTMwMjAxYmYwNjAw'));
        try
        {
            $data = $this->form->getData(); // get form data as array
            TTransaction::open('permission'); 
            $unidade = SystemUnit::where('id','=',$data->unidade)->load();
            $data->nome_fantasia = $unidade[0]->name;
            TTransaction::close(); // 
            TTransaction::open(self::$database); // open a transaction
            $messageAction = null;
            $this->form->validate(); // validate form data
            $object = new Loja(); // create an empty object 
            $object->fromArray( (array) $data); // load the object with data

            $caminho_dir = 'resources/imports/';  

            $object->store(); // save the object 
            if($object->deposito){
                $deposito = new Deposito($object->deposito);
                $deposito->nomeLoja = $object->id;
                $deposito->store();
            }
if($data->e_notas == 2){
          $dadosEmpresa = array();
            //pre configurações de dados-------
            $toRemove=array(".","/","-");
            $cnpj           = str_replace($toRemove,"",$object->cnpj);
            $inscMunicipal  = str_replace($toRemove,"",$object->inscr_municipal);
            $inscEstadual   = str_replace($toRemove,"",$object->inscr_estadual);
            TTransaction::open('permission');
            $unidade = new SystemUnit($object->unidade);
            TTransaction::close();
            $estados;
            $UF;
                $estado    = new Estado ($object->estado);
                if($estado->nome        == 'MINAS GERAIS'){
                    $UF       = "MG";
                }else{
                    $UF       = "RJ";
                }
            $cidade    = new Cidade($object->cidade);
            if($object->regime_tribut =="true"){
               $simples = true;
            }else{
               $simples = false;
            }
            $object->idEmpresa = strtoupper($data->idEmpresa);
            //ARRAY DE DADOS DA EMPRESA, NECESSÁRIO IF DEVIDO A SER MODIFICAÇÃO OU NOVA EMPRESA
        if(isset($object->idEmpresa)){
            $dadosEmpresa = array(
			'id' => $object->idEmpresa,
			'cnpj' => $cnpj,
			'inscricaoEstadual' => $inscEstadual,
			'inscricaoMunicipal' => $inscMunicipal, //opcional
			'razaoSocial' => $object->razao_social,
			'nomeFantasia' => $unidade->name, //OK
			'optanteSimplesNacional' => $simples,
			'email' => $object->email,
			'telefoneComercial' => $object->fone,
			'endereco' => array(
				'uf' => $UF, 
				'cidade' => $cidade->nome,
				'logradouro' => $object->rua,
				'numero' => $object->numero,
				'complemento' => $object->complemento,
				'bairro' => $object->bairro,
				'cep' => $object->cep
			),
		'emissaoNFeConsumidor' => array(
				'ambienteProducao' => array(
					'sequencialNFe' => $object->seq_nf_producao,
					'serieNFe' => $object->serie_nf_producao,
					'csc' => array(
						'id' =>$object->id_csc_producao , //id do Código de Segurança do Contribuiente (CSC) necessário para emsisão de NFC-e
						'codigo' => $object->csc_producao //Código de Segurança do Contribuiente (CSC) necessário para emsisão de NFC-e
					)
			),
				'ambienteHomologacao' => array(
					'sequencialNFe' =>$object->seq_nf_homologacao ,
					'serieNFe' => $object->serie_nf_homologacao,
					'csc' => array(
						'id' =>$object->id_csc_homologacao , //id do Código de Segurança do Contribuiente (CSC) necessário para emsisão de NFC-e
						'codigo' => $object->csc_homologacao //Código de Segurança do Contribuiente (CSC) necessário para emsisão de NFC-e
					)
				)
			)
		);
        }else{
            $dadosEmpresa = array(
			//'id' => $object->idEmpresa,
			'cnpj' => $cnpj,
			'inscricaoEstadual' => $inscEstadual,
			'inscricaoMunicipal' => $inscMunicipal, //opcional
			'razaoSocial' => $object->razao_social,
			'nomeFantasia' => $unidade->name, //OK
			'optanteSimplesNacional' => $simples,
			'email' => $object->email,
			'telefoneComercial' => $object->fone,
			'endereco' => array(
				'uf' => $UF, 
				'cidade' => $cidade->nome,
				'logradouro' => $object->rua,
				'numero' => $object->numero,
				'complemento' => $object->complemento,
				'bairro' => $object->bairro,
				'cep' => $object->cep
			),
		'emissaoNFeConsumidor' => array(
				'ambienteProducao' => array(
					'sequencialNFe' => $object->seq_nf_producao,
					'serieNFe' => $object->serie_nf_producao,
					'csc' => array(
						'id' =>$object->id_csc_producao , //id do Código de Segurança do Contribuiente (CSC) necessário para emsisão de NFC-e
						'codigo' => $object->csc_producao //Código de Segurança do Contribuiente (CSC) necessário para emsisão de NFC-e
					)
			),
				'ambienteHomologacao' => array(
					'sequencialNFe' =>$object->seq_nf_homologacao ,
					'serieNFe' => $object->serie_nf_homologacao,
					'csc' => array(
						'id' =>$object->id_csc_homologacao , //id do Código de Segurança do Contribuiente (CSC) necessário para emsisão de NFC-e
						'codigo' => $object->csc_homologacao //Código de Segurança do Contribuiente (CSC) necessário para emsisão de NFC-e
					)
				)
			)
		);
            }
        //echo "sincronização Enotas desabilitada ";
		$result = eNotasGW::$EmpresaApi->inserirAtualizar($dadosEmpresa);
		$object->idEmpresa = $result->empresaId;
		$object->store();
		$data->idEmpresa = $result->empresaId;
        $path = json_decode(urldecode($param['caminho']));

        if(isset($path)){
            $fileName = substr($path->fileName,4);
            $arquivoPfxOuP12 = fileParameter::fromPath ($path->fileName,'application/x-pkcs12',$fileName);
            $senhaDoArquivo  = $object->senha_certificado;
            eNotasGW::$EmpresaApi->atualizarCertificado($object->idEmpresa, $arquivoPfxOuP12, $senhaDoArquivo);
            new TMessage('info', 'Certificado salvo!');
        }
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

            $this->saveFile($object, $data, 'caminho', $caminho_dir); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /*$messageAction = new TAction(['LojaList', 'onReload']);

            new TMessage('info', "Registro salvo", $messageAction); 

*/

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

    TApplication::loadPage('LojaList', 'onReload');

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

                $object = new Loja($key); // instantiates the Active Record 

                                $object->fk_cidade_estado_id = $object->fk_cidade->estado_id;

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

