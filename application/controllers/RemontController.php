<?php
require_once 'ParentController.php';

class RemontController extends ParentController{

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
    public function createRequestAction(){
        $ob = new Application_Model_DbTable_Remont();

        $mode = $this->_getParam('mode', '');
        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('create-request', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->create_client_request($params);
            return;
        }
        $this->view->row = [];
    }
    public function clientRequestListAction(){
        $ob = new Application_Model_DbTable_Remont();
        $client_request_status_id = $this->_getParam('client_request_status_id', 0);

        $this->view->row = $ob->client_request_read($client_request_status_id)['value'];
    }
    public function clientRequestEditAction(){
        $ob = new Application_Model_DbTable_Remont();
        $client_request_id = $this->_getParam('client_request_id', 0);
        $this->view->client_request_id = $client_request_id;

        $mode = $this->_getParam('mode', '');
        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->upd_client_request($params);
            return;
        }
        if ($mode == 'upd-wish'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->upd_client_wish($params);
            return;
        }
        if ($mode == 'upd-doc'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->document_upd($params);
            return;
        }
        if ($mode == 'upd-payment'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->payment_upd($params);
            return;
        }
        if($mode == 'del-doc'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->document_del($a['document_id']);
            $this->view->result = $result;
        }
        if($mode == 'del-payment'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->payment_del($a['payment_id']);
            $this->view->result = $result;
        }
        if($mode == 'create-remont'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->create_remont($a['client_request_id']);
            $this->view->result = $result;
        }

        $this->view->row_mp = $ob->read_employee_fs('MP')['value'];
        $this->view->row = $ob->client_request_get($client_request_id)['value'];
    }
    public function documentListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $client_request_id = $this->_getParam('client_request_id', 0);
        $this->view->client_request_id = $client_request_id;

        $this->view->row = $ob->document_read($client_request_id)['value'];
    }
    public function documentEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $client_request_id = $this->_getParam('client_request_id', 0);
        $this->view->client_request_id = $client_request_id;

        $this->view->row_type = $ob->document_type_read()['value'];
    }
    public function paymentListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $client_request_id = $this->_getParam('client_request_id', 0);
        $this->view->client_request_id = $client_request_id;

        $this->view->row = $ob->payment_read($client_request_id)['value'];
    }
    public function paymentEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $client_request_id = $this->_getParam('client_request_id', 0);
        $this->view->client_request_id = $client_request_id;

        $this->view->row_type = $ob->payment_type_read()['value'];
    }
}

