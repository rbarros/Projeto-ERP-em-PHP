<?php

class TransferenciaEtqForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'TransferenciaEtq';
    private static $primaryKey = 'id';
    private static $formName = 'form_TransferenciaEtq';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.60, null);
        parent::setTitle("Transferencia");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Transferencia");


        $deposito_env = new TDBCombo('deposito_env', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $estoque_id = new TDBUniqueSearch('estoque_id', 'base_banco', 'Produto', 'id', 'descricao','descricao asc'  );
        $qtd_atual = new TEntry('qtd_atual');
        $destino1 = new TDBCombo('destino1', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino2 = new TDBCombo('destino2', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino3 = new TDBCombo('destino3', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino4 = new TDBCombo('destino4', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino5 = new TDBCombo('destino5', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino6 = new TDBCombo('destino6', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino7 = new TDBCombo('destino7', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino8 = new TDBCombo('destino8', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino9 = new TDBCombo('destino9', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino10 = new TDBCombo('destino10', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino11 = new TDBCombo('destino11', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino12 = new TDBCombo('destino12', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino13 = new TDBCombo('destino13', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino14 = new TDBCombo('destino14', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino15 = new TDBCombo('destino15', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino16 = new TDBCombo('destino16', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino17 = new TDBCombo('destino17', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino18 = new TDBCombo('destino18', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino19 = new TDBCombo('destino19', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino20 = new TDBCombo('destino20', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino21 = new TDBCombo('destino21', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino22 = new TDBCombo('destino22', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino23 = new TDBCombo('destino23', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $destino24 = new TDBCombo('destino24', 'base_banco', 'Deposito', 'id', '{nome_deposito}','nome_deposito asc'  );
        $saldoDestino1 = new TEntry('saldoDestino1');
        $saldoDestino2 = new TEntry('saldoDestino2');
        $saldoDestino3 = new TEntry('saldoDestino3');
        $saldoDestino4 = new TEntry('saldoDestino4');
        $saldoDestino5 = new TEntry('saldoDestino5');
        $saldoDestino6 = new TEntry('saldoDestino6');
        $saldoDestino7 = new TEntry('saldoDestino7');
        $saldoDestino8 = new TEntry('saldoDestino8');
        $saldoDestino9 = new TEntry('saldoDestino9');
        $saldoDestino10 = new TEntry('saldoDestino10');
        $saldoDestino11 = new TEntry('saldoDestino11');
        $saldoDestino12 = new TEntry('saldoDestino12');
        $saldoDestino13 = new TEntry('saldoDestino13');
        $saldoDestino14 = new TEntry('saldoDestino14');
        $saldoDestino15 = new TEntry('saldoDestino15');
        $saldoDestino16 = new TEntry('saldoDestino16');
        $saldoDestino17 = new TEntry('saldoDestino17');
        $saldoDestino18 = new TEntry('saldoDestino18');
        $saldoDestino19 = new TEntry('saldoDestino19');
        $saldoDestino20 = new TEntry('saldoDestino20');
        $saldoDestino21 = new TEntry('saldoDestino21');
        $saldoDestino22 = new TEntry('saldoDestino22');
        $saldoDestino23 = new TEntry('saldoDestino23');
        $saldoDestino24 = new TEntry('saldoDestino24');
        $quantidade1 = new TEntry('quantidade1');
        $quantidade2 = new TEntry('quantidade2');
        $quantidade3 = new TEntry('quantidade3');
        $quantidade4 = new TEntry('quantidade4');
        $quantidade5 = new TEntry('quantidade5');
        $quantidade6 = new TEntry('quantidade6');
        $quantidade7 = new TEntry('quantidade7');
        $quantidade8 = new TEntry('quantidade8');
        $quantidade9 = new TEntry('quantidade9');
        $quantidade10 = new TEntry('quantidade10');
        $quantidade11 = new TEntry('quantidade11');
        $quantidade12 = new TEntry('quantidade12');
        $quantidade13 = new TEntry('quantidade13');
        $quantidade14 = new TEntry('quantidade14');
        $quantidade15 = new TEntry('quantidade15');
        $quantidade16 = new TEntry('quantidade16');
        $quantidade17 = new TEntry('quantidade17');
        $quantidade18 = new TEntry('quantidade18');
        $quantidade19 = new TEntry('quantidade19');
        $quantidade20 = new TEntry('quantidade20');
        $quantidade21 = new TEntry('quantidade21');
        $quantidade22 = new TEntry('quantidade22');
        $quantidade23 = new TEntry('quantidade23');
        $quantidade24 = new TEntry('quantidade24');

        $deposito_env->setChangeAction(new TAction([$this,'onChangeDeposito']));
        $estoque_id->setChangeAction(new TAction([$this,'onChangeProduto']));

        $quantidade1->setExitAction(new TAction([$this,'onQuantidadeUM']));
        $quantidade2->setExitAction(new TAction([$this,'onQuantidadeA']));
        $quantidade3->setExitAction(new TAction([$this,'onQuantidadeB']));
        $quantidade4->setExitAction(new TAction([$this,'onQuantidadeC']));
        $quantidade5->setExitAction(new TAction([$this,'onQuantidadeD']));
        $quantidade6->setExitAction(new TAction([$this,'onQuantidadeE']));
        $quantidade7->setExitAction(new TAction([$this,'onQuantidadeF']));
        $quantidade8->setExitAction(new TAction([$this,'onQuantidadeG']));
        $quantidade9->setExitAction(new TAction([$this,'onQuantidadeH']));
        $quantidade10->setExitAction(new TAction([$this,'onQuantidadeI']));
        $quantidade11->setExitAction(new TAction([$this,'onQuantidadeJ']));
        $quantidade12->setExitAction(new TAction([$this,'onQuantidadeK']));
        $quantidade13->setExitAction(new TAction([$this,'onQuantidadeL']));
        $quantidade14->setExitAction(new TAction([$this,'onQuantidadeM']));
        $quantidade15->setExitAction(new TAction([$this,'onQuantidadeN']));
        $quantidade16->setExitAction(new TAction([$this,'onQuantidadeO']));
        $quantidade17->setExitAction(new TAction([$this,'onQuantidadeP']));
        $quantidade18->setExitAction(new TAction([$this,'onQuantidadeQ']));
        $quantidade19->setExitAction(new TAction([$this,'onQuantidadeR']));
        $quantidade20->setExitAction(new TAction([$this,'onQuantidadeS']));
        $quantidade21->setExitAction(new TAction([$this,'onQuantidadeT']));
        $quantidade22->setExitAction(new TAction([$this,'onQuantidadeU']));
        $quantidade23->setExitAction(new TAction([$this,'onQuantidadeZ']));
        $quantidade24->setExitAction(new TAction([$this,'onQuantidadeV']));

        $deposito_env->addValidation("Deposito env", new TRequiredValidator()); 
        $estoque_id->addValidation("Produto id", new TRequiredValidator()); 
        $destino1->addValidation("Deposito rec", new TRequiredValidator()); 

        $estoque_id->setMinLength(2);

        $quantidade2->setMask('999999999');
        $quantidade3->setMask('999999999');
        $quantidade4->setMask('999999999');
        $quantidade5->setMask('999999999');
        $quantidade6->setMask('999999999');
        $quantidade7->setMask('999999999');
        $quantidade8->setMask('999999999');
        $quantidade9->setMask('999999999');
        $quantidade1->setMask('999999999');
        $quantidade24->setMask('999999999');
        $quantidade17->setMask('999999999');
        $quantidade22->setMask('999999999');
        $quantidade21->setMask('999999999');
        $quantidade20->setMask('999999999');
        $quantidade19->setMask('999999999');
        $quantidade18->setMask('999999999');
        $quantidade12->setMask('999999999');
        $quantidade16->setMask('999999999');
        $quantidade15->setMask('999999999');
        $quantidade14->setMask('999999999');
        $quantidade13->setMask('999999999');
        $quantidade11->setMask('999999999');
        $quantidade10->setMask('999999999');
        $estoque_id->setMask('{SKU} {descricao} {desc_variacao} {referencia} ');

        $destino1->setValue('9');
        $destino7->setValue('4');
        $destino24->setValue('2');
        $destino9->setValue('17');
        $destino21->setValue('1');
        $destino17->setValue('6');
        $destino14->setValue('5');
        $destino11->setValue('3');
        $destino12->setValue('7');
        $destino8->setValue('13');
        $destino6->setValue('11');
        $destino5->setValue('19');
        $destino4->setValue('20');
        $destino3->setValue('24');
        $destino2->setValue('22');
        $destino10->setValue('25');
        $destino13->setValue('10');
        $destino15->setValue('12');
        $destino16->setValue('18');
        $destino18->setValue('26');
        $destino19->setValue('15');
        $destino20->setValue('21');
        $destino22->setValue('23');
        $destino23->setValue('27');
        $deposito_env->setValue('2');

        $destino2->setEditable(false);
        $destino3->setEditable(false);
        $destino4->setEditable(false);
        $destino5->setEditable(false);
        $destino6->setEditable(false);
        $destino7->setEditable(false);
        $destino8->setEditable(false);
        $destino9->setEditable(false);
        $destino1->setEditable(false);
        $qtd_atual->setEditable(false);
        $destino18->setEditable(false);
        $destino23->setEditable(false);
        $destino22->setEditable(false);
        $destino21->setEditable(false);
        $destino20->setEditable(false);
        $destino19->setEditable(false);
        $destino24->setEditable(false);
        $destino17->setEditable(false);
        $destino15->setEditable(false);
        $destino14->setEditable(false);
        $destino13->setEditable(false);
        $destino12->setEditable(false);
        $destino11->setEditable(false);
        $destino10->setEditable(false);
        $destino16->setEditable(false);
        $saldoDestino6->setEditable(false);
        $saldoDestino8->setEditable(false);
        $saldoDestino7->setEditable(false);
        $saldoDestino9->setEditable(false);
        $saldoDestino5->setEditable(false);
        $saldoDestino3->setEditable(false);
        $saldoDestino2->setEditable(false);
        $saldoDestino1->setEditable(false);
        $saldoDestino4->setEditable(false);
        $saldoDestino18->setEditable(false);
        $saldoDestino23->setEditable(false);
        $saldoDestino22->setEditable(false);
        $saldoDestino21->setEditable(false);
        $saldoDestino20->setEditable(false);
        $saldoDestino19->setEditable(false);
        $saldoDestino15->setEditable(false);
        $saldoDestino17->setEditable(false);
        $saldoDestino16->setEditable(false);
        $saldoDestino14->setEditable(false);
        $saldoDestino13->setEditable(false);
        $saldoDestino12->setEditable(false);
        $saldoDestino11->setEditable(false);
        $saldoDestino10->setEditable(false);
        $saldoDestino24->setEditable(false);

        $destino6->setSize('100%');
        $destino1->setSize('100%');
        $destino2->setSize('100%');
        $destino3->setSize('100%');
        $destino4->setSize('100%');
        $destino5->setSize('100%');
        $destino7->setSize('100%');
        $destino8->setSize('100%');
        $destino9->setSize('100%');
        $destino21->setSize('100%');
        $destino19->setSize('100%');
        $destino20->setSize('100%');
        $destino23->setSize('100%');
        $destino22->setSize('100%');
        $destino17->setSize('100%');
        $destino24->setSize('100%');
        $destino18->setSize('100%');
        $destino14->setSize('100%');
        $destino16->setSize('100%');
        $destino15->setSize('100%');
        $destino13->setSize('100%');
        $destino12->setSize('100%');
        $destino11->setSize('100%');
        $destino10->setSize('100%');
        $qtd_atual->setSize('100%');
        $estoque_id->setSize('100%');
        $quantidade3->setSize('100%');
        $quantidade4->setSize('100%');
        $quantidade5->setSize('100%');
        $quantidade6->setSize('100%');
        $quantidade7->setSize('100%');
        $quantidade8->setSize('100%');
        $quantidade9->setSize('100%');
        $quantidade2->setSize('100%');
        $quantidade1->setSize('100%');
        $quantidade16->setSize('100%');
        $quantidade15->setSize('100%');
        $quantidade14->setSize('100%');
        $quantidade17->setSize('100%');
        $quantidade13->setSize('100%');
        $quantidade12->setSize('100%');
        $quantidade11->setSize('100%');
        $quantidade10->setSize('100%');
        $quantidade18->setSize('100%');
        $quantidade19->setSize('100%');
        $quantidade20->setSize('100%');
        $quantidade21->setSize('100%');
        $quantidade22->setSize('100%');
        $quantidade23->setSize('100%');
        $deposito_env->setSize('100%');
        $quantidade24->setSize('100%');
        $saldoDestino1->setSize('100%');
        $saldoDestino2->setSize('100%');
        $saldoDestino3->setSize('100%');
        $saldoDestino4->setSize('100%');
        $saldoDestino5->setSize('100%');
        $saldoDestino6->setSize('100%');
        $saldoDestino7->setSize('100%');
        $saldoDestino8->setSize('100%');
        $saldoDestino9->setSize('100%');
        $saldoDestino24->setSize('100%');
        $saldoDestino10->setSize('100%');
        $saldoDestino12->setSize('100%');
        $saldoDestino23->setSize('100%');
        $saldoDestino13->setSize('100%');
        $saldoDestino14->setSize('100%');
        $saldoDestino15->setSize('100%');
        $saldoDestino16->setSize('100%');
        $saldoDestino17->setSize('100%');
        $saldoDestino18->setSize('100%');
        $saldoDestino19->setSize('100%');
        $saldoDestino20->setSize('100%');
        $saldoDestino21->setSize('100%');
        $saldoDestino22->setSize('100%');
        $saldoDestino11->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Deposito origem: ", null, '14px', 'B', '100%'),$deposito_env],[new TLabel("Produto:", null, '14px', 'B', '100%'),$estoque_id],[new TLabel("Saldo atual:", null, '14px', 'B'),$qtd_atual]);
        $row1->layout = [' col-sm-4',' col-sm-6','col-sm-2'];

        $row2 = $this->form->addContent([new TFormSeparator("", '#333333', '18', '#ff0091')]);
        $row3 = $this->form->addFields([new TLabel("Deposito destino 1:", null, '14px', null, '100%'),$destino1,new TLabel("Deposito destino 2:", null, '14px', null, '100%'),$destino2,new TLabel("Deposito destino 3:", null, '14px', null, '100%'),$destino3,new TLabel("Deposito destino 4:", null, '14px', null, '100%'),$destino4,new TLabel("Deposito destino 5:", null, '14px', null, '100%'),$destino5,new TLabel("Deposito destino 6:", null, '14px', null, '100%'),$destino6,new TLabel("Deposito destino 7:", null, '14px', null, '100%'),$destino7,new TLabel("Deposito destino 8:", null, '14px', null, '100%'),$destino8,new TLabel("Deposito destino 9:", null, '14px', null, '100%'),$destino9,new TLabel("Deposito destino 10:", null, '14px', null, '100%'),$destino10,new TLabel("Deposito destino 11:", null, '14px', null, '100%'),$destino11,new TLabel("Deposito destino 12:", null, '14px', null, '100%'),$destino12,new TLabel("Deposito destino 13", null, '14px', null, '100%'),$destino13,new TLabel("Deposito destino 14:", null, '14px', null, '100%'),$destino14,new TLabel("Deposito destino 15:", null, '14px', null, '100%'),$destino15,new TLabel("Deposito destino 16:", null, '14px', null, '100%'),$destino16,new TLabel("Deposito destino 17:", null, '14px', null, '100%'),$destino17,new TLabel("Deposito destino 18:", null, '14px', null, '100%'),$destino18,new TLabel("Deposito destino 19:", null, '14px', null),$destino19,new TLabel("Deposito destino 20:", null, '14px', null, '100%'),$destino20,new TLabel("Deposito destino 21:", null, '14px', null, '100%'),$destino21,new TLabel("Deposito destino 22:", null, '14px', null, '100%'),$destino22,new TLabel("deposito destino 23:", null, '14px', null, '100%'),$destino23,new TLabel("Deposito destino 24:", null, '14px', 'B', '100%'),$destino24],[new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino1,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino2,new TLabel("Saldo", null, '14px', null, '100%'),$saldoDestino3,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino4,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino5,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino6,new TLabel("Saldo", null, '14px', null, '100%'),$saldoDestino7,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino8,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino9,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino10,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino11,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino12,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino13,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino14,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino15,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino16,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino17,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino18,new TLabel("Saldo:", null, '14px', null),$saldoDestino19,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino20,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino21,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino22,new TLabel("Saldo:", null, '14px', null, '100%'),$saldoDestino23,new TLabel("Saldo:", null, '14px', 'B', '100%'),$saldoDestino24],[new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade1,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade2,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade3,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade4,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade5,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade6,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade7,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade8,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade9,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade10,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade11,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade12,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade13,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade14,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade15,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade16,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade17,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade18,new TLabel("Qtd.:", null, '14px', null),$quantidade19,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade20,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade21,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade22,new TLabel("Qtd.:", null, '14px', null, '100%'),$quantidade23,new TLabel("Qtd.:", null, '14px', 'B', '100%'),$quantidade24]);
        $row3->layout = [' col-sm-8','col-sm-2',' col-sm-2'];

        $row4 = $this->form->addContent([new TFormSeparator(" ", '#333333', '18', '#ff0091')]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Transferir", new TAction([$this, 'onSave']), 'fas:exchange-alt #0016ff');
        $this->btn_onsave = $btn_onsave;

        $btn_onlimpar = $this->form->addAction("Limpar Quantidades", new TAction([$this, 'onLimpar']), 'fas:eraser #dd5a43');
        $this->btn_onlimpar = $btn_onlimpar;


        $style = new TStyle('window');
        $style->width = '50% !important';   
        $style->show();

        parent::add($this->form);

    }

    public static function onQuantidadeUM($param = null) 
    {
        try 
        {
            $produto = $param['estoque_id'];
            $deposito_origem = $param['deposito_env'];
            TTransaction::open(self::$database);
            $estoques = ProdEstoque::where('id_produto','=',$produto)
                                  ->where('id_deposito','=',$deposito_origem)
                                  ->load();
            if($estoques){
                $estoque = $estoques[0];
                $qtd_atual = $estoque->quantidade;
            }
            $total=0;
            $total += intval($param['quantidade1']);
            $total += intval($param['quantidade2']);
            $total += intval($param['quantidade3']);
            $total += intval($param['quantidade4']);
            $total += intval($param['quantidade5']);
            $total += intval($param['quantidade6']);
            $total += intval($param['quantidade7']);
            $total += intval($param['quantidade8']);
            $total += intval($param['quantidade9']);
            $total += intval($param['quantidade10']);
            $total += intval($param['quantidade11']);
            $total += intval($param['quantidade12']);
            $total += intval($param['quantidade13']);
            $total += intval($param['quantidade14']);
            $total += intval($param['quantidade15']);
            $total += intval($param['quantidade16']);
            $total += intval($param['quantidade17']);
            $total += intval($param['quantidade18']);
            $total += intval($param['quantidade19']);
            $total += intval($param['quantidade20']);
            $total += intval($param['quantidade21']);
            $total += intval($param['quantidade22']);
            $total += intval($param['quantidade23']);
            $total += intval($param['quantidade24']);
            $object = new stdClass();

            $object->qtd_atual = $qtd_atual - $total;

            TForm::sendData(self::$formName, $object);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeA($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeB($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeC($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeD($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeE($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeF($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeG($param = null) 
    {
        try 
        {
           self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeH($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeI($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeJ($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeK($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeL($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeM($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeN($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeO($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeP($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeQ($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeR($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeS($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeT($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeU($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeZ($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onQuantidadeV($param = null) 
    {
        try 
        {
            self::onQuantidadeUM($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeDeposito($param = null) 
    {
        try 
        {
            //função para bloquear o campo do deposito escolhido
            $deposito_origem = $param['deposito_env'];
                    TDBCombo::enableField(self::$formName, 'quantidade21');
                    TDBCombo::enableField(self::$formName, 'quantidade23');
                    TDBCombo::enableField(self::$formName, 'quantidade11');
                    TDBCombo::enableField(self::$formName, 'quantidade7');
                    TDBCombo::enableField(self::$formName, 'quantidade14');
                    TDBCombo::enableField(self::$formName, 'quantidade17');
                    TDBCombo::enableField(self::$formName, 'quantidade12');
                    TDBCombo::enableField(self::$formName, 'quantidade1');
                    TDBCombo::enableField(self::$formName, 'quantidade13');
                    TDBCombo::enableField(self::$formName, 'quantidade6');
                    TDBCombo::enableField(self::$formName, 'quantidade15');
                    TDBCombo::enableField(self::$formName, 'quantidade8');
                    TDBCombo::enableField(self::$formName, 'quantidade19');
                    TDBCombo::enableField(self::$formName, 'quantidade9');
                    TDBCombo::enableField(self::$formName, 'quantidade16');
                    TDBCombo::enableField(self::$formName, 'quantidade5');
                    TDBCombo::enableField(self::$formName, 'quantidade4');
                    TDBCombo::enableField(self::$formName, 'quantidade20');
                    TDBCombo::enableField(self::$formName, 'quantidade2');
                    TDBCombo::enableField(self::$formName, 'quantidade22');
                    TDBCombo::enableField(self::$formName, 'quantidade3');
                    TDBCombo::enableField(self::$formName, 'quantidade10');
                    TDBCombo::enableField(self::$formName, 'quantidade18'); 
                    TDBCombo::enableField(self::$formName, 'quantidade24'); 

            switch($deposito_origem){
                    case 1:TDBCombo::disableField(self::$formName, 'quantidade21');break;
                    case 2:TDBCombo::disableField(self::$formName, 'quantidade24');break;
                    case 3:TDBCombo::disableField(self::$formName, 'quantidade11');break;
                    case 4:TDBCombo::disableField(self::$formName, 'quantidade7');break;
                    case 5:TDBCombo::disableField(self::$formName, 'quantidade14');break;
                    case 6:TDBCombo::disableField(self::$formName, 'quantidade17');break;
                    case 7:TDBCombo::disableField(self::$formName, 'quantidade12');break;
                    case 9:TDBCombo::disableField(self::$formName, 'quantidade1');break;
                    case 10:TDBCombo::disableField(self::$formName, 'quantidade13');break;
                    case 11:TDBCombo::disableField(self::$formName, 'quantidade6');break;
                    case 12:TDBCombo::disableField(self::$formName, 'quantidade15');break;
                    case 13:TDBCombo::disableField(self::$formName, 'quantidade8');break;
                    case 15:TDBCombo::disableField(self::$formName, 'quantidade19');break;
                    case 17:TDBCombo::disableField(self::$formName, 'quantidade9');break;
                    case 18:TDBCombo::disableField(self::$formName, 'quantidade16');break;
                    case 19:TDBCombo::disableField(self::$formName, 'quantidade5');break;
                    case 20:TDBCombo::disableField(self::$formName, 'quantidade4');break;
                    case 21:TDBCombo::disableField(self::$formName, 'quantidade20');break;
                    case 22:TDBCombo::disableField(self::$formName, 'quantidade2');break;
                    case 23:TDBCombo::disableField(self::$formName, 'quantidade22');break;
                    case 24:TDBCombo::disableField(self::$formName, 'quantidade3');break;
                    case 25:TDBCombo::disableField(self::$formName, 'quantidade10');break;
                    case 26:TDBCombo::disableField(self::$formName, 'quantidade18');break;
                    case 26:TDBCombo::disableField(self::$formName, 'quantidade18');break;
                    case 27:TDBCombo::disableField(self::$formName, 'quantidade23');break;
                }
            self::onChangeProduto($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeProduto($param = null) 
    {
        try 
        {
            $produto = $param['estoque_id'];
            $deposito_origem = $param['deposito_env'];
            TTransaction::open(self::$database);
            $estoques = ProdEstoque::where('id_produto','=',$produto)
                                  ->where('id_deposito','=',$deposito_origem)
                                  ->load();
            $object = new stdClass();
            if($estoques){
                $estoque = $estoques[0];
                $object->qtd_atual = $estoque->quantidade;
                $object->saldoDestino1   = "novo estoque"; 
                $object->saldoDestino2   = "novo estoque"; 
                $object->saldoDestino3   = "novo estoque"; 
                $object->saldoDestino4   = "novo estoque"; 
                $object->saldoDestino5   = "novo estoque"; 
                $object->saldoDestino6   = "novo estoque"; 
                $object->saldoDestino7   = "novo estoque"; 
                $object->saldoDestino8   = "novo estoque"; 
                $object->saldoDestino9   = "novo estoque"; 
                $object->saldoDestino10  = "novo estoque"; 
                $object->saldoDestino11  = "novo estoque"; 
                $object->saldoDestino12  = "novo estoque"; 
                $object->saldoDestino13  = "novo estoque"; 
                $object->saldoDestino14  = "novo estoque"; 
                $object->saldoDestino15  = "novo estoque"; 
                $object->saldoDestino16  = "novo estoque"; 
                $object->saldoDestino17  = "novo estoque"; 
                $object->saldoDestino18  = "novo estoque"; 
                $object->saldoDestino19  = "novo estoque"; 
                $object->saldoDestino20  = "novo estoque"; 
                $object->saldoDestino21  = "novo estoque"; 
                $object->saldoDestino22  = "novo estoque"; 
                $object->saldoDestino23  = "novo estoque"; 
                $object->saldoDestino24  = "novo estoque"; 
            }else{
                if($produto!=""){
                new TMessage('info','Produto não possui estoque neste depósito');
                }
            }
            $estoques_destino = ProdEstoque::where('id_produto','=',$produto)->load();
            foreach($estoques_destino as $destino){
                switch($destino->id_deposito){
                    case 1:$object->saldoDestino21  = $destino->quantidade; break;
                    case 2:$object->saldoDestino24  = $destino->quantidade; break;
                    case 3:$object->saldoDestino11  = $destino->quantidade; break;
                    case 4:$object->saldoDestino7   = $destino->quantidade; break;
                    case 5:$object->saldoDestino14  = $destino->quantidade; break;
                    case 6:$object->saldoDestino17  = $destino->quantidade; break;
                    case 7:$object->saldoDestino12  = $destino->quantidade; break;
                    case 9:$object->saldoDestino1   = $destino->quantidade; break;
                    case 10:$object->saldoDestino13 = $destino->quantidade; break;
                    case 11:$object->saldoDestino6  = $destino->quantidade; break;
                    case 12:$object->saldoDestino15 = $destino->quantidade; break;
                    case 13:$object->saldoDestino8  = $destino->quantidade; break;
                    case 15:$object->saldoDestino19 = $destino->quantidade; break;
                    case 17:$object->saldoDestino9  = $destino->quantidade; break;
                    case 18:$object->saldoDestino16 = $destino->quantidade; break;
                    case 19:$object->saldoDestino5  = $destino->quantidade; break;
                    case 20:$object->saldoDestino4  = $destino->quantidade; break;
                    case 21:$object->saldoDestino20 = $destino->quantidade; break;
                    case 22:$object->saldoDestino2  = $destino->quantidade; break;
                    case 23:$object->saldoDestino22 = $destino->quantidade; break;
                    case 24:$object->saldoDestino3  = $destino->quantidade; break;
                    case 25:$object->saldoDestino10 = $destino->quantidade; break;
                    case 26:$object->saldoDestino18 = $destino->quantidade; break;
                    case 27:$object->saldoDestino23 = $destino->quantidade; break;
                    default:
                        throw new exeption("deposito não localizado");
                        break; 
                }
            }
            TForm::sendData(self::$formName, $object);
            TTransaction::close();

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
            $messageAction          = null;
            $this->form->validate(); // validate form data
            /*
            $object = new TransferenciaEtq(); // create an empty object 
            */
            $data                   = $this->form->getData(); // get form data as array
            $produto                = new Produto ($data->estoque_id);
            $produto_descricao      = $produto->descricao ." ".$produto->desc_variacao ." ".$produto->referencia;
            $usuario                = TSession::getValue('userid');
            $data_atual             = date('Y-m-d H:i');
            //somatório de trodas as transferencias
            $total=0;
            $total += intval($data->quantidade1);
            $total += intval($data->quantidade2);
            $total += intval($data->quantidade3);
            $total += intval($data->quantidade4);
            $total += intval($data->quantidade5);
            $total += intval($data->quantidade6);
            $total += intval($data->quantidade7);
            $total += intval($data->quantidade8);
            $total += intval($data->quantidade9);
            $total += intval($data->quantidade10);
            $total += intval($data->quantidade11);
            $total += intval($data->quantidade12);
            $total += intval($data->quantidade13);
            $total += intval($data->quantidade14);
            $total += intval($data->quantidade15);
            $total += intval($data->quantidade16);
            $total += intval($data->quantidade17);
            $total += intval($data->quantidade18);
            $total += intval($data->quantidade19);
            $total += intval($data->quantidade20);
            $total += intval($data->quantidade21);
            $total += intval($data->quantidade22);
            $total += intval($data->quantidade23);
            $total += intval($data->quantidade24);
            //Origem
            $estoque_origem;
            $deposito_origem = new Deposito($data->deposito_env);
            $estoques_origem = ProdEstoque::where('id_deposito','=',$data->deposito_env)
                                           ->where('id_produto','=',$data->estoque_id)
                                           ->load();
            if($estoques_origem && $estoques_origem[0]->quantidade>=$total){
                    $estoque_origem = $estoques_origem[0];
            }else{
              throw new Exception("quantidade de transferencia maior do que disponivel no estoque $deposito_origem->nome_deposito");   
            }
            $estoque_origem->produto_sku    = $produto->SKU;
            $estoque_origem->produto_nome   = $produto_descricao;
            //Destinos
            //----------------------------------DESTINO 1--------------------------------------------------------------------------
            if($data->quantidade1 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;
                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade1); 

                $destinos1 = ProdEstoque::where('id_deposito','=',$data->destino1)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos1){
                    //estoque quando existe
                    $destino1                    = $destinos1[0];
                    $destino1->quantidade        = intval($destino1->quantidade) + intval($data->quantidade1);
                    $destino1->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino1;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade1;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino1                    = new ProdEstoque();
                    $destino1->quantidade        = intval($destino1->quantidade) + intval($data->quantidade1);
                    $destino1->id_deposito       = $data->destino1;
                    $destino1->id_produto        = $produto->id;
                    $destino1->produto_sku       = $produto->SKU;
                    $destino1->produto_nome      = $produto_descricao;
                    $destino1->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino1;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade1;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 1--------------------------------------------------------------------------

            //----------------------------------DESTINO 2--------------------------------------------------------------------------
            if($data->quantidade2 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade2); 

                $destinos2 = ProdEstoque::where('id_deposito','=',$data->destino2)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos2){
                    //estoque quando existe
                    $destino2                    = $destinos2[0];
                    $destino2->quantidade        = intval($destino2->quantidade) + intval($data->quantidade2);
                    $destino2->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino2;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade2;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino2                   = new ProdEstoque();
                    $destino2->quantidade       = intval($destino2->quantidade) + intval($data->quantidade2);
                    $destino2->id_deposito      = $data->destino2;
                    $destino2->id_produto       = $produto->id;
                    $destino2->produto_sku      = $produto->SKU;
                    $destino2->produto_nome     = $produto_descricao;
                    $destino2->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino2;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade2;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 2--------------------------------------------------------------------------

            //----------------------------------DESTINO 3--------------------------------------------------------------------------
            if($data->quantidade3 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade3); 

                $destinos3 = ProdEstoque::where('id_deposito','=',$data->destino3)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos3){
                    //estoque quando existe
                    $destino3                    = $destinos3[0];
                    $destino3->quantidade        = intval($destino3->quantidade) + intval($data->quantidade3);
                    $destino3->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino3;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade3;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino3                   = new ProdEstoque();
                    $destino3->quantidade       = intval($destino3->quantidade) + intval($data->quantidade3);
                    $destino3->id_deposito      = $data->destino3;
                    $destino3->id_produto       = $produto->id;
                    $destino3->produto_sku      = $produto->SKU;
                    $destino3->produto_nome     = $produto_descricao;
                    $destino3->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino3;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade3;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 3--------------------------------------------------------------------------

            //----------------------------------DESTINO 4--------------------------------------------------------------------------
            if($data->quantidade4 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade4); 

                $destinos4 = ProdEstoque::where('id_deposito','=',$data->destino4)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos4){
                    //estoque quando existe
                    $destino4                    = $destinos4[0];
                    $destino4->quantidade        = intval($destino4->quantidade) + intval($data->quantidade4);
                    $destino4->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino4;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade4;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino4                   = new ProdEstoque();
                    $destino4->quantidade       = intval($destino4->quantidade) + intval($data->quantidade4);
                    $destino4->id_deposito      = $data->destino4;
                    $destino4->id_produto       = $produto->id;
                    $destino4->produto_sku      = $produto->SKU;
                    $destino4->produto_nome     = $produto_descricao;
                    $destino4->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino4;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade4;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 4--------------------------------------------------------------------------

            //----------------------------------DESTINO 5--------------------------------------------------------------------------
            if($data->quantidade5 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade5); 

                $destinos5 = ProdEstoque::where('id_deposito','=',$data->destino5)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos5){
                    //estoque quando existe
                    $destino5                    = $destinos5[0];
                    $destino5->quantidade        = intval($destino5->quantidade) + intval($data->quantidade5);
                    $destino5->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino5;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade5;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino5                   = new ProdEstoque();
                    $destino5->quantidade       = intval($destino5->quantidade) + intval($data->quantidade5);
                    $destino5->id_deposito      = $data->destino5;
                    $destino5->id_produto       = $produto->id;
                    $destino5->produto_sku      = $produto->SKU;
                    $destino5->produto_nome     = $produto_descricao;
                    $destino5->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino5;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade5;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 5--------------------------------------------------------------------------

            //----------------------------------DESTINO 6--------------------------------------------------------------------------
            if($data->quantidade6 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade6); 

                $destinos6 = ProdEstoque::where('id_deposito','=',$data->destino6)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos6){
                    //estoque quando existe
                    $destino6                    = $destinos6[0];
                    $destino6->quantidade        = intval($destino6->quantidade) + intval($data->quantidade6);
                    $destino6->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino6;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade6;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino6                   = new ProdEstoque();
                    $destino6->quantidade       = intval($destino6->quantidade) + intval($data->quantidade6);
                    $destino6->id_deposito      = $data->destino6;
                    $destino6->id_produto       = $produto->id;
                    $destino6->produto_sku      = $produto->SKU;
                    $destino6->produto_nome     = $produto_descricao;
                    $destino6->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino6;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade6;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 6--------------------------------------------------------------------------

            //----------------------------------DESTINO 7--------------------------------------------------------------------------
            if($data->quantidade7 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade7); 

                $destinos7 = ProdEstoque::where('id_deposito','=',$data->destino7)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos7){
                    //estoque quando existe
                    $destino7                    = $destinos7[0];
                    $destino7->quantidade        = intval($destino7->quantidade) + intval($data->quantidade7);
                    $destino7->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino7;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade7;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino7                   = new ProdEstoque();
                    $destino7->quantidade       = intval($destino7->quantidade) + intval($data->quantidade7);
                    $destino7->id_deposito      = $data->destino7;
                    $destino7->id_produto       = $produto->id;
                    $destino7->produto_sku      = $produto->SKU;
                    $destino7->produto_nome     = $produto_descricao;
                    $destino7->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino7;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade7;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 7--------------------------------------------------------------------------

            //----------------------------------DESTINO 8--------------------------------------------------------------------------
            if($data->quantidade8 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade8); 

                $destinos8 = ProdEstoque::where('id_deposito','=',$data->destino8)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos8){
                    //estoque quando existe
                    $destino8                    = $destinos8[0];
                    $destino8->quantidade        = intval($destino8->quantidade) + intval($data->quantidade8);
                    $destino8->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino8;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade8;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino8                   = new ProdEstoque();
                    $destino8->quantidade       = intval($destino8->quantidade) + intval($data->quantidade8);
                    $destino8->id_deposito      = $data->destino8;
                    $destino8->id_produto       = $produto->id;
                    $destino8->produto_sku      = $produto->SKU;
                    $destino8->produto_nome     = $produto_descricao;
                    $destino8->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino8;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade8;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 8--------------------------------------------------------------------------

            //----------------------------------DESTINO 9--------------------------------------------------------------------------
            if($data->quantidade9 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade9); 

                $destinos9 = ProdEstoque::where('id_deposito','=',$data->destino9)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos9){
                    //estoque quando existe
                    $destino9                    = $destinos9[0];
                    $destino9->quantidade        = intval($destino9->quantidade) + intval($data->quantidade9);
                    $destino9->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino9;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade9;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino9                   = new ProdEstoque();
                    $destino9->quantidade       = intval($destino9->quantidade) + intval($data->quantidade9);
                    $destino9->id_deposito      = $data->destino9;
                    $destino9->id_produto       = $produto->id;
                    $destino9->produto_sku      = $produto->SKU;
                    $destino9->produto_nome     = $produto_descricao;
                    $destino9->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino9;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade9;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 9--------------------------------------------------------------------------

            //----------------------------------DESTINO 10--------------------------------------------------------------------------
            if($data->quantidade10 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade10); 

                $destinos10 = ProdEstoque::where('id_deposito','=',$data->destino10)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos10){
                    //estoque quando existe
                    $destino10                    = $destinos10[0];
                    $destino10->quantidade        = intval($destino10->quantidade) + intval($data->quantidade10);
                    $destino10->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino10;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade10;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino10                   = new ProdEstoque();
                    $destino10->quantidade       = intval($destino10->quantidade) + intval($data->quantidade10);
                    $destino10->id_deposito      = $data->destino10;
                    $destino10->id_produto       = $produto->id;
                    $destino10->produto_sku      = $produto->SKU;
                    $destino10->produto_nome     = $produto_descricao;
                    $destino10->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino10;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade10;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 10--------------------------------------------------------------------------

            //----------------------------------DESTINO 11--------------------------------------------------------------------------
            if($data->quantidade11 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade11); 

                $destinos11 = ProdEstoque::where('id_deposito','=',$data->destino11)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos11){
                    //estoque quando existe
                    $destino11                    = $destinos11[0];
                    $destino11->quantidade        = intval($destino11->quantidade) + intval($data->quantidade11);
                    $destino11->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino11;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade11;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino11                   = new ProdEstoque();
                    $destino11->quantidade       = intval($destino11->quantidade) + intval($data->quantidade11);
                    $destino11->id_deposito      = $data->destino11;
                    $destino11->id_produto       = $produto->id;
                    $destino11->produto_sku      = $produto->SKU;
                    $destino11->produto_nome     = $produto_descricao;
                    $destino11->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino11;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade11;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 11--------------------------------------------------------------------------

            //----------------------------------DESTINO 12--------------------------------------------------------------------------
            if($data->quantidade12 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade12); 

                $destinos12 = ProdEstoque::where('id_deposito','=',$data->destino12)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos12){
                    //estoque quando existe
                    $destino12                    = $destinos12[0];
                    $destino12->quantidade        = intval($destino12->quantidade) + intval($data->quantidade12);
                    $destino12->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino12;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade12;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino12                   = new ProdEstoque();
                    $destino12->quantidade       = intval($destino12->quantidade) + intval($data->quantidade12);
                    $destino12->id_deposito      = $data->destino12;
                    $destino12->id_produto       = $produto->id;
                    $destino12->produto_sku      = $produto->SKU;
                    $destino12->produto_nome     = $produto_descricao;
                    $destino12->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino12;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade12;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 12--------------------------------------------------------------------------

            //----------------------------------DESTINO 13--------------------------------------------------------------------------
            if($data->quantidade13 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade13); 

                $destinos13 = ProdEstoque::where('id_deposito','=',$data->destino13)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos13){
                    //estoque quando existe
                    $destino13                    = $destinos13[0];
                    $destino13->quantidade        = intval($destino13->quantidade) + intval($data->quantidade13);
                    $destino13->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino13;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade13;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino13                   = new ProdEstoque();
                    $destino13->quantidade       = intval($destino13->quantidade) + intval($data->quantidade13);
                    $destino13->id_deposito      = $data->destino13;
                    $destino13->id_produto       = $produto->id;
                    $destino13->produto_sku      = $produto->SKU;
                    $destino13->produto_nome     = $produto_descricao;
                    $destino13->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino13;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade13;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 13--------------------------------------------------------------------------

            //----------------------------------DESTINO 14--------------------------------------------------------------------------
            if($data->quantidade14 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade14); 

                $destinos14 = ProdEstoque::where('id_deposito','=',$data->destino14)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos14){
                    //estoque quando existe
                    $destino14                    = $destinos14[0];
                    $destino14->quantidade        = intval($destino14->quantidade) + intval($data->quantidade14);
                    $destino14->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino14;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade14;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino14                   = new ProdEstoque();
                    $destino14->quantidade       = intval($destino14->quantidade) + intval($data->quantidade14);
                    $destino14->id_deposito      = $data->destino14;
                    $destino14->id_produto       = $produto->id;
                    $destino14->produto_sku      = $produto->SKU;
                    $destino14->produto_nome     = $produto_descricao;
                    $destino14->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino14;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade14;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 14--------------------------------------------------------------------------

            //----------------------------------DESTINO 15--------------------------------------------------------------------------
            if($data->quantidade15 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade15); 

                $destinos15 = ProdEstoque::where('id_deposito','=',$data->destino15)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos15){
                    //estoque quando existe
                    $destino15                    = $destinos15[0];
                    $destino15->quantidade        = intval($destino15->quantidade) + intval($data->quantidade15);
                    $destino15->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino15;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade15;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino15                   = new ProdEstoque();
                    $destino15->quantidade       = intval($destino15->quantidade) + intval($data->quantidade15);
                    $destino15->id_deposito      = $data->destino15;
                    $destino15->id_produto       = $produto->id;
                    $destino15->produto_sku      = $produto->SKU;
                    $destino15->produto_nome     = $produto_descricao;
                    $destino15->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino15;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade15;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 15--------------------------------------------------------------------------

            //----------------------------------DESTINO 16--------------------------------------------------------------------------
            if($data->quantidade16 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade16); 

                $destinos16 = ProdEstoque::where('id_deposito','=',$data->destino16)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos16){
                    //estoque quando existe
                    $destino16                    = $destinos16[0];
                    $destino16->quantidade        = intval($destino16->quantidade) + intval($data->quantidade16);
                    $destino16->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino16;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade16;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino16                   = new ProdEstoque();
                    $destino16->quantidade       = intval($destino16->quantidade) + intval($data->quantidade16);
                    $destino16->id_deposito      = $data->destino16;
                    $destino16->id_produto       = $produto->id;
                    $destino16->produto_sku      = $produto->SKU;
                    $destino16->produto_nome     = $produto_descricao;
                    $destino16->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino16;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade16;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 16--------------------------------------------------------------------------

            //----------------------------------DESTINO 17--------------------------------------------------------------------------
            if($data->quantidade17 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade17); 

                $destinos17 = ProdEstoque::where('id_deposito','=',$data->destino17)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos17){
                    //estoque quando existe
                    $destino17                    = $destinos17[0];
                    $destino17->quantidade        = intval($destino17->quantidade) + intval($data->quantidade17);
                    $destino17->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino17;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade17;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino17                   = new ProdEstoque();
                    $destino17->quantidade       = intval($destino17->quantidade) + intval($data->quantidade17);
                    $destino17->id_deposito      = $data->destino17;
                    $destino17->id_produto       = $produto->id;
                    $destino17->produto_sku      = $produto->SKU;
                    $destino17->produto_nome     = $produto_descricao;
                    $destino17->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino17;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade17;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 17--------------------------------------------------------------------------

            //----------------------------------DESTINO 18--------------------------------------------------------------------------
            if($data->quantidade18 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade18); 

                $destinos18 = ProdEstoque::where('id_deposito','=',$data->destino18)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos18){
                    //estoque quando existe
                    $destino18                    = $destinos18[0];
                    $destino18->quantidade        = intval($destino18->quantidade) + intval($data->quantidade18);
                    $destino18->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino18;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade18;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino18                   = new ProdEstoque();
                    $destino18->quantidade       = intval($destino18->quantidade) + intval($data->quantidade18);
                    $destino18->id_deposito      = $data->destino18;
                    $destino18->id_produto       = $produto->id;
                    $destino18->produto_sku      = $produto->SKU;
                    $destino18->produto_nome     = $produto_descricao;
                    $destino18->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino18;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade18;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 18--------------------------------------------------------------------------

            //----------------------------------DESTINO 19--------------------------------------------------------------------------
            if($data->quantidade19 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade19); 

                $destinos19 = ProdEstoque::where('id_deposito','=',$data->destino19)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos19){
                    //estoque quando existe
                    $destino19                    = $destinos19[0];
                    $destino19->quantidade        = intval($destino19->quantidade) + intval($data->quantidade19);
                    $destino19->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino19;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade19;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino19                   = new ProdEstoque();
                    $destino19->quantidade       = intval($destino19->quantidade) + intval($data->quantidade19);
                    $destino19->id_deposito      = $data->destino19;
                    $destino19->id_produto       = $produto->id;
                    $destino19->produto_sku      = $produto->SKU;
                    $destino19->produto_nome     = $produto_descricao;
                    $destino19->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino19;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade19;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 19--------------------------------------------------------------------------

            //----------------------------------DESTINO 20--------------------------------------------------------------------------
            if($data->quantidade20 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade20); 

                $destinos20 = ProdEstoque::where('id_deposito','=',$data->destino20)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos20){
                    //estoque quando existe
                    $destino20                    = $destinos20[0];
                    $destino20->quantidade        = intval($destino20->quantidade) + intval($data->quantidade20);
                    $destino20->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino20;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade20;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino20                   = new ProdEstoque();
                    $destino20->quantidade       = intval($destino20->quantidade) + intval($data->quantidade20);
                    $destino20->id_deposito      = $data->destino20;
                    $destino20->id_produto       = $produto->id;
                    $destino20->produto_sku      = $produto->SKU;
                    $destino20->produto_nome     = $produto_descricao;
                    $destino20->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino20;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade20;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 20--------------------------------------------------------------------------

            //----------------------------------DESTINO 21--------------------------------------------------------------------------
            if($data->quantidade21 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade21); 

                $destinos21 = ProdEstoque::where('id_deposito','=',$data->destino21)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos21){
                    //estoque quando existe
                    $destino21                    = $destinos21[0];
                    $destino21->quantidade        = intval($destino21->quantidade) + intval($data->quantidade21);
                    $destino21->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino21;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade21;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino21                   = new ProdEstoque();
                    $destino21->quantidade       = intval($destino21->quantidade) + intval($data->quantidade21);
                    $destino21->id_deposito      = $data->destino21;
                    $destino21->id_produto       = $produto->id;
                    $destino21->produto_sku      = $produto->SKU;
                    $destino21->produto_nome     = $produto_descricao;
                    $destino21->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino21;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade21;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 21--------------------------------------------------------------------------

            //----------------------------------DESTINO 22--------------------------------------------------------------------------
            if($data->quantidade22 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade22); 

                $destinos22 = ProdEstoque::where('id_deposito','=',$data->destino22)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos22){
                    //estoque quando existe
                    $destino22                    = $destinos22[0];
                    $destino22->quantidade        = intval($destino22->quantidade) + intval($data->quantidade22);
                    $destino22->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino22;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade22;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino22                   = new ProdEstoque();
                    $destino22->quantidade       = intval($destino22->quantidade) + intval($data->quantidade22);
                    $destino22->id_deposito      = $data->destino22;
                    $destino22->id_produto       = $produto->id;
                    $destino22->produto_sku      = $produto->SKU;
                    $destino22->produto_nome     = $produto_descricao;
                    $destino22->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino22;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade22;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 22--------------------------------------------------------------------------
             //----------------------------------DESTINO 23--------------------------------------------------------------------------
            if($data->quantidade23 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade23); 

                $destinos23 = ProdEstoque::where('id_deposito','=',$data->destino23)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos23){
                    //estoque quando existe
                    $destino23                    = $destinos23[0];
                    $destino23->quantidade        = intval($destino23->quantidade) + intval($data->quantidade23);
                    $destino23->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino23;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade23;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino23                   = new ProdEstoque();
                    $destino23->quantidade       = intval($destino23->quantidade) + intval($data->quantidade23);
                    $destino23->id_deposito      = $data->destino23;
                    $destino23->id_produto       = $produto->id;
                    $destino23->produto_sku      = $produto->SKU;
                    $destino23->produto_nome     = $produto_descricao;
                    $destino23->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino23;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade23;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 23--------------------------------------------------------------------------

            //----------------------------------DESTINO 24----------------------------------------------------------------------------------
            if($data->quantidade24 != ""){ 
                $transferencia = new TransferenciaEtq();
                $transferencia->deposito_env    = $data->deposito_env;
                $transferencia->dt_registro     = $data_atual;
                $transferencia->usuario         = $usuario;

                $transferencia->id_produto      = $produto->id;
                $estoque_origem->quantidade     = intval($estoque_origem->quantidade) - intval($data->quantidade24); 

                $destinos24 = ProdEstoque::where('id_deposito','=',$data->destino24)->where('id_produto','=',$data->estoque_id)->load();
                if($destinos24){
                    //estoque quando existe
                    $destino24                    = $destinos24[0];
                    $destino24->quantidade        = intval($destino24->quantidade) + intval($data->quantidade24);
                    $destino24->store();
                    //transferencia
                    $transferencia->deposito_rec = $data->destino24;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade24;
                    $transferencia->store();
                }else{
                    //estoque se não existe
                    $destino24                   = new ProdEstoque();
                    $destino24->quantidade       = intval($destino24->quantidade) + intval($data->quantidade24);
                    $destino24->id_deposito      = $data->destino24;
                    $destino24->id_produto       = $produto->id;
                    $destino24->produto_sku      = $produto->SKU;
                    $destino24->produto_nome     = $produto_descricao;
                    $destino24->store(); 
                    //transferencia
                    $transferencia->deposito_rec = $data->destino24;
                    $transferencia->estoque_id   = $estoque_origem->id;
                    $transferencia->quantidade   = $data->quantidade24;
                    $transferencia->store();
                }
            }
            //---------------------------------- FIM DO DESTINO 24--------------------------------------------------------------------------
            //---------------------------------- FIM DOS DESTINOS

            $estoque_origem->store(); //fora dos if's

/*
            $object->store(); // save the object 

            $data->id = $object->id; 
*/
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['TransferenciaEtqList', 'onReload']);

            new TMessage('info', "Registro salvo", $messageAction); 

                TWindow::closeWindow(parent::getId()); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public function onLimpar($param = null) 
    {
        try 
        {
            $object = new stdClass();
            $object->quantidade1   = ""; 
            $object->quantidade2   = ""; 
            $object->quantidade3   = ""; 
            $object->quantidade4   = ""; 
            $object->quantidade5   = ""; 
            $object->quantidade6   = ""; 
            $object->quantidade7   = ""; 
            $object->quantidade8   = ""; 
            $object->quantidade9   = ""; 
            $object->quantidade10  = ""; 
            $object->quantidade11  = ""; 
            $object->quantidade12  = ""; 
            $object->quantidade13  = ""; 
            $object->quantidade14  = ""; 
            $object->quantidade15  = ""; 
            $object->quantidade16  = ""; 
            $object->quantidade17  = ""; 
            $object->quantidade18  = ""; 
            $object->quantidade19  = ""; 
            $object->quantidade20  = ""; 
            $object->quantidade21  = ""; 
            $object->quantidade22  = ""; 
            $object->quantidade23  = ""; 
            TForm::sendData(self::$formName, $object);

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
            TDBCombo::disableField(self::$formName, 'quantidade24');
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new TransferenciaEtq($key); // instantiates the Active Record 

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

        TDBCombo::disableField(self::$formName, 'quantidade24');
    } 

}

