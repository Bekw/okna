<?php
require_once 'ParentController.php';

class IndexController extends ParentController{

    public function preDispatch(){
        $this->session = new Zend_Session_Namespace('Default');

        $action_name =  (Zend_Controller_Front::getInstance()->getRequest()->getActionName());
        //Прописать action-ы в которых нужна сессия
        if ($action_name == 'myaction' || $action_name == 'myaction2'){
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
    }

    public function indexAction(){
//        $this->_redirect('/system/login/');
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();

        $mode = $this->_getParam('mode', '');
        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('index', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->create_landing_request($params);
            return;
        }
        $this->view->row = $ob->package_read_fs()['value'];
    }
}

