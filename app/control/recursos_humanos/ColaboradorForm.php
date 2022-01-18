<?php

class ColaboradorForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Colaborador';
    private static $primaryKey = 'id';
    private static $formName = 'form_ColaboradorForm';

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
        $this->form->setFormTitle("Cadastro Funcionário");


        $id = new TEntry('id');
        $pessoa_id = new TEntry('pessoa_id');
        $dt_ativacao = new TDate('dt_ativacao');
        $dt_desativacao = new TDate('dt_desativacao');
        $tipo_pessoa = new TDBRadioGroup('tipo_pessoa', 'base_banco', 'Grupo', 'id', '{nome}','nome asc'  );
        $pessoa_nome = new TEntry('pessoa_nome');
        $pessoa_documento = new TEntry('pessoa_documento');
        $pessoa_email = new TEntry('pessoa_email');
        $pessoa_fone = new TEntry('pessoa_fone');
        $pessoa_estado_id = new TDBCombo('pessoa_estado_id', 'base_banco', 'Estado', 'id', '{nome}','nome asc'  );
        $pessoa_cidade_id = new TCombo('pessoa_cidade_id');
        $pessoa_endereco = new TEntry('pessoa_endereco');
        $pessoa_cep = new TEntry('pessoa_cep');
        $pessoa_obs = new TEntry('pessoa_obs');
        $loja_registro = new TDBCombo('loja_registro', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $loja_atual = new TDBCombo('loja_atual', 'base_banco', 'Loja', 'id', '{nome_fantasia}','razao_social asc'  );
        $status_colaborador = new TCombo('status_colaborador');
        $status_ferias = new TEntry('status_ferias');
        $rg = new TEntry('rg');
        $ctps = new TEntry('ctps');
        $cnh = new TEntry('cnh');
        $dt_nascimento = new TDate('dt_nascimento');
        $dt_registro = new TDate('dt_registro');
        $contrato1 = new TDate('contrato1');
        $contrato2 = new TDate('contrato2');
        $dt_desligamento = new TDate('dt_desligamento');
        $cargo = new TDBCombo('cargo', 'base_banco', 'Cargo', 'id', '{cargo}','id asc'  );
        $salario = new TNumeric('salario', '2', ',', '.' );
        $bonificacao = new TRadioGroup('bonificacao');
        $salario_familia = new TRadioGroup('salario_familia');
        $salario_familia_qtd = new TEntry('salario_familia_qtd');
        $escala = new TDBCombo('escala', 'base_banco', 'Escala', 'id', '{descricao}','id asc'  );
        $hora_diaria = new TTime('hora_diaria');
        $id_escala = new THidden('id_escala');
        $passage_diaria = new TNumeric('passage_diaria', '0', '', '' );
        $valor_passagem = new TNumeric('valor_passagem', '2', ',', '.' );
        $id_vale_transporte = new THidden('id_vale_transporte');
        $aso_colaborador_id = new THidden('aso_colaborador_id');
        $aso_colaborador_dt_realizado = new TDate('aso_colaborador_dt_realizado');
        $aso_colaborador_tipo_aso = new TCombo('aso_colaborador_tipo_aso');
        $aso_colaborador_status = new TEntry('aso_colaborador_status');
        $aso_colaborador_vencimento = new TDate('aso_colaborador_vencimento');
        $button_adicionar_aso_colaborador = new TButton('button_adicionar_aso_colaborador');
        $atestado_colaborador_id = new THidden('atestado_colaborador_id');
        $atestado_colaborador_dt_atestado = new TDate('atestado_colaborador_dt_atestado');
        $atestado_colaborador_dias = new TEntry('atestado_colaborador_dias');
        $atestado_colaborador_motivo = new TEntry('atestado_colaborador_motivo');
        $button_adicionar_atestado_colaborador = new TButton('button_adicionar_atestado_colaborador');
        $ferias_colaborador_id = new THidden('ferias_colaborador_id');
        $ferias_colaborador_dt_inicio = new TDate('ferias_colaborador_dt_inicio');
        $ferias_colaborador_dt_fim = new TDate('ferias_colaborador_dt_fim');
        $ferias_colaborador_status_ferias = new TEntry('ferias_colaborador_status_ferias');
        $button_adicionar_ferias_colaborador = new TButton('button_adicionar_ferias_colaborador');
        $advertencia_colaborador_id = new THidden('advertencia_colaborador_id');
        $advertencia_colaborador_motivo = new TEntry('advertencia_colaborador_motivo');
        $advertencia_colaborador_dt_advertencia = new TDate('advertencia_colaborador_dt_advertencia');
        $button_adicionar_advertencia_colaborador = new TButton('button_adicionar_advertencia_colaborador');
        $documento_colaborador_id = new THidden('documento_colaborador_id');
        $documento_colaborador_descricao = new TEntry('documento_colaborador_descricao');
        $documento_colaborador_dt_registro = new TDate('documento_colaborador_dt_registro');
        $documento_colaborador_tipo_documento = new TDBCombo('documento_colaborador_tipo_documento', 'base_banco', 'TipoDocumento', 'id', '{id}','id asc'  );
        $button_adicionar_documento_colaborador = new TButton('button_adicionar_documento_colaborador');
        $transferencia_colaborador_colaborador_id = new THidden('transferencia_colaborador_colaborador_id');
        $transferencia_colaborador_colaborador_loja_origem = new TDBCombo('transferencia_colaborador_colaborador_loja_origem', 'base_banco', 'Loja', 'id', '{razao_social}','razao_social asc'  );
        $transferencia_colaborador_colaborador_loja_destino = new TDBCombo('transferencia_colaborador_colaborador_loja_destino', 'base_banco', 'Loja', 'id', '{razao_social}','razao_social asc'  );
        $transferencia_colaborador_colaborador_dt_transferencia = new TDate('transferencia_colaborador_colaborador_dt_transferencia');
        $transferencia_colaborador_colaborador_motivo_transferencia = new TDBCombo('transferencia_colaborador_colaborador_motivo_transferencia', 'base_banco', 'MotivoTransferenciaColaborador', 'id', '{id}','id asc'  );
        $button_adicionar_transferencia_colaborador_colaborador = new TButton('button_adicionar_transferencia_colaborador_colaborador');

        $pessoa_estado_id->setChangeAction(new TAction([$this,'onChangeEstado']));
        $loja_registro->setChangeAction(new TAction([$this,'onChangeLoja']));
        $cargo->setChangeAction(new TAction([$this,'onChangeCargo']));
        $escala->setChangeAction(new TAction([$this,'onChangeEscala']));

        $pessoa_email->addValidation("Email", new TRequiredValidator()); 
        $loja_registro->addValidation("loja no qual o colaborador é registrado", new TRequiredValidator()); 
        $loja_atual->addValidation("loja na qual o colaborador está", new TRequiredValidator()); 
        $status_colaborador->addValidation("Status colaborador", new TRequiredValidator()); 
        $status_ferias->addValidation("Status ferias", new TRequiredValidator()); 
        $dt_nascimento->addValidation("Dt nascimento", new TRequiredValidator()); 
        $dt_registro->addValidation("Dt registro", new TRequiredValidator()); 
        $cargo->addValidation("Cargo", new TRequiredValidator()); 
        $salario->addValidation("Salario", new TRequiredValidator()); 
        $salario_familia->addValidation("Salario familia", new TRequiredValidator()); 
        $escala->addValidation("Carga horaria", new TRequiredValidator()); 

        $status_colaborador->setDefaultOption(false);
        $aso_colaborador_tipo_aso->setDefaultOption(false);

        $bonificacao->setBooleanMode();
        $salario_familia->setBooleanMode();

        $tipo_pessoa->setLayout('horizontal');
        $bonificacao->setLayout('horizontal');
        $salario_familia->setLayout('horizontal');

        $bonificacao->addItems(['1'=>'Sim','2'=>'Não']);
        $salario_familia->addItems(['1'=>'Sim','2'=>'Não']);
        $status_colaborador->addItems(['ativo'=>'Ativo','ferias'=>'Férias','desligado'=>'Desligado','afastado'=>'Afastado']);
        $aso_colaborador_tipo_aso->addItems(['admissional'=>'Admissional','periodico'=>'Periódico','demissional'=>'Demissional']);

        $id->setEditable(false);
        $loja_atual->setEditable(false);
        $dt_ativacao->setEditable(false);
        $tipo_pessoa->setEditable(false);
        $status_ferias->setEditable(false);
        $dt_desativacao->setEditable(false);

        $button_adicionar_aso_colaborador->setAction(new TAction([$this, 'onAddDetailAsoColaborador'],['static' => 1]), "Adicionar");
        $button_adicionar_ferias_colaborador->setAction(new TAction([$this, 'onAddDetailFeriasColaborador'],['static' => 1]), "Adicionar");
        $button_adicionar_atestado_colaborador->setAction(new TAction([$this, 'onAddDetailAtestadoColaborador'],['static' => 1]), "Adicionar");
        $button_adicionar_documento_colaborador->setAction(new TAction([$this, 'onAddDetailDocumentoColaborador'],['static' => 1]), "Adicionar");
        $button_adicionar_advertencia_colaborador->setAction(new TAction([$this, 'onAddDetailAdvertenciaColaborador'],['static' => 1]), "Adicionar");
        $button_adicionar_transferencia_colaborador_colaborador->setAction(new TAction([$this, 'onAddDetailTransferenciaColaboradorColaborador'],['static' => 1]), "Adicionar");

        $button_adicionar_aso_colaborador->addStyleClass('btn-default');
        $button_adicionar_ferias_colaborador->addStyleClass('btn-default');
        $button_adicionar_atestado_colaborador->addStyleClass('btn-default');
        $button_adicionar_documento_colaborador->addStyleClass('btn-default');
        $button_adicionar_advertencia_colaborador->addStyleClass('btn-default');
        $button_adicionar_transferencia_colaborador_colaborador->addStyleClass('btn-default');

        $button_adicionar_aso_colaborador->setImage('fas:plus #2ecc71');
        $button_adicionar_ferias_colaborador->setImage('fas:plus #2ecc71');
        $button_adicionar_atestado_colaborador->setImage('fas:plus #2ecc71');
        $button_adicionar_documento_colaborador->setImage('fas:plus #2ecc71');
        $button_adicionar_advertencia_colaborador->setImage('fas:plus #2ecc71');
        $button_adicionar_transferencia_colaborador_colaborador->setImage('fas:plus #2ecc71');

        $button_adicionar_aso_colaborador->id = '611433b0dc65b';
        $button_adicionar_ferias_colaborador->id = '61152135dff02';
        $button_adicionar_atestado_colaborador->id = '6114358fdc65d';
        $button_adicionar_documento_colaborador->id = '61150ab735c66';
        $button_adicionar_advertencia_colaborador->id = '61152a027273e';
        $button_adicionar_transferencia_colaborador_colaborador->id = '6115161735c73';

        $tipo_pessoa->setValue('4');
        $bonificacao->setValue('true');
        $salario_familia->setValue('2');
        $status_ferias->setValue('inapto');
        $dt_ativacao->setValue(date('d/m/Y'));
        $dt_registro->setValue(date('d/m/Y'));
        $status_colaborador->setValue('ativo');
        $dt_desativacao->setValue(date('d/m/Y'));
        $documento_colaborador_dt_registro->setValue(date('d/m/Y'));

        $rg->setMaxLength(30);
        $cnh->setMaxLength(30);
        $ctps->setMaxLength(40);
        $status_ferias->setMaxLength(30);
        $aso_colaborador_status->setMaxLength(30);
        $atestado_colaborador_motivo->setMaxLength(150);
        $advertencia_colaborador_motivo->setMaxLength(200);
        $documento_colaborador_descricao->setMaxLength(50);
        $ferias_colaborador_status_ferias->setMaxLength(30);

        $contrato1->setDatabaseMask('yyyy-mm-dd');
        $contrato2->setDatabaseMask('yyyy-mm-dd');
        $dt_ativacao->setDatabaseMask('yyyy-mm-dd');
        $dt_registro->setDatabaseMask('yyyy-mm-dd');
        $dt_nascimento->setDatabaseMask('yyyy-mm-dd');
        $dt_desativacao->setDatabaseMask('yyyy-mm-dd');
        $dt_desligamento->setDatabaseMask('yyyy-mm-dd');
        $ferias_colaborador_dt_fim->setDatabaseMask('yyyy-mm-dd');
        $aso_colaborador_vencimento->setDatabaseMask('yyyy-mm-dd');
        $aso_colaborador_dt_realizado->setDatabaseMask('yyyy-mm-dd');
        $ferias_colaborador_dt_inicio->setDatabaseMask('yyyy-mm-dd');
        $atestado_colaborador_dt_atestado->setDatabaseMask('yyyy-mm-dd');
        $documento_colaborador_dt_registro->setDatabaseMask('yyyy-mm-dd');
        $advertencia_colaborador_dt_advertencia->setDatabaseMask('yyyy-mm-dd');
        $transferencia_colaborador_colaborador_dt_transferencia->setDatabaseMask('yyyy-mm-dd');

        $contrato1->setMask('dd/mm/yyyy');
        $contrato2->setMask('dd/mm/yyyy');
        $dt_ativacao->setMask('dd/mm/yyyy');
        $dt_registro->setMask('dd/mm/yyyy');
        $dt_nascimento->setMask('dd/mm/yyyy');
        $dt_desativacao->setMask('dd/mm/yyyy');
        $dt_desligamento->setMask('dd/mm/yyyy');
        $pessoa_fone->setMask('(00)00000-0000', true);
        $ferias_colaborador_dt_fim->setMask('dd/mm/yyyy');
        $pessoa_documento->setMask('000.000.000-00', true);
        $aso_colaborador_vencimento->setMask('dd/mm/yyyy');
        $aso_colaborador_dt_realizado->setMask('dd/mm/yyyy');
        $ferias_colaborador_dt_inicio->setMask('dd/mm/yyyy');
        $atestado_colaborador_dt_atestado->setMask('dd/mm/yyyy');
        $documento_colaborador_dt_registro->setMask('dd/mm/yyyy');
        $advertencia_colaborador_dt_advertencia->setMask('dd/mm/yyyy');
        $transferencia_colaborador_colaborador_dt_transferencia->setMask('dd/mm/yyyy');

        $id->setSize(100);
        $rg->setSize('100%');
        $cnh->setSize('100%');
        $ctps->setSize('100%');
        $cargo->setSize('100%');
        $id_escala->setSize(200);
        $escala->setSize('100%');
        $salario->setSize('100%');
        $bonificacao->setSize(80);
        $tipo_pessoa->setSize(80);
        $contrato2->setSize('100%');
        $pessoa_id->setSize('100%');
        $contrato1->setSize('100%');
        $pessoa_cep->setSize('100%');
        $pessoa_obs->setSize('100%');
        $loja_atual->setSize('100%');
        $dt_registro->setSize('100%');
        $salario_familia->setSize(80);
        $pessoa_fone->setSize('100%');
        $dt_ativacao->setSize('100%');
        $hora_diaria->setSize('100%');
        $pessoa_nome->setSize('100%');
        $pessoa_email->setSize('100%');
        $dt_nascimento->setSize('100%');
        $loja_registro->setSize('100%');
        $status_ferias->setSize('100%');
        $valor_passagem->setSize('100%');
        $passage_diaria->setSize('100%');
        $dt_desativacao->setSize('100%');
        $pessoa_endereco->setSize('100%');
        $dt_desligamento->setSize('100%');
        $id_vale_transporte->setSize(200);
        $aso_colaborador_id->setSize(200);
        $pessoa_cidade_id->setSize('100%');
        $pessoa_estado_id->setSize('100%');
        $pessoa_documento->setSize('100%');
        $status_colaborador->setSize('100%');
        $ferias_colaborador_id->setSize(200);
        $salario_familia_qtd->setSize('100%');
        $atestado_colaborador_id->setSize(200);
        $documento_colaborador_id->setSize(200);
        $aso_colaborador_status->setSize('100%');
        $advertencia_colaborador_id->setSize(200);
        $aso_colaborador_tipo_aso->setSize('100%');
        $ferias_colaborador_dt_fim->setSize('100%');
        $atestado_colaborador_dias->setSize('100%');
        $aso_colaborador_vencimento->setSize('100%');
        $atestado_colaborador_motivo->setSize('100%');
        $ferias_colaborador_dt_inicio->setSize('100%');
        $aso_colaborador_dt_realizado->setSize('100%');
        $advertencia_colaborador_motivo->setSize('100%');
        $documento_colaborador_descricao->setSize('100%');
        $ferias_colaborador_status_ferias->setSize('100%');
        $atestado_colaborador_dt_atestado->setSize('100%');
        $documento_colaborador_dt_registro->setSize('100%');
        $documento_colaborador_tipo_documento->setSize('100%');
        $transferencia_colaborador_colaborador_id->setSize(200);
        $advertencia_colaborador_dt_advertencia->setSize('100%');
        $transferencia_colaborador_colaborador_loja_origem->setSize('100%');
        $transferencia_colaborador_colaborador_loja_destino->setSize('100%');
        $transferencia_colaborador_colaborador_dt_transferencia->setSize('100%');
        $transferencia_colaborador_colaborador_motivo_transferencia->setSize('100%');

        $this->form->appendPage("Dados funcionário");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Cód. funcionário", null, '14px', null, '100%'),$id],[new TLabel("Cód. Cliente:", null, '14px', null, '100%'),$pessoa_id],[],[new TLabel("Data de ativação:", null, '14px', null, '100%'),$dt_ativacao],[new TLabel("Data de desativação:", null, '14px', null, '100%'),$dt_desativacao],[new TLabel("Tipo pessoa:", null, '14px', null, '100%'),$tipo_pessoa]);
        $row1->layout = [' col-sm-6 col-lg-1',' col-sm-6 col-lg-1',' col-sm-2 col-lg-4','col-sm-2','col-sm-2','col-sm-2'];

        $row2 = $this->form->addContent([new TFormSeparator("Cliente", '#333', '18', '#FF0091')]);
        $row3 = $this->form->addFields([new TLabel("Nome:", null, '14px', null)],[$pessoa_nome],[new TLabel("CPF:", null, '14px', null)],[$pessoa_documento],[new TLabel("Email:", null, '14px', null)],[$pessoa_email],[new TLabel("Telefone:", null, '14px', null)],[$pessoa_fone]);
        $row3->layout = [' col-sm-3 col-lg-1 control-label',' col-sm-3 col-lg-2',' col-sm-6 col-lg-1 control-label','col-sm-2',' col-sm-2 col-lg-1 control-label','col-sm-2',' col-sm-2 col-lg-1 control-label','col-sm-2'];

        $row4 = $this->form->addFields([new TLabel("Estado:", null, '14px', null)],[$pessoa_estado_id],[new TLabel("Cidade:", null, '14px', null)],[$pessoa_cidade_id],[new TLabel("Endereço:", null, '14px', null)],[$pessoa_endereco]);
        $row4->layout = ['col-sm-3 col-lg-1 control-label','col-sm-2','col-sm-2 col-lg-1 control-label','col-sm-3 col-lg-2','col-sm-6 col-lg-1 control-label',' col-sm-2 col-lg-5'];

        $row5 = $this->form->addFields([new TLabel("CEP:", null, '14px', null)],[$pessoa_cep]);
        $row5->layout = ['col-sm-2 col-lg-1 control-label','col-sm-2'];

        $row6 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$pessoa_obs]);
        $row6->layout = [' col-sm-3 col-lg-12'];

        $row7 = $this->form->addContent([new TFormSeparator("Funcionário", '#333', '18', '#FF0091')]);
        $row8 = $this->form->addFields([new TLabel("Loja registro:", null, '14px', null, '100%')],[$loja_registro],[new TLabel("Loja atual:", null, '14px', null, '100%')],[$loja_atual],[new TLabel("Status cadastro:", null, '14px', null, '100%')],[$status_colaborador],[new TLabel("Status ferias:", null, '14px', null, '100%')],[$status_ferias]);
        $row8->layout = [' col-sm-3 col-lg-1',' col-sm-3 col-lg-2',' col-sm-6 col-lg-1','col-sm-2',' col-sm-2 col-lg-1','col-sm-2',' col-sm-2 col-lg-1','col-sm-2'];

        $row9 = $this->form->addFields([new TLabel("RG:", null, '14px', null, '100%')],[$rg],[new TLabel("Ctps:", null, '14px', null, '100%')],[$ctps],[new TLabel("CNH:", null, '14px', null, '100%')],[$cnh],[new TLabel("Data nascimento:", null, '14px', null, '100%')],[$dt_nascimento]);
        $row9->layout = [' col-sm-6 col-lg-1','col-sm-2',' col-sm-2 col-lg-1',' col-sm-6 col-lg-2',' col-sm-2 col-lg-1','col-sm-2',' col-sm-2 col-lg-1','col-sm-2'];

        $row10 = $this->form->addFields([new TLabel("Data registro:", null, '14px', null, '100%')],[$dt_registro],[new TLabel("Venc. do 1º contrato:", null, '14px', null, '100%')],[$contrato1],[new TLabel("Venc. do 2º contrato:", null, '14px', null, '100%')],[$contrato2],[new TLabel("Desligamento:", null, '14px', null, '100%')],[$dt_desligamento]);
        $row10->layout = [' col-sm-6 col-lg-1',' col-sm-6 col-lg-2',' col-sm-2 col-lg-1','col-sm-2',' col-sm-2 col-lg-1','col-sm-2',' col-sm-2 col-lg-1','col-sm-2'];

        $row11 = $this->form->addFields([new TLabel("Cargo:", null, '14px', null, '100%')],[$cargo],[new TLabel("Salario:", null, '14px', null, '100%')],[$salario],[new TLabel("Bonificação:", null, '14px', null, '100%')],[$bonificacao],[],[]);
        $row11->layout = [' col-sm-3 col-lg-1',' col-sm-3 col-lg-2',' col-sm-6 col-lg-1','col-sm-2',' col-sm-2 col-lg-1','col-sm-2',' col-sm-2 col-lg-2',' col-sm-2 col-lg-1'];

        $row12 = $this->form->addFields([new TLabel("Salario familia:", null, '14px', null, '100%')],[$salario_familia],[new TLabel("Dependentes:", null, '14px', null, '100%')],[$salario_familia_qtd],[],[],[],[]);
        $row12->layout = [' col-sm-6 col-lg-1','col-sm-2',' col-sm-2 col-lg-1','col-sm-2',' col-sm-2 col-lg-1','col-sm-2',' col-sm-6 col-lg-1','col-sm-2'];

        $row13 = $this->form->addFields([new TLabel("Escala:", null, '14px', null)],[$escala],[new TLabel("hora diaria:", null, '14px', null)],[$hora_diaria],[],[$id_escala]);
        $row13->layout = [' col-sm-6 col-lg-1',' col-sm-6 col-lg-2',' col-sm-2 col-lg-1','col-sm-2',' col-sm-2 col-lg-5',' col-sm-2 col-lg-1'];

        $row14 = $this->form->addFields([new TLabel("Passagens p/ dia:", null, '14px', null)],[$passage_diaria],[new TLabel("Valor passagem:", null, '14px', null)],[$valor_passagem],[],[$id_vale_transporte]);
        $row14->layout = [' col-sm-6 col-lg-1',' col-sm-6 col-lg-2',' col-sm-2 col-lg-1','col-sm-2',' col-sm-2 col-lg-5',' col-sm-2 col-lg-1'];

        $this->form->appendPage("ASO");

        $this->detailFormAsoColaborador = new BootstrapFormBuilder('detailFormAsoColaborador');
        $this->detailFormAsoColaborador->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormAsoColaborador->setProperty('class', 'form-horizontal builder-detail-form');

        $row15 = $this->detailFormAsoColaborador->addFields([new TFormSeparator("Atestado de Saúde Ocupacional", '#333', '18', '#FF0091')]);
        $row15->layout = [' col-sm-12'];

        $row16 = $this->detailFormAsoColaborador->addFields([new TLabel("realizado em:", null, '14px', null, '100%'),$aso_colaborador_id],[$aso_colaborador_dt_realizado],[new TLabel("Tipo ASO:", null, '14px', null, '100%')],[$aso_colaborador_tipo_aso],[new TLabel("Status:", null, '14px', null, '100%')],[$aso_colaborador_status],[new TLabel("Vencimento:", null, '14px', null, '100%')],[$aso_colaborador_vencimento]);
        $row16->layout = [' col-sm-6 col-lg-1 control-label',' col-sm-2 col-lg-2',' col-sm-6 col-lg-1 control-label',' col-sm-2 col-lg-2',' control-label','col-sm-2',' col-sm-2 col-lg-1 control-label','col-sm-2'];

        $row17 = $this->detailFormAsoColaborador->addFields([],[$button_adicionar_aso_colaborador],[]);
        $row17->layout = [' col-sm-2 col-lg-5',' col-sm-2 control-label',' col-sm-2 col-lg-5'];

        $row18 = $this->detailFormAsoColaborador->addFields([new THidden('aso_colaborador__row__id')]);
        $this->aso_colaborador_criteria = new TCriteria();

        $this->aso_colaborador_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->aso_colaborador_list->disableHtmlConversion();;
        $this->aso_colaborador_list->generateHiddenFields();
        $this->aso_colaborador_list->setId('aso_colaborador_list');

        $this->aso_colaborador_list->style = 'width:100%';
        $this->aso_colaborador_list->class .= ' table-bordered';

        $column_aso_colaborador_dt_realizado = new TDataGridColumn('dt_realizado', "Realizado em", 'left');
        $column_aso_colaborador_tipo_aso = new TDataGridColumn('tipo_aso', "Tipo", 'left');
        $column_aso_colaborador_status = new TDataGridColumn('status', "Status", 'left');
        $column_aso_colaborador_vencimento = new TDataGridColumn('vencimento', "Vencimento", 'left');
        $column_aso_colaborador_link_scan_aso = new TDataGridColumn('link_scan_aso', "Link", 'left');

        $column_aso_colaborador__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_aso_colaborador__row__data->setVisibility(false);

        $action_onEditDetailAso = new TDataGridAction(array('ColaboradorForm', 'onEditDetailAso'));
        $action_onEditDetailAso->setUseButton(false);
        $action_onEditDetailAso->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailAso->setLabel("Editar");
        $action_onEditDetailAso->setImage('far:edit #478fca');
        $action_onEditDetailAso->setFields(['__row__id', '__row__data']);

        $this->aso_colaborador_list->addAction($action_onEditDetailAso);

        $this->aso_colaborador_list->addColumn($column_aso_colaborador_dt_realizado);
        $this->aso_colaborador_list->addColumn($column_aso_colaborador_tipo_aso);
        $this->aso_colaborador_list->addColumn($column_aso_colaborador_status);
        $this->aso_colaborador_list->addColumn($column_aso_colaborador_vencimento);
        $this->aso_colaborador_list->addColumn($column_aso_colaborador_link_scan_aso);

        $this->aso_colaborador_list->addColumn($column_aso_colaborador__row__data);

        $this->aso_colaborador_list->createModel();
        $this->detailFormAsoColaborador->addContent([$this->aso_colaborador_list]);

        $row19 = $this->form->addFields([$this->detailFormAsoColaborador]);
        $row19->layout = [' col-sm-3 col-lg-12'];

        $this->form->appendPage("Atestado médico e afastamentos");

        $this->detailFormAtestadoColaborador = new BootstrapFormBuilder('detailFormAtestadoColaborador');
        $this->detailFormAtestadoColaborador->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormAtestadoColaborador->setProperty('class', 'form-horizontal builder-detail-form');

        $row20 = $this->detailFormAtestadoColaborador->addFields([new TFormSeparator("Atestado Médico e Afastamentos", '#333', '18', '#FF0091')]);
        $row20->layout = [' col-sm-12'];

        $row21 = $this->detailFormAtestadoColaborador->addFields([new TLabel("Data:", null, '14px', null, '100%'),$atestado_colaborador_id],[$atestado_colaborador_dt_atestado],[new TLabel("Dias:", null, '14px', null, '100%')],[$atestado_colaborador_dias],[new TLabel("Motivo:", null, '14px', null, '100%')],[$atestado_colaborador_motivo]);
        $row21->layout = [' col-sm-6 col-lg-1 control-label','col-sm-2',' col-sm-6 col-lg-1 control-label','col-sm-2',' col-sm-2 col-lg-1 control-label',' col-sm-2 col-lg-5'];

        $row22 = $this->detailFormAtestadoColaborador->addFields([],[$button_adicionar_atestado_colaborador],[]);
        $row22->layout = [' col-sm-2 col-lg-5','col-sm-2',' col-sm-2 col-lg-5'];

        $row23 = $this->detailFormAtestadoColaborador->addFields([new THidden('atestado_colaborador__row__id')]);
        $this->atestado_colaborador_criteria = new TCriteria();

        $this->atestado_colaborador_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->atestado_colaborador_list->disableHtmlConversion();;
        $this->atestado_colaborador_list->generateHiddenFields();
        $this->atestado_colaborador_list->setId('atestado_colaborador_list');

        $this->atestado_colaborador_list->style = 'width:100%';
        $this->atestado_colaborador_list->class .= ' table-bordered';

        $column_atestado_colaborador_dt_atestado = new TDataGridColumn('dt_atestado', "Data inicio", 'left');
        $column_atestado_colaborador_colaborador_id = new TDataGridColumn('colaborador_id', "Data retorno", 'left');
        $column_atestado_colaborador_dias = new TDataGridColumn('dias', "Dias", 'left');
        $column_atestado_colaborador_motivo = new TDataGridColumn('motivo', "Motivo", 'left');
        $column_atestado_colaborador_link_scan_atestado = new TDataGridColumn('link_scan_atestado', "Link scan atestado", 'left');

        $column_atestado_colaborador__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_atestado_colaborador__row__data->setVisibility(false);

        $action_onEditDetailAtestado = new TDataGridAction(array('ColaboradorForm', 'onEditDetailAtestado'));
        $action_onEditDetailAtestado->setUseButton(false);
        $action_onEditDetailAtestado->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailAtestado->setLabel("Editar");
        $action_onEditDetailAtestado->setImage('far:edit #478fca');
        $action_onEditDetailAtestado->setFields(['__row__id', '__row__data']);

        $this->atestado_colaborador_list->addAction($action_onEditDetailAtestado);
        $action_onDeleteDetailAtestado = new TDataGridAction(array('ColaboradorForm', 'onDeleteDetailAtestado'));
        $action_onDeleteDetailAtestado->setUseButton(false);
        $action_onDeleteDetailAtestado->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailAtestado->setLabel("Excluir");
        $action_onDeleteDetailAtestado->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailAtestado->setFields(['__row__id', '__row__data']);

        $this->atestado_colaborador_list->addAction($action_onDeleteDetailAtestado);

        $this->atestado_colaborador_list->addColumn($column_atestado_colaborador_dt_atestado);
        $this->atestado_colaborador_list->addColumn($column_atestado_colaborador_colaborador_id);
        $this->atestado_colaborador_list->addColumn($column_atestado_colaborador_dias);
        $this->atestado_colaborador_list->addColumn($column_atestado_colaborador_motivo);
        $this->atestado_colaborador_list->addColumn($column_atestado_colaborador_link_scan_atestado);

        $this->atestado_colaborador_list->addColumn($column_atestado_colaborador__row__data);

        $this->atestado_colaborador_list->createModel();
        $this->detailFormAtestadoColaborador->addContent([$this->atestado_colaborador_list]);

        $row24 = $this->form->addFields([$this->detailFormAtestadoColaborador]);
        $row24->layout = [' col-sm-3 col-lg-12'];

        $this->form->appendPage("Férias");

        $this->detailFormFeriasColaborador = new BootstrapFormBuilder('detailFormFeriasColaborador');
        $this->detailFormFeriasColaborador->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormFeriasColaborador->setProperty('class', 'form-horizontal builder-detail-form');

        $row25 = $this->detailFormFeriasColaborador->addFields([new TFormSeparator("Férias", '#333', '18', '#FF0091')]);
        $row25->layout = [' col-sm-12'];

        $row26 = $this->detailFormFeriasColaborador->addFields([new TLabel("Data inicio:", null, '14px', null, '100%'),$ferias_colaborador_id],[$ferias_colaborador_dt_inicio],[new TLabel("Data fim:", null, '14px', null, '100%')],[$ferias_colaborador_dt_fim],[new TLabel("Status:", null, '14px', null, '100%')],[$ferias_colaborador_status_ferias]);
        $row26->layout = [' col-sm-6 col-lg-1 control-label',' col-sm-2 col-lg-2',' col-sm-6 col-lg-1 control-label',' col-sm-2 col-lg-2',' col-sm-2 col-lg-1 control-label',' col-sm-2 col-lg-2'];

        $row27 = $this->detailFormFeriasColaborador->addFields([],[$button_adicionar_ferias_colaborador],[]);
        $row27->layout = [' col-sm-2 col-lg-5','col-sm-2',' col-sm-2 col-lg-5'];

        $row28 = $this->detailFormFeriasColaborador->addFields([new THidden('ferias_colaborador__row__id')]);
        $this->ferias_colaborador_criteria = new TCriteria();

        $this->ferias_colaborador_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->ferias_colaborador_list->disableHtmlConversion();;
        $this->ferias_colaborador_list->generateHiddenFields();
        $this->ferias_colaborador_list->setId('ferias_colaborador_list');

        $this->ferias_colaborador_list->style = 'width:100%';
        $this->ferias_colaborador_list->class .= ' table-bordered';

        $column_ferias_colaborador_dt_inicio = new TDataGridColumn('dt_inicio', "Data inicio", 'left');
        $column_ferias_colaborador_dt_fim = new TDataGridColumn('dt_fim', "Data fim:", 'left');
        $column_ferias_colaborador_status_ferias = new TDataGridColumn('status_ferias', "Status ferias", 'left');

        $column_ferias_colaborador__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_ferias_colaborador__row__data->setVisibility(false);

        $action_onEditDetailFerias = new TDataGridAction(array('ColaboradorForm', 'onEditDetailFerias'));
        $action_onEditDetailFerias->setUseButton(false);
        $action_onEditDetailFerias->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailFerias->setLabel("Editar");
        $action_onEditDetailFerias->setImage('far:edit #478fca');
        $action_onEditDetailFerias->setFields(['__row__id', '__row__data']);

        $this->ferias_colaborador_list->addAction($action_onEditDetailFerias);
        $action_onDeleteDetailFerias = new TDataGridAction(array('ColaboradorForm', 'onDeleteDetailFerias'));
        $action_onDeleteDetailFerias->setUseButton(false);
        $action_onDeleteDetailFerias->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailFerias->setLabel("Excluir");
        $action_onDeleteDetailFerias->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailFerias->setFields(['__row__id', '__row__data']);

        $this->ferias_colaborador_list->addAction($action_onDeleteDetailFerias);

        $this->ferias_colaborador_list->addColumn($column_ferias_colaborador_dt_inicio);
        $this->ferias_colaborador_list->addColumn($column_ferias_colaborador_dt_fim);
        $this->ferias_colaborador_list->addColumn($column_ferias_colaborador_status_ferias);

        $this->ferias_colaborador_list->addColumn($column_ferias_colaborador__row__data);

        $this->ferias_colaborador_list->createModel();
        $this->detailFormFeriasColaborador->addContent([$this->ferias_colaborador_list]);

        $row29 = $this->form->addFields([$this->detailFormFeriasColaborador]);
        $row29->layout = [' col-sm-3 col-lg-12'];

        $this->form->appendPage("Advertências");

        $this->detailFormAdvertenciaColaborador = new BootstrapFormBuilder('detailFormAdvertenciaColaborador');
        $this->detailFormAdvertenciaColaborador->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormAdvertenciaColaborador->setProperty('class', 'form-horizontal builder-detail-form');

        $row30 = $this->detailFormAdvertenciaColaborador->addFields([new TFormSeparator("Advertências", '#333333', '18', '#FF0091')]);
        $row30->layout = [' col-sm-12'];

        $row31 = $this->detailFormAdvertenciaColaborador->addFields([new TLabel("Motivo:", null, '14px', null, '100%'),$advertencia_colaborador_id],[$advertencia_colaborador_motivo],[new TLabel("Data:", null, '14px', null, '100%')],[$advertencia_colaborador_dt_advertencia]);
        $row31->layout = [' col-sm-6 col-lg-1 control-label',' col-sm-2 col-lg-8',' col-sm-6 col-lg-1 control-label','col-sm-2'];

        $row32 = $this->detailFormAdvertenciaColaborador->addFields([],[$button_adicionar_advertencia_colaborador],[]);
        $row32->layout = [' col-sm-2 col-lg-5','col-sm-2',' col-sm-2 col-lg-5'];

        $row33 = $this->detailFormAdvertenciaColaborador->addFields([new THidden('advertencia_colaborador__row__id')]);
        $this->advertencia_colaborador_criteria = new TCriteria();

        $this->advertencia_colaborador_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->advertencia_colaborador_list->disableHtmlConversion();;
        $this->advertencia_colaborador_list->generateHiddenFields();
        $this->advertencia_colaborador_list->setId('advertencia_colaborador_list');

        $this->advertencia_colaborador_list->style = 'width:100%';
        $this->advertencia_colaborador_list->class .= ' table-bordered';

        $column_advertencia_colaborador_motivo = new TDataGridColumn('motivo', "Motivo", 'left');
        $column_advertencia_colaborador_dt_advertencia = new TDataGridColumn('dt_advertencia', "Data:", 'left');

        $column_advertencia_colaborador__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_advertencia_colaborador__row__data->setVisibility(false);

        $action_onEditDetailAdvertencia = new TDataGridAction(array('ColaboradorForm', 'onEditDetailAdvertencia'));
        $action_onEditDetailAdvertencia->setUseButton(false);
        $action_onEditDetailAdvertencia->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailAdvertencia->setLabel("Editar");
        $action_onEditDetailAdvertencia->setImage('far:edit #478fca');
        $action_onEditDetailAdvertencia->setFields(['__row__id', '__row__data']);

        $this->advertencia_colaborador_list->addAction($action_onEditDetailAdvertencia);
        $action_onDeleteDetailAdvertencia = new TDataGridAction(array('ColaboradorForm', 'onDeleteDetailAdvertencia'));
        $action_onDeleteDetailAdvertencia->setUseButton(false);
        $action_onDeleteDetailAdvertencia->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailAdvertencia->setLabel("Excluir");
        $action_onDeleteDetailAdvertencia->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailAdvertencia->setFields(['__row__id', '__row__data']);

        $this->advertencia_colaborador_list->addAction($action_onDeleteDetailAdvertencia);

        $this->advertencia_colaborador_list->addColumn($column_advertencia_colaborador_motivo);
        $this->advertencia_colaborador_list->addColumn($column_advertencia_colaborador_dt_advertencia);

        $this->advertencia_colaborador_list->addColumn($column_advertencia_colaborador__row__data);

        $this->advertencia_colaborador_list->createModel();
        $this->detailFormAdvertenciaColaborador->addContent([$this->advertencia_colaborador_list]);

        $row34 = $this->form->addFields([$this->detailFormAdvertenciaColaborador]);
        $row34->layout = [' col-sm-3 col-lg-12'];

        $this->form->appendPage("Documentos");

        $this->detailFormDocumentoColaborador = new BootstrapFormBuilder('detailFormDocumentoColaborador');
        $this->detailFormDocumentoColaborador->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormDocumentoColaborador->setProperty('class', 'form-horizontal builder-detail-form');

        $row35 = $this->detailFormDocumentoColaborador->addFields([new TFormSeparator("Envio de documentação do funcionário", '#333', '18', '#FF0091')]);
        $row35->layout = [' col-sm-12'];

        $row36 = $this->detailFormDocumentoColaborador->addFields([new TLabel("Descricao:", null, '14px', null, '100%'),$documento_colaborador_id],[$documento_colaborador_descricao],[new TLabel("Data Registro:", null, '14px', null, '100%')],[$documento_colaborador_dt_registro],[new TLabel("Tipo documento:", null, '14px', null, '100%')],[$documento_colaborador_tipo_documento]);
        $row36->layout = [' col-sm-6 col-lg-1 control-label',' col-sm-2 col-lg-5',' col-sm-6 col-lg-1 control-label',' col-sm-2 col-lg-2',' col-sm-2 col-lg-1 control-label',' col-sm-2 col-lg-2'];

        $row37 = $this->detailFormDocumentoColaborador->addFields([],[$button_adicionar_documento_colaborador],[]);
        $row37->layout = [' col-sm-2 col-lg-5','col-sm-2',' col-sm-2 col-lg-5'];

        $row38 = $this->detailFormDocumentoColaborador->addFields([new THidden('documento_colaborador__row__id')]);
        $this->documento_colaborador_criteria = new TCriteria();

        $this->documento_colaborador_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->documento_colaborador_list->disableHtmlConversion();;
        $this->documento_colaborador_list->generateHiddenFields();
        $this->documento_colaborador_list->setId('documento_colaborador_list');

        $this->documento_colaborador_list->style = 'width:100%';
        $this->documento_colaborador_list->class .= ' table-bordered';

        $column_documento_colaborador_descricao = new TDataGridColumn('descricao', "Descricao", 'left');
        $column_documento_colaborador_dt_registro = new TDataGridColumn('dt_registro', "Dt registro", 'left');
        $column_documento_colaborador_tipo_documento = new TDataGridColumn('tipo_documento', "Tipo documento", 'left');
        $column_documento_colaborador_link_scan_documento = new TDataGridColumn('link_scan_documento', "Link scan documento", 'left');

        $column_documento_colaborador__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_documento_colaborador__row__data->setVisibility(false);

        $action_onEditDetailDocumento = new TDataGridAction(array('ColaboradorForm', 'onEditDetailDocumento'));
        $action_onEditDetailDocumento->setUseButton(false);
        $action_onEditDetailDocumento->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailDocumento->setLabel("Editar");
        $action_onEditDetailDocumento->setImage('far:edit #478fca');
        $action_onEditDetailDocumento->setFields(['__row__id', '__row__data']);

        $this->documento_colaborador_list->addAction($action_onEditDetailDocumento);
        $action_onDeleteDetailDocumento = new TDataGridAction(array('ColaboradorForm', 'onDeleteDetailDocumento'));
        $action_onDeleteDetailDocumento->setUseButton(false);
        $action_onDeleteDetailDocumento->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailDocumento->setLabel("Excluir");
        $action_onDeleteDetailDocumento->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailDocumento->setFields(['__row__id', '__row__data']);

        $this->documento_colaborador_list->addAction($action_onDeleteDetailDocumento);

        $this->documento_colaborador_list->addColumn($column_documento_colaborador_descricao);
        $this->documento_colaborador_list->addColumn($column_documento_colaborador_dt_registro);
        $this->documento_colaborador_list->addColumn($column_documento_colaborador_tipo_documento);
        $this->documento_colaborador_list->addColumn($column_documento_colaborador_link_scan_documento);

        $this->documento_colaborador_list->addColumn($column_documento_colaborador__row__data);

        $this->documento_colaborador_list->createModel();
        $this->detailFormDocumentoColaborador->addContent([$this->documento_colaborador_list]);

        $row39 = $this->form->addFields([$this->detailFormDocumentoColaborador]);
        $row39->layout = [' col-sm-3 col-lg-12'];

        $this->form->appendPage("Compras");

        $this->detailFormFk = new BootstrapFormBuilder('detailFormFk');
        $this->detailFormFk->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormFk->setProperty('class', 'form-horizontal builder-detail-form');

        $row40 = $this->detailFormFk->addFields([new TFormSeparator("Compras do funcionário", '#333', '18', '#FF0091')]);
        $row40->layout = [' col-sm-12'];

        $row41 = $this->detailFormFk->addFields([new THidden('_fk___row__id')]);
        $this->_fk__criteria = new TCriteria();

        $this->_fk__list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->_fk__list->disableHtmlConversion();;
        $this->_fk__list->generateHiddenFields();
        $this->_fk__list->setId('_fk__list');

        $this->_fk__list->disableDefaultClick();
        $this->_fk__list->style = 'width:100%';
        $this->_fk__list->class .= ' table-bordered';

        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Nº venda", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Data", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Loja", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Funcionário caixa", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Forma pagamento", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Total desconto", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Valor total", 'left');
        $column_venda_cliente_ = new TDataGridColumn('venda->cliente->', "Status", 'left');

        $column__fk___row__data = new TDataGridColumn('__row__data', '', 'center');
        $column__fk___row__data->setVisibility(false);

        $action_onDeleteDetailVenda = new TDataGridAction(array('ColaboradorForm', 'onDeleteDetailVenda'));
        $action_onDeleteDetailVenda->setUseButton(false);
        $action_onDeleteDetailVenda->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailVenda->setLabel("Excluir");
        $action_onDeleteDetailVenda->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailVenda->setFields(['__row__id', '__row__data']);
        $action_onDeleteDetailVenda->setDisplayCondition('ColaboradorForm::isDeletable');

        $this->_fk__list->addAction($action_onDeleteDetailVenda);

        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);
        $this->_fk__list->addColumn($column_venda_cliente_);

        $this->_fk__list->addColumn($column__fk___row__data);

        $this->_fk__list->createModel();
        $this->detailFormFk->addContent([$this->_fk__list]);

        $row42 = $this->form->addFields([$this->detailFormFk]);
        $row42->layout = [' col-sm-3 col-lg-12'];

        $this->form->appendPage("Transferência de loja");

        $this->detailFormTransferenciaColaboradorColaborador = new BootstrapFormBuilder('detailFormTransferenciaColaboradorColaborador');
        $this->detailFormTransferenciaColaboradorColaborador->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormTransferenciaColaboradorColaborador->setProperty('class', 'form-horizontal builder-detail-form');

        $row43 = $this->detailFormTransferenciaColaboradorColaborador->addFields([new TFormSeparator("Detail", '#333', '18', '#FF0091')]);
        $row43->layout = [' col-sm-12'];

        $row44 = $this->detailFormTransferenciaColaboradorColaborador->addFields([new TLabel("Loja origem:", null, '14px', null, '100%'),$transferencia_colaborador_colaborador_id],[$transferencia_colaborador_colaborador_loja_origem],[new TLabel("Loja destino:", null, '14px', null, '100%')],[$transferencia_colaborador_colaborador_loja_destino],[new TLabel("Data transferencia:", null, '14px', null, '100%')],[$transferencia_colaborador_colaborador_dt_transferencia],[new TLabel("Motivo transferencia:", null, '14px', null, '100%')],[$transferencia_colaborador_colaborador_motivo_transferencia]);
        $row44->layout = [' col-sm-6 col-lg-1 control-label','col-sm-2',' col-sm-6 col-lg-1 control-label','col-sm-2',' col-sm-2 col-lg-1 control-label','col-sm-2',' col-sm-2 col-lg-1 control-label','col-sm-2'];

        $row45 = $this->detailFormTransferenciaColaboradorColaborador->addFields([],[$button_adicionar_transferencia_colaborador_colaborador],[]);
        $row45->layout = [' col-sm-2 col-lg-5','col-sm-2',' col-sm-2 col-lg-5'];

        $row46 = $this->detailFormTransferenciaColaboradorColaborador->addFields([new THidden('transferencia_colaborador_colaborador__row__id')]);
        $this->transferencia_colaborador_colaborador_criteria = new TCriteria();

        $this->transferencia_colaborador_colaborador_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->transferencia_colaborador_colaborador_list->disableHtmlConversion();;
        $this->transferencia_colaborador_colaborador_list->generateHiddenFields();
        $this->transferencia_colaborador_colaborador_list->setId('transferencia_colaborador_colaborador_list');

        $this->transferencia_colaborador_colaborador_list->style = 'width:100%';
        $this->transferencia_colaborador_colaborador_list->class .= ' table-bordered';

        $column_transferencia_colaborador_colaborador_fk_loja_origem_razao_social = new TDataGridColumn('fk_loja_origem->razao_social', "Loja origem", 'left');
        $column_transferencia_colaborador_colaborador_fk_loja_destino_razao_social = new TDataGridColumn('fk_loja_destino->razao_social', "Loja destino", 'left');
        $column_transferencia_colaborador_colaborador_dt_transferencia = new TDataGridColumn('dt_transferencia', "Dt transferencia", 'left');
        $column_transferencia_colaborador_colaborador_motivo_transferencia = new TDataGridColumn('motivo_transferencia', "Motivo transferencia", 'left');

        $column_transferencia_colaborador_colaborador__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_transferencia_colaborador_colaborador__row__data->setVisibility(false);

        $action_onEditDetailTransferenciaColaborador = new TDataGridAction(array('ColaboradorForm', 'onEditDetailTransferenciaColaborador'));
        $action_onEditDetailTransferenciaColaborador->setUseButton(false);
        $action_onEditDetailTransferenciaColaborador->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailTransferenciaColaborador->setLabel("Editar");
        $action_onEditDetailTransferenciaColaborador->setImage('far:edit #478fca');
        $action_onEditDetailTransferenciaColaborador->setFields(['__row__id', '__row__data']);

        $this->transferencia_colaborador_colaborador_list->addAction($action_onEditDetailTransferenciaColaborador);
        $action_onDeleteDetailTransferenciaColaborador = new TDataGridAction(array('ColaboradorForm', 'onDeleteDetailTransferenciaColaborador'));
        $action_onDeleteDetailTransferenciaColaborador->setUseButton(false);
        $action_onDeleteDetailTransferenciaColaborador->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailTransferenciaColaborador->setLabel("Excluir");
        $action_onDeleteDetailTransferenciaColaborador->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailTransferenciaColaborador->setFields(['__row__id', '__row__data']);

        $this->transferencia_colaborador_colaborador_list->addAction($action_onDeleteDetailTransferenciaColaborador);

        $this->transferencia_colaborador_colaborador_list->addColumn($column_transferencia_colaborador_colaborador_fk_loja_origem_razao_social);
        $this->transferencia_colaborador_colaborador_list->addColumn($column_transferencia_colaborador_colaborador_fk_loja_destino_razao_social);
        $this->transferencia_colaborador_colaborador_list->addColumn($column_transferencia_colaborador_colaborador_dt_transferencia);
        $this->transferencia_colaborador_colaborador_list->addColumn($column_transferencia_colaborador_colaborador_motivo_transferencia);

        $this->transferencia_colaborador_colaborador_list->addColumn($column_transferencia_colaborador_colaborador__row__data);

        $this->transferencia_colaborador_colaborador_list->createModel();
        $this->detailFormTransferenciaColaboradorColaborador->addContent([$this->transferencia_colaborador_colaborador_list]);

        $row47 = $this->form->addFields([$this->detailFormTransferenciaColaboradorColaborador]);
        $row47->layout = [' col-sm-3 col-lg-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Recursos Humanos","Cadastro Funcionário"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangeEstado($param = null) 
    {
        try 
        {
            $estado         = $param['estado_id'];
            $combo_estado   = [];
            if($estado != ""){
              TTransaction::open(self::$database);
              $estado_busca = Cidade::where("estado_id",'=',$estado)->load();
              if($estado_busca){
                  foreach($estado_busca as $cidade){
                      $combo_cidade[$cidade->id] = $cidade->nome;
                  }
                  TCombo::reload(self::$formName, 'cidade_id', $combo_cidade, true);
              }else{
                  new TMessage ('info','estado sem uma cidade cadastrada');
              }
              TTransaction::close();
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeLoja($param = null) 
    {
        try 
        {
            $registro   = $param['dt_registro'];
            $registro   = self::dateEmMysql($registro);
            $contrato1  = new DateTime($registro);
            $contrato1  = $contrato1->modify('+1 month');
            $contrato2  = new DateTime($registro);
            $contrato2  = $contrato2->modify('+3 month');

            $object = new stdClass();
            $object->contrato1 = $contrato1->format('d/m/Y');
            $object->contrato2 = $contrato2->format('d/m/Y');

            TForm::sendData(self::$formName, $object);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeCargo($param = null) 
    {
        try 
        {
            $cargo = $param['cargo'];
            if($cargo){
                TTransaction::open(self::$database);
                $stdClass               = new StdClass();
                $cargo                  = new Cargo($cargo);
                $escala                 = new Escala($cargo->escala);

                $stdClass->salario      = $cargo->salario;
                $stdClass->escala       = $cargo->escala;
                $stdClass->hora_diaria  = $escala->carga_horaria_diaria;
                TForm::sendData(self::$formName, $stdClass);

                TTransaction::close();
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeEscala($param = null) 
    {
        try 
        {
            $escala = $param['escala'];
            if($escala){
                TTransaction::open(self::$database);
                $stdClass               = new StdClass();
                $escala                 = new Escala($escala->escala);
                $stdClass->hora_diaria  = $escala->carga_horaria_diaria;
                TForm::sendData(self::$formName, $stdClass);

                TTransaction::close();
            }

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailAsoColaborador($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields["aso_colaborador_tipo_aso"] = "Tipo aso";
            $requiredFields["aso_colaborador_status"] = "Status";
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

            $__row__id = !empty($data->aso_colaborador__row__id) ? $data->aso_colaborador__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new Aso();
            $grid_data->__row__id = $__row__id;
            $grid_data->id = $data->aso_colaborador_id;
            $grid_data->dt_realizado = $data->aso_colaborador_dt_realizado;
            $grid_data->tipo_aso = $data->aso_colaborador_tipo_aso;
            $grid_data->status = $data->aso_colaborador_status;
            $grid_data->vencimento = $data->aso_colaborador_vencimento;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['id'] =  $param['aso_colaborador_id'] ?? null;
            $__row__data['__display__']['dt_realizado'] =  $param['aso_colaborador_dt_realizado'] ?? null;
            $__row__data['__display__']['tipo_aso'] =  $param['aso_colaborador_tipo_aso'] ?? null;
            $__row__data['__display__']['status'] =  $param['aso_colaborador_status'] ?? null;
            $__row__data['__display__']['vencimento'] =  $param['aso_colaborador_vencimento'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->aso_colaborador_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('aso_colaborador_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->aso_colaborador_id = '';
            $data->aso_colaborador_dt_realizado = '';
            $data->aso_colaborador_tipo_aso = '';
            $data->aso_colaborador_status = '';
            $data->aso_colaborador_vencimento = '';
            $data->aso_colaborador__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#611433b0dc65b');
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

    public  function onAddDetailAtestadoColaborador($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields["atestado_colaborador_dt_atestado"] = "Dt atestado";
            $requiredFields["atestado_colaborador_dias"] = "Dias";
            $requiredFields["atestado_colaborador_motivo"] = "Motivo";
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

            $__row__id = !empty($data->atestado_colaborador__row__id) ? $data->atestado_colaborador__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new Atestado();
            $grid_data->__row__id = $__row__id;
            $grid_data->id = $data->atestado_colaborador_id;
            $grid_data->dt_atestado = $data->atestado_colaborador_dt_atestado;
            $grid_data->dias = $data->atestado_colaborador_dias;
            $grid_data->motivo = $data->atestado_colaborador_motivo;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['id'] =  $param['atestado_colaborador_id'] ?? null;
            $__row__data['__display__']['dt_atestado'] =  $param['atestado_colaborador_dt_atestado'] ?? null;
            $__row__data['__display__']['dias'] =  $param['atestado_colaborador_dias'] ?? null;
            $__row__data['__display__']['motivo'] =  $param['atestado_colaborador_motivo'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->atestado_colaborador_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('atestado_colaborador_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->atestado_colaborador_id = '';
            $data->atestado_colaborador_dt_atestado = '';
            $data->atestado_colaborador_dias = '';
            $data->atestado_colaborador_motivo = '';
            $data->atestado_colaborador__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#6114358fdc65d');
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

    public  function onAddDetailFeriasColaborador($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields["ferias_colaborador_status_ferias"] = "Status ferias";
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

            $__row__id = !empty($data->ferias_colaborador__row__id) ? $data->ferias_colaborador__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new Ferias();
            $grid_data->__row__id = $__row__id;
            $grid_data->id = $data->ferias_colaborador_id;
            $grid_data->dt_inicio = $data->ferias_colaborador_dt_inicio;
            $grid_data->dt_fim = $data->ferias_colaborador_dt_fim;
            $grid_data->status_ferias = $data->ferias_colaborador_status_ferias;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['id'] =  $param['ferias_colaborador_id'] ?? null;
            $__row__data['__display__']['dt_inicio'] =  $param['ferias_colaborador_dt_inicio'] ?? null;
            $__row__data['__display__']['dt_fim'] =  $param['ferias_colaborador_dt_fim'] ?? null;
            $__row__data['__display__']['status_ferias'] =  $param['ferias_colaborador_status_ferias'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->ferias_colaborador_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('ferias_colaborador_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->ferias_colaborador_id = '';
            $data->ferias_colaborador_dt_inicio = '';
            $data->ferias_colaborador_dt_fim = '';
            $data->ferias_colaborador_status_ferias = '';
            $data->ferias_colaborador__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#61152135dff02');
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

    public  function onAddDetailAdvertenciaColaborador($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields["advertencia_colaborador_motivo"] = "Motivo";
            $requiredFields["advertencia_colaborador_dt_advertencia"] = "Dt advertencia";
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

            $__row__id = !empty($data->advertencia_colaborador__row__id) ? $data->advertencia_colaborador__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new Advertencia();
            $grid_data->__row__id = $__row__id;
            $grid_data->id = $data->advertencia_colaborador_id;
            $grid_data->motivo = $data->advertencia_colaborador_motivo;
            $grid_data->dt_advertencia = $data->advertencia_colaborador_dt_advertencia;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['id'] =  $param['advertencia_colaborador_id'] ?? null;
            $__row__data['__display__']['motivo'] =  $param['advertencia_colaborador_motivo'] ?? null;
            $__row__data['__display__']['dt_advertencia'] =  $param['advertencia_colaborador_dt_advertencia'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->advertencia_colaborador_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('advertencia_colaborador_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->advertencia_colaborador_id = '';
            $data->advertencia_colaborador_motivo = '';
            $data->advertencia_colaborador_dt_advertencia = '';
            $data->advertencia_colaborador__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#61152a027273e');
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

    public  function onAddDetailDocumentoColaborador($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields["documento_colaborador_descricao"] = "Descricao";
            $requiredFields["documento_colaborador_dt_registro"] = "Dt registro";
            $requiredFields["documento_colaborador_tipo_documento"] = "Tipo documento";
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

            $__row__id = !empty($data->documento_colaborador__row__id) ? $data->documento_colaborador__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new Documento();
            $grid_data->__row__id = $__row__id;
            $grid_data->id = $data->documento_colaborador_id;
            $grid_data->descricao = $data->documento_colaborador_descricao;
            $grid_data->dt_registro = $data->documento_colaborador_dt_registro;
            $grid_data->tipo_documento = $data->documento_colaborador_tipo_documento;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['id'] =  $param['documento_colaborador_id'] ?? null;
            $__row__data['__display__']['descricao'] =  $param['documento_colaborador_descricao'] ?? null;
            $__row__data['__display__']['dt_registro'] =  $param['documento_colaborador_dt_registro'] ?? null;
            $__row__data['__display__']['tipo_documento'] =  $param['documento_colaborador_tipo_documento'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->documento_colaborador_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('documento_colaborador_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->documento_colaborador_id = '';
            $data->documento_colaborador_descricao = '';
            $data->documento_colaborador_dt_registro = date('d/m/Y');
            $data->documento_colaborador_tipo_documento = '';
            $data->documento_colaborador__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#61150ab735c66');
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

    public  function onAddDetailTransferenciaColaboradorColaborador($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields["transferencia_colaborador_colaborador_loja_origem"] = "Loja origem";
            $requiredFields["transferencia_colaborador_colaborador_loja_destino"] = "Loja destino";
            $requiredFields["transferencia_colaborador_colaborador_dt_transferencia"] = "Dt transferencia";
            $requiredFields["transferencia_colaborador_colaborador_motivo_transferencia"] = "Motivo transferencia";
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

            $__row__id = !empty($data->transferencia_colaborador_colaborador__row__id) ? $data->transferencia_colaborador_colaborador__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new TransferenciaColaborador();
            $grid_data->__row__id = $__row__id;
            $grid_data->id = $data->transferencia_colaborador_colaborador_id;
            $grid_data->loja_origem = $data->transferencia_colaborador_colaborador_loja_origem;
            $grid_data->loja_destino = $data->transferencia_colaborador_colaborador_loja_destino;
            $grid_data->dt_transferencia = $data->transferencia_colaborador_colaborador_dt_transferencia;
            $grid_data->motivo_transferencia = $data->transferencia_colaborador_colaborador_motivo_transferencia;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['id'] =  $param['transferencia_colaborador_colaborador_id'] ?? null;
            $__row__data['__display__']['loja_origem'] =  $param['transferencia_colaborador_colaborador_loja_origem'] ?? null;
            $__row__data['__display__']['loja_destino'] =  $param['transferencia_colaborador_colaborador_loja_destino'] ?? null;
            $__row__data['__display__']['dt_transferencia'] =  $param['transferencia_colaborador_colaborador_dt_transferencia'] ?? null;
            $__row__data['__display__']['motivo_transferencia'] =  $param['transferencia_colaborador_colaborador_motivo_transferencia'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->transferencia_colaborador_colaborador_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('transferencia_colaborador_colaborador_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->transferencia_colaborador_colaborador_id = '';
            $data->transferencia_colaborador_colaborador_loja_origem = '';
            $data->transferencia_colaborador_colaborador_loja_destino = '';
            $data->transferencia_colaborador_colaborador_dt_transferencia = '';
            $data->transferencia_colaborador_colaborador_motivo_transferencia = '';
            $data->transferencia_colaborador_colaborador__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#6115161735c73');
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

    public static function onEditDetailAso($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->aso_colaborador_id = $__row__data->__display__->id ?? null;
            $data->aso_colaborador_dt_realizado = $__row__data->__display__->dt_realizado ?? null;
            $data->aso_colaborador_tipo_aso = $__row__data->__display__->tipo_aso ?? null;
            $data->aso_colaborador_status = $__row__data->__display__->status ?? null;
            $data->aso_colaborador_vencimento = $__row__data->__display__->vencimento ?? null;
            $data->aso_colaborador__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#611433b0dc65b');
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
    public static function onEditDetailAtestado($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->atestado_colaborador_id = $__row__data->__display__->id ?? null;
            $data->atestado_colaborador_dt_atestado = $__row__data->__display__->dt_atestado ?? null;
            $data->atestado_colaborador_dias = $__row__data->__display__->dias ?? null;
            $data->atestado_colaborador_motivo = $__row__data->__display__->motivo ?? null;
            $data->atestado_colaborador__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#6114358fdc65d');
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
    public static function onDeleteDetailAtestado($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->atestado_colaborador_id = '';
            $data->atestado_colaborador_dt_atestado = '';
            $data->atestado_colaborador_dias = '';
            $data->atestado_colaborador_motivo = '';
            $data->atestado_colaborador__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('atestado_colaborador_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onEditDetailFerias($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->ferias_colaborador_id = $__row__data->__display__->id ?? null;
            $data->ferias_colaborador_dt_inicio = $__row__data->__display__->dt_inicio ?? null;
            $data->ferias_colaborador_dt_fim = $__row__data->__display__->dt_fim ?? null;
            $data->ferias_colaborador_status_ferias = $__row__data->__display__->status_ferias ?? null;
            $data->ferias_colaborador__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#61152135dff02');
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
    public static function onDeleteDetailFerias($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->ferias_colaborador_id = '';
            $data->ferias_colaborador_dt_inicio = '';
            $data->ferias_colaborador_dt_fim = '';
            $data->ferias_colaborador_status_ferias = '';
            $data->ferias_colaborador__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('ferias_colaborador_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onEditDetailAdvertencia($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->advertencia_colaborador_id = $__row__data->__display__->id ?? null;
            $data->advertencia_colaborador_motivo = $__row__data->__display__->motivo ?? null;
            $data->advertencia_colaborador_dt_advertencia = $__row__data->__display__->dt_advertencia ?? null;
            $data->advertencia_colaborador__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#61152a027273e');
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
    public static function onDeleteDetailAdvertencia($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->advertencia_colaborador_id = '';
            $data->advertencia_colaborador_motivo = '';
            $data->advertencia_colaborador_dt_advertencia = '';
            $data->advertencia_colaborador__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('advertencia_colaborador_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onEditDetailDocumento($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->documento_colaborador_id = $__row__data->__display__->id ?? null;
            $data->documento_colaborador_descricao = $__row__data->__display__->descricao ?? null;
            $data->documento_colaborador_dt_registro = $__row__data->__display__->dt_registro ?? null;
            $data->documento_colaborador_tipo_documento = $__row__data->__display__->tipo_documento ?? null;
            $data->documento_colaborador__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#61150ab735c66');
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
    public static function onDeleteDetailDocumento($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->documento_colaborador_id = '';
            $data->documento_colaborador_descricao = '';
            $data->documento_colaborador_dt_registro = '';
            $data->documento_colaborador_tipo_documento = '';
            $data->documento_colaborador__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('documento_colaborador_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailVenda($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->_fk___row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('_fk__list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function isDeletable($object)
    {
        try 
        {
            if(!is_numeric($object->id))
            {
                return true;
            }

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public static function onEditDetailTransferenciaColaborador($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->transferencia_colaborador_colaborador_id = $__row__data->__display__->id ?? null;
            $data->transferencia_colaborador_colaborador_loja_origem = $__row__data->__display__->loja_origem ?? null;
            $data->transferencia_colaborador_colaborador_loja_destino = $__row__data->__display__->loja_destino ?? null;
            $data->transferencia_colaborador_colaborador_dt_transferencia = $__row__data->__display__->dt_transferencia ?? null;
            $data->transferencia_colaborador_colaborador_motivo_transferencia = $__row__data->__display__->motivo_transferencia ?? null;
            $data->transferencia_colaborador_colaborador__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#6115161735c73');
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
    public static function onDeleteDetailTransferenciaColaborador($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->transferencia_colaborador_colaborador_id = '';
            $data->transferencia_colaborador_colaborador_loja_origem = '';
            $data->transferencia_colaborador_colaborador_loja_destino = '';
            $data->transferencia_colaborador_colaborador_dt_transferencia = '';
            $data->transferencia_colaborador_colaborador_motivo_transferencia = '';
            $data->transferencia_colaborador_colaborador__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('transferencia_colaborador_colaborador_list', $__row__data->__row__id);

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

            $this->form->validate(); // validate form data

            $object = new Colaborador(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $pessoa;
            if($data->pessoa_id){
                $pessoa = new Pessoa($data->pessoa_id);
            }else{
                $pessoa = new Pessoa();
            }
            $pessoa->fromArray( (array) $data); // 
            $data->pessoa_id = $pessoa->id;

            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            $advertencia_colaborador_items = $this->storeMasterDetailItems('Advertencia', 'colaborador_id', 'advertencia_colaborador', $object, $param['advertencia_colaborador_list___row__data'] ?? [], $this->form, $this->advertencia_colaborador_list, function($masterObject, $detailObject){ 

                //code here

            }); 

            $ferias_colaborador_items = $this->storeMasterDetailItems('Ferias', 'colaborador_id', 'ferias_colaborador', $object, $param['ferias_colaborador_list___row__data'] ?? [], $this->form, $this->ferias_colaborador_list, function($masterObject, $detailObject){ 

                //code here

            }); 

            $_fk__items = $this->storeMasterDetailItems('', '', '_fk_', $object, $param['_fk__list___row__data'] ?? [], $this->form, $this->_fk__list, function($masterObject, $detailObject){ 

                //code here

            }); 

            $transferencia_colaborador_colaborador_items = $this->storeMasterDetailItems('TransferenciaColaborador', 'colaborador_id', 'transferencia_colaborador_colaborador', $object, $param['transferencia_colaborador_colaborador_list___row__data'] ?? [], $this->form, $this->transferencia_colaborador_colaborador_list, function($masterObject, $detailObject){ 

                //code here

            }); 

            $documento_colaborador_items = $this->storeMasterDetailItems('Documento', 'colaborador_id', 'documento_colaborador', $object, $param['documento_colaborador_list___row__data'] ?? [], $this->form, $this->documento_colaborador_list, function($masterObject, $detailObject){ 

                //code here

            }); 

            $atestado_colaborador_items = $this->storeMasterDetailItems('Atestado', 'colaborador_id', 'atestado_colaborador', $object, $param['atestado_colaborador_list___row__data'] ?? [], $this->form, $this->atestado_colaborador_list, function($masterObject, $detailObject){ 

                //code here

            }); 

            $aso_colaborador_items = $this->storeMasterDetailItems('Aso', 'colaborador_id', 'aso_colaborador', $object, $param['aso_colaborador_list___row__data'] ?? [], $this->form, $this->aso_colaborador_list, function($masterObject, $detailObject){ 

                //code here

            }); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            new TMessage('info', "Registro salvo", $messageAction); 

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
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Colaborador($key); // instantiates the Active Record 
                $pessoas = Pessoa::where('colaborador_id','=',$key)->load();
                if($pessoas){
                    $pessoa = $pessoas[0];
                    $pessoa->pessoa_id  = $pessoa->id;
                    TForm::sendData(self::$formName, $pessoa);
                }
                                $object->pessoa_nome = $object->pessoa->nome;
                $object->pessoa_documento = $object->pessoa->documento;
                $object->pessoa_email = $object->pessoa->email;
                $object->pessoa_fone = $object->pessoa->fone;
                $object->pessoa_estado_id = $object->pessoa->estado_id;
                $object->pessoa_cidade_id = $object->pessoa->cidade_id;
                $object->pessoa_endereco = $object->pessoa->endereco;
                $object->pessoa_cep = $object->pessoa->cep;
                $object->pessoa_obs = $object->pessoa->obs;

                $advertencia_colaborador_items = $this->loadMasterDetailItems('Advertencia', 'colaborador_id', 'advertencia_colaborador', $object, $this->form, $this->advertencia_colaborador_list, $this->advertencia_colaborador_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $ferias_colaborador_items = $this->loadMasterDetailItems('Ferias', 'colaborador_id', 'ferias_colaborador', $object, $this->form, $this->ferias_colaborador_list, $this->ferias_colaborador_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $_fk__items = $this->loadMasterDetailItems('', '', '_fk_', $object, $this->form, $this->_fk__list, $this->_fk__criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $transferencia_colaborador_colaborador_items = $this->loadMasterDetailItems('TransferenciaColaborador', 'colaborador_id', 'transferencia_colaborador_colaborador', $object, $this->form, $this->transferencia_colaborador_colaborador_list, $this->transferencia_colaborador_colaborador_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $documento_colaborador_items = $this->loadMasterDetailItems('Documento', 'colaborador_id', 'documento_colaborador', $object, $this->form, $this->documento_colaborador_list, $this->documento_colaborador_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $atestado_colaborador_items = $this->loadMasterDetailItems('Atestado', 'colaborador_id', 'atestado_colaborador', $object, $this->form, $this->atestado_colaborador_list, $this->atestado_colaborador_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $aso_colaborador_items = $this->loadMasterDetailItems('Aso', 'colaborador_id', 'aso_colaborador', $object, $this->form, $this->aso_colaborador_list, $this->aso_colaborador_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

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

public static function dateEmMysql($dateSql){
    $ano= substr($dateSql, 6);
    $mes= substr($dateSql, 3,-5);
    $dia= substr($dateSql, 0,-8);
    return $ano."-".$mes."-".$dia;
}

}

