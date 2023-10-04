<?php
class Api_Model_DbTable_Dict extends Application_Model_DbTable_Parent {
    public function brand__read(){
        $result = $this->readSP(__FUNCTION__, "public.brand__read('cur')");
        return $result;
    }
    public function banner__read(){
        $result = $this->readSP(__FUNCTION__, "public.banner__read('cur')");
        return $result;
    }
    public function city__read(){
        $result = $this->readSP(__FUNCTION__, "public.city__read('cur')");
        return $result;
    }
}