<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.09.2023
 * Time: 16:44
 */

class Api_ProductController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->getHelper('layout')->disableLayout();

        $auth = new Api_Model_DbTable_Auth();

        $bearerToken = $this->getBearerToken();
        if ($bearerToken === null || !$auth->validateJwt($bearerToken)) {
            $this->sendResponse(null, 401, 'Invalid or missing token');
        }
    }

    private function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    private function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    protected function sendResponse($data, $status = 200, $error_msg = '', $error_desc = '') {
        $responseFormat = [
            'data' => $data,
            'response' => ($status === 200),
            'error' => [
                'msg' => $error_msg,
                'desc' => $error_desc
            ]
        ];

        // Устанавливаем HTTP статус-код
        http_response_code($status);

        // Устанавливаем заголовок Content-Type
        header('Content-Type: application/json');

        // Выводим тело ответа в формате JSON
        echo json_encode($responseFormat);

        // Завершаем выполнение скрипта
        exit;
    }

    public function indexAction()
    {
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->appendBody(json_encode([
                'message' => 'Welcome to the iHealth API',
                'status'  => 'success'
            ]))
            ->setHeader('Content-Type', 'application/json');
    }

    public function categoryListAction(){
        $ob = new Api_Model_DbTable_Product();
        $row = $ob->category__read();
        if(!$row['status']){
            $this->sendResponse(null, 404, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }

    public function stockListAction(){
        $ob = new Api_Model_DbTable_Product();
        $row = $ob->stock__read();
        if(!$row['status']){
            $this->sendResponse(null, 404, $row['error']);
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
            $this->sendResponse(null, 404, $row['error']);
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
            $this->sendResponse(null, 404, $row['error']);
            return;
        }
        $data['products'] = $row['value'];
        $data['total_count'] = $row['scalar'];
        $data['total_pages'] = ceil($row['scalar'] / (isset($raw['limit']) && $raw['limit'] > 0 ? $raw['limit'] : 1));
        $this->sendResponse($data);
    }

    public function productListAction(){
        $ob = new Api_Model_DbTable_Product();
        $row = $ob->product__read();
        if(!$row['status']){
            $this->sendResponse(null, 404, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }

    public function productAction(){
        $ob = new Api_Model_DbTable_Product();
        $product_id = $this->_getParam('product_id', 0);
        $row = $ob->product__get($product_id);
        if(!$row['status']){
            $this->sendResponse(null, 404, $row['error']);
            return;
        }
        $row_img = $ob->product_img__read($product_id);
        if(!$row_img['status']){
            $this->sendResponse(null, 404, $row_img['error']);
            return;
        }
        $row_similar = $ob->product_similar__read($product_id);
        if(!$row_similar['status']){
            $this->sendResponse(null, 404, $row_similar['error']);
            return;
        }

        $data['product'] = $row['value'];
        $data['product']['images'] = $row_img['value'];
        $data['similar_products'] = $row_similar['value'];

        $this->sendResponse($data);
    }
}


