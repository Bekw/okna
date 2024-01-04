<?php

class Application_Model_DbTable_Dict extends Application_Model_DbTable_Parent{

    public function category_group_tab__read(){
        $result = $this->readSP(__FUNCTION__, "back_office.category_group_tab__read('cur')");
        return $result;
    }
    public function category_group_tab__get($category_group_id){
        $p['category_group_id'] = $category_group_id;
        $result = $this->getSP(__FUNCTION__, "back_office.category_group_tab__get('cur', :category_group_id)", $p);
        return $result;
    }
    public function category_group_tab__delete($category_group_id){
        $p['category_group_id'] = $category_group_id;
        $result = $this->execSP(__FUNCTION__, "back_office.category_group_tab__delete(:category_group_id)", $p);
        return $result;
    }
    public function category_group_tab__modify($a){
        $p['category_group_id'] = $a['category_group_id'];
        $p['category_group_name'] = $a['category_group_name'];
        $p['category_group_code'] = $a['category_group_code'];
        $p['is_active'] = $a['is_active'] ?? false;
        $p['category_group_img'] = null;

        $tmpFilePath = $_FILES['upload']['tmp_name'];
        if ($tmpFilePath != ""){
            $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
            $dir = $_SERVER['DOCUMENT_ROOT']."/documents/category_group_photo/";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $filename = "/documents/".date('Y.m.d')."/category_group_photo/".uniqid('category_group_photo',true)."." . $ext;
            $p['category_group_img'] = $filename;
            $newFilePath = $_SERVER['DOCUMENT_ROOT']. $filename;

            if(move_uploaded_file_smart($tmpFilePath, $newFilePath)) {
            }
        }
        $result = $this->execSP(__FUNCTION__, "back_office.category_group_tab__modify(:category_group_id, :category_group_name, :category_group_code, :category_group_img, :is_active)", $p);
        return $result;
    }
    public function category_tab__read($category_group_id){
        $p['category_group_id'] = $category_group_id;
        $result = $this->readSP(__FUNCTION__, "back_office.category_tab__read('cur', :category_group_id)", $p);
        return $result;
    }
    public function category_tab__get($category_id){
        $p['category_id'] = $category_id;
        $result = $this->getSP(__FUNCTION__, "back_office.category_tab__get('cur', :category_id)", $p);
        return $result;
    }
    public function category_tab__delete($category_id){
        $p['category_id'] = $category_id;
        $result = $this->execSP(__FUNCTION__, "back_office.category_tab__delete(:category_id)", $p);
        return $result;
    }
    public function category_tab__modify($a){
        $p['category_id'] = $a['category_id'];
        $p['category_group_id'] = $a['category_group_id'];
        $p['category_name'] = $a['category_name'];
        $p['category_code'] = $a['category_code'];
        $p['is_active'] = $a['is_active'] ?? false;

        $result = $this->execSP(__FUNCTION__, "back_office.category_tab__modify(:category_id, :category_name, :category_code, :category_group_id, :is_active)", $p);
        return $result;
    }
    public function brand_tab__read(){
        $result = $this->readSP(__FUNCTION__, "back_office.brand_tab__read('cur')");
        return $result;
    }
    public function brand_tab__get($brand_id){
        $p['brand_id'] = $brand_id;
        $result = $this->getSP(__FUNCTION__, "back_office.brand_tab__get('cur', :brand_id)", $p);
        return $result;
    }
    public function brand_tab__delete($brand_id){
        $p['brand_id'] = $brand_id;
        $result = $this->execSP(__FUNCTION__, "back_office.brand_tab__delete(:brand_id)", $p);
        return $result;
    }
    public function brand_tab__modify($a){
        $p['brand_id'] = $a['brand_id'];
        $p['brand_name'] = $a['brand_name'];
        $p['brand_code'] = $a['brand_code'];
        $p['is_active'] = $a['is_active'] ?? false;
        $p['brand_img'] = null;

        $tmpFilePath = $_FILES['upload']['tmp_name'];
        if ($tmpFilePath != ""){
            $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
            $dir = $_SERVER['DOCUMENT_ROOT']."/documents/brand_photo/";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $filename = "/documents/".date('Y.m.d')."/brand_photo/".uniqid('brand_photo',true)."." . $ext;
            $p['brand_img'] = $filename;
            $newFilePath = $_SERVER['DOCUMENT_ROOT']. $filename;

            if(move_uploaded_file_smart($tmpFilePath, $newFilePath)) {
            }
        }
        $result = $this->execSP(__FUNCTION__, "back_office.brand_tab__modify(:brand_id, :brand_name, :brand_code, :brand_img, :is_active)", $p);
        return $result;
    }
    public function brand_tab__is_active($brand_id){
        $p['brand_id'] = $brand_id;
        $result = $this->execSP(__FUNCTION__, "back_office.brand_tab__is_active(:brand_id)", $p);
        return $result;
    }
    public function category_tab__is_active($category_id){
        $p['category_id'] = $category_id;
        $result = $this->execSP(__FUNCTION__, "back_office.category_tab__is_active(:category_id)", $p);
        return $result;
    }
    public function category_group_tab__is_active($category_group_id){
        $p['category_group_id'] = $category_group_id;
        $result = $this->execSP(__FUNCTION__, "back_office.category_group_tab__is_active(:category_group_id)", $p);
        return $result;
    }

    public function brief_type_read(){
        $result = $this->readSP(__FUNCTION__, "public.brief_type_read('cur')");
        return $result;
    }
    public function brief_type_get($brief_type_id){
        $p['brief_type_id'] = $brief_type_id;
        $result = $this->getSP(__FUNCTION__, "public.brief_type_get('cur', :brief_type_id)", $p);
        return $result;
    }
    public function brief_type_del($brief_type_id){
        $p['brief_type_id'] = $brief_type_id;
        $result = $this->execSP(__FUNCTION__, "public.brief_type_del(:brief_type_id)", $p);
        return $result;
    }
    public function brief_type_upd($a){
        $p['brief_type_id'] = $a['brief_type_id'];
        $p['brief_type_name'] = $a['brief_type_name'];
        $p['brief_type_code'] = $a['brief_type_code'];
        $p['brief_type'] = $a['brief_type'];

        $result = $this->execSP(__FUNCTION__, "public.brief_type_upd(:brief_type_id, :brief_type_name, :brief_type_code, :brief_type)", $p);
        return $result;
    }
    public function brief_item_read($brief_type_id){
        $p['brief_type_id'] = $brief_type_id;
        $result = $this->readSP(__FUNCTION__, "public.brief_item_read('cur', :brief_type_id)", $p);
        return $result;
    }
    public function brief_item_get($brief_item_id){
        $p['brief_item_id'] = $brief_item_id;
        $result = $this->getSP(__FUNCTION__, "public.brief_item_get('cur', :brief_item_id)", $p);
        return $result;
    }
    public function brief_item_del($brief_item_id){
        $p['brief_item_id'] = $brief_item_id;
        $result = $this->execSP(__FUNCTION__, "public.brief_item_del(:brief_item_id)", $p);
        return $result;
    }
    public function brief_item_upd($a){
        $p['brief_item_id'] = $a['brief_item_id'];
        $p['brief_type_id'] = $a['brief_type_id'];
        $p['brief_item_name'] = $a['brief_item_name'];
        $p['brief_item_code'] = $a['brief_item_code'];

        $result = $this->execSP(__FUNCTION__, "public.brief_item_upd(:brief_item_id, :brief_type_id, :brief_item_name, :brief_item_code)", $p);
        return $result;
    }
    public function measure_type_read(){
        $result = $this->readSP(__FUNCTION__, "public.measure_type_read('cur')");
        return $result;
    }
    public function measure_type_get($measure_type_id){
        $p['measure_type_id'] = $measure_type_id;
        $result = $this->getSP(__FUNCTION__, "public.measure_type_get('cur', :measure_type_id)", $p);
        return $result;
    }
    public function measure_type_del($measure_type_id){
        $p['measure_type_id'] = $measure_type_id;
        $result = $this->execSP(__FUNCTION__, "public.measure_type_del(:measure_type_id)", $p);
        return $result;
    }
    public function measure_type_upd($a){
        $p['measure_type_id'] = $a['measure_type_id'];
        $p['measure_type_name'] = $a['measure_type_name'];
        $p['measure_type_code'] = $a['measure_type_code'];

        $result = $this->execSP(__FUNCTION__, "public.measure_type_upd(:measure_type_id, :measure_type_name, :measure_type_code)", $p);
        return $result;
    }
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
        $result = $this->execSP(__FUNCTION__, "public.create_client_request(:first_name, :second_name, :last_name, :client_phone, :client_email, :address, :area_house, :area_field, :house_type)", $p);
        return $result;
    }
}
