<?php
require_once 'ParentController.php';

class SystemController extends ParentController{

    public function preDispatch(){
        $action_name =  (Zend_Controller_Front::getInstance()->getRequest()->getActionName());
        if ($action_name == 'index-json' && $this->_getParam('mode', '') == 'get-menu-cnt-ajax'){
            session_write_close();
        }
        //Прописать action-ы в которых нужна сессия
        if ($action_name == 'notification-list' || $action_name == 'myaction2'){
            $params = $this->_getAllParams();
            foreach ($params as $key => $value) {
                $this->session->param[$key] = $value;
            }
        }
    }

    public function init(){
        $this->_helper->layout->setLayout('layout-system');
        parent::init();
        $this->user_id = 0;
        $action_name =  (Zend_Controller_Front::getInstance()->getRequest()->getActionName());
        $actions_except = array('login',
                                'send-mail',
                                'check-mail',
                                'blank-table',
                                'login-url',
                                'redirect-email',);
        if (!in_array($action_name, $actions_except)){
            if (!Zend_Auth::getInstance()->hasIdentity()){
                Zend_Auth::getInstance()->clearIdentity();
                Zend_Session::destroy();
                $this->_redirect('/system/login');
            }
            if (getCurEmployee() == 0){
                Zend_Auth::getInstance()->clearIdentity();
                Zend_Session::destroy();
                $this->_redirect('/system/login');
            }
        }
        if (Zend_Auth::getInstance()->hasIdentity()){
            $this->user_id = getCurUser();
        }
    }

    public function dbLogAction(){
        $dir = $_SERVER['DOCUMENT_ROOT']. '/log/db_func';
        $file_name = $dir . "/" . Zend_Session::getId() . ".log";

        if($this->getRequest()->isPost()){
            $a = $this->_getAllParams();
            if (isset($a['btn_active'])){
                $this->session->is_db_func_log = true;
            }
            if (isset($a['btn_clear'])){
                if (file_exists($file_name)) {
                    unlink($file_name);
                    $this->session->is_db_func_log = false;
                }
            }
        }
        if (file_exists($file_name)) {
            $a = file_get_contents($file_name);
            $this->view->content = $a;
        }

    }

    public function indexJsonAction(){
        $mode = $this->_getParam('mode', '');
        $this->view->mode = $mode;

        if($mode == "block-employee"){
            $ob = new Application_Model_DbTable_System();
            $employee_id = $this->_getParam('employee_id', 0);
            $result = $ob->block_employee($employee_id);
            $this->view->result = $result;
        }

        if($mode == "unblock-employee"){
            $ob = new Application_Model_DbTable_System();
            $employee_id = $this->_getParam('employee_id', 0);
            $result = $ob->unblock_employee($employee_id);
            $this->view->result = $result;
        }

        if($mode == "del-employee"){
            $ob = new Application_Model_DbTable_System();
            $employee_id = $this->_getParam('employee_id', 0);
            $result = $ob->del_employee($employee_id);
            $this->view->result = $result;
        }

        if($mode == "upd-employee"){
            $ob = new Application_Model_DbTable_System();
            $a = $this->_getAllParams();
            $result = $ob->upd_employee($a);
            if ($result['status'] == false){
                $a['status'] = false;
                $a['error'] = $result['error'];
                $this->view->result = $a;
                return;
            }
            $this->view->result = $result;
        }

        if($mode == "upd-group"){
            $ob = new Application_Model_DbTable_System();
            $a = $this->_getAllParams();
            $result = $ob->upd_group($a);
            if ($result['status'] == false){
                $a['status'] = false;
                $a['error'] = $result['error'];
                $this->view->result = $a;
                return;
            }
            $this->view->result = $result;
        }

        if($mode == "del-group"){
            $ob = new Application_Model_DbTable_System();
            $group_id = $this->_getParam('group_id', 0);
            $result = $ob->del_group($group_id);
            $this->view->result = $result;
        }

        if($mode == "upd-menu"){
            $ob = new Application_Model_DbTable_System();
            $a = $this->_getAllParams();
            $result = $ob->upd_menu($a);
            if ($result['status'] == false){
                $a['status'] = false;
                $a['error'] = $result['error'];
                $this->view->result = $a;
                return;
            }
            $this->view->result = $result;
        }
        if($mode == "del-menu"){
            $ob = new Application_Model_DbTable_System();
            $menu_id = $this->_getParam('menu_id', 0);
            $result = $ob->del_menu($menu_id);
            $this->view->result = $result;
        }
        if($mode == "group-menu-link"){
            $ob = new Application_Model_DbTable_System();
            $group_id = $this->_getParam('group_id', 0);
            $menu_id = $this->_getParam('menu_id', 0);
            $result = $ob->group_menu_link($group_id, $menu_id);
            $this->view->result = $result;
        }

        if($mode == "menu-read-by-group"){
            $ob = new Application_Model_DbTable_System();
            $group_id = $this->_getParam('group_id', 0);
            $row = $ob->menu_read($group_id);
            $this->view->result = $row;
        }
        if($mode == "employee-group-link"){
            $ob = new Application_Model_DbTable_System();
            $group_id = $this->_getParam('group_id', 0);
            $employee_id = $this->_getParam('employee_id', 0);
            $result = $ob->employee_group_link($group_id, $employee_id);
            $this->view->result = $result;
        }
        if($mode == "group-read-by-employee"){
            $ob = new Application_Model_DbTable_System();
            $employee_id = $this->_getParam('employee_id', 0);
            $row = $ob->group_read($employee_id);
            $this->view->result = $row;
        }
        if($mode == "reset-password"){
            $ob = new Application_Model_DbTable_System();
            $employee_id = $this->_getParam('employee_id', 0);
            $result = $ob->reset_password($employee_id);
            $this->view->result = $result;
        }
    }

    public function blankAction(){
        //$this->_helper->layout()->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);
    }

    private function login($login, $password, $by_token = false){
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('admin.employee_view');
        $authAdapter->setIdentityColumn('email');
        $authAdapter->setCredentialColumn('password');

        $ob = new Application_Model_DbTable_System();

        $authAdapter->setIdentity(strtolower(trim($login)));
        if($by_token){
            $authAdapter->setCredential($password);
        }else{
            $authAdapter->setCredential(md5($password."dki#ds%$"));
        }
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);

        if ($result->isValid()){
            $identity = $authAdapter->getResultRowObject();
            $authStorage = $auth->getStorage();
            $authStorage->write($identity);
            if($identity->is_active == 0){
                Zend_Auth::getInstance()->clearIdentity();
                return 'blocked';
            }
            return 'valid';
        }
        else{
            $result = $ob->employee_failed_login_upd($login);
            return 'no_valid';
        }
    }

    public function loginAction(){
        $ob = new Application_Model_DbTable_System();
        $mode = $this->_getParam('mode', '');

        if (Zend_Auth::getInstance()->hasIdentity()){
            $this->_redirect('/system/blank/');
        }
        $this->_helper->layout()->disableLayout();
        if($this->getRequest()->isPost()){
            $this->view->login = $this->_getParam('login', 'empty');
            $password = $this->_getParam('password', 'empty');
            $validate = $this->login($this->view->login, $password);
            if ($validate == 'valid'){
                $ob->employee_set_last_login();
                $this->_redirect('/system/blank/');
            } else if($validate == 'blocked'){
                $this->view->error = "Пользователь заблокирован";
            } else{
                $this->view->error = "Неверный логин или пароль";
            }
        }
    }

    public function loginUrlAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_System();
        $token = $this->_getParam('token', '');
        $row = $ob->employee_info_by_token_get($token)['value'];
        if($row != null){
            $validate = $this->login($row['email'], $row['password'], true);
            if ($validate == 'valid'){
                //$ob->employee_set_last_login();
                $this->_redirect('/system/blank/');
            }else{
                Zend_Auth::getInstance()->clearIdentity();
                Zend_Session::destroy();
                $this->_redirect('/system/login');
            }
        }else{
            Zend_Auth::getInstance()->clearIdentity();
            Zend_Session::destroy();
            $this->_redirect('/system/login');
        }
    }

    public function logoutAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        $this->_redirect('/system/login');
    }

    public function menuListAction(){
        $ob = new Application_Model_DbTable_System();
        $mode = $this->_getParam('mode', '');

        if ($mode == "menu-json"){
            $a = $this->_getAllParams();
            $this->_helper->AjaxContext()->addActionContext('menu-list', 'json')->initContext('json');
            $this->view->result = $ob->read_menu_json($a['group_id']);
            return;
        }
        $row = $ob->read_group();
        $this->view->row_group = $row['value'];
        $row = $ob->menu_read(0);
        $this->view->row_menu = $row['value'];
    }

    public function groupEditAction(){
        $this->_helper->layout->disableLayout();
        $this->view->group_id = $this->_getParam('group_id', 0);
        $ob = new Application_Model_DbTable_System();
        $row = $ob->get_group($this->view->group_id);
        $this->view->row = $row['value'];
    }

    public function menuEditAction(){
        $this->_helper->layout->disableLayout();
        $this->view->menu_id = $this->_getParam('menu_id', 0);
        $ob = new Application_Model_DbTable_System();
        $row = $ob->get_menu($this->view->menu_id);
        $this->view->row = $row['value'];
        $row = $ob->menu_read_for_select();
        $this->view->row_menu_for_select = $row['value'];
    }

    public function employeeListAction(){
        $this->view->email = $this->_getParam('email', '');
        $this->view->fio = $this->_getParam('fio', '');
        $ob = new Application_Model_DbTable_System();
        $row = $ob->read_employee($this->view->email, $this->view->fio);
        $this->view->row = $row['value'];
    }

    public function employeeEditAction(){
        $this->_helper->layout->disableLayout();
        $this->view->employee_id = $this->_getParam('employee_id', 0);
        $ob = new Application_Model_DbTable_System();
        $row = $ob->get_employee($this->view->employee_id);

        $this->view->row = $row['value'];

    }

    public function employeeGroupAction(){
        $ob = new Application_Model_DbTable_System();
        $row = $ob->read_employee('', '');
        $this->view->row_employee = $row['value'];
        $row = $ob->group_read(0);
        $this->view->row_group = $row['value'];
    }

    public function changePasswordAction(){
        $ob = new Application_Model_DbTable_System();
        if (isset($_POST["change_password"])) {
            $a = $this->_getAllParams();
            $result = $ob->change_password($a);
            if($result['status'] == false){
                $a['error'] = $result['error'];
                $this->view->row = $a;
                return;
            }
            $this->view->success_password_change = 1;
        }
    }
    function userRightAction(){
        $ob = new Application_Model_DbTable_System();
        $mode = $this->_getParam('mode', '');

        if($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('user-right', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->user_right_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('user-right', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->user_right_del($a['user_right_id']);
            $this->view->result = $result;
        }

        $this->view->row = $ob->user_right_read()['value'];
    }
    function userRightEditAction(){
        $this->_helper->layout->disableLayout();
        $this->view->user_right_id = $user_right_id = $this->_getParam("user_right_id", 0);
        $ob = new Application_Model_DbTable_System();
        $this->view->row = $ob->user_right_get($user_right_id)['value'];
    }
    function userRightItemListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_System();
        $this->view->user_right_id = $user_right_id = $this->_getParam("user_right_id", 0);

        $mode = $this->_getParam('mode', '');
        if($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('user-right-item-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->user_right_item_upd($a);
            $this->view->result = $result;
        }
        if($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('user-right-item-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->user_right_item_del($a['user_right_item_id']);
            $this->view->result = $result;
        }

        $this->view->row = $ob->user_right_item_read($user_right_id)['value'];
    }
    function userRightItemEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_System();

        $this->view->user_right_item_id = $user_right_item_id = $this->_getParam("user_right_item_id", 0);
        $this->view->user_right_id = $user_right_item_id = $this->_getParam("user_right_id", 0);
        $this->view->row_employee = $ob->employee_read_user_rights($this->view->user_right_id)['value'];
    }
    public function select2Action(){
        $this->view->a = $this->_getAllParams();
        $layout = $this->_getParam('layout', false);
        if($layout == false){
            $this->_helper->layout->disableLayout();
        }
    }
}

