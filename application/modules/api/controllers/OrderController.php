<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.09.2023
 * Time: 16:44
 */
require_once 'ParentController.php';

class Api_OrderController extends Api_ParentController
{
    public function init()
    {
        parent::init();

        $authModel = new Api_Model_DbTable_Auth();

        $this->payload = $authModel->validateJwt($this->bearer_token);
    }

    public function indexAction(){
        $this->sendResponse(['api' => 'iHealth']);
    }

    public function addToCartAction(){
        $ob = new Api_Model_DbTable_Order();
        $rawData = $this->getRequest()->getRawBody();

        if ($rawData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Неверный формат JSON', 'Тело запроса должен бытьв формате JSON');
            return;
        }

        $jsonData = json_decode($rawData, true);

        $client_id = $this->payload['client_id'] ?? 0;
        $order_id = $jsonData['order_id'] ?? null;
        $product_id = $jsonData['product_id'] ?? null;
        $stock_id = $jsonData['stock_id'] ?? null;

        if (!$product_id) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Ошибка при добавлении в корзину', 'Товар не выбран');
            return;
        }

        $result = $ob->product__add_to_cart($order_id, $client_id, $product_id, $stock_id);

        if(!$result['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $result['error']);
            return;
        }

        $order_id = $result['value'];

        $result = $ob->cart__read($order_id);

        if(!$result['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $result['error']);
            return;
        }

        $cart_data['order_id'] = $order_id;
        $cart_data['sum'] = $result['scalar'];
        $cart_data['products'] = $result['value'];

        $this->sendResponse($cart_data);
    }

    public function cartPlusMinusAction(){
        $ob = new Api_Model_DbTable_Order();
        $rawData = $this->getRequest()->getRawBody();

        if ($rawData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Неверный формат JSON', 'Тело запроса должен бытьв формате JSON');
            return;
        }

        $jsonData = json_decode($rawData, true);

        $order_item_id = $jsonData['order_item_id'] ?? null;
        $cnt = $jsonData['cnt'] ?? null;

        if (!$order_item_id) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Ошибка при увеличении количества товара в корзине', 'Товар не выбран');
            return;
        }

        $result = $ob->cart__plus_minus($order_item_id, $cnt);

        if(!$result['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $result['error']);
            return;
        }
        $order_id = $result['value'];

        $result = $ob->cart__read($order_id);

        if(!$result['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $result['error']);
            return;
        }

        $cart_data['order_id'] = $order_id;
        $cart_data['sum'] = $result['scalar'];
        $cart_data['products'] = $result['value'];

        $this->sendResponse($cart_data);
    }

    public function cartClearAction(){
        $ob = new Api_Model_DbTable_Order();
        $rawData = $this->getRequest()->getRawBody();

        if ($rawData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Неверный формат JSON', 'Тело запроса должен бытьв формате JSON');
            return;
        }

        $jsonData = json_decode($rawData, true);

        $order_id = $jsonData['order_id'] ?? null;

        if (!$order_id) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Ошибка при очистки корзины', 'Заказ не найден');
            return;
        }

        $result = $ob->cart__clear($order_id);

        if(!$result['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $result['error']);
            return;
        }
        $order_id = $result['value'];

        $result = $ob->cart__read($order_id);

        if(!$result['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $result['error']);
            return;
        }

        $cart_data['order_id'] = $order_id;
        $cart_data['sum'] = $result['scalar'];
        $cart_data['products'] = $result['value'];

        $this->sendResponse($cart_data);
    }

    public function cartAction(){
        $ob = new Api_Model_DbTable_Order();
        $rawData = $this->getRequest()->getRawBody();

        if ($rawData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Неверный формат JSON', 'Тело запроса должен бытьв формате JSON');
            return;
        }

        $jsonData = json_decode($rawData, true);

        $order_id = $jsonData['order_id'] ?? null;

        if (!$order_id) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Ошибка при получении корзины', 'Заказ не найден');
            return;
        }

        $result = $ob->cart__read($order_id);

        if(!$result['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $result['error']);
            return;
        }

        $cart_data['order_id'] = $order_id;
        $cart_data['sum'] = $result['scalar'];
        $cart_data['products'] = $result['value'];

        $this->sendResponse($cart_data);
    }
}


