<?php
class Api_Model_DbTable_Product extends Application_Model_DbTable_Parent {
    public function product__read(){
        $result = $this->readSP(__FUNCTION__, "public.product__read('cur')");
        return $result;
    }
    public function product__get($product_id){
        $p['product_id'] = $product_id;
        $result = $this->getSP(__FUNCTION__, "public.product__get('cur', :product_id)", $p);
        return $result;
    }
    public function product_by_tag__read($tag_code){
        $p['tag_code'] = $tag_code;
        $result = $this->readSP(__FUNCTION__, "public.product_by_tag__read('cur', :tag_code)", $p);
        return $result;
    }
    public function product_img__read($product_id){
        $p['product_id'] = $product_id;
        $result = $this->readSP(__FUNCTION__, "public.product_img__read('cur', :product_id)", $p);
        return $result;
    }
    public function product_similar__read($product_id){
        $p['product_id'] = $product_id;
        $result = $this->readSP(__FUNCTION__, "public.product_similar__read('cur', :product_id)", $p);
        return $result;
    }
    public function category__read(){
        $result = $this->readSP(__FUNCTION__, "public.category__read('cur')");
        return $result;
    }
    public function stock__read(){
        $result = $this->readSP(__FUNCTION__, "public.stock__read('cur')");
        return $result;
    }
    public function product_by_stock__read($stock_id, $limit, $offset){
        $p['stock_id'] = zeroToNull($stock_id);
        $p['limit'] = zeroToNull($limit);
        $p['offset'] = zeroToNull($offset);
        $result = $this->readSP(__FUNCTION__, "public.product_by_stock__read('cur', :stock_id, :limit, :offset)", $p);
        return $result;
    }
    public function product_catalog__read($filter_json){
        $p['filter_json'] = $filter_json ?? null;
        $result = $this->readSP(__FUNCTION__, "public.product_catalog__read('cur', :filter_json)", $p);
        return $result;
    }
    public function product__search($filter_json){
        $p['filter_json'] = $filter_json ?? null;
        $result = $this->readSP(__FUNCTION__, "public.product__search('cur', :filter_json)", $p);
        return $result;
    }
}