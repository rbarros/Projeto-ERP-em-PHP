<?php

use Google\client;
use Google\service;

class CestForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'base_banco';
    private static $activeRecord = 'Cest';
    private static $primaryKey = 'id';
    private static $formName = 'form_Cest';

    use BuilderMasterDetailTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.50, null);
        parent::setTitle("CESTe NCM");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("CESTe NCM");


        $Ncm_fk_cest_ = new THidden('Ncm_fk_cest_');
        $button_adicionar_Ncm_fk_cest = new TButton('button_adicionar_Ncm_fk_cest');
        $n_cest = new TDBEntry('n_cest', 'base_banco', 'Cest', 'id','n_cest asc'  );
        $descricao = new TText('descricao');

        $n_cest->addValidation("N cest", new TRequiredValidator()); 
        $descricao->addValidation("Descricao", new TRequiredValidator()); 

        $button_adicionar_Ncm_fk_cest->setAction(new TAction([$this, 'onAddDetailNcmFkCest'],['static' => 1]), "Adicionar");
        $button_adicionar_Ncm_fk_cest->addStyleClass('btn-default');
        $button_adicionar_Ncm_fk_cest->setImage('fas:plus #2ecc71');
        $n_cest->setDisplayMask('{n_cest}');
        $Ncm_fk_cest_->setDisplayMask('{n_ncm}');

        $button_adicionar_Ncm_fk_cest->id = '60d4a5545b9a3';

        $n_cest->setSize('100%');
        $Ncm_fk_cest_->setSize(200);
        $Ncm_fk_cest_->setSize('100%');
        $descricao->setSize('96%', 310);

        $this->detailFormNcmFkCest = new BootstrapFormBuilder('detailFormNcmFkCest');
        $this->detailFormNcmFkCest->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormNcmFkCest->setProperty('class', 'form-horizontal builder-detail-form');

        $row1 = $this->detailFormNcmFkCest->addFields([new TLabel("NCM:", null, '14px', null, '100%'),$Ncm_fk_cest_,$Ncm_fk_cest_]);
        $row1->layout = [' col-sm-6 col-lg-12'];

        $row2 = $this->detailFormNcmFkCest->addFields([$button_adicionar_Ncm_fk_cest],[],[]);
        $row2->layout = ['col-sm-3','col-sm-3', 'col-sm-6'];

        $row3 = $this->detailFormNcmFkCest->addFields([new THidden('Ncm_fk_cest__row__id')]);
        $this->Ncm_fk_cest_criteria = new TCriteria();

        $this->Ncm_fk_cest_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->Ncm_fk_cest_list->disableHtmlConversion();;
        $this->Ncm_fk_cest_list->generateHiddenFields();
        $this->Ncm_fk_cest_list->setId('Ncm_fk_cest_list');

        $this->Ncm_fk_cest_list->style = 'width:100%';
        $this->Ncm_fk_cest_list->class .= ' table-bordered';

        $column_Ncm_fk_cest_ = new TDataGridColumn('', "CEST", 'left');
        $column_Ncm_fk_cest_ = new TDataGridColumn('', "NCM", 'left');

        $column_Ncm_fk_cest__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_Ncm_fk_cest__row__data->setVisibility(false);

        $action_onEditDetailNcm = new TDataGridAction(array('CestForm', 'onEditDetailNcm'));
        $action_onEditDetailNcm->setUseButton(false);
        $action_onEditDetailNcm->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailNcm->setLabel("Editar");
        $action_onEditDetailNcm->setImage('far:edit #478fca');
        $action_onEditDetailNcm->setFields(['__row__id', '__row__data']);

        $this->Ncm_fk_cest_list->addAction($action_onEditDetailNcm);
        $action_onDeleteDetailNcm = new TDataGridAction(array('CestForm', 'onDeleteDetailNcm'));
        $action_onDeleteDetailNcm->setUseButton(false);
        $action_onDeleteDetailNcm->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailNcm->setLabel("Excluir");
        $action_onDeleteDetailNcm->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailNcm->setFields(['__row__id', '__row__data']);

        $this->Ncm_fk_cest_list->addAction($action_onDeleteDetailNcm);

        $this->Ncm_fk_cest_list->addColumn($column_Ncm_fk_cest_);
        $this->Ncm_fk_cest_list->addColumn($column_Ncm_fk_cest_);

        $this->Ncm_fk_cest_list->addColumn($column_Ncm_fk_cest__row__data);

        $this->Ncm_fk_cest_list->createModel();
        $this->detailFormNcmFkCest->addContent([$this->Ncm_fk_cest_list]);

        $row4 = $this->form->addFields([new TFormSeparator("1º informar o NCM", '#333333', '18', '#ff0091'),$this->detailFormNcmFkCest],[new TFormSeparator("2º informar o CEST", '#333333', '18', '#ff0091'),new TLabel("Número do CEST:", null, '14px', null, '100%'),$n_cest,new TLabel("Descrição:", null, '14px', null, '100%'),$descricao]);
        $row4->layout = [' col-sm-2 col-lg-6',' col-sm-4 col-lg-6'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        parent::add($this->form);

    }

    public  function onAddDetailNcmFkCest($param = null) 
    {
        try
        {
            $data = $this->form->getData();

                $errors = [];
                $requiredFields = [];
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

                $__row__id = !empty($data->Ncm_fk_cest__row__id) ? $data->Ncm_fk_cest__row__id : 'b'.uniqid();

                TTransaction::open(self::$database);

                $grid_data = new Ncm();
                $grid_data->__row__id = $__row__id;
                $grid_data-> = $data->Ncm_fk_cest_;
                $grid_data-> = $data->Ncm_fk_cest_;

                $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
                $__row__data['__row__id'] = $__row__id;
                $__row__data['__display__'][''] =  $param['Ncm_fk_cest_'] ?? null;
                $__row__data['__display__'][''] =  $param['Ncm_fk_cest_'] ?? null;

                $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
                $row = $this->Ncm_fk_cest_list->addItem($grid_data);
                $row->id = $grid_data->__row__id;

                TDataGrid::replaceRowById('Ncm_fk_cest_list', $grid_data->__row__id, $row);

                TTransaction::close();

                $data = new stdClass;
                $data->Ncm_fk_cest_ = '';
                $data->Ncm_fk_cest_ = '';
                $data->Ncm_fk_cest__row__id = '';

                TForm::sendData(self::$formName, $data);
                TScript::create("
                   var element = $('#60d4a5545b9a3');
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

    public static function onEditDetailNcm($param = null) 
    {
        try
        {

                $__row__data = unserialize(base64_decode($param['__row__data']));
                $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

                $data = new stdClass;
                $data->Ncm_fk_cest_ = $__row__data->__display__-> ?? null;
                $data->Ncm_fk_cest_ = $__row__data->__display__-> ?? null;
                $data->Ncm_fk_cest__row__id = $__row__data->__row__id;

                TForm::sendData(self::$formName, $data);
                TScript::create("
                   var element = $('#60d4a5545b9a3');
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

    public static function onDeleteDetailNcm($param = null) 
    {
        try
        {

                $__row__data = unserialize(base64_decode($param['__row__data']));

                $data = new stdClass;
                $data->Ncm_fk_cest_ = '';
                $data->Ncm_fk_cest_ = '';
                $data->Ncm_fk_cest__row__id = '';

                TForm::sendData(self::$formName, $data);

                TDataGrid::removeRowById('Ncm_fk_cest_list', $__row__data->__row__id);

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

            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Cest(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            $Ncm_fk_cest_items = $this->storeMasterDetailItems('Ncm', 'cest', 'Ncm_fk_cest', $object, $param['Ncm_fk_cest_list___row__data'] ?? [], $this->form, $this->Ncm_fk_cest_list, function($masterObject, $detailObject){ 

                //code here

            }); 


            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

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

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Cest($key); // instantiates the Active Record 

                $Ncm_fk_cest_items = $this->loadMasterDetailItems('Ncm', 'cest', 'Ncm_fk_cest', $object, $this->form, $this->Ncm_fk_cest_list, $this->Ncm_fk_cest_criteria, function($masterObject, $detailObject, $objectItems){ 

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

}

