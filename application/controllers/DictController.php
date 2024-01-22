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
    public function briefTypeListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('brief-type-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->brief_type_upd($params);
            return;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('brief-type-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->brief_type_del($a['brief_type_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->brief_type_read()['value'];
    }
    public function briefTypeEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->brief_type_id = $this->_getParam('brief_type_id', 0);

        $this->view->row = $ob->brief_type_get($this->view->brief_type_id)['value'];
    }
    function briefItemFormAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Dict();
        $this->view->brief_type_id = $brief_type_id = $this->_getParam("brief_type_id", 0);

        $mode = $this->_getParam('mode', '');
        if($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('brief-item-form', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->brief_item_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('brief-item-form', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->brief_item_del($a['brief_item_id']);
            $this->view->result = $result;
        }

        $this->view->row = $ob->brief_item_read($brief_type_id)['value'];
    }
    function briefItemEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Dict();

        $this->view->brief_type_id = $this->_getParam("brief_type_id", 0);
        $this->view->brief_item_id = $this->_getParam("brief_item_id", 0);
        $this->view->row = $ob->brief_item_get($this->view->brief_item_id)['value'];
    }
    public function measureTypeListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('measure-type-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->measure_type_upd($params);
            return;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('measure-type-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->measure_type_del($a['measure_type_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->measure_type_read()['value'];
    }
    public function measureTypeEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->measure_type_id = $this->_getParam('measure_type_id', 0);

        $this->view->row = $ob->measure_type_get($this->view->measure_type_id)['value'];
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
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('material-edit', 'json')->initContext('json');
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
    function contractorListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('contractor-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->contractor_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('contractor-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->contractor_del($a['contractor_id']);
            $this->view->result = $result;
        }
        if($mode == 'set-active'){
            $this->_helper->AjaxContext()->addActionContext('contractor-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->contractor_is_active($a['contractor_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->contractor_read()['value'];
    }
    function contractorEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->contractor_id = $this->_getParam("contractor_id", 0);

        $this->view->row = $ob->contractor_get($this->view->contractor_id)['value'];
    }
    function roomListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('room-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->room_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('room-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->room_del($a['room_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->room_read()['value'];
    }
    function roomEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->room_id = $this->_getParam("room_id", 0);

        $this->view->row = $ob->room_get($this->view->room_id)['value'];
    }
}
