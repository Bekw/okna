<?php

class Application_Model_DbTable_Work extends Application_Model_DbTable_Parent{

    public function create_client_request($a){
        $p['client_type'] = $a['client_type'];
        $p['client_fio'] = $a['client_fio'];
        $p['client_iin'] = $a['client_iin'];
        $p['client_phone'] = $a['client_phone'];
        $p['address'] = $a['address'];
        $result = $this->execSP(__FUNCTION__, "public.create_request(:client_type, :client_fio, :client_iin, :client_phone, :address) res", $p, 'res');
        return $result;
    }
    public function client_request_read($client_request_status){
        $p['client_request_status_id'] = $client_request_status;
        $result = $this->readSP(__FUNCTION__, "public.client_request_read('cur', :client_request_status_id)", $p);
        return $result;
    }
    public function client_request_get($client_request_id){
        $p['client_request_id'] = $client_request_id;
        $result = $this->getSP(__FUNCTION__, "public.client_request_get('cur', :client_request_id)", $p);
        return $result;
    }
    public function client_request_upd($a){
        $p['client_request_id'] = $a['client_request_id'];
        $p['total_price'] = $a['total_price'];
        $p['mp_employee_id'] = zeroToNull($a['mp_employee_id']);
        $result = $this->execSP(__FUNCTION__, "public.client_request_upd(:client_request_id, :total_price, :mp_employee_id)", $p);
        return $result;
    }
    public function client_wants_upd($a){
        $p['client_request_id'] = $a['client_request_id'];
        $p['client_wants'] = $a['client_wants'];
        $result = $this->execSP(__FUNCTION__, "public.client_wants_upd(:client_request_id, :client_wants)", $p);
        return $result;
    }
    public function document_read($client_request_id){
        $p['client_request_id'] = $client_request_id;
        $result = $this->readSP(__FUNCTION__, "public.document_read('cur', :client_request_id)", $p);
        return $result;
    }
    public function document_get($document_id){
        $p['document_id'] = $document_id;
        $result = $this->getSP(__FUNCTION__, "public.document_get('cur', :document_id)", $p);
        return $result;
    }
    public function document_del($document_id){
        $p['document_id'] = $document_id;
        $result = $this->execSP(__FUNCTION__, "public.document_del(:document_id)", $p);
        return $result;
    }
    public function document_upd($a){
        $p['document_id']  = $a['document_id'];
        $p['document_name']  = $a['document_name'];
        $p['document_type_id']  = $a['document_type_id'];
        $p['client_request_id']  = $a['client_request_id'];
        $p['document_url'] = null;

        $tmpFilePath = $_FILES['upload']['tmp_name'];
        if ($tmpFilePath != ""){
            $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
            $dir = $_SERVER['DOCUMENT_ROOT']."/documents/".date('Y.m.d')."/request_documents/";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $filename = "/documents/".date('Y.m.d')."/request_documents/".uniqid('doc_',true)."." . $ext;
            $p['document_url'] = $filename;
            $newFilePath = $_SERVER['DOCUMENT_ROOT']. $filename;

            if(move_uploaded_file_smart($tmpFilePath, $newFilePath)) {
            }
        }
        $result = $this->execSP(__FUNCTION__, "public.document_upd(:document_id, :document_name, :document_url, :document_type_id, :client_request_id)", $p);
        return $result;
    }
    public function payment_read($client_request_id){
        $p['client_request_id'] = $client_request_id;
        $result = $this->readSP(__FUNCTION__, "public.payment_read('cur', :client_request_id)", $p);
        return $result;
    }
    public function payment_get($payment_id){
        $p['payment_id'] = $payment_id;
        $result = $this->getSP(__FUNCTION__, "public.payment_get('cur', :payment_id)", $p);
        return $result;
    }
    public function payment_del($payment_id){
        $p['payment_id'] = $payment_id;
        $result = $this->execSP(__FUNCTION__, "public.payment_del(:payment_id)", $p);
        return $result;
    }
    public function payment_upd($a){
        $p['payment_id']  = $a['payment_id'];
        $p['client_request_id']  = $a['client_request_id'];
        $p['payment_type_id']  = $a['payment_type_id'];
        $p['payment_comment']  = $a['payment_comment'];
        $p['payment_sum']  = $a['payment_sum'];
        $p['payment_url'] = null;
        $tmpFilePath = $_FILES['upload']['tmp_name'];
        if ($tmpFilePath != ""){
            $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
            $dir = $_SERVER['DOCUMENT_ROOT']."/documents/".date('Y.m.d')."/payment_documents/";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $filename = "/documents/".date('Y.m.d')."/payment_documents/".uniqid('payment_',true)."." . $ext;
            $p['payment_url'] = $filename;
            $newFilePath = $_SERVER['DOCUMENT_ROOT']. $filename;

            if(move_uploaded_file_smart($tmpFilePath, $newFilePath)) {
            }
        }
        $result = $this->execSP(__FUNCTION__, "public.payment_upd(:payment_id, :client_request_id, :payment_type_id, :payment_comment, :payment_sum, :payment_url)", $p);
        return $result;
    }
    public function document_type_read(){
        $result = $this->readSP(__FUNCTION__, "public.document_type_read('cur')");
        return $result;
    }
    public function payment_type_read(){
        $result = $this->readSP(__FUNCTION__, "public.payment_type_read('cur')");
        return $result;
    }
    public function payment_status_upd($a){
        $p['payment_id'] = $a['payment_id'];
        $p['payment_status'] = $a['payment_status'];
        $result = $this->execSP(__FUNCTION__, "public.payment_status_upd(:payment_id, :payment_status)", $p);
        return $result;
    }
    public function create_work_from_request($client_request_id){
        $p['client_request_id'] = $client_request_id;
        $result = $this->execSP(__FUNCTION__, "public.create_work_from_request(:client_request_id) res", $p, 'res');
        return $result;
    }
    public function work_read($stage_id){
        $p['stage_id'] = $stage_id;
        $result = $this->readSP(__FUNCTION__, "public.work_read('cur', :stage_id)", $p);
        return $result;
    }
    public function work_get($work_id){
        $p['work_id'] = $work_id;
        $result = $this->getSP(__FUNCTION__, "public.work_get('cur', :work_id)", $p);
        return $result;
    }
    public function read_employee_fs($code){
        $p['position_code'] = $code;
        $result = $this->readSP(__FUNCTION__, "public.read_employee_fs('cur', :position_code)", $p);
        return $result;
    }

    public function work_measure_doc_read($work_id){
        $p['work_id'] = $work_id;
        $result = $this->readSP(__FUNCTION__, "public.work_measure_doc_read('cur', :work_id)", $p);
        return $result;
    }
    public function work_measure_doc_get($work_measure_doc_id){
        $p['work_measure_doc_id'] = $work_measure_doc_id;
        $result = $this->getSP(__FUNCTION__, "public.work_measure_doc_get('cur', :work_measure_doc_id)", $p);
        return $result;
    }
    public function work_measure_doc_del($work_measure_doc_id){
        $p['work_measure_doc_id'] = $work_measure_doc_id;
        $result = $this->execSP(__FUNCTION__, "public.work_measure_doc_del(:work_measure_doc_id)", $p);
        return $result;
    }
    public function work_measure_doc_upd($a){
        $p['work_measure_doc_id'] = $a['work_measure_doc_id'];
        $p['work_id'] = $a['work_id'];
        $p['doc_name'] = $a['doc_name'];
        $p['doc_url'] = null;
        $tmpFilePath = $_FILES['upload']['tmp_name'];
        if ($tmpFilePath != ""){
            $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
            $dir = $_SERVER['DOCUMENT_ROOT']."/documents/".date('Y.m.d')."/measure_doc/";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $filename = "/documents/".date('Y.m.d')."/measure_doc/".uniqid('measure_',true)."." . $ext;
            $p['doc_url'] = $filename;
            $newFilePath = $_SERVER['DOCUMENT_ROOT']. $filename;

            if(move_uploaded_file_smart($tmpFilePath, $newFilePath)) {
            }
        }
        $result = $this->execSP(__FUNCTION__, "public.work_measure_doc_upd(:work_measure_doc_id, :work_id, :doc_name, :doc_url)", $p);
        return $result;
    }

    public function work_project_read($work_id){
        $p['work_id'] = $work_id;
        $result = $this->readSP(__FUNCTION__, "public.work_project_read('cur', :work_id)", $p);
        return $result;
    }
    public function work_project_get($work_project_id){
        $p['work_project_id'] = $work_project_id;
        $result = $this->getSP(__FUNCTION__, "public.work_project_get('cur', :work_project_id)", $p);
        return $result;
    }
    public function work_project_del($work_project_id){
        $p['work_project_id'] = $work_project_id;
        $result = $this->execSP(__FUNCTION__, "public.work_project_del(:work_project_id)", $p);
        return $result;
    }
    public function work_project_upd($a){
        $p['work_project_id'] = $a['work_project_id'];
        $p['work_id'] = $a['work_id'];
        $p['work_project_filename'] = $a['work_project_filename'];
        $p['is_final'] = 0; #$a['is_final'];
        if($a['is_final'] == 'on'){
            $p['is_final'] = 1;
        }
        $p['work_project_url'] = null;
        $tmpFilePath = $_FILES['upload']['tmp_name'];
        if ($tmpFilePath != ""){
            $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
            $dir = $_SERVER['DOCUMENT_ROOT']."/documents/".date('Y.m.d')."/project_work/";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $filename = "/documents/".date('Y.m.d')."/project_work/".uniqid('project_',true)."." . $ext;
            $p['work_project_url'] = $filename;
            $newFilePath = $_SERVER['DOCUMENT_ROOT']. $filename;

            if(move_uploaded_file_smart($tmpFilePath, $newFilePath)) {
            }
        }
        $result = $this->execSP(__FUNCTION__, "public.work_project_upd(:work_project_id, :work_id, :work_project_url, :work_project_filename, :is_final)", $p);
        return $result;
    }
    public function work_project_accept($work_project_id, $is_accepted){
        $p['work_project_id'] = $work_project_id;
        $p['is_accepted'] = $is_accepted;
        $result = $this->execSP(__FUNCTION__, "public.work_project_accept(:work_project_id, :is_accepted)", $p);
        return $result;
    }

    public function material_smeta_read($work_id){
        $p['work_id'] = $work_id;
        $result = $this->readSP(__FUNCTION__, "public.material_smeta_read('cur', :work_id)", $p);
        return $result;
    }
    public function material_smeta_del($material_smeta_id){
        $p['material_smeta_id'] = $material_smeta_id;
        $result = $this->execSP(__FUNCTION__, "public.material_smeta_del(:material_smeta_id)", $p);
        return $result;
    }
    public function material_smeta_upd($a){
        $p['material_id'] = $a['material_id'];
        $p['material_cnt'] = $a['material_cnt'];
        $p['material_price'] = $a['material_price'];
        $p['work_id'] = $a['work_id'];
        $result = $this->execSP(__FUNCTION__, "public.material_smeta_upd(:material_id, :material_cnt, :material_price, :work_id)", $p);
        return $result;
    }
    public function material_read_fs($material_type_id){
        $p['material_type_id'] = $material_type_id;
        $result = $this->readSP(__FUNCTION__, "public.material_read_fs('cur', :material_type_id)", $p);
        return $result;
    }
    public function material_type_read_fs(){
        $result = $this->readSP(__FUNCTION__, "public.material_type_read_fs('cur')");
        return $result;
    }
    public function material_cur_price_get($material_id){
        $p['material_id'] = $material_id;
        $result = $this->scalarSP(__FUNCTION__, "public.material_cur_price_get(:material_id) res", $p, 'res');
        return $result;
    }
    public function material_smeta_get($material_smeta_id){
        $p['material_smeta_id'] = $material_smeta_id;
        $result = $this->getSP(__FUNCTION__, "public.material_smeta_get('cur', :material_smeta_id)", $p);
        return $result;
    }
    public function material_smeta_upd_price($a){
        $p['material_price'] = $a['material_price'];
        $p['material_smeta_id'] = $a['material_smeta_id'];
        $result = $this->execSP(__FUNCTION__, "public.material_smeta_upd_price(:material_smeta_id, :material_price)", $p);
        return $result;
    }
    public function work_stage_read($work_id){
        $p['work_id'] = $work_id;
        $result = $this->readSP(__FUNCTION__, "public.work_stage_read('cur', :work_id)", $p);
        return $result;
    }
    public function work_stage_upd_form($a){
        $p['work_id'] = $a['work_id'];
        $p['stage_id'] = $a['stage_id'];
        $p['stage_status_id'] = $a['stage_status_id'];
        $p['stage_comment'] = $a['stage_comment'];
        $result = $this->execSP(__FUNCTION__, "public.work_stage_upd_form(:work_id, :stage_id, :stage_status_id, :stage_comment)", $p);
        return $result;
    }
    public function work_stage_del($work_stage_id){
        $p['work_stage_id'] = $work_stage_id;
        $result = $this->execSP(__FUNCTION__, "public.work_stage_del(:work_stage_id)", $p);
        return $result;
    }
    public function stage_status_read_fs(){
        $result = $this->readSP(__FUNCTION__, "public.stage_status_read_fs('cur')");
        return $result;
    }
    public function stage_read_fs(){
        $result = $this->readSP(__FUNCTION__, "public.stage_read_fs('cur')");
        return $result;
    }
    public function work_mark_read($work_id){
        $p['work_id'] = $work_id;
        $result = $this->readSP(__FUNCTION__, "public.work_mark_read('cur', :work_id)", $p);
        return $result;
    }
    public function work_mark_upd($a){
        $p['work_id'] = $a['work_id'];
        $p['mark_type_id'] = $a['mark_type_id'];
        $result = $this->execSP(__FUNCTION__, "public.work_mark_upd(:work_id, :mark_type_id)", $p);
        return $result;
    }
    public function work_upd($a){
        $p['work_id'] = $a['work_id'];
        $p['measure_date'] = zeroToNull($a['measure_date']);
        $p['date_start'] = zeroToNull($a['date_start']);
        $p['measure_employee_id'] = zeroToNull($a['measure_employee_id']);
        $p['designer_employee_id'] = zeroToNull($a['designer_employee_id']);
        $p['total_area'] = zeroToNull($a['total_area']);
        $result = $this->execSP(__FUNCTION__, "public.work_upd(:work_id, to_date(:measure_date, 'dd.mm.yyyy'), to_date(:date_start, 'dd.mm.yyyy'), :measure_employee_id, :designer_employee_id, :total_area)", $p);
        return $result;
    }
    public function client_data_upd($a){
        $p['client_request_id'] = $a['client_request_id'];
        $p['client_fio'] = $a['client_fio'];
        $p['client_iin'] = $a['client_iin'];
        $p['client_phone'] = $a['client_phone'];
        $p['address'] = $a['address'];
        $p['client_doc_num'] = $a['client_doc_num'];
        $p['client_doc_source'] = $a['client_doc_source'];
        $result = $this->execSP(__FUNCTION__, "public.client_data_upd(:client_request_id, :client_fio, :client_iin, :client_phone, :address, :client_doc_num, :client_doc_source)", $p);
        return $result;
    }
    public function client_request_set_status($a){
        $p['client_request_id'] = $a['client_request_id'];
        $p['client_request_status_id'] = $a['client_request_status_id'];
        $result = $this->execSP(__FUNCTION__, "public.client_request_set_status(:client_request_id, :client_request_status_id)", $p);
        return $result;
    }
}
