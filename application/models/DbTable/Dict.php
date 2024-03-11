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
}
