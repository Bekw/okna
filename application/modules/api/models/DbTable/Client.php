<?php
class Api_Model_DbTable_Client extends Application_Model_DbTable_Parent {
    public function client_order_hist__read($client_id){
        $p['client_id'] = $client_id;
        $result = $this->readSP(__FUNCTION__, "public.client_order_hist__read('cur', :client_id)", $p);
        return $result;
    }
}