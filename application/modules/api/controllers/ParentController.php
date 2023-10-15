<?php

class Api_ParentController extends Zend_Controller_Action{
    protected $skipAuthActions = [
        'login',
        'register',
        'getHash',
        'refresh-token',
        'sms-send',
        'reset-password',
        'stock-list',
        'category-list',
        'product-list',
        'product-by-tag-list',
        'product',
        'product-stock-list',
        'product-catalog-list',
        'product-search',
        'brand-list',
        'banner-list',
        'city-list',
        'add-to-cart',
        'cart-plus-minus',
        'cart',
        'cart-clear',
        'cart-product-del',

    ];

    // 1xx Информационные
    const HTTP_CONTINUE = 100; // Продолжить, клиент должен продолжить запрос
    const HTTP_SWITCHING_PROTOCOLS = 101; // Переключение протоколов

    // 2xx Успешно
    const HTTP_OK = 200; // Запрос успешно выполнен
    const HTTP_CREATED = 201; // Запрос выполнен, ресурс создан
    const HTTP_ACCEPTED = 202; // Запрос принят, но обработка не завершена
    const HTTP_NO_CONTENT = 204; // Запрос успешно обработан, но в ответе должно быть отсутствие тела

    // 3xx Перенаправление
    const HTTP_MULTIPLE_CHOICES = 300; // Несколько вариантов выбора для ресурса
    const HTTP_MOVED_PERMANENTLY = 301; // Ресурс перемещен на постоянную основу
    const HTTP_FOUND = 302; // Ресурс временно перемещен
    const HTTP_UNUSED = 306; // Ресурс временно перемещен

    // 4xx Ошибка клиента
    const HTTP_BAD_REQUEST = 400; // Плохой, некорректный запрос от клиента
    const HTTP_UNAUTHORIZED = 401; // Необходима аутентификация
    const HTTP_FORBIDDEN = 403; // Запрещено, нет прав на выполнение операции
    const HTTP_NOT_FOUND = 404; // Ресурс не найден
    const HTTP_METHOD_NOT_ALLOWED = 405; // Метод не поддерживается для данного ресурса
    const HTTP_GONE = 410; // Истек

    // 5xx Ошибка сервера
    const HTTP_INTERNAL_SERVER_ERROR = 500; // Внутренняя ошибка сервера
    const HTTP_NOT_IMPLEMENTED = 501; // Функционал не поддерживается
    const HTTP_BAD_GATEWAY = 502; // Плохой, ошибочный шлюз
    const HTTP_SERVICE_UNAVAILABLE = 503; // Сервис недоступен
    const HTTP_GATEWAY_TIMEOUT = 504; // Время ожидания шлюза истекло

    public function init(){
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->getHelper('layout')->disableLayout();

        $currentAction = $this->_request->getActionName();

        $parent = new Application_Model_DbTable_Parent();

        $config = $parent->getConfigs();

        $this->debug = $config['debug'];
        $this->secret_key = $config['secret_key'];
        $this->access_exp = $config['access_exp'];
        $this->refresh_exp = $config['refresh_exp'];
        $this->refresh_key = $config['refresh_key'];
        $this->bearer_token = null;

        $bearerToken = $this->getBearerToken();
        if (!in_array($currentAction, $this->skipAuthActions)) {
            $auth = new Api_Model_DbTable_Auth();

            if ($bearerToken === null || !$auth->validateJwt($bearerToken)) {
                $this->sendResponse(null, self::HTTP_UNAUTHORIZED, 'Invalid or missing token');
            }
        }
        $this->bearer_token = $bearerToken;
    }

    public function sendResponse($data, $status = self::HTTP_OK, $error_msg = '', $error_desc = '') {
        $responseFormat = [
            'data' => $data,
            'response' => ($status === self::HTTP_OK),
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
}

