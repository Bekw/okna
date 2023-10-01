<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.09.2023
 * Time: 16:44
 */

class Api_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->getHelper('layout')->disableLayout();
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
}


