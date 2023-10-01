<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.09.2023
 * Time: 16:44
 */

class Api_DictController extends Zend_Controller_Action
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

    public function brandListAction(){
        $ob = new Api_Model_DbTable_Dict();
        $row = $ob->brand__read();
        if(!$row['status']){
            $this->sendResponse(null, 404, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }

    public function bannerListAction(){
        $ob = new Api_Model_DbTable_Dict();
        $row = $ob->banner__read();
        if(!$row['status']){
            $this->sendResponse(null, 404, $row['error']);
            return;
        }
        $this->sendResponse($row['value']);
    }
}


