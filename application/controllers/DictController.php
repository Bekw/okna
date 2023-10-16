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
        if ($mode == 'upd-product'){
            $this->_helper->AjaxContext()->addActionContext('stock-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->product_stock__modify($params);
            return;
        }
        if($mode == 'del-product'){
            $this->_helper->AjaxContext()->addActionContext('stock-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->product_stock__del($a['product_stock_id']);
            $this->view->result = $result;
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

    public function productListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('product-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->product_tab__modify($params);
            return;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('product-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->product_tab__delete($a['product_id']);
            $this->view->result = $result;
        }
        if($mode == 'set-avail'){
            $this->_helper->AjaxContext()->addActionContext('product-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->product_tab__is_avail($a['product_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->product_tab__read()['value'];
    }
    public function productEditAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');
        $this->view->product_id = $this->_getParam('product_id', 0);

        $this->view->row = $ob->product_tab__get($this->view->product_id)['value'];
        $this->view->row_img = $ob->product_img_tab__read($this->view->product_id)['value'];
        $this->view->row_category = $ob->category__read_fs()['value'];
        $this->view->row_tag = $ob->tag__read_fs()['value'];
        $this->view->row_brand = $ob->brand__read_fs()['value'];

        if (isset($_POST["save"])) {
            $a = $this->_getAllParams();
            $result = $ob->product_tab__modify($a);
            if ($result['status'] == false) {
                $this->view->globalError = $result['error'];
                $this->view->row = $a;
                return;
            }
            $this->_redirector->gotoUrl('/dict/product-edit/product_id/'.$result['value']);
        }
        if ($mode == 'upd-img'){
            $this->_helper->AjaxContext()->addActionContext('product-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->product_img_tab__upd($params);
            return;
        }
        if ($mode == 'del-img'){
            $this->_helper->AjaxContext()->addActionContext('product-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->product_img_tab__del($params['product_img_id']);
            return;
        }
        if ($mode == 'set-main'){
            $this->_helper->AjaxContext()->addActionContext('product-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->product_img_tab__set_main($params['product_img_id']);
            return;
        }
    }
    public function stockProductFormAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->stock_id = $this->_getParam('stock_id', 0);

        $this->view->row = $ob->product_stock__read($this->view->stock_id)['value'];
    }
    function stockProductEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Dict();

        $this->view->stock_id = $this->_getParam("stock_id", 0);
        $this->view->row_product = $ob->product_tab_read_fs()['value'];
    }
    public function productImgEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Dict();

        $this->view->product_id = $this->_getParam("product_id", 0);
    }
    public function bannerListAction(){
        $ob = new Application_Model_DbTable_Dict();
        $mode = $this->_getParam('mode', '');

        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('banner-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->banner_tab__modify($params);
            return;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('banner-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->banner_tab__delete($a['banner_id']);
            $this->view->result = $result;
        }
        if($mode == 'set-active'){
            $this->_helper->AjaxContext()->addActionContext('banner-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->banner_tab__is_active($a['banner_id']);
            $this->view->result = $result;
        }
        $this->view->row = $ob->banner_tab__read()['value'];
    }
    public function bannerEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Dict();

        $this->view->banner_id = $this->_getParam('banner_id', 0);

        $this->view->row = $ob->banner_tab__get($this->view->banner_id)['value'];
    }
}
