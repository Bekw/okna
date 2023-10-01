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
}
