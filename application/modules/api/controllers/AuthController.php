<?php

class Api_AuthController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->getHelper('layout')->disableLayout();

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $this->debug = $config->constants->debug;
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

    public function indexAction() {
        $this->sendResponse(null, 302, 'Redirecting to index');
        $this->_helper->redirector->gotoSimple('index', 'index', 'api');
    }

    public function loginAction() {
        $authModel = new Api_Model_DbTable_Auth();
        $rawData = $this->getRequest()->getRawBody();
        $jsonData = json_decode($rawData, true);

        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(null, 400, 'Invalid JSON format', 'The request body is not a valid JSON format.');
            return;
        }

        $phone = $jsonData['phone'] ?? null;
        $password = $jsonData['password'] ?? null;

        if (!$phone || !$password) {
            $this->sendResponse(null, 400, 'Missing phone or password', 'The phone number or password is missing from the request.');
            return;
        }

        $client = $authModel->client__get($phone);

        if($client['status'] === false){
            $this->sendResponse(null, 400, 'DB error', $client['error']);
            return;
        }

        $client = $client['value'];

        if ($client && password_verify($password, $client['passwd'])) {
            $client_id = $client['client_id'];
            $client_fio = $client['client_fio'];
            $accessToken = $authModel->generateJwt($client_id, $client_fio);
            $refreshToken = $authModel->generateRefreshToken($client_id);

            if($refreshToken['status'] === false){
                $this->sendResponse(null, 400, 'Token Generation Failed', $refreshToken['error']);
                return;
            }

            $key = "yehtrt92@@";  // Секретный ключ (не хранить в коде на продакшене)
            $originalValue = $refreshToken['refresh_token'];

            $encryptedValue = encryptCookie($originalValue, $key);

            if($this->debug){
                setcookie("secureCookie", $encryptedValue, time() + (7 * 24 * 60 * 60), "/", ".itp.kz", false, false);
            }else{
                setcookie("secureCookie", $encryptedValue, time() + (7 * 24 * 60 * 60), "/", ".i-helath.kz", true, true);
            }
            $this->sendResponse([
                'access_token' => $accessToken,
                'token_type' => 'bearer',
                'expires_in' => 3600
            ]);
        } else {
            $this->sendResponse(null, 401, 'Authentication failed', 'The phone number or password is incorrect.');
        }
    }

    public function refreshTokenAction() {
        $authModel = new Api_Model_DbTable_Auth();

        // Чтение зашифрованного refresh token из cookie
        $encryptedRefreshToken = $_COOKIE['secureCookie'] ?? null;
        $key = "yehtrt92@@";
        // Дешифровка (предполагая, что у вас есть метод для дешифровки)
        $refreshToken = decryptCookie($encryptedRefreshToken, $key);

        // Валидация refresh token и получение информации о пользователе
        $user = $authModel->client_by_token__get($refreshToken);

        if(!$user['status']){
            $this->sendResponse(null, 404, 'Token Issue', $user['error']);
        }

        $user = $user['value'];

        if (!$user['is_valid']){
            $this->sendResponse(null, 401, 'Token Issue', 'The provided refresh token has expired or is invalid. Please authenticate again.');
        }

        if (!is_null($user)) {
            // Генерация новых токенов
            $newAccessToken = $authModel->generateJwt($user['client_id'], $user['client_fio']);
            $newRefreshToken = $authModel->generateRefreshToken($user['client_id']);

            if(!$newRefreshToken['status']){
                $this->sendResponse(null, 500, 'Generate refresh token error', $newRefreshToken['error']);
            }

            // Шифрование нового refresh token и сохранение в cookie
            $encryptedNewRefreshToken = encryptCookie($newRefreshToken['refresh_token'], $key);
            if($this->debug){
                setcookie("secureCookie", $encryptedNewRefreshToken, time() + (7 * 24 * 60 * 60), "/", ".itp.kz", false, false);
            }else{
                setcookie("secureCookie", $encryptedNewRefreshToken, time() + (7 * 24 * 60 * 60), "/", ".i-helath.kz", true, true);
            }

            // Отправка новых токенов клиенту
            $this->sendResponse([
                'access_token' => $newAccessToken,
                'token_type' => 'bearer',
                'expires_in' => 3600
            ]);
        } else {
            $this->sendResponse(null, 404, 'Invalid refresh token', 'The provided refresh token is invalid or expired.');
        }
    }

    public function getHashAction(){
        $password = "123456";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        echo $hashedPassword;
    }
}