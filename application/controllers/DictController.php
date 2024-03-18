<?php
require_once 'ParentController.php';

class DictController extends ParentController{

    public function preDispatch(){
        $this->session = new Zend_Session_Namespace('Global');
        $action_name =  (Zend_Controller_Front::getInstance()->getRequest()->getActionName());
        //Прописать action-ы в которых нужна сессия
        if ($action_name == 'action-name')
        {
            $params = $this->_getAllParams();
            foreach ($params as $key => $value) {
                $this->session->param[$key] = $value;
            }
        }

        $params = $this->_getAllParams();
        foreach ($params as $key => $value) {
            if (is_array($value)){
                $this->param[$key] = $value;
            } else {
                $this->param[$key] = htmlspecialchars($value, ENT_QUOTES);
            }
        }

    }

    function getParam($param_name, $second_value=null){
        if (isset($this->param[$param_name])){
            return $this->param[$param_name];
        } else{
            return $second_value;
        }
    }

    function getAllParams(){
        return $this->param;
    }

    public function init(){
        $this->_helper->layout->setLayout('layout-system');
        parent::init();
        $action_name =  (Zend_Controller_Front::getInstance()->getRequest()->getActionName());
        $actions_except = array('send-email', 'send-tg');
        if (!in_array($action_name, $actions_except)){
            if (!Zend_Auth::getInstance()->hasIdentity()){
                $this->_redirect('/system/login');
            }
        }
    }

    public function permissionAction(){

    }
    public function indexJsonAction(){
        $mode = $this->_getParam('mode', '');
        $this->view->mode = $mode;
    }
    public function documentTypeListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('document-type-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->document_type_upd($params);
            return;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('document-type-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->document_type_del($a['document_type_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->document_type_read()['value'];
    }
    public function documentTypeEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->document_type_id = $this->_getParam('document_type_id', 0);

        $this->view->row = $ob->document_type_get($this->view->document_type_id)['value'];
    }
    public function paymentTypeListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('payment-type-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->payment_type_upd($params);
            return;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('payment-type-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->payment_type_del($a['payment_type_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->payment_type_read()['value'];
    }
    public function paymentTypeEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->payment_type_id = $this->_getParam('payment_type_id', 0);

        $this->view->row = $ob->payment_type_get($this->view->payment_type_id)['value'];
    }
    public function employeeRequestTypeListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('employee-request-type-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->employee_request_type_upd($params);
            return;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('employee-request-type-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->employee_request_type_del($a['employee_request_type_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->employee_request_type_read()['value'];
    }
    public function employeeRequestTypeEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->employee_request_type_id = $this->_getParam('employee_request_type_id', 0);

        $this->view->row = $ob->employee_request_type_get($this->view->employee_request_type_id)['value'];
    }

    public function materialTypeListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('material-type-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->material_type_upd($params);
            return;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('material-type-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->material_type_del($a['material_type_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->material_type_read()['value'];
    }

    public function materialTypeEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->material_type_id = $this->_getParam('material_type_id', 0);

        $this->view->row = $ob->material_type_get($this->view->material_type_id)['value'];
    }
    function materialFormAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Dict();
        $this->view->material_type_id = $material_type_id = $this->_getParam("material_type_id", 0);

        $mode = $this->_getParam('mode', '');
        if($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('material-form', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->material_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('material-form', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->material_del($a['material_id']);
            $this->view->result = $result;
        }

        $this->view->row = $ob->material_read($material_type_id)['value'];
    }
    function materialEditAction(){
        $ob = new Application_Model_DbTable_Dict();

        $this->view->material_id = $this->_getParam("material_id", 0);

        $this->view->row_type = $ob->material_type_read_fs()['value'];
        $this->view->row_unit_type = $ob->unit_type_read_fs()['value'];
        $this->view->row = $ob->material_get($this->view->material_id)['value'];
        if($this->view->material_id <> 0){
            $this->view->row_price = $ob->material_price_read($this->view->material_id)['value'];
            $this->view->package_row = $ob->material_package_read($this->view->material_id)['value'];
        }
    }
    function materialPriceFormAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Dict();

        $this->view->material_id = $this->_getParam("material_id", 0);
        $this->view->row = $ob->material_price_read($this->view->material_id)['value'];
    }
    public function materialListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('material-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->material_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'upd-price'){
            $this->_helper->AjaxContext()->addActionContext('material-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->material_price_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'upd-package'){
            $this->_helper->AjaxContext()->addActionContext('material-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->material_package_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('material-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->material_del($a['material_id']);
            $this->view->result = $result;
        }
        if($mode == 'del-price'){
            $this->_helper->AjaxContext()->addActionContext('material-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->material_price_del($a['material_price_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->material_read()['value'];
    }
    function materialPriceEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->material_id = $this->_getParam("material_id", 0);
    }

    function stageMarkListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('stage-mark-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->upd_stage_mark($a);
            $this->view->result = $result;
        }
        $this->view->row = $ob->stage_read()['value'];
    }
    function markTypeFormAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->stage_id = $this->_getParam("stage_id", 0);

        $this->view->row = $ob->stage_mark_read($this->view->stage_id)['value'];
    }
    public function employeeRequestAction(){
        $ob = new Application_Model_DbTable_Dict();

        $this->view->row_created = $ob->employee_request_read(0)['value'];
        $this->view->row_in_process = $ob->employee_request_read(1)['value'];
        $this->view->row_done = $ob->employee_request_read(2)['value'];
        $this->view->row_cancel = $ob->employee_request_read(3)['value'];
    }
    public function employeeRequestEditAction(){
        $ob = new Application_Model_DbTable_Dict();
        $this->view->employee_request_id = $this->_getParam("employee_request_id", 0);
        $mode = $this->_getParam('mode', '');

        if($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('employee-request-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->employee_request_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'upd-doc'){
            $this->_helper->AjaxContext()->addActionContext('employee-request-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->employee_request_doc_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'del-doc'){
            $this->_helper->AjaxContext()->addActionContext('employee-request-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->employee_request_doc_del($a['employee_request_doc_id']);
            $this->view->result = $result;
        }
        if($mode == 'upd-list'){
            $this->_helper->AjaxContext()->addActionContext('employee-request-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->employee_request_list_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'del-list'){
            $this->_helper->AjaxContext()->addActionContext('employee-request-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->employee_request_list_del($a['employee_request_list_id']);
            $this->view->result = $result;
        }
        if($mode == 'upd-status'){
            $this->_helper->AjaxContext()->addActionContext('employee-request-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->employee_request_status_upd($a);
            $this->view->result = $result;
        }

        $this->view->row = $ob->employee_request_get($this->view->employee_request_id)['value'];
        $this->view->row_type = $ob->employee_request_type_read()['value'];
        if ($this->view->employee_request_id <> 0){
            $this->view->row_doc = $ob->employee_request_doc_read($this->view->employee_request_id)['value'];
            $this->view->row_list = $ob->employee_request_list_read($this->view->employee_request_id)['value'];
        }
    }
    function employeeRequestDocEditAction(){
        $this->_helper->layout->disableLayout();
        $this->view->employee_request_id = $this->_getParam("employee_request_id", 0);

        $this->view->employee_request_doc_id = $this->_getParam("employee_request_doc_id", 0);
    }
    function employeeRequestListEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Dict();

        $this->view->employee_request_id = $this->_getParam("employee_request_id", 0);
        $this->view->employee_request_list_id = $this->_getParam("employee_request_list_id", 0);

        $this->view->row_employee = $ob->employee_read_fs()['value'];
    }
    public function employeeRequestAcceptAction(){
        $ob = new Application_Model_DbTable_Dict();

        $this->view->row = $ob->employee_request_accept_read()['value'];
    }
    public function employeeRequestDetailAction(){
        $ob = new Application_Model_DbTable_Dict();
        $this->view->employee_request_id = $this->_getParam("employee_request_id", 0);
        $mode = $this->_getParam('mode', '');

        if($mode == 'upd-status'){
            $this->_helper->AjaxContext()->addActionContext('employee-request-detail', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->employee_request_list_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'upd-list-status'){
            $this->_helper->AjaxContext()->addActionContext('employee-request-detail', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->employee_request_list_status_upd($a);

            $this->view->result = $result;
        }

        $this->view->row = $ob->employee_request_get($this->view->employee_request_id)['value'];
        $this->view->row_type = $ob->employee_request_type_read()['value'];
        $this->view->row_doc = $ob->employee_request_doc_read($this->view->employee_request_id)['value'];
        $this->view->row_list = $ob->employee_request_list_read($this->view->employee_request_id)['value'];
    }
    function employeeRequestListAcceptAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Dict();

        $this->view->employee_request_list_id = $this->_getParam("employee_request_list_id", 0);
        $this->view->employee_request_id = $this->_getParam("employee_request_id", 0);
        $this->view->accept_type = $this->_getParam("accept_type", 0);

        $this->view->row_employee = $ob->employee_read_fs()['value'];
    }
}
