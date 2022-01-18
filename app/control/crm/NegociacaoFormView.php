<?php

class NegociacaoFormView extends TPage
{
    protected $form; // form
    private static $database = 'base_banco';
    private static $activeRecord = 'Negociacao';
    private static $primaryKey = 'id';
    private static $formName = 'formView_Negociacao';

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

        TTransaction::open(self::$database);
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setTagName('div');

        $negociacao = new Negociacao($param['key']);
        // define the form title
        $this->form->setFormTitle("Negociação");

        $label1 = new TLabel("Id:", '#333333', '12px', '');
        $text1 = new TTextDisplay($negociacao->id, '#333333', '12px', '');
        $label6 = new TLabel("Tipo da negociação:", '#333333', '12px', '');
        $text5 = new TTextDisplay($negociacao->tipo_negociacao->nome, '#333333', '12px', '');
        $label9 = new TLabel("Descrição:", '#333333', '12px', '');
        $text9 = new TTextDisplay($negociacao->descricao, '#333333', '12px', '');
        $label2 = new TLabel("Cliente:", '#333333', '12px', '');
        $text2 = new TTextDisplay($negociacao->cliente->nome, '#333333', '12px', '');
        $label22 = new TLabel("Vendedor:", '#333333', '12px', '');
        $text3 = new TTextDisplay($negociacao->vendedor->nome, '#333333', '12px', '');
        $label4 = new TLabel("Origem da negociação:", '#333333', '12px', '');
        $text4 = new TTextDisplay($negociacao->origem_negociacao->nome, '#333333', '12px', '');
        $label8 = new TLabel("Fase da negociação:", '#333333', '12px', '');
        $text6 = new TTextDisplay($negociacao->estado_negociacao->nome, '#333333', '12px', '');
        $label7 = new TLabel("Início da negociação:", '#333333', '12px', '');
        $text7 = new TTextDisplay(TDateTime::convertToMask($negociacao->dt_inicio_negociacao, 'yyyy-mm-dd hh:ii', 'dd/mm/yyyy hh:ii'), '#333333', '12px', '');
        $label11 = new TLabel("Fim da negociação:", '#333333', '12px', '');
        $text8 = new TTextDisplay(TDateTime::convertToMask($negociacao->dt_fim_negociacao, 'yyyy-mm-dd hh:ii', 'dd/mm/yyyy hh:ii'), '#333333', '12px', '');
        $label10 = new TLabel("Observação:", '#333333', '12px', '');
        $text10 = new TTextDisplay($negociacao->obs, '#333333', '12px', '');


        $row1 = $this->form->addFields([$label1],[$text1],[$label6],[$text5]);
        $row2 = $this->form->addFields([$label9],[$text9]);
        $row3 = $this->form->addFields([$label2],[$text2],[$label22],[$text3]);
        $row4 = $this->form->addFields([$label4],[$text4],[$label8],[$text6]);
        $row5 = $this->form->addFields([$label7],[$text7],[$label11],[$text8]);
        $row6 = $this->form->addFields([$label10],[$text10]);

        $this->negociacao_produto_negociacao_id_list = new TQuickGrid;
        $this->negociacao_produto_negociacao_id_list->disableHtmlConversion();
        $this->negociacao_produto_negociacao_id_list->style = 'width:100%';
        $this->negociacao_produto_negociacao_id_list->disableDefaultClick();

        $column_produto_descricao = $this->negociacao_produto_negociacao_id_list->addQuickColumn("Produto", 'produto->descricao', 'left');

        $this->negociacao_produto_negociacao_id_list->createModel();

        $criteria_negociacao_produto_negociacao_id = new TCriteria();
        $criteria_negociacao_produto_negociacao_id->add(new TFilter('negociacao_id', '=', $negociacao->id));

        $negociacao_produto_negociacao_id_items = NegociacaoProduto::getObjects($criteria_negociacao_produto_negociacao_id);

        $this->negociacao_produto_negociacao_id_list->addItems($negociacao_produto_negociacao_id_items);

        $icon = new TImage('far:circle #000000');
        $title = new TTextDisplay("{$icon} Produtos", '#333333', '12px', '{$fontStyle}');

        $panel = new TPanelGroup($title, '#f5f5f5');
        $panel->class = 'panel panel-default formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->negociacao_produto_negociacao_id_list));

        $this->form->addContent([$panel]);

        $this->historico_negociacao_negociacao_id_list = new TQuickGrid;
        $this->historico_negociacao_negociacao_id_list->disableHtmlConversion();
        $this->historico_negociacao_negociacao_id_list->style = 'width:100%';
        $this->historico_negociacao_negociacao_id_list->disableDefaultClick();

        $column_tipo_contato_nome = $this->historico_negociacao_negociacao_id_list->addQuickColumn("Tipo", 'tipo_contato->nome', 'left' , '250px');
        $column_descricao = $this->historico_negociacao_negociacao_id_list->addQuickColumn("Descrição", 'descricao', 'left');
        $column_dt_contato_transformed = $this->historico_negociacao_negociacao_id_list->addQuickColumn("Data", 'dt_contato', 'center' , '140px');

        $column_dt_contato_transformed->setTransformer(function($value, $object, $row) 
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $this->historico_negociacao_negociacao_id_list->createModel();

        $criteria_historico_negociacao_negociacao_id = new TCriteria();
        $criteria_historico_negociacao_negociacao_id->add(new TFilter('negociacao_id', '=', $negociacao->id));

        $historico_negociacao_negociacao_id_items = HistoricoNegociacao::getObjects($criteria_historico_negociacao_negociacao_id);

        $this->historico_negociacao_negociacao_id_list->addItems($historico_negociacao_negociacao_id_items);

        $icon = new TImage('far:circle #000000');
        $title = new TTextDisplay("{$icon} Históricos", '#333333', '12px', '{$fontStyle}');

        $panel = new TPanelGroup($title, '#f5f5f5');
        $panel->class = 'panel panel-default formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->historico_negociacao_negociacao_id_list));

        $this->form->addContent([$panel]);

        $btnNegociacaoFormOnEditAction = new TAction(['NegociacaoForm', 'onEdit'],['key'=>$negociacao->id]);
        $btnNegociacaoFormOnEditLabel = new TLabel("Editar");
        $btnNegociacaoFormOnEditLabel->setFontSize('12px'); 
        $btnNegociacaoFormOnEditLabel->setFontColor('#333333'); 

        $btnNegociacaoFormOnEdit = $this->form->addHeaderAction($btnNegociacaoFormOnEditLabel, $btnNegociacaoFormOnEditAction, 'far:edit #000000'); 

        $btnNegociacaoFormOnShowAction = new TAction(['NegociacaoForm', 'onShow']);
        $btnNegociacaoFormOnShowLabel = new TLabel("Nogociação");
        $btnNegociacaoFormOnShowLabel->setFontSize('12px'); 
        $btnNegociacaoFormOnShowLabel->setFontColor('#333333'); 

        $btnNegociacaoFormOnShow = $this->form->addHeaderAction($btnNegociacaoFormOnShowLabel, $btnNegociacaoFormOnShowAction, 'fas:plus #000000'); 

        $btnOrcamentoFormOnEditAction = new TAction(['OrcamentoForm', 'onEdit'],['negociacao_id'=>$negociacao->id]);
        $btnOrcamentoFormOnEditLabel = new TLabel("Gerar orçamento");
        $btnOrcamentoFormOnEditLabel->setFontSize('12px'); 
        $btnOrcamentoFormOnEditLabel->setFontColor('#333333'); 

        $btnOrcamentoFormOnEdit = $this->form->addHeaderAction($btnOrcamentoFormOnEditLabel, $btnOrcamentoFormOnEditAction, 'far:file-alt #000000'); 

        $btnHistoricoNegociacaoFormOnEditAction = new TAction(['HistoricoNegociacaoForm', 'onEdit'],['negociacao_id'=>$negociacao->id]);
        $btnHistoricoNegociacaoFormOnEditLabel = new TLabel("Novo histórico");
        $btnHistoricoNegociacaoFormOnEditLabel->setFontSize('12px'); 
        $btnHistoricoNegociacaoFormOnEditLabel->setFontColor('#333333'); 

        $btnHistoricoNegociacaoFormOnEdit = $this->form->addHeaderAction($btnHistoricoNegociacaoFormOnEditLabel, $btnHistoricoNegociacaoFormOnEditAction, 'far:comments #000000'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($this->form);

        TTransaction::close();
        parent::add($container);

    }

    public function onShow($param = null)
    {     

    }

}

