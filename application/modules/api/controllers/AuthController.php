<?php
require_once 'ParentController.php';

class Api_AuthController extends Api_ParentController {

    public function init() {
        parent::init();
    }

    private function setRefreshTokenCookie($token) {
        $encryptedValue = encryptCookie($token, $this->refresh_key);
        $domain = $this->debug ? ".localhost" : ".i-health.kz";
        setcookie("secureCookie", $encryptedValue, time() + $this->refresh_exp, "/", $domain, !$this->debug, !$this->debug);
    }

    public function indexAction() {
        $this->sendResponse(null, self::HTTP_FOUND, 'Redirecting to index');
        $this->_helper->redirector->gotoSimple('index', 'index', 'api');
    }

    public function registerAction() {
        $authModel = new Api_Model_DbTable_Auth();
        $rawData = $this->getRequest()->getRawBody();
        $jsonData = json_decode($rawData, true);

        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Invalid JSON format', 'The request body is not a valid JSON format.');
            return;
        }

        $fio = $jsonData['fio'] ?? null;
        $phone = $jsonData['phone'] ?? null;
        $password = $jsonData['password'] ?? null;
        $confirmPassword = $jsonData['confirm_password'] ?? null;

        if (!$phone || !$password || !$confirmPassword || !$fio) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Missing required fields', 'Phone number, password, or confirmation password is missing.');
            return;
        }

        if ($password !== $confirmPassword) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Password mismatch', 'Password and confirmation password do not match.');
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $registrationResult = $authModel->client__register($fio, $phone, $hashedPassword);

        if($registrationResult['status'] === false){
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Registration Failed', $registrationResult['error']);
            return;
        }

        $client = $authModel->client__get($phone);

        if($client['status'] === false){
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Registration Failed', $client['error']);
            return;
        }

        $client_id = $client['value']['client_id'];
        $client_fio = $client['value']['client_fio']; // or any default value you want

        $accessToken = $authModel->generateJwt($client_id, $client_fio);
        $refreshToken = $authModel->generateRefreshToken($client_id);

        if ($refreshToken['status'] === false) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Token Generation Failed', $refreshToken['error']);
            return;
        }

        $this->setRefreshTokenCookie($refreshToken['refresh_token']);

        $this->sendResponse([
            'access_token' => $accessToken,
            'token_type' => 'bearer',
            'expires_in' => $this->access_exp
        ]);
    }

    public function loginAction() {
        $authModel = new Api_Model_DbTable_Auth();
        $rawData = $this->getRequest()->getRawBody();
        $jsonData = json_decode($rawData, true);

        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Invalid JSON format', 'The request body is not a valid JSON format.');
            return;
        }

        $phone = $jsonData['phone'] ?? null;
        $password = $jsonData['password'] ?? null;

        if (!$phone || !$password) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Missing phone or password', 'The phone number or password is missing from the request.');
            return;
        }

        $client = $authModel->client__get($phone);

        if($client['status'] === false){
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'DB error', $client['error']);
            return;
        }

        $client = $client['value'];

        if ($client && password_verify($password, $client['passwd'])) {
            $client_id = $client['client_id'];
            $client_fio = $client['client_fio'];
            $accessToken = $authModel->generateJwt($client_id, $client_fio);
            $refreshToken = $authModel->generateRefreshToken($client_id);

            if($refreshToken['status'] === false){
                $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Token Generation Failed', $refreshToken['error']);
                return;
            }

            $this->setRefreshTokenCookie($refreshToken['refresh_token']);

            $this->sendResponse([
                'access_token' => $accessToken,
                'token_type' => 'bearer',
                'expires_in' => $this->access_exp
            ]);
        } else {
            $this->sendResponse(null, self::HTTP_UNAUTHORIZED, 'Authentication failed', 'The phone number or password is incorrect.');
        }
    }

    public function refreshTokenAction() {
        $authModel = new Api_Model_DbTable_Auth();

        // Чтение зашифрованного refresh token из cookie
        $encryptedRefreshToken = $_COOKIE['secureCookie'] ?? null;
        // Дешифровка (предполагая, что у вас есть метод для дешифровки)
        $refreshToken = decryptCookie($encryptedRefreshToken, $this->refresh_key);

        // Валидация refresh token и получение информации о пользователе
        $user = $authModel->client_by_token__get($refreshToken);

        if(!$user['status']){
            $this->sendResponse(null, self::HTTP_NOT_FOUND, 'Token Issue', $user['error']);
        }

        $user = $user['value'];

        if (!$user['is_valid']){
            $this->sendResponse(null, self::HTTP_UNAUTHORIZED, 'Token Issue', 'The provided refresh token has expired or is invalid. Please authenticate again.');
        }

        if (!is_null($user)) {
            // Генерация новых токенов
            $newAccessToken = $authModel->generateJwt($user['client_id'], $user['client_fio']);
            $newRefreshToken = $authModel->generateRefreshToken($user['client_id']);

            if(!$newRefreshToken['status']){
                $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, 'Generate refresh token error', $newRefreshToken['error']);
            }

            // Шифрование нового refresh token и сохранение в cookie
            $this->setRefreshTokenCookie($newRefreshToken['refresh_token']);

            // Отправка новых токенов клиенту
            $this->sendResponse([
                'access_token' => $newAccessToken,
                'token_type' => 'bearer',
                'expires_in' => 3600
            ]);
        } else {
            $this->sendResponse(null, self::HTTP_NOT_FOUND, 'Invalid refresh token', 'The provided refresh token is invalid or expired.');
        }
    }

    public function getHashAction(){
        $password = "123456";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        echo $hashedPassword;
    }
}