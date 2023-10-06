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
    public function stock_tab__read(){
        $result = $this->readSP(__FUNCTION__, "back_office.stock_tab__read('cur')");
        return $result;
    }
    public function stock_tab__get($stock_id){
        $p['stock_id'] = $stock_id;
        $result = $this->getSP(__FUNCTION__, "back_office.stock_tab__get('cur', :stock_id)", $p);
        return $result;
    }
    public function stock_tab__delete($stock_id){
        $p['stock_id'] = $stock_id;
        $result = $this->execSP(__FUNCTION__, "back_office.stock_tab__delete(:stock_id)", $p);
        return $result;
    }
    public function stock_tab__modify($a){
        $p['stock_id'] = $a['stock_id'];
        $p['stock_name'] = $a['stock_name'];
        $p['discount_percent'] = $a['discount_percent'];
        $p['stock_start'] = zeroToNull($a['stock_start']);
        $p['stock_end'] = zeroToNull($a['stock_end']);
        $p['stock_img'] = null;

        $tmpFilePath = $_FILES['upload']['tmp_name'];
        if ($tmpFilePath != ""){
            $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
            $dir = $_SERVER['DOCUMENT_ROOT']."/documents/stock_photo/";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $filename = "/documents/".date('Y.m.d')."/stock_photo/".uniqid('stock_photo',true)."." . $ext;
            $p['stock_img'] = $filename;
            $newFilePath = $_SERVER['DOCUMENT_ROOT']. $filename;

            if(move_uploaded_file_smart($tmpFilePath, $newFilePath)) {
            }
        }
        $result = $this->execSP(__FUNCTION__, "back_office.stock_tab__modify(:stock_id, :stock_name, :stock_img, :discount_percent, to_date(:stock_start, 'dd.mm.yyyy'), to_date(:stock_end, 'dd.mm.yyyy'))", $p);
        return $result;
    }
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
    public function product_tab__read(){
        $result = $this->readSP(__FUNCTION__, "back_office.product_tab__read('cur')");
        return $result;
    }
    public function product_tab__get($product_id){
        $p['product_id'] = $product_id;
        $result = $this->getSP(__FUNCTION__, "back_office.product_tab__get('cur', :product_id)", $p);
        return $result;
    }
    public function product_tab__delete($product_id){
        $p['product_id'] = $product_id;
        $result = $this->execSP(__FUNCTION__, "back_office.product_tab__delete(:product_id)", $p);
        return $result;
    }
    public function product_tab__modify($a){
        $p['product_id'] = $a['product_id'];
        $p['product_name'] = $a['product_name'];
        $p['product_desc'] = $a['product_desc'];
        $p['brand_id'] = $a['brand_id'];
        $p['price'] = $a['price'];
        $p['price_old'] = $a['price_old'];
        $p['is_avail'] = $a['is_avail'] ?? false;
        if (isset($a['category_id_arr'])){
            $p['category_id_arr'] = '{'.implode(",", zeroToNull($a['category_id_arr'])).'}';
        }else{
            $p['category_id_arr'] = '{}';
        }
        if (isset($a['tag_id_arr'])){
            $p['tag_id_arr'] = '{'.implode(",", zeroToNull($a['tag_id_arr'])).'}';
        }else{
            $p['tag_id_arr'] = '{}';
        }
        $result = $this->execSP(__FUNCTION__, "back_office.product_tab__modify(:product_id, :product_name, :product_desc, :brand_id, :price, :price_old, :is_avail, :category_id_arr, :tag_id_arr) res", $p, 'res');
        return $result;
    }
    public function product_tab__is_avail($product_id){
        $p['product_id'] = $product_id;
        $result = $this->execSP(__FUNCTION__, "back_office.product_tab__is_avail(:product_id)", $p);
        return $result;
    }
    public function product_img_tab__read($product_id){
        $p['product_id'] = $product_id;
        $result = $this->readSP(__FUNCTION__, "back_office.product_img_tab__read('cur', :product_id)", $p);
        return $result;
    }
    public function brand__read_fs(){
        $result = $this->readSP(__FUNCTION__, "back_office.brand__read_fs('cur')");
        return $result;
    }
    public function category__read_fs(){
        $result = $this->readSP(__FUNCTION__, "back_office.category__read_fs('cur')");
        return $result;
    }
    public function tag__read_fs(){
        $result = $this->readSP(__FUNCTION__, "back_office.tag__read_fs('cur')");
        return $result;
    }
    public function product_tab_read_fs(){
        $result = $this->readSP(__FUNCTION__, "back_office.product_tab_read_fs('cur')");
        return $result;
    }
    public function product_stock__read($stock_id){
        $p['stock_id'] = $stock_id;
        $result = $this->readSP(__FUNCTION__, "back_office.product_stock__read('cur', :stock_id)", $p);
        return $result;
    }
    public function product_stock__modify($a){
        $p['stock_id'] = $a['stock_id'];
        $p['product_id'] = $a['product_id'];
        $result = $this->execSP(__FUNCTION__, "back_office.product_stock__modify(:stock_id, :product_id)", $p);
        return $result;
    }
    public function product_stock__del($product_stock_id){
        $p['product_stock_id'] = $product_stock_id;
        $result = $this->execSP(__FUNCTION__, "back_office.product_stock__del(:product_stock_id)", $p);
        return $result;
    }
}
