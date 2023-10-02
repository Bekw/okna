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
    public function stockListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('stock-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->stock_tab__modify($params);
            return;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('stock-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->stock_tab__delete($a['stock_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->stock_tab__read()['value'];
    }
    public function stockEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->stock_id = $this->_getParam('stock_id', 0);

        $this->view->row = $ob->stock_tab__get($this->view->stock_id)['value'];
    }
    public function categoryListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('category-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->category_group_tab__modify($params);
            return;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('category-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->category_group_tab__delete($a['category_group_id']);
            $this->view->result = $result;
        }
        if($mode == 'set-active'){
            $this->_helper->AjaxContext()->addActionContext('category-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->category_group_tab__is_active($a['category_group_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->category_group_tab__read()['value'];

    }
    public function categoryGroupEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->category_group_id = $this->_getParam('category_group_id', 0);

        $this->view->row = $ob->category_group_tab__get($this->view->category_group_id)['value'];
    }
    function categoryFormAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Dict();
        $this->view->category_group_id = $category_group_id = $this->_getParam("category_group_id", 0);

        $mode = $this->_getParam('mode', '');
        if($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('category-form', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->category_tab__modify($a);
            $this->view->result = $result;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('category-form', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->category_tab__delete($a['category_id']);
            $this->view->result = $result;
        }
        if($mode == 'set-active'){
            $this->_helper->AjaxContext()->addActionContext('category-form', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->category_tab__is_active($a['category_id']);
            $this->view->result = $result;
        }

        $this->view->row = $ob->category_tab__read($category_group_id)['value'];
    }
    function categoryEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Dict();

        $this->view->category_group_id = $category_group_id = $this->_getParam("category_group_id", 0);
        $this->view->category_id = $category_id = $this->_getParam("category_id", 0);
        $this->view->row = $ob->category_tab__get($this->view->category_id)['value'];
    }
    public function brandListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('brand-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->brand_tab__modify($params);
            return;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('brand-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->brand_tab__delete($a['brand_id']);
            $this->view->result = $result;
        }
        if($mode == 'set-active'){
            $this->_helper->AjaxContext()->addActionContext('brand-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->brand_tab__is_active($a['brand_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->brand_tab__read()['value'];
    }
    public function brandEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->brand_id = $this->_getParam('brand_id', 0);

        $this->view->row = $ob->brand_tab__get($this->view->brand_id)['value'];
    }
}
