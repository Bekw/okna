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
            $filename = "/documents/category_group_photo/".uniqid('category_group_photo',true)."." . $ext;
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
    public function contractor_read(){
        $result = $this->readSP(__FUNCTION__, "public.contractor_read('cur')");
        return $result;
    }
    public function contractor_get($contractor_id){
        $p['contractor_id'] = $contractor_id;
        $result = $this->getSP(__FUNCTION__, "public.contractor_get('cur', :contractor_id)", $p);
        return $result;
    }
    public function contractor_del($contractor_id){
        $p['contractor_id'] = $contractor_id;
        $result = $this->execSP(__FUNCTION__, "public.contractor_del(:contractor_id)", $p);
        return $result;
    }
    public function contractor_upd($a){
        $p['contractor_id'] = $a['contractor_id'];
        $p['contractor_name'] = $a['contractor_name'];
        $p['contractor_bin'] = $a['contractor_bin'];
        $p['contractor_address'] = $a['contractor_address'];
        $p['contractor_cheif_fio'] = $a['contractor_cheif_fio'];
        $p['add_date'] = $a['add_date'];
        $p['is_active'] = $a['is_active'] ?? false;
        $result = $this->execSP(__FUNCTION__, "public.contractor_upd(:contractor_id, :contractor_name, :contractor_bin, :contractor_address, :contractor_cheif_fio, to_date(:add_date, 'dd.mm.yyyy'), :is_active)", $p);
        return $result;
    }
    public function contractor_is_active($contractor_id){
        $p['contractor_id'] = $contractor_id;
        $result = $this->execSP(__FUNCTION__, "public.contractor_is_active(:contractor_id)", $p);
        return $result;
    }
    public function room_read(){
        $result = $this->readSP(__FUNCTION__, "public.room_read('cur')");
        return $result;
    }
    public function room_get($room_id){
        $p['room_id'] = $room_id;
        $result = $this->getSP(__FUNCTION__, "public.room_get('cur', :room_id)", $p);
        return $result;
    }
    public function room_del($room_id){
        $p['room_id'] = $room_id;
        $result = $this->execSP(__FUNCTION__, "public.room_del(:room_id)", $p);
        return $result;
    }
    public function room_upd($a){
        $p['room_id'] = $a['room_id'];
        $p['room_name'] = $a['room_name'];
        $p['room_code'] = $a['room_code'];
        $result = $this->execSP(__FUNCTION__, "public.room_upd(:room_id, :room_name, :room_code)", $p);
        return $result;
    }
}
