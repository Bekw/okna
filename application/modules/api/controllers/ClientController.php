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
    public function init()
    {
        parent::init();
    }

    public function indexAction(){
        $this->sendResponse(['api' => 'iHealth']);
    }

    public function orderHistoryAction(){
        $ob = new Api_Model_DbTable_Client();
        $client_id = $this->_getParam('client_id', 0);
        $row = $ob->client_order_hist__read($client_id);
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }

    public function clientProfileAction(){
        $ob = new Api_Model_DbTable_Client();
        $client_id = $this->_getParam('client_id', 0);
        $row = $ob->client_profile__get($client_id);
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }
}


