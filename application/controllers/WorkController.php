<?php
require_once 'ParentController.php';

class WorkController extends ParentController{

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
        $ob = new Application_Model_DbTable_Work();

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
        $ob = new Application_Model_DbTable_Work();
        $client_request_status_id = $this->_getParam('client_request_status_id', 0);
        $mode = $this->_getParam('mode', '');
        if($mode == 'upd-status'){
            $this->_helper->AjaxContext()->addActionContext('client-request-list', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->client_request_set_status($a);
            $this->view->result = $result;
        }

        $this->view->row = $ob->client_request_read($client_request_status_id)['value'];
    }
    public function clientRequestEditAction(){
        $ob = new Application_Model_DbTable_Work();
        $client_request_id = $this->_getParam('client_request_id', 0);
        $this->view->client_request_id = $client_request_id;

        $mode = $this->_getParam('mode', '');
        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->client_request_upd($params);
            return;
        }
        if ($mode == 'upd-wish'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->client_wants_upd($params);
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
        if ($mode == 'upd-payment-status'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->payment_status_upd($params);
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
        if($mode == 'create-work'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->create_work_from_request($a['client_request_id']);
            $this->view->result = $result;
        }
        if($mode == 'upd-client'){
            $this->_helper->AjaxContext()->addActionContext('client-request-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->client_data_upd($a);
            $this->view->result = $result;
        }

        $this->view->row = $ob->client_request_get($client_request_id)['value'];
    }
    public function clientEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $client_request_id = $this->_getParam('client_request_id', 0);
        $this->view->client_request_id = $client_request_id;

        $this->view->row = $ob->client_request_get($client_request_id)['value'];
    }
    public function documentListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $client_request_id = $this->_getParam('client_request_id', 0);
        $this->view->client_request_id = $client_request_id;

        $this->view->row = $ob->document_read($client_request_id)['value'];
    }
    public function documentEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $client_request_id = $this->_getParam('client_request_id', 0);
        $this->view->client_request_id = $client_request_id;

        $this->view->row_type = $ob->document_type_read()['value'];
    }
    public function paymentListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $client_request_id = $this->_getParam('client_request_id', 0);
        $this->view->client_request_id = $client_request_id;

        $this->view->row = $ob->payment_read($client_request_id)['value'];
    }
    public function paymentEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $client_request_id = $this->_getParam('client_request_id', 0);
        $this->view->client_request_id = $client_request_id;

        $this->view->row_type = $ob->payment_type_read()['value'];
    }
    public function workListAction(){
        $ob = new Application_Model_DbTable_Work();
        $stage_id = $this->_getParam('stage_id', 0);

        $this->view->row = $ob->work_read($stage_id)['value'];
    }
    public function workEditAction(){
        $ob = new Application_Model_DbTable_Work();
        $work_id = $this->_getParam('work_id', 0);
        $this->view->work_id = $work_id;

        $mode = $this->_getParam('mode', '');
        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->work_upd($params);
            return;
        }
        if ($mode == 'upd-measure-doc'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->work_measure_doc_upd($params);
            return;
        }
        if ($mode == 'upd-project'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->work_project_upd($params);
            return;
        }
        if ($mode == 'accept-project'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->work_project_accept($params['work_project_id'], $params['is_accepted']);
            return;
        }
        if($mode == 'del-project'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->work_project_del($a['work_project_id']);
            $this->view->result = $result;
        }
        if($mode == 'del-measure-doc'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->work_measure_doc_del($a['work_measure_doc_id']);
            $this->view->result = $result;
        }
        if ($mode == 'get-price'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->material_cur_price_get($params['material_id']);
            return;
        }
        if ($mode == 'upd-smeta'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->material_smeta_upd($params);
            return;
        }
        if($mode == 'del-smeta'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->material_smeta_del($a['material_smeta_id']);
            $this->view->result = $result;
        }
        if ($mode == 'upd-smeta-price'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->material_smeta_upd_price($params);
            return;
        }
        if ($mode == 'upd-stage'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->work_stage_upd_form($params);
            return;
        }
        if($mode == 'del-stage'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $a = $this->_getAllParams();
            $result = $ob->work_stage_del($a['work_stage_id']);
            $this->view->result = $result;
        }
        if ($mode == 'upd-mark'){
            $this->_helper->AjaxContext()->addActionContext('work-edit', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->work_mark_upd($params);
            return;
        }
        $this->view->row_measure = $ob->read_employee_fs('MEASURE')['value'];
        $this->view->row_designer = $ob->read_employee_fs('DESIGNER')['value'];
        $this->view->row_type = $ob->material_type_read_fs()['value'];
        $this->view->row_stage = $ob->work_stage_read($work_id)['value'];
        $this->view->row = $ob->work_get($work_id)['value'];
    }
    public function workMeasureDocAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $this->view->work_id = $work_id = $this->_getParam('work_id', 0);

        $this->view->row = $ob->work_measure_doc_read($work_id)['value'];
    }
    public function workMeasureDocEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $this->view->work_measure_doc_id = $work_measure_doc_id = $this->_getParam('work_measure_doc_id', 0);
        $this->view->work_id = $work_id = $this->_getParam('work_id', 0);

        $this->view->row = $ob->work_measure_doc_get($work_measure_doc_id)['value'];
    }
    public function workProjectListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $this->view->work_id = $work_id = $this->_getParam('work_id', 0);

        $this->view->row = $ob->work_project_read($work_id)['value'];
    }
    public function workProjectEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $this->view->work_project_id = $work_project_id = $this->_getParam('work_project_id', 0);
        $this->view->work_id = $work_id = $this->_getParam('work_id', 0);

        $this->view->row = $ob->work_project_get($work_project_id)['value'];
    }

    public function workSmetaListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $this->view->work_id = $work_id = $this->_getParam('work_id', 0);

        $this->view->row = $ob->material_smeta_read($work_id)['value'];
    }
    public function materialSelectFormAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $this->view->material_type_id = $material_type_id = $this->_getParam('material_type_id', 0);

        $this->view->row = $ob->material_read_fs($material_type_id)['value'];
    }
    public function workSmetaEditAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $this->view->material_smeta_id = $material_smeta_id = $this->_getParam('material_smeta_id', 0);
        $this->view->work_id = $work_id = $this->_getParam('work_id', 0);

        $this->view->row = $ob->material_smeta_get($material_smeta_id)['value'];
    }
    public function workMarkListAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $this->view->work_id = $work_id = $this->_getParam('work_id', 0);

        $this->view->row = $ob->work_mark_read($this->view->work_id)['value'];
    }
    public function workStageFormAction(){
        $this->_helper->layout->disableLayout();
        $ob = new Application_Model_DbTable_Work();
        $this->view->work_id = $work_id = $this->_getParam('work_id', 0);

        $this->view->row_stage = $ob->stage_read_fs()['value'];
        $this->view->row_stage_status = $ob->stage_status_read_fs()['value'];
    }
    public function agreementDownloadAction(){
        require 'phpword/vendor/autoload.php';
        $ob = new Application_Model_DbTable_Work();
        $request_id = $this->_getParam('client_request_id', 0);
        $row = $ob->client_request_get($request_id)['value'];

        require 'phpword/vendor/autoload.php';
        $cur_date = date('d.m.Y');

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'] ."/templates_docs/client_agreement.docx");
        $templateProcessor->setValue('contract_date', russian_date($cur_date));
        $templateProcessor->setValue('client_fio', $row['client_fio']);
        $templateProcessor->setValue('contract_num', $row['client_request_id']);
        $templateProcessor->setValue('client_iin', $row['client_iin']);
        $templateProcessor->setValue('client_doc_source', $row['client_doc_source']);
        $templateProcessor->setValue('client_doc_num', $row['client_doc_num']);
        $templateProcessor->setValue('client_phone', $row['client_phone']);
        $templateProcessor->setValue('address', $row['address']);
        $templateProcessor->setValue('total_price', tenge_text($row['total_price']));
        $templateProcessor->setValue('total_price_txt',num2str($row['total_price']));

        $filename = uniqid('request_agreement_', true) . '.docx';
        if (check_ob_content()){
            $this->_helper->layout->setLayout('layout-system');
            $this->_helper->viewRenderer->setNoRender(true);
            return;
        };

        $templateProcessor->saveAs($filename);

        if ($this->getParam('is_pdf', 'false') == 'true'){
            $filename_pdf = str_replace('docx', 'pdf', $filename);

//            $output = shell_exec('lowriter --convert-to pdf -env:UserInstallation=file:///tmp/lohome '.escapeshellcmd($filename));
            $output = shell_exec('libreoffice writer --convert-to pdf -env:UserInstallation=file:///tmp/lohome '.escapeshellcmd($filename));

            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename='.'"Договор'. $row['client_fio']. '.pdf"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename_pdf));
            flush();
            readfile($filename_pdf);
            unlink($filename);
            unlink($filename_pdf);
            exit;
        }else{
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.'Договор'. $row['client_fio']. '.docx');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            flush();
            readfile($filename);
            unlink($filename);
            exit;
        }
    }
    public function buhPaymentListAction(){
        $ob = new Application_Model_DbTable_Work();
        $this->view->payment_status = $work_id = $this->_getParam('payment_status', -1);
        $this->view->date_begin = $this->_getParam('date_begin', date('01.m.Y'));
        $this->view->date_end = $this->_getParam('date_end', date('d.m.Y'));

        $this->view->row = $ob->buh_read_payment($this->view->payment_status, $this->view->date_begin, $this->view->date_end)['value'];
    }
    public function tabelListAction(){
        $ob = new Application_Model_DbTable_Work();
        $this->view->cur_date = $this->_getParam('cur_date', date('d.m.Y'));
        $mode = $this->_getParam('mode', '');
        if ($mode == 'upd'){
            $this->_helper->AjaxContext()->addActionContext('tabel-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->tabel_upd($params);
            return;
        }
        if ($mode == 'del'){
            $this->_helper->AjaxContext()->addActionContext('tabel-list', 'json')->initContext('json');
            $params = $this->getAllParams();
            $this->view->result = $ob->tabel_del($params['tabel_id']);
            return;
        }
        $this->view->row = $ob->tabel_read_by_date($this->view->cur_date)['value'];
    }
    public function tabelEditAction(){
        $this->_helper->layout->disableLayout();

        $ob = new Application_Model_DbTable_Work();

        $this->view->row_employee = $ob->read_tabel_employee()['value'];
    }
    public function tabelReportAction(){
        $ob = new Application_Model_DbTable_Work();
        $this->view->date_begin = $this->_getParam('date_begin', date('01.m.Y'));
        $this->view->date_end = $this->_getParam('date_end', date('t.m.Y'));
        $this->view->employee_id = $this->_getParam('employee_id', 0);
        $this->view->row_employee = $ob->read_tabel_employee()['value'];

        $this->view->row = $ob->report_tabel($this->view->date_begin, $this->view->date_end, $this->view->employee_id)['value'];
    }
}

