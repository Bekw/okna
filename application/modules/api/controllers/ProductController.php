<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.09.2023
 * Time: 16:44
 */
require_once 'ParentController.php';

class Api_ProductController extends Api_ParentController
{
    public function init()
    {
        parent::init();
    }

    public function indexAction(){
        $this->sendResponse(['api' => 'iHealth']);
    }

    public function categoryListAction(){
        $ob = new Api_Model_DbTable_Product();
        $row = $ob->category__read();
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }

    public function stockListAction(){
        $ob = new Api_Model_DbTable_Product();
        $row = $ob->stock__read();
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }

    public function productStockListAction(){
        $ob = new Api_Model_DbTable_Product();
        $stock_id = $this->_getParam('stock_id', 0);
        $limit = $this->_getParam('limit', 10);
        $offset = $this->_getParam('offset', 0);
        $row = $ob->product_by_stock__read($stock_id, $limit, $offset);
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row['error']);
            return;
        }
        $data['products'] = $row['value'];
        $data['total_count'] = $row['scalar'];
        $data['total_pages'] = ceil($row['scalar'] / $limit);
        $this->sendResponse($data);
    }

    public function productCatalogListAction(){
        $ob = new Api_Model_DbTable_Product();
        $rawData = $this->getRequest()->getRawBody();
        $raw = json_decode($rawData, true);
        $row = $ob->product_catalog__read($rawData);
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row['error']);
            return;
        }
        $data['products'] = $row['value'];
        $data['total_count'] = $row['scalar'];
        $data['total_pages'] = ceil($row['scalar'] / (isset($raw['limit']) && $raw['limit'] > 0 ? $raw['limit'] : 1));
        $this->sendResponse($data);
    }

    public function productSearchAction(){
        $ob = new Api_Model_DbTable_Product();
        $rawData = $this->getRequest()->getRawBody();
        $raw = json_decode($rawData, true);
        $row = $ob->product__search($rawData);
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row['error']);
            return;
        }
        $data['products'] = $row['value'];
        $data['total_count'] = $row['scalar'];
        $data['total_pages'] = ceil($row['scalar'] / (isset($raw['limit']) && $raw['limit'] > 0 ? $raw['limit'] : 1));
        $this->sendResponse($data);
    }

    public function productByTagListAction(){
        $ob = new Api_Model_DbTable_Product();
        $tag = $this->_getParam('tag', 'NEW');
        $row = $ob->product_by_tag__read($tag);
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }

    public function productListAction(){
        $ob = new Api_Model_DbTable_Product();
        $row_popular = $ob->product_by_tag__read('POPULAR');
        if(!$row_popular['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row_popular['error']);
            return;
        }
        $row_new = $ob->product_by_tag__read('NEW');
        if(!$row_new['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row_new['error']);
            return;
        }
        $data['popular'] = $row_popular['value'];
        $data['new'] = $row_new['value'];
        $this->sendResponse($data);
    }

    public function productAction(){
        $ob = new Api_Model_DbTable_Product();
        $product_id = $this->_getParam('product_id', 0);
        $row = $ob->product__get($product_id);
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row['error']);
            return;
        }
        $row_img = $ob->product_img__read($product_id);
        if(!$row_img['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row_img['error']);
            return;
        }
        $row_similar = $ob->product_similar__read($product_id);
        if(!$row_similar['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row_similar['error']);
            return;
        }

        $data['product'] = $row['value'];
        $data['product']['images'] = $row_img['value'];
        $data['similar_products'] = $row_similar['value'];

        $this->sendResponse($data);
    }
}


