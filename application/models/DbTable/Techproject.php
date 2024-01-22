<?php

class Application_Model_DbTable_Techproject extends Application_Model_DbTable_Parent{
    public function remont_read_employee(){
        $result = $this->readSP(__FUNCTION__, "public.remont_read_employee('cur')");
        return $result;
    }
}
