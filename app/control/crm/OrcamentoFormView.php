<?php

class OrcamentoFormView extends TPage
{
    protected $form; // form
    private static $database = 'base_banco';
    private static $activeRecord = 'Orcamento';
    private static $primaryKey = 'id';
    private static $formName = 'formView_Orcamento';

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

        $orcamento = new Orcamento($param['key']);
        // define the form title
        $this->form->setFormTitle("Orçamento");

        $label1 = new TLabel("Id:", '#333333', '12px', '');
        $text1 = new TTextDisplay($orcamento->id, '#333333', '12px', '');
        $label22 = new TLabel("Data do orçamento:", '#333333', '12px', '');
        $text5 = new TTextDisplay(TDateTime::convertToMask($orcamento->dt_orcamento, 'yyyy-mm-dd hh:ii', 'dd/mm/yyyy hh:ii'), '#333333', '12px', '');
        $label2 = new TLabel("Cliente:", '#333333', '12px', '');
        $text2 = new TTextDisplay($orcamento->cliente->nome, '#333333', '12px', '');
        $label44 = new TLabel("Vendedor:", '#333333', '12px', '');
        $text3 = new TTextDisplay($orcamento->vendedor->nome, '#333333', '12px', '');
        $label4 = new TLabel("Estado do orçamento:", '#333333', '12px', '');
        $text4 = new TTextDisplay($orcamento->estado_orcamento->nome, '#333333', '12px', '');
        $label66 = new TLabel("Valor do frete:", '#333333', '12px', '');
        $text7 = new TTextDisplay(number_format($orcamento->frete, '2', ',', '.'), '#333333', '12px', '');
        $label6 = new TLabel("Observação:", '#333333', '12px', '');
        $text6 = new TTextDisplay($orcamento->obs, '#333333', '12px', '');


        $row1 = $this->form->addFields([$label1],[$text1],[$label22],[$text5]);
        $row2 = $this->form->addFields([$label2],[$text2],[$label44],[$text3]);
        $row3 = $this->form->addFields([$label4],[$text4],[$label66],[$text7]);
        $row4 = $this->form->addFields([$label6],[$text6]);

        $this->orcamento_item_orcamento_id_list = new TQuickGrid;
        $this->orcamento_item_orcamento_id_list->disableHtmlConversion();
        $this->orcamento_item_orcamento_id_list->style = 'width:100%';
        $this->orcamento_item_orcamento_id_list->disableDefaultClick();

        $column_id = $this->orcamento_item_orcamento_id_list->addQuickColumn("Id", 'id', 'left');
        $column_quantidade = $this->orcamento_item_orcamento_id_list->addQuickColumn("Quantidade", 'quantidade', 'left');
        $column_desconto_transformed = $this->orcamento_item_orcamento_id_list->addQuickColumn("Desconto", 'desconto', 'left');
        $column_valor_transformed = $this->orcamento_item_orcamento_id_list->addQuickColumn("Valor", 'valor', 'left');
        $column_calculated_4 = $this->orcamento_item_orcamento_id_list->addQuickColumn("Total", '=( ({valor} - {desconto} )* {quantidade}  )', 'left');

        $column_calculated_4->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_desconto_transformed->setTransformer(function($value, $object, $row) 
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_valor_transformed->setTransformer(function($value, $object, $row) 
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_calculated_4->setTransformer(function($value, $object, $row) 
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $this->orcamento_item_orcamento_id_list->createModel();

        $criteria_orcamento_item_orcamento_id = new TCriteria();
        $criteria_orcamento_item_orcamento_id->add(new TFilter('orcamento_id', '=', $orcamento->id));

        $orcamento_item_orcamento_id_items = OrcamentoItem::getObjects($criteria_orcamento_item_orcamento_id);

        $this->orcamento_item_orcamento_id_list->addItems($orcamento_item_orcamento_id_items);

        $icon = new TImage('far:circle #000000');
        $title = new TTextDisplay("{$icon} Produtos", '#333333', '14px', '{$fontStyle}');

        $panel = new TPanelGroup($title, '#f5f5f5');
        $panel->class = 'panel panel-default formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->orcamento_item_orcamento_id_list));

        $this->form->addContent([$panel]);

        $btnOrcamentoFormOnEditAction = new TAction(['OrcamentoForm', 'onEdit'],['key'=>$orcamento->id]);
        $btnOrcamentoFormOnEditLabel = new TLabel("Editar");
        $btnOrcamentoFormOnEditLabel->setFontSize('12px'); 
        $btnOrcamentoFormOnEditLabel->setFontColor('#333333'); 

        $btnOrcamentoFormOnEdit = $this->form->addHeaderAction($btnOrcamentoFormOnEditLabel, $btnOrcamentoFormOnEditAction, 'far:edit #000000'); 

        $btnOrcamentoFormOnShowAction = new TAction(['OrcamentoForm', 'onShow']);
        $btnOrcamentoFormOnShowLabel = new TLabel("Orçamento");
        $btnOrcamentoFormOnShowLabel->setFontSize('12px'); 
        $btnOrcamentoFormOnShowLabel->setFontColor('#333333'); 

        $btnOrcamentoFormOnShow = $this->form->addHeaderAction($btnOrcamentoFormOnShowLabel, $btnOrcamentoFormOnShowAction, 'fas:plus #000000'); 

        $btnVendaFormOnEditAction = new TAction(['VendaForm', 'onEdit'],['orcamento_id'=>$orcamento->id]);
        $btnVendaFormOnEditLabel = new TLabel("Gerar venda");
        $btnVendaFormOnEditLabel->setFontSize('12px'); 
        $btnVendaFormOnEditLabel->setFontColor('#333333'); 

        $btnVendaFormOnEdit = $this->form->addHeaderAction($btnVendaFormOnEditLabel, $btnVendaFormOnEditAction, 'far:money-bill-alt #000000'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["CRM","Visualizar orçamento"]));
        }
        $container->add($this->form);

        TTransaction::close();
        parent::add($container);

    }

    public function onShow($param = null)
    {     

    }

}

