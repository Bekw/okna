<?php
class Api_Model_DbTable_Order extends Application_Model_DbTable_Parent {
    public function product__add_to_cart($order_id, $client_id, $product_id, $stock_id){
        $p['order_id'] = $order_id;
        $p['client_id'] = zeroToNull($client_id);
        $p['product_id'] = $product_id;
        $p['stock_id'] = $stock_id;
        $result = $this->execSP(__FUNCTION__, "public.product__add_to_cart(:order_id, :client_id, :product_id, :stock_id) id", $p, 'id');
        return $result;
    }
    public function cart__plus_minus($order_item_id, $cnt){
        $p['order_item_id'] = $order_item_id;
        $p['cnt'] = $cnt;
        $result = $this->execSP(__FUNCTION__, "public.cart__plus_minus(:order_item_id, :cnt) id", $p, 'id');
        return $result;
    }
    public function cart__read($order_id){
        $p['order_id'] = $order_id;
        $result = $this->readSP(__FUNCTION__, "public.cart__read('cur', :order_id)", $p);
        return $result;
    }
    public function cart__clear($order_id){
        $p['order_id'] = $order_id;
        $result = $this->execSP(__FUNCTION__, "public.cart__clear(:order_id) id", $p, 'id');
        return $result;
    }
    public function cart_product__del($order_item_id){
        $p['order_item_id'] = $order_item_id;
        $result = $this->execSP(__FUNCTION__, "public.cart_product__del(:order_item_id) id", $p, 'id');
        return $result;
    }
}