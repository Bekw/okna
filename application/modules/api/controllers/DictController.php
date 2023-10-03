<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.09.2023
 * Time: 16:44
 */
require_once 'ParentController.php';

class Api_DictController extends Api_ParentController
{
    public function init(){
        parent::init();
    }

    public function indexAction(){
        $this->sendResponse(['api' => 'iHealth']);
    }

    public function brandListAction(){
        $ob = new Api_Model_DbTable_Dict();
        $row = $ob->brand__read();
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }

    public function bannerListAction(){
        $ob = new Api_Model_DbTable_Dict();
        $row = $ob->banner__read();
        if(!$row['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }
}


