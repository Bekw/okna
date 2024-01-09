<?php

class Application_Model_DbTable_Remont extends Application_Model_DbTable_Parent{

    public function create_client_request($a){
        $p['first_name'] = $a['first_name'];
        $p['second_name'] = $a['second_name'];
        $p['last_name'] = $a['last_name'];
        $p['client_phone'] = $a['client_phone'];
        $p['client_email'] = $a['client_email'];
        $p['address'] = $a['address'];
        $p['area_house'] = $a['area_house'];
        $p['area_field'] = $a['area_field'];
        $p['house_type'] = $a['house_type'];
        $p['iin'] = $a['iin'];
        $result = $this->execSP(__FUNCTION__, "public.create_client_request(:first_name, :second_name, :last_name, :client_phone, :client_email, :address, :area_house, :area_field, :house_type, :iin) res", $p, 'res');
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
    public function read_employee_fs($code){
        $p['position_code'] = $code;
        $result = $this->readSP(__FUNCTION__, "admin.read_employee_fs('cur', :position_code)", $p);
        return $result;
    }
    public function upd_client_request($a){
        $p['client_request_id'] = $a['client_request_id'];
        $p['mp_employee_id'] = zeroToNull($a['mp_employee_id']);
        $p['total_price'] = $a['total_price'];
        $result = $this->execSP(__FUNCTION__, "public.upd_client_request(:client_request_id, :mp_employee_id, :total_price)", $p);
        return $result;
    }
    public function upd_client_wish($a){
        $p['client_request_id'] = $a['client_request_id'];
        $p['client_wants'] = $a['client_wants'];
        $result = $this->execSP(__FUNCTION__, "public.upd_client_wish(:client_request_id, :client_wants)", $p);
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
        $p['document_id'] = 0;
        $p['document_name'] = $a['document_name'];
        $p['document_type_id'] = $a['document_type_id'];
        $p['client_request_id'] = $a['client_request_id'];
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
        $result = $this->execSP(__FUNCTION__, "public.document_upd(:document_id, :document_name, :document_type_id, :document_url, :client_request_id)", $p);
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
        $p['payment_id'] = 0;
        $p['payment_type_id'] = $a['payment_type_id'];
        $p['payment_sum'] = $a['payment_sum'];
        $p['payment_comment'] = $a['payment_comment'];
        $p['client_request_id'] = $a['client_request_id'];
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
        $result = $this->execSP(__FUNCTION__, "public.payment_upd(:payment_id, :payment_type_id, :payment_sum, :payment_comment, :client_request_id, :payment_url)", $p);
        return $result;
    }
    public function create_remont($client_request_id){
        $p['client_request_id'] = $client_request_id;
        $result = $this->execSP(__FUNCTION__, "public.create_remont(:client_request_id) res", $p, 'res');
        return $result;
    }
    public function remont_read(){
        $result = $this->readSP(__FUNCTION__, "public.remont_read('cur')");
        return $result;
    }
    public function remont_get($remont_id){
        $p['remont_id'] = $remont_id;
        $result = $this->getSP(__FUNCTION__, "public.remont_get('cur', :remont_id)", $p);
        return $result;
    }
    public function remont_upd($a){
        $p['remont_id'] = $a['remont_id'];
        $p['measure_date'] = zeroToNull($a['measure_date']);
        $p['remont_start_date'] = zeroToNull($a['remont_start_date']);
        $p['measure_employee_id'] = zeroToNull($a['measure_employee_id']);
        $p['designer_employee_id'] = zeroToNull($a['designer_employee_id']);
        $p['tech_employee_id'] = zeroToNull($a['tech_employee_id']);
        $p['contractor_id'] = zeroToNull($a['contractor_id']);
        $result = $this->execSP(__FUNCTION__, "public.remont_upd(:remont_id, to_date(:measure_date, 'dd.mm.yyyy'), to_date(:remont_start_date, 'dd.mm.yyyy'), :measure_employee_id, :designer_employee_id, :tech_employee_id, :contractor_id)", $p);
        return $result;
    }
    public function read_contractor_fs(){
        $result = $this->readSP(__FUNCTION__, "public.read_contractor_fs('cur')");
        return $result;
    }
    public function remont_room_read($remont_id){
        $p['remont_id'] = $remont_id;
        $result = $this->readSP(__FUNCTION__, "public.remont_room_read('cur', :remont_id)", $p);
        return $result;
    }
    public function read_room_fs(){
        $result = $this->readSP(__FUNCTION__, "public.read_room_fs('cur')");
        return $result;
    }
    public function remont_room_upd($a){
        $p['remont_room_id'] = $a['remont_room_id'];
        $p['remont_id'] = $a['remont_id'];
        $p['room_id'] = $a['room_id'];
        $p['remont_room_name'] = $a['remont_room_name'];
        $result = $this->execSP(__FUNCTION__, "public.remont_room_upd(:remont_room_id, :remont_id, :room_id, :remont_room_name)", $p);
        return $result;
    }
    public function remont_room_del($remont_room_id){
        $p['remont_room_id'] = $remont_room_id;
        $result = $this->execSP(__FUNCTION__, "public.remont_room_del(:remont_room_id)", $p);
        return $result;
    }
    public function remont_room_get($remont_room_id){
        $p['remont_room_id'] = $remont_room_id;
        $result = $this->getSP(__FUNCTION__, "public.remont_room_get('cur', :remont_room_id)", $p);
        return $result;
    }
    public function remont_room_brief_read($remont_room_id){
        $p['remont_room_id'] = $remont_room_id;
        $result = $this->readSP(__FUNCTION__, "public.remont_room_brief_read('cur', :remont_room_id)", $p);
        return $result;
    }
    public function remont_room_brief_get($remont_room_brief_id){
        $p['remont_room_brief_id'] = $remont_room_brief_id;
        $result = $this->getSP(__FUNCTION__, "public.remont_room_brief_get('cur', :remont_room_brief_id)", $p);
        return $result;
    }
    public function remont_room_brief_del($remont_room_brief_id){
        $p['remont_room_brief_id'] = $remont_room_brief_id;
        $result = $this->execSP(__FUNCTION__, "public.remont_room_brief_del(:remont_room_brief_id)", $p);
        return $result;
    }
    public function remont_room_brief_upd($a){
        $p['remont_room_brief_id'] = $a['remont_room_brief_id'];
        $p['remont_room_id'] = $a['remont_room_id'];
        $p['brief_type_id'] = $a['brief_type_id'];
        $p['brief_item_id'] = $a['brief_item_id'] ?? null;
        $p['brief_value'] = $a['brief_value'] ?? null;
        $result = $this->execSP(__FUNCTION__, "public.remont_room_brief_upd(:remont_room_brief_id, :remont_room_id, :brief_item_id, :brief_value, :brief_type_id)", $p);
        return $result;
    }
    public function brief_item_read_fs($brief_type_id){
        $p['brief_type_id'] = $brief_type_id;
        $result = $this->readSP(__FUNCTION__, "public.brief_item_read_fs('cur', :brief_type_id)", $p);
        return $result;
    }
    public function brief_read_fs(){
        $result = $this->readSP(__FUNCTION__, "public.brief_read_fs('cur')");
        return $result;
    }
    public function remont_room_measure_read($remont_room_id){
        $p['remont_room_id'] = $remont_room_id;
        $result = $this->readSP(__FUNCTION__, "public.remont_room_measure_read('cur', :remont_room_id)", $p);
        return $result;
    }
    public function remont_room_measure_del($remont_room_measure_id){
        $p['remont_room_measure_id'] = $remont_room_measure_id;
        $result = $this->execSP(__FUNCTION__, "public.remont_room_measure_del(:remont_room_measure_id)", $p);
        return $result;
    }
    public function remont_room_measure_upd($a){
        $p['remont_room_measure_id'] = $a['remont_room_measure_id'];
        $p['remont_room_id'] = $a['remont_room_id'];
        $p['measure_type_id'] = $a['measure_type_id'];
        $p['measure_value'] = $a['measure_value'];
        $result = $this->execSP(__FUNCTION__, "public.remont_room_measure_upd(:remont_room_measure_id, :remont_room_id, :measure_type_id, :measure_value)", $p);
        return $result;
    }
    public function measure_read_fs(){
        $result = $this->readSP(__FUNCTION__, "public.measure_read_fs('cur')");
        return $result;
    }
    public function remont_room_measure_get($remont_room_measure_id){
        $p['remont_room_measure_id'] = $remont_room_measure_id;
        $result = $this->getSP(__FUNCTION__, "public.remont_room_measure_get('cur', :remont_room_measure_id)", $p);
        return $result;
    }
    public function remont_project_read($remont_id){
        $p['remont_id'] = $remont_id;
        $result = $this->readSP(__FUNCTION__, "public.remont_project_read('cur', :remont_id)", $p);
        return $result;
    }
    public function remont_project_get($remont_project_id){
        $p['remont_project_id'] = $remont_project_id;
        $result = $this->getSP(__FUNCTION__, "public.remont_project_get('cur', :remont_project_id)", $p);
        return $result;
    }
    public function remont_project_del($remont_project_id){
        $p['remont_project_id'] = $remont_project_id;
        $result = $this->execSP(__FUNCTION__, "public.remont_project_del(:remont_project_id)", $p);
        return $result;
    }
    public function remont_project_upd($a){
        $p['remont_project_id'] = $a['remont_project_id'];
        $p['remont_id'] = $a['remont_id'];
        $p['remont_project_filename'] = $a['remont_project_filename'];
        $p['is_final'] = 0; #$a['is_final'];
        if($a['is_final'] == 'on'){
            $p['is_final'] = 1;
        }
        $p['remont_project_url'] = null;
        $tmpFilePath = $_FILES['upload']['tmp_name'];
        if ($tmpFilePath != ""){
            $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
            $dir = $_SERVER['DOCUMENT_ROOT']."/documents/".date('Y.m.d')."/project_remont/";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $filename = "/documents/".date('Y.m.d')."/project_remont/".uniqid('project_',true)."." . $ext;
            $p['remont_project_url'] = $filename;
            $newFilePath = $_SERVER['DOCUMENT_ROOT']. $filename;

            if(move_uploaded_file_smart($tmpFilePath, $newFilePath)) {
            }
        }
        $result = $this->execSP(__FUNCTION__, "public.remont_project_upd(:remont_project_id, :remont_id, :remont_project_url, :remont_project_filename, :is_final)", $p);
        return $result;
    }
    public function remont_project_accept($remont_project_id, $is_accepted){
        $p['remont_project_id'] = $remont_project_id;
        $p['is_accepted'] = $is_accepted;
        $result = $this->execSP(__FUNCTION__, "public.remont_project_accept(:remont_project_id, :is_accepted)", $p);
        return $result;
    }
    public function material_smeta_read($remont_id){
        $p['remont_id'] = $remont_id;
        $result = $this->readSP(__FUNCTION__, "public.material_smeta_read('cur', :remont_id)", $p);
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
        $p['remont_id'] = $a['remont_id'];
        $result = $this->execSP(__FUNCTION__, "public.material_smeta_upd(:material_id, :material_cnt, :material_price, :remont_id)", $p);
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
}
