<?php
/**
 * @license LGPL
 */

/**
 * Vytvoří editovatelný datagrid
 *
 * Vyžaduje jako formulář AppForm,
 * Vyžaduje verzi Nette >425 (http://forum.nettephp.com/cs/2092-rev-425-beforeprepare-prepare-view-deprecated-co-s-tim, http://forum.nettephp.com/cs/2065-rev-412-nette-application-control-ma-arrayaccess-interface)
 */
class EditableDatagrid extends DataGrid {

    const TEXT = "TEXT";
    const TEXTAREA = "TEXTAREA";
    const SELECT = "SELECT";
    const DATE = "DATE";

    static public $dgSnippetName = "grid";

    /**
     * Editační formulář
     * @var AppForm
     */
    private $editForm;

    /**
     * Editable fields
     * @var array
     */
    public $editableFields = array();

    /**
     * Callback - save data
     * @var array
     */
    public $onDataReceived = array();

    /**
     * Callback - save data
     * @var array
     */
    public $onInvalidDataReceived = array();

    /**
     * How much rows can be on one page in fast mode.
     * @var int
     */
    public $maxDataOnPageInFastMode = 20;

    /**
     * Supported types of
     * @var array
     */
    static public $supportedTypes = array(
        self::TEXT,
        self::TEXTAREA,
        self::SELECT,
        self::DATE
    );

    /**
     * Getts form
     * @return AppForm
     */
    function getEditForm(){
        if(!($this->editForm instanceof AppForm))
            throw new InvalidStateException("\$form is not instance of AppForm. \$form is type ".gettype($this->editForm));
        return $this->editForm;
    }

    /**
     * Setts form
     * @param AppForm $form
     * @return EditableDatagrid
     */
    function setEditForm(AppForm $form){
        $this->editForm = $form;
        return $this;
    }

    /**
     * Adds editable column
     * @param string $name
     */
    function addEditableField($name,$type=null){
        $form = $this->getEditForm();
        $formCol = $form[$name]; // Is column in Form?
        $this[$name]; // Is column in datagrid?
        if($type===null){
            if($formCol instanceof TextArea){
                $type = self::TEXTAREA;
            }elseif($formCol instanceof DatePicker){
                $type = self::DATE;
            }elseif($formCol instanceof TextInput){
                $type = self::TEXT;
            }elseif($formCol instanceof SelectBox){
                $type = self::SELECT;
            }else{
                throw new NotSupportedException("Input with type '".get_class($formCol)."' is not supported.");
            }
        }

        if(!in_array($type, self::$supportedTypes))
            throw new NotSupportedException("Can't add field with type '".$type."'. This type is not supported!");

        $store = new EditableColumn();
        $store->type = $type;
        $store->formControl = $formCol;
        $store->parent = $this;
        $store->columnName = $formCol->control->name;
        if($type === self::SELECT){
            $store->dictionary = $formCol->items;
        }
        $this->editableFields[$name] = $store;
    }

    function  __construct() {
        parent::__construct();
        $this->getRenderer()->onRowRender[] = array($this,"fce_onRowRender");
    }

    function fce_onRowRender(Html $row, DibiRow $data){
        $key = $this->keyName;
        $row->id = $this->getUniqueId()."___id___".$data->$key;
    }

    function renderEditable(){
        $this->render();

        //if(SnippetHelper::$outputAllowed){ // Template si to řeší sama
            $template = $this->createTemplate();
            $template->setFile(dirname(__FILE__)."/EditableDatagrid.phtml");
            $template->render();
        //}
    }

    function handleSaveColumnData($nazevPolicka, $data, $cisloRadku, $dataGrid,$origSha1){
        if($this->getUniqueId() != $dataGrid)
            throw new InvalidArgumentException("Invalid datagrid id.");
        if(!isSet($this->editableFields[$nazevPolicka]))
            throw new InvalidStateException("Field '".$nazevPolicka."' is not editable.");
        $form = $this->getEditForm();
        $policko = $form[$nazevPolicka]; // Vyhodí výjimku pokud není nalezeno

        $origValue = $policko->value;
        $errors = $policko->errors;

        $policko->value = $data;

        if(!IsSet($this->presenter->payload->editableDatagrid))
            $this->presenter->payload->editableDatagrid = (object)null;
        $payload = $this->presenter->payload->editableDatagrid;

        $success = $policko->rules->validate();
        $payload->success = $success;
        
        if($success){
            $this->onDataReceived($cisloRadku,$policko,$origSha1);
        }else{
            foreach($form->errors AS $error){
                $this->addError($error);
            }
            $this->onInvalidDataReceived($cisloRadku,$policko,$origSha1);
        }
        $policko->value = $origValue;
    }

    function addError($text){
        if(!IsSet($this->presenter->payload->editableDatagrid))
            $this->presenter->payload->editableDatagrid = (object)null;
        $payload = $this->presenter->payload->editableDatagrid;
        $payload->errors[] = $text;
    }
}