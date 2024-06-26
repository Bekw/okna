<?php

class Application_Model_DbTable_Dict extends Application_Model_DbTable_Parent{

    public function document_type_read(){
        $result = $this->readSP(__FUNCTION__, "public.document_type_read('cur')");
        return $result;
    }
    public function document_type_get($document_type_id){
        $p['document_type_id'] = $document_type_id;
        $result = $this->getSP(__FUNCTION__, "public.document_type_get('cur', :document_type_id)", $p);
        return $result;
    }
    public function document_type_del($document_type_id){
        $p['document_type_id'] = $document_type_id;
        $result = $this->execSP(__FUNCTION__, "public.document_type_del(:document_type_id)", $p);
        return $result;
    }
    public function document_type_upd($a){
        $p['document_type_id'] = $a['document_type_id'];
        $p['document_type_name'] = $a['document_type_name'];
        $p['document_type_code'] = $a['document_type_code'];
        $result = $this->execSP(__FUNCTION__, "public.document_type_upd(:document_type_id, :document_type_name, :document_type_code)", $p);
        return $result;
    }
    public function payment_type_read(){
        $result = $this->readSP(__FUNCTION__, "public.payment_type_read('cur')");
        return $result;
    }
    public function payment_type_get($payment_type_id){
        $p['payment_type_id'] = $payment_type_id;
        $result = $this->getSP(__FUNCTION__, "public.payment_type_get('cur', :payment_type_id)", $p);
        return $result;
    }
    public function payment_type_del($payment_type_id){
        $p['payment_type_id'] = $payment_type_id;
        $result = $this->execSP(__FUNCTION__, "public.payment_type_del(:payment_type_id)", $p);
        return $result;
    }
    public function payment_type_upd($a){
        $p['payment_type_id'] = $a['payment_type_id'];
        $p['payment_type_name'] = $a['payment_type_name'];
        $p['payment_type_code'] = $a['payment_type_code'];
        $result = $this->execSP(__FUNCTION__, "public.payment_type_upd(:payment_type_id, :payment_type_name, :payment_type_code)", $p);
        return $result;
    }
    public function employee_request_type_read(){
        $result = $this->readSP(__FUNCTION__, "public.employee_request_type_read('cur')");
        return $result;
    }
    public function employee_request_type_get($employee_request_type_id){
        $p['employee_request_type_id'] = $employee_request_type_id;
        $result = $this->getSP(__FUNCTION__, "public.employee_request_type_get('cur', :employee_request_type_id)", $p);
        return $result;
    }
    public function employee_request_type_del($employee_request_type_id){
        $p['employee_request_type_id'] = $employee_request_type_id;
        $result = $this->execSP(__FUNCTION__, "public.employee_request_type_del(:employee_request_type_id)", $p);
        return $result;
    }
    public function employee_request_type_upd($a){
        $p['employee_request_type_id'] = $a['employee_request_type_id'];
        $p['employee_request_type_name'] = $a['employee_request_type_name'];
        $p['employee_request_type_code'] = $a['employee_request_type_code'];
        $result = $this->execSP(__FUNCTION__, "public.employee_request_type_upd(:employee_request_type_id, :employee_request_type_name, :employee_request_type_code)", $p);
        return $result;
    }

    public function material_type_read(){
        $result = $this->readSP(__FUNCTION__, "public.material_type_read('cur')");
        return $result;
    }
    public function material_type_get($material_type_id){
        $p['material_type_id'] = $material_type_id;
        $result = $this->getSP(__FUNCTION__, "public.material_type_get('cur', :material_type_id)", $p);
        return $result;
    }
    public function material_type_del($material_type_id){
        $p['material_type_id'] = $material_type_id;
        $result = $this->execSP(__FUNCTION__, "public.material_type_del(:material_type_id)", $p);
        return $result;
    }
    public function material_type_upd($a){
        $p['material_type_id'] = $a['material_type_id'];
        $p['material_type_name'] = $a['material_type_name'];
        $p['material_type_code'] = $a['material_type_code'];

        $result = $this->execSP(__FUNCTION__, "public.material_type_upd(:material_type_id, :material_type_name, :material_type_code)", $p);
        return $result;
    }
    public function material_read(){
        $result = $this->readSP(__FUNCTION__, "public.material_read('cur')");
        return $result;
    }
    public function material_get($material_id){
        $p['material_id'] = $material_id;
        $result = $this->getSP(__FUNCTION__, "public.material_get('cur', :material_id)", $p);
        return $result;
    }
    public function material_del($material_id){
        $p['material_id'] = $material_id;
        $result = $this->execSP(__FUNCTION__, "public.material_del(:material_id)", $p);
        return $result;
    }
    public function material_upd($a){
        $p['material_id'] = $a['material_id'];
        $p['material_type_id'] = $a['material_type_id'];
        $p['material_name'] = $a['material_name'];
        $p['material_code'] = $a['material_code'];
        $p['unit_type_id'] = $a['unit_type_id'];
        $p['photo_name'] = null;
        $p['photo_url'] = null;

        $tmpFilePath = $_FILES['upload']['tmp_name'];
        if ($tmpFilePath != ""){
            $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
            $dir = $_SERVER['DOCUMENT_ROOT']."/documents/material_img/";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $filename = "/documents/material_img/".uniqid('material_img',true)."." . $ext;
            $p['photo_name'] = $filename;
            $p['photo_url'] = $filename;
            $newFilePath = $_SERVER['DOCUMENT_ROOT']. $filename;

            if(move_uploaded_file_smart($tmpFilePath, $newFilePath)) {
            }
        }
        $result = $this->execSP(__FUNCTION__, "public.material_upd(:material_id, :material_type_id, :material_name, :material_code, :photo_name, :photo_url, :unit_type_id) res", $p, 'res');
        return $result;
    }
    public function material_price_read($material_id){
        $p['material_id'] = $material_id;
        $result = $this->readSP(__FUNCTION__, "public.material_price_read('cur', :material_id)", $p);
        return $result;
    }
    public function material_package_read($material_id){
        $p['material_id'] = $material_id;
        $result = $this->readSP(__FUNCTION__, "public.material_package_read('cur', :material_id)", $p);
        return $result;
    }
    public function material_price_del($material_price_id){
        $p['material_price_id'] = $material_price_id;
        $result = $this->execSP(__FUNCTION__, "public.material_price_del(:material_price_id)", $p);
        return $result;
    }
    public function material_price_upd($a){
        $p['material_id'] = $a['material_id'];
        $p['price'] = $a['price'];
        $p['date_start'] = zeroToNull($a['date_start']);
        $p['date_end'] = zeroToNull($a['date_end']);
        $result = $this->execSP(__FUNCTION__, "public.material_price_upd(:material_id, :price, to_date(:date_start, 'dd.mm.yyyy'), to_date(:date_end, 'dd.mm.yyyy'))", $p);
        return $result;
    }
    public function material_type_read_fs(){
        $result = $this->readSP(__FUNCTION__, "public.material_type_read_fs('cur')");
        return $result;
    }
    public function unit_type_read_fs(){
        $result = $this->readSP(__FUNCTION__, "public.unit_type_read_fs('cur')");
        return $result;
    }

    public function stage_read(){
        $result = $this->readSP(__FUNCTION__, "public.stage_read('cur')");
        return $result;
    }
    public function stage_mark_read($stage_id){
        $p['stage_id'] = $stage_id;
        $result = $this->readSP(__FUNCTION__, "public.stage_mark_read('cur', :stage_id)", $p);
        return $result;
    }
    public function upd_stage_mark($a){
        $p['stage_id'] = $a['stage_id'];
        $p['mark_type_id'] = $a['mark_type_id'];
        $p['type'] = $a['type'];
        $result = $this->execSP(__FUNCTION__, "public.upd_stage_mark(:stage_id, :mark_type_id, :type)", $p);
        return $result;
    }
    public function employee_request_read($request_status){
        $p['request_status'] = $request_status;
        $result = $this->readSP(__FUNCTION__, "public.employee_request_read('cur', :request_status)", $p);
        return $result;
    }
    public function employee_request_all_read($request_status){
        $p['request_status'] = $request_status;
        $result = $this->readSP(__FUNCTION__, "public.employee_request_all_read('cur', :request_status)", $p);
        return $result;
    }
    public function employee_request_get($employee_request_id){
        $p['employee_request_id'] = $employee_request_id;
        $result = $this->getSP(__FUNCTION__, "public.employee_request_get('cur', :employee_request_id)", $p);
        return $result;
    }
    public function employee_request_del($employee_request_id){
        $p['employee_request_id'] = $employee_request_id;
        $result = $this->execSP(__FUNCTION__, "public.employee_request_del(:employee_request_id)", $p);
        return $result;
    }
    public function employee_request_upd($a){
        $p['employee_request_id'] = $a['employee_request_id'];
        $p['description'] = $a['description'];
        $p['employee_request_type_id'] = $a['employee_request_type_id'];
        $result = $this->execSP(__FUNCTION__, "public.employee_request_upd(:employee_request_id, :description, :employee_request_type_id) res", $p, "res");
        return $result;
    }
    public function employee_request_list_read($employee_request_id){
        $p['employee_request_id'] = $employee_request_id;
        $result = $this->readSP(__FUNCTION__, "public.employee_request_list_read('cur', :employee_request_id)", $p);
        return $result;
    }
    public function employee_request_list_del($employee_request_list_id){
        $p['employee_request_list_id'] = $employee_request_list_id;
        $result = $this->execSP(__FUNCTION__, "public.employee_request_list_del(:employee_request_list_id)", $p);
        return $result;
    }
    public function employee_request_list_upd($a){
        $p['employee_request_id'] = $a['employee_request_id'];
        $p['list_employee_id'] = $a['list_employee_id'];
        $p['request_text'] = $a['request_text'];
        $p['order_type'] = $a['order_type'];
        $result = $this->execSP(__FUNCTION__, "public.employee_request_list_upd(:employee_request_id, :list_employee_id, :request_text, :order_type)", $p);
        return $result;
    }
    public function employee_request_doc_read($employee_request_id){
        $p['employee_request_id'] = $employee_request_id;
        $result = $this->readSP(__FUNCTION__, "public.employee_request_doc_read('cur', :employee_request_id)", $p);
        return $result;
    }
    public function employee_request_doc_del($employee_request_doc_id){
        $p['employee_request_doc_id'] = $employee_request_doc_id;
        $result = $this->execSP(__FUNCTION__, "public.employee_request_doc_del(:employee_request_doc_id)", $p);
        return $result;
    }
    public function employee_request_doc_upd($a){
        $p['employee_request_id'] = $a['employee_request_id'];
        $p['doc_name'] = null;
        $p['doc_url'] = null;

        $tmpFilePath = $_FILES['upload']['tmp_name'];
        if ($tmpFilePath != ""){
            $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
            $dir = $_SERVER['DOCUMENT_ROOT']."/documents/request_docs/";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $p['doc_name'] = uniqid('request',true)."." . $ext;
            $filename = "/documents/request_docs/".$p['doc_name'];
            $p['doc_url'] = $filename;
            $newFilePath = $_SERVER['DOCUMENT_ROOT']. $filename;

            if(move_uploaded_file_smart($tmpFilePath, $newFilePath)) {
            }
        }
        $result = $this->execSP(__FUNCTION__, "public.employee_request_doc_upd(:employee_request_id, :doc_name, :doc_url)", $p);
        return $result;
    }
    public function employee_read_fs(){
        $result = $this->readSP(__FUNCTION__, "public.employee_read_fs('cur')");
        return $result;
    }
    public function employee_request_accept_read(){
        $result = $this->readSP(__FUNCTION__, "public.employee_request_accept_read('cur')");
        return $result;
    }
    public function employee_request_status_upd($a){
        $p['employee_request_id'] = $a['employee_request_id'];
        $p['request_status'] = $a['request_status'];
        $result = $this->execSP(__FUNCTION__, "public.employee_request_status_upd(:employee_request_id, :request_status)", $p);
        return $result;
    }
    public function employee_request_list_status_upd($a){
        $p['employee_request_list_id'] = $a['employee_request_list_id'];
        $p['list_status'] = $a['list_status'] ?? null;
        $p['status_comment'] = $a['status_comment'] ?? null;
        $p['list_employee_id'] = $a['list_employee_id'] ?? null;
        $p['order_type'] = $a['order_type'] ?? null;
        $result = $this->execSP(__FUNCTION__, "public.employee_request_list_status_upd(:employee_request_list_id, :list_status, :status_comment, :list_employee_id, :order_type)", $p);
        return $result;
    }
}
