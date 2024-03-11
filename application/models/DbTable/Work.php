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
}
