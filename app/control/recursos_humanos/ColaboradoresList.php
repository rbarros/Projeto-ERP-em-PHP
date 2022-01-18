<?php

class ColaboradoresList extends TPage
{
    private $form; // form
    private $cardView; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'base_banco';
    private static $activeRecord = 'Colaborador';
    private static $primaryKey = 'id';
    private static $formName = 'form_ColaboradoresList';
    private $showMethods = ['onReload', 'onSearch'];

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Colaboradores");

        $id = new TEntry('id');
        $pessoa_id = new TEntry('pessoa_id');
        $rg = new TEntry('rg');
        $ctps = new TEntry('ctps');
        $nome = new TDBEntry('nome', 'base_banco', 'Pessoa', 'nome','nome asc'  );
        $cnh = new TEntry('cnh');
        $contrato2 = new TDate('contrato2');
        $salario_familia = new TRadioGroup('salario_familia');
        $salario_familia_qtd = new TEntry('salario_familia_qtd');
        $bonificacao = new TRadioGroup('bonificacao');
        $status_ferias = new TEntry('status_ferias');
        $loja_registro = new TDBCombo('loja_registro', 'base_banco', 'Loja', 'id', '{razao_social}','razao_social asc'  );
        $loja_atual = new TEntry('loja_atual');
        $cargo = new TDBCombo('cargo', 'base_banco', 'Cargo', 'id', '{id}','id asc'  );
        $salario = new TDBCombo('salario', 'base_banco', 'Salario', 'id', '{id}','id asc'  );
        $carga_horaria = new TDBCombo('carga_horaria', 'base_banco', 'Escala', 'id', '{id}','id asc'  );
        $dt_nascimento = new TDate('dt_nascimento');
        $contrato1 = new TDate('contrato1');
        $dt_desligamento = new TDate('dt_desligamento');
        $status_colaborador = new TEntry('status_colaborador');
        $escala = new TDBCombo('escala', 'base_banco', 'Escala', 'id', '{id}','id asc'  );
        $dt_registro = new TDate('dt_registro');

        $nome->setDisplayMask('{nome}');
        $bonificacao->addItems(['1'=>'Sim','2'=>'Não']);
        $salario_familia->addItems(['1'=>'Sim','2'=>'Não']);

        $bonificacao->setLayout('horizontal');
        $salario_familia->setLayout('horizontal');

        $bonificacao->setBooleanMode();
        $salario_familia->setBooleanMode();

        $rg->setMaxLength(30);
        $cnh->setMaxLength(30);
        $ctps->setMaxLength(40);
        $status_ferias->setMaxLength(30);
        $status_colaborador->setMaxLength(20);

        $contrato2->setMask('dd/mm/yyyy');
        $contrato1->setMask('dd/mm/yyyy');
        $dt_registro->setMask('dd/mm/yyyy');
        $dt_nascimento->setMask('dd/mm/yyyy');
        $dt_desligamento->setMask('dd/mm/yyyy');

        $contrato2->setDatabaseMask('yyyy-mm-dd');
        $contrato1->setDatabaseMask('yyyy-mm-dd');
        $dt_registro->setDatabaseMask('yyyy-mm-dd');
        $dt_nascimento->setDatabaseMask('yyyy-mm-dd');
        $dt_desligamento->setDatabaseMask('yyyy-mm-dd');

        $id->setSize('100%');
        $rg->setSize('100%');
        $cnh->setSize('100%');
        $ctps->setSize('100%');
        $nome->setSize('100%');
        $cargo->setSize('100%');
        $contrato2->setSize(110);
        $escala->setSize('100%');
        $contrato1->setSize(110);
        $bonificacao->setSize(80);
        $salario->setSize('100%');
        $dt_registro->setSize(110);
        $pessoa_id->setSize('100%');
        $loja_atual->setSize('100%');
        $dt_nascimento->setSize(110);
        $salario_familia->setSize(80);
        $dt_desligamento->setSize(110);
        $status_ferias->setSize('100%');
        $carga_horaria->setSize('100%');
        $loja_registro->setSize('100%');
        $status_colaborador->setSize('100%');
        $salario_familia_qtd->setSize('100%');

        $this->form->appendPage("Pesquisa básica");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Registro funcionário:", null, '14px', null, '100%'),$id],[new TLabel("Registro Cliente:", null, '14px', null, '100%'),$pessoa_id],[new TLabel("Rg:", null, '14px', null, '100%'),$rg],[new TLabel("Ctps:", null, '14px', null, '100%'),$ctps]);
        $row1->layout = [' col-sm-6 col-lg-2',' col-sm-6 col-lg-2',' col-sm-2 col-lg-4',' col-sm-2 col-lg-4'];

        $row2 = $this->form->addFields([new TLabel("Nome Funcionário:", null, '14px', null),$nome],[],[new TLabel("Cnh:", null, '14px', null, '100%'),$cnh]);
        $row2->layout = [' col-sm-6 col-lg-4','col-sm-2','col-sm-6'];

        $this->form->appendPage("Pesquisa avançada");
        $row3 = $this->form->addFields([new TLabel("Rótulo:", null, '14px', null)],[]);
        $row4 = $this->form->addFields([new TLabel("vencimento do 2º contrato:", null, '14px', null, '100%'),$contrato2],[new TLabel("Salario familia:", null, '14px', null, '100%'),$salario_familia]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([new TLabel("Salario familia qtd:", null, '14px', null, '100%'),$salario_familia_qtd],[new TLabel("bonificação do funcionário:", null, '14px', null, '100%'),$bonificacao]);
        $row5->layout = ['col-sm-6','col-sm-6'];

        $row6 = $this->form->addFields([new TLabel("Status ferias:", null, '14px', null, '100%'),$status_ferias],[new TLabel("loja no qual o colaborador é registrado:", null, '14px', null, '100%'),$loja_registro]);
        $row6->layout = ['col-sm-6','col-sm-6'];

        $row7 = $this->form->addFields([new TLabel("loja na qual o colaborador está:", null, '14px', null, '100%'),$loja_atual],[new TLabel("Cargo:", null, '14px', null, '100%'),$cargo]);
        $row7->layout = ['col-sm-6','col-sm-6'];

        $row8 = $this->form->addFields([new TLabel("Salario:", null, '14px', null, '100%'),$salario],[new TLabel("Carga horaria:", null, '14px', null, '100%'),$carga_horaria]);
        $row8->layout = ['col-sm-6','col-sm-6'];

        $row9 = $this->form->addFields([new TLabel("Dt nascimento:", null, '14px', null, '100%'),$dt_nascimento],[new TLabel("vencimento do 1º contrato:", null, '14px', null, '100%'),$contrato1]);
        $row9->layout = ['col-sm-6','col-sm-6'];

        $row10 = $this->form->addFields([new TLabel("Dt desligamento:", null, '14px', null, '100%'),$dt_desligamento],[new TLabel("Status colaborador:", null, '14px', null, '100%'),$status_colaborador]);
        $row10->layout = ['col-sm-6','col-sm-6'];

        $row11 = $this->form->addFields([new TLabel("Escala:", null, '14px', null, '100%'),$escala],[new TLabel("Dt registro:", null, '14px', null, '100%'),$dt_registro]);
        $row11->layout = ['col-sm-6','col-sm-6'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['ColaboradorForm', 'onShow']), 'fas:plus #69aa46');
        $this->btn_onshow = $btn_onshow;

        $this->cardView = new TCardView;

        $this->cardView->setContentHeight(170);
        $this->cardView->setTitleTemplate('{pessoa->nome}');
        $this->cardView->setItemTemplate("");

        $this->cardView->setItemDatabase(self::$database);

        $this->filter_criteria = new TCriteria;

        $action_ColaboradorForm_onEdit = new TAction(['ColaboradorForm', 'onEdit'], ['key'=> '{id}']);

        $this->cardView->addAction($action_ColaboradorForm_onEdit, "Editar", 'far:edit #478fca'); 

        $action_ColaboradoresList_onDelete = new TAction(['ColaboradoresList', 'onDelete'], ['key'=> '{id}']);

        $this->cardView->addAction($action_ColaboradoresList_onDelete, "Excluir", 'fas:trash-alt #dd5a43'); 

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));

        $panel = new TPanelGroup;
        $panel->add($this->cardView);

        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(TBreadCrumb::create(["Recursos Humanos","Colaboradores"]));
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public function onDelete($param = null) 
    { 
        if(isset($param['delete']) && $param['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new Colaborador($key, FALSE); 

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $param );
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
        }
    }

    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->pessoa_id) AND ( (is_scalar($data->pessoa_id) AND $data->pessoa_id !== '') OR (is_array($data->pessoa_id) AND (!empty($data->pessoa_id)) )) )
        {

            $filters[] = new TFilter('pessoa_id', '=', $data->pessoa_id);// create the filter 
        }

        if (isset($data->rg) AND ( (is_scalar($data->rg) AND $data->rg !== '') OR (is_array($data->rg) AND (!empty($data->rg)) )) )
        {

            $filters[] = new TFilter('rg', 'like', "%{$data->rg}%");// create the filter 
        }

        if (isset($data->ctps) AND ( (is_scalar($data->ctps) AND $data->ctps !== '') OR (is_array($data->ctps) AND (!empty($data->ctps)) )) )
        {

            $filters[] = new TFilter('ctps', 'like', "%{$data->ctps}%");// create the filter 
        }

        if (isset($data->nome) AND ( (is_scalar($data->nome) AND $data->nome !== '') OR (is_array($data->nome) AND (!empty($data->nome)) )) )
        {

            $filters[] = new TFilter('pessoa_id', '=', $data->nome);// create the filter 
        }

        if (isset($data->cnh) AND ( (is_scalar($data->cnh) AND $data->cnh !== '') OR (is_array($data->cnh) AND (!empty($data->cnh)) )) )
        {

            $filters[] = new TFilter('cnh', 'like', "%{$data->cnh}%");// create the filter 
        }

        if (isset($data->contrato2) AND ( (is_scalar($data->contrato2) AND $data->contrato2 !== '') OR (is_array($data->contrato2) AND (!empty($data->contrato2)) )) )
        {

            $filters[] = new TFilter('contrato2', '=', $data->contrato2);// create the filter 
        }

        if (isset($data->salario_familia) AND ( (is_scalar($data->salario_familia) AND $data->salario_familia !== '') OR (is_array($data->salario_familia) AND (!empty($data->salario_familia)) )) )
        {

            $filters[] = new TFilter('salario_familia', '=', $data->salario_familia);// create the filter 
        }

        if (isset($data->salario_familia_qtd) AND ( (is_scalar($data->salario_familia_qtd) AND $data->salario_familia_qtd !== '') OR (is_array($data->salario_familia_qtd) AND (!empty($data->salario_familia_qtd)) )) )
        {

            $filters[] = new TFilter('salario_familia_qtd', '=', $data->salario_familia_qtd);// create the filter 
        }

        if (isset($data->bonificacao) AND ( (is_scalar($data->bonificacao) AND $data->bonificacao !== '') OR (is_array($data->bonificacao) AND (!empty($data->bonificacao)) )) )
        {

            $filters[] = new TFilter('bonificacao', '=', $data->bonificacao);// create the filter 
        }

        if (isset($data->status_ferias) AND ( (is_scalar($data->status_ferias) AND $data->status_ferias !== '') OR (is_array($data->status_ferias) AND (!empty($data->status_ferias)) )) )
        {

            $filters[] = new TFilter('status_ferias', 'like', "%{$data->status_ferias}%");// create the filter 
        }

        if (isset($data->loja_registro) AND ( (is_scalar($data->loja_registro) AND $data->loja_registro !== '') OR (is_array($data->loja_registro) AND (!empty($data->loja_registro)) )) )
        {

            $filters[] = new TFilter('loja_registro', '=', $data->loja_registro);// create the filter 
        }

        if (isset($data->loja_atual) AND ( (is_scalar($data->loja_atual) AND $data->loja_atual !== '') OR (is_array($data->loja_atual) AND (!empty($data->loja_atual)) )) )
        {

            $filters[] = new TFilter('loja_atual', '=', $data->loja_atual);// create the filter 
        }

        if (isset($data->cargo) AND ( (is_scalar($data->cargo) AND $data->cargo !== '') OR (is_array($data->cargo) AND (!empty($data->cargo)) )) )
        {

            $filters[] = new TFilter('cargo', '=', $data->cargo);// create the filter 
        }

        if (isset($data->salario) AND ( (is_scalar($data->salario) AND $data->salario !== '') OR (is_array($data->salario) AND (!empty($data->salario)) )) )
        {

            $filters[] = new TFilter('salario', '=', $data->salario);// create the filter 
        }

        if (isset($data->carga_horaria) AND ( (is_scalar($data->carga_horaria) AND $data->carga_horaria !== '') OR (is_array($data->carga_horaria) AND (!empty($data->carga_horaria)) )) )
        {

            $filters[] = new TFilter('carga_horaria', '=', $data->carga_horaria);// create the filter 
        }

        if (isset($data->dt_nascimento) AND ( (is_scalar($data->dt_nascimento) AND $data->dt_nascimento !== '') OR (is_array($data->dt_nascimento) AND (!empty($data->dt_nascimento)) )) )
        {

            $filters[] = new TFilter('dt_nascimento', '=', $data->dt_nascimento);// create the filter 
        }

        if (isset($data->contrato1) AND ( (is_scalar($data->contrato1) AND $data->contrato1 !== '') OR (is_array($data->contrato1) AND (!empty($data->contrato1)) )) )
        {

            $filters[] = new TFilter('contrato1', '=', $data->contrato1);// create the filter 
        }

        if (isset($data->dt_desligamento) AND ( (is_scalar($data->dt_desligamento) AND $data->dt_desligamento !== '') OR (is_array($data->dt_desligamento) AND (!empty($data->dt_desligamento)) )) )
        {

            $filters[] = new TFilter('dt_desligamento', '=', $data->dt_desligamento);// create the filter 
        }

        if (isset($data->status_colaborador) AND ( (is_scalar($data->status_colaborador) AND $data->status_colaborador !== '') OR (is_array($data->status_colaborador) AND (!empty($data->status_colaborador)) )) )
        {

            $filters[] = new TFilter('status_colaborador', 'like', "%{$data->status_colaborador}%");// create the filter 
        }

        if (isset($data->escala) AND ( (is_scalar($data->escala) AND $data->escala !== '') OR (is_array($data->escala) AND (!empty($data->escala)) )) )
        {

            $filters[] = new TFilter('escala', '=', $data->escala);// create the filter 
        }

        if (isset($data->dt_registro) AND ( (is_scalar($data->dt_registro) AND $data->dt_registro !== '') OR (is_array($data->dt_registro) AND (!empty($data->dt_registro)) )) )
        {

            $filters[] = new TFilter('dt_registro', '=', $data->dt_registro);// create the filter 
        }

        $param = array();
        $param['offset']     = 0;
        $param['first_page'] = 1;

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload($param);
    }

    public function onReload($param = NULL)
    {
        try
        {

            // open a transaction with database 'base_banco'
            TTransaction::open(self::$database);

            // creates a repository for Colaborador
            $repository = new TRepository(self::$activeRecord);
            $limit = 20;

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->cardView->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    $this->cardView->addItem($object);

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
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

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

}

