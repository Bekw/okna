<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.09.2023
 * Time: 16:44
 */
require_once 'ParentController.php';

class Api_ClientController extends Api_ParentController
{
    public function init(){
        parent::init();

        $authModel = new Api_Model_DbTable_Auth();

        $this->payload = $authModel->validateJwt($this->bearer_token);
    }

    public function indexAction(){
        $this->sendResponse(['api' => 'iHealth']);
    }

    public function orderHistoryAction(){
        $ob = new Api_Model_DbTable_Client();
        $client_id = $this->payload['client_id'] ?? 0;
        $row = $ob->client_order_hist__read($client_id);
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }

    public function clientProfileAction(){
        $ob = new Api_Model_DbTable_Client();
        $client_id = $this->payload['client_id'] ?? 0;
        $row = $ob->client_profile__get($client_id);
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }

    public function clientProfileModifyAction(){
        $ob = new Api_Model_DbTable_Client();
        $rawData = $this->getRequest()->getRawBody();
        $jsonData = json_decode($rawData, true);

        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Invalid JSON format', 'The request body is not a valid JSON format.');
            return;
        }

        $result = $ob->client_profile__modify($rawData);

        if(!$result['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $result['error']);
            return;
        }
        $this->sendResponse($result['value']);
    }
    public function clientAddressListAction(){
        $ob = new Api_Model_DbTable_Client();
        $client_id = $this->payload['client_id'] ?? 0;
        $row = $ob->client_address__read($client_id);
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }

    public function clientAddressModifyAction(){
        $ob = new Api_Model_DbTable_Client();
        $rawData = $this->getRequest()->getRawBody();
        $jsonData = json_decode($rawData, true);

        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Invalid JSON format', 'The request body is not a valid JSON format.');
            return;
        }

        $client_id = $jsonData['client_id'] ?? null;
        $city_id = $jsonData['city_id'] ?? null;
        $address = $jsonData['address'] ?? null;

        if (!$client_id || !$city_id || !$address) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Missing required fields', 'client_id, city_id or address is missing.');
            return;
        }

        $result = $ob->client_address__modify($rawData);

        if(!$result['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $result['error']);
            return;
        }
        $this->sendResponse($result['value']);
    }
}


