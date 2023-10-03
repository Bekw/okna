<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.09.2023
 * Time: 16:44
 */
require_once 'ParentController.php';

class Api_IndexController extends Api_ParentController
{
    public function init(){
        parent::init();
    }

    public function indexAction(){
        $this->sendResponse(['api' => 'iHealth']);
    }
}


