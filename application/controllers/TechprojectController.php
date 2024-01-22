<?php
require_once 'ParentController.php';

class TechprojectController extends ParentController{

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
    public function remontListAction(){
        $ob = new Application_Model_DbTable_Techproject();

        $this->view->row = $ob->remont_read_employee()['value'];
    }
    public function remontEditAction(){
        $ob = new Application_Model_DbTable_Techproject();
        $remont = new Application_Model_DbTable_Remont();
        $remont_id = $this->_getParam('remont_id', 0);

        $this->view->remont_id = $remont_id;

        $mode = $this->_getParam('mode', '');
        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $remont->remont_upd($params);
            return;
        }

        $this->view->row = $remont->remont_get($remont_id)['value'];
        $this->view->row_stage = $remont->remont_stage_read($remont_id)['value'];
    }
}

