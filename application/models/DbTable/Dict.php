<?php

class Application_Model_DbTable_Dict extends Application_Model_DbTable_Parent{
    public function year_read(){
        $result = $this->readSP(__FUNCTION__,"public.year_read('cur')");
        return $result;
    }
    public function month_read(){
        $result = $this->readSP(__FUNCTION__,"public.month_read('cur')");
        return $result;
    }
}
