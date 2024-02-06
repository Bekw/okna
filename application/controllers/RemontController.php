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
        $actions_except = array('send-email', 'send-tg', 'create-request-landing');
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
    public function remontListAction(){
        $ob = new Application_Model_DbTable_Remont();

        $this->view->row = $ob->remont_read()['value'];
    }
    public function remontEditAction(){
        $ob = new Application_Model_DbTable_Remont();
        $remont_id = $this->_getParam('remont_id', 0);
        $this->view->remont_id = $remont_id;

        $mode = $this->_getParam('mode', '');
        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->remont_upd($params);
            return;
        }
        if ($mode == 'upd-room'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->remont_room_upd($params);
            return;
        }
        if ($mode == 'upd-room-brief'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->remont_room_brief_upd($params);
            return;
        }
        if ($mode == 'upd-room-measure'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->remont_room_measure_upd($params);
            return;
        }
        if ($mode == 'upd-project'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->remont_project_upd($params);
            return;
        }
        if ($mode == 'upd-smeta'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->material_smeta_upd($params);
            return;
        }
        if ($mode == 'upd-room-material'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->remont_material_upd($params);
            return;
        }
        if ($mode == 'upd-smeta-price'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->material_smeta_upd_price($params);
            return;
        }
        if ($mode == 'upd-measure-doc'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->remont_measure_doc_upd($params);
            return;
        }
        if ($mode == 'upd-stage'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->remont_stage_upd($params);
            return;
        }
        if ($mode == 'upd-mark'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->remont_mark_upd($params);
            return;
        }
        if ($mode == 'accept-project'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->remont_project_accept($params['remont_project_id'], $params['is_accepted']);
            return;
        }
        if($mode == 'del-room'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->remont_room_del($a['remont_room_id']);
            $this->view->result = $result;
        }
        if($mode == 'del-room-brief'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->remont_room_brief_del($a['remont_room_brief_id']);
            $this->view->result = $result;
        }
        if($mode == 'del-room-measure'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->remont_room_measure_del($a['remont_room_measure_id']);
            $this->view->result = $result;
        }
        if($mode == 'del-project'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->remont_project_del($a['remont_project_id']);
            $this->view->result = $result;
        }
        if($mode == 'del-smeta'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->material_smeta_del($a['material_smeta_id']);
            $this->view->result = $result;
        }
        if($mode == 'del-room-material'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->remont_material_del($a['remont_material_id']);
            $this->view->result = $result;
        }
        if($mode == 'del-measure-doc'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->remont_measure_doc_del($a['remont_measure_doc_id']);
            $this->view->result = $result;
        }
        if($mode == 'del-stage'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->remont_stage_del($a['remont_stage_id']);
            $this->view->result = $result;
        }
        if ($mode == 'get-price'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->material_cur_price_get($params['material_id']);
            return;
        }
        if ($mode == 'auto-smeta-load'){
            $this->_helper->AjaxContext()->addActionContext('remont-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->smeta_auto_insert($params['remont_id']);
            return;
        }

        $this->view->row_measure = $ob->read_employee_fs('MEASURE')['value'];
        $this->view->row_designer = $ob->read_employee_fs('DESIGNER')['value'];
        $this->view->row_tech = $ob->read_employee_fs('TECH')['value'];
        $this->view->row_contractor = $ob->read_contractor_fs()['value'];
        $this->view->row_type = $ob->material_type_read_fs()['value'];

        $this->view->row_stage = $ob->remont_stage_read($remont_id)['value'];
        $this->view->row = $ob->remont_get($remont_id)['value'];
    }
    public function remontRoomListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $remont_id = $this->_getParam('remont_id', 0);
        $this->view->remont_id = $remont_id;

        $this->view->row = $ob->remont_room_read($remont_id)['value'];
    }
    public function remontRoomEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $remont_room_id = $this->_getParam('remont_room_id', 0);
        $remont_id = $this->_getParam('remont_id', 0);
        $this->view->remont_id = $remont_id;
        $this->view->remont_room_id = $remont_room_id;

        $this->view->row_room = $ob->read_room_fs()['value'];
        $this->view->row = $ob->remont_room_get($remont_room_id)['value'];
    }
    public function remontRoomBriefAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $remont_id = $this->_getParam('remont_id', 0);
        $this->view->remont_id = $remont_id;

        $this->view->row = $ob->remont_room_read($remont_id)['value'];
    }
    public function remontBriefTableAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_room_id = $remont_room_id = $this->_getParam('remont_room_id', 0);

        $this->view->row = $ob->remont_room_brief_read($remont_room_id)['value'];
    }
    public function remontRoomBriefEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_room_brief_id = $remont_room_brief_id = $this->_getParam('remont_room_brief_id', 0);
        $this->view->remont_room_id = $remont_room_id = $this->_getParam('remont_room_id', 0);

        $this->view->row = $ob->remont_room_brief_get($remont_room_brief_id)['value'];
        $this->view->row_brief = $ob->brief_read_fs()['value'];
    }
    public function briefItemFormAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->brief_type_id = $this->_getParam('brief_type_id', 0);
        $this->view->brief_item_id = $this->_getParam('brief_item_id', 0);
        $this->view->brief_value = $this->_getParam('brief_value', '');
        $this->view->brief_type = $this->_getParam('brief_type', 0);

        $this->view->row = $ob->brief_item_read_fs($this->view->brief_type_id)['value'];
    }
    public function remontRoomMeasureAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $remont_id = $this->_getParam('remont_id', 0);
        $this->view->remont_id = $remont_id;

        $this->view->row_measure = $ob->measure_read_fs()['value'];
        $this->view->row = $ob->remont_room_read($remont_id)['value'];
    }
    public function remontMeasureTableAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_room_id = $remont_room_id = $this->_getParam('remont_room_id', 0);

        $this->view->row = $ob->remont_room_measure_read($remont_room_id)['value'];
    }
    public function remontRoomMeasureEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_room_measure_id = $remont_room_measure_id = $this->_getParam('remont_room_measure_id', 0);
        $this->view->remont_room_id = $remont_room_id = $this->_getParam('remont_room_id', 0);

        $this->view->row = $ob->remont_room_measure_get($remont_room_measure_id)['value'];
        $this->view->row_measure = $ob->measure_read_fs()['value'];
    }
    public function remontProjectListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_id = $remont_id = $this->_getParam('remont_id', 0);

        $this->view->row = $ob->remont_project_read($remont_id)['value'];
    }
    public function remontProjectEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_project_id = $remont_project_id = $this->_getParam('remont_project_id', 0);
        $this->view->remont_id = $remont_id = $this->_getParam('remont_id', 0);

        $this->view->row = $ob->remont_project_get($remont_project_id)['value'];
    }
    public function remontSmetaListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_id = $remont_id = $this->_getParam('remont_id', 0);

        $this->view->row = $ob->material_smeta_read($remont_id)['value'];
    }
    public function materialSelectFormAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->material_type_id = $material_type_id = $this->_getParam('material_type_id', 0);

        $this->view->row = $ob->material_read_fs($material_type_id)['value'];
    }

    public function remontRoomMaterialAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $remont_id = $this->_getParam('remont_id', 0);
        $this->view->remont_id = $remont_id;

        $this->view->row_material_type = $ob->material_type_read_fs()['value'];
        $this->view->row = $ob->remont_room_read($remont_id)['value'];
    }
    public function remontMaterialTableAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_room_id = $remont_room_id = $this->_getParam('remont_room_id', 0);

        $this->view->row = $ob->remont_material_read($remont_room_id)['value'];
    }
    public function remontSmetaEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->material_smeta_id = $material_smeta_id = $this->_getParam('material_smeta_id', 0);
        $this->view->remont_id = $remont_id = $this->_getParam('remont_id', 0);

        $this->view->row = $ob->material_smeta_get($material_smeta_id)['value'];
    }
    public function remontMeasureDocAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_id = $remont_id = $this->_getParam('remont_id', 0);

        $this->view->row = $ob->remont_measure_doc_read($remont_id)['value'];
    }
    public function remontMeasureDocEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_measure_doc_id = $remont_measure_doc_id = $this->_getParam('remont_measure_doc_id', 0);
        $this->view->remont_id = $remont_id = $this->_getParam('remont_id', 0);

        $this->view->row = $ob->remont_measure_doc_get($remont_measure_doc_id)['value'];
    }
    public function remontStageFormAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_id = $remont_id = $this->_getParam('remont_id', 0);

        $this->view->row_stage = $ob->stage_read_fs()['value'];
        $this->view->row_stage_status = $ob->stage_status_read_fs()['value'];
    }
    public function remontMarkListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Remont();
        $this->view->remont_id = $remont_id = $this->_getParam('remont_id', 0);

        $this->view->row = $ob->remont_mark_read($this->view->remont_id)['value'];
    }
}

