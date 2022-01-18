<?php

class ClonarPreco extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_ClonarPreco';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param = null)
    {
        parent::__construct();
        parent::setSize(0.40, null);
        parent::setTitle("Clonar Preço");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Clonar Preço");


        $id_tabela_origem = new TDBCombo('id_tabela_origem', 'base_banco', 'TabelaPreco', 'id', '{nome_tabela_preco}','nome_tabela_preco asc'  );
        $id_tabela_destino = new TEntry('id_tabela_destino');

        $id_tabela_destino->setEditable(false);
        $id_tabela_origem->setSize('100%');
        $id_tabela_destino->setSize('100%');


        $row1 = $this->form->addFields([new TLabel("Clonar preços da tabela:", null, '14px', null, '100%'),$id_tabela_origem],[new TLabel("Para a tabela:", null, '14px', null, '100%'),$id_tabela_destino]);
        $row1->layout = [' col-sm-6',' col-sm-6'];

        // create the form actions
        $btn_onclone = $this->form->addAction("Clonar", new TAction([$this, 'onClone']), 'far:clone #ffffff');
        $this->btn_onclone = $btn_onclone;
        $btn_onclone->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public function onClone($param = null) 
    {
        try
        {
        TTransaction::open('base_banco');
        $data = $this->form->getValues();
        $id_origem  = $param['id_tabela_origem'];
        $id_destino = $param['id_tabela_destino'];
        $originais = Preco::where('id_tabela','=',$id_origem)->load();
        $copia;
        $qtd =0 ;
        if($originais){
            foreach ($originais as $original) {
                $qtd++;
                $copia = new Preco();
                $copia->preco_venda = $original->preco_venda;
                $copia->preco_custo = $original->preco_custo;
                $copia->id_produto  = $original->id_produto;
                $copia->id_tabela   = $id_destino;
                $copia->store();
            }
        }else{
            echo "não achou produtos";
        }
        $data->id_tabela_origem  = $id_origem;
        $data->id_tabela_destino = $id_destino;
        $this->form->setData($data);
        TTransaction::close();
        new TMessage('info', "foram clonados $qtd produtos.");
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onShow($param = null)
    {               

        //var_dump($param);
        $data = $this->form->getData();
        $data->id_tabela_destino = $param['key'];
        $this->form->setData($data);
    } 

}

