<?php
class Api_Model_DbTable_Client extends Application_Model_DbTable_Parent {
    public function client_order_hist__read($client_id){
        $p['client_id'] = $client_id;
        $result = $this->readSP(__FUNCTION__, "public.client_order_hist__read('cur', :client_id)", $p);
        return $result;
    }
    public function client_profile__get($client_id){
        $p['client_id'] = $client_id;
        $result = $this->getSP(__FUNCTION__, "public.client_profile__get('cur', :client_id)", $p);
        return $result;
    }
    public function client_profile__modify($json_data){
        $p['json_data'] = $json_data;
        $result = $this->execSP(__FUNCTION__, "public.client_profile__modify(:json_data) id", $p, 'id');
        return $result;
    }
}