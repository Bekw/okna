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
}
