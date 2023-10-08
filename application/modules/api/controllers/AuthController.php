<?php
require_once 'ParentController.php';

class Api_AuthController extends Api_ParentController {

    public function init() {
        parent::init();
    }
    private function smsCodeGen() {
        try{
            $code = $this->debug ? '000000' : random_int(100000, 999999);
        }catch (Exception $e){
            return false;
        }
        return $code;
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
        $verify_code = $jsonData['verify_code'] ?? null;
        $uuid = $jsonData['uuid'] ?? null;

        if (!$phone || !$password || !$confirmPassword || !$fio || !$verify_code || !$uuid) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Missing required fields', 'Phone number, password, confirmation password, verify code, or uuid is missing.');
            return;
        }

        $phone = formatPhoneNumber($phone);

        $row_verify = $authModel->sms_verify_by_uuid__get($uuid);

        if(!$row_verify['status']){
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Registration Failed', $row_verify['error']);
            return;
        }

        $row_verify = $row_verify['value'];

        if($row_verify['is_expired']){
            $this->sendResponse(null, self::HTTP_GONE, 'Registration Failed', 'Verification code has expired. Please request a new code');
            return;
        }

        if($row_verify['is_verify']){
            $this->sendResponse(null, self::HTTP_UNUSED, 'Registration Failed', 'Verification code is unused. Please request a new code');
            return;
        }

        if (!password_verify($verify_code, $row_verify['verify_code'])) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Registration Failed', 'Invalid verification code. Please request a new code');
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

        //$this->setRefreshTokenCookie($refreshToken['refresh_token']);

        $encryptedValue = encryptCookie($refreshToken['refresh_token'], $this->refresh_key);

        $authModel->sms_verify__set($row_verify['sms_verify_id']);

        $this->sendResponse([
            'access_token' => $accessToken,
            'refresh_token' => $encryptedValue,
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

            //$this->setRefreshTokenCookie($refreshToken['refresh_token']);

            $encryptedValue = encryptCookie($refreshToken['refresh_token'], $this->refresh_key);

            $this->sendResponse([
                'access_token' => $accessToken,
                'refresh_token' => $encryptedValue,
                'token_type' => 'bearer',
                'expires_in' => $this->access_exp
            ]);
        } else {
            $this->sendResponse(null, self::HTTP_UNAUTHORIZED, 'Authentication failed', 'The phone number or password is incorrect.');
        }
    }

    public function resetPasswordAction() {
        $authModel = new Api_Model_DbTable_Auth();
        $rawData = $this->getRequest()->getRawBody();
        $jsonData = json_decode($rawData, true);

        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Invalid JSON format', 'The request body is not a valid JSON format.');
            return;
        }

        $phone = $jsonData['phone'] ?? null;
        $password = $jsonData['password'] ?? null;
        $confirmPassword = $jsonData['confirm_password'] ?? null;
        $verify_code = $jsonData['verify_code'] ?? null;
        $uuid = $jsonData['uuid'] ?? null;

        if (!$phone || !$password || !$confirmPassword || !$verify_code || !$uuid) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Missing required fields', 'Phone number, password, confirmation password, verify code, or uuid is missing.');
            return;
        }

        $phone = formatPhoneNumber($phone);

        $row_verify = $authModel->sms_verify_by_uuid__get($uuid);

        if(!$row_verify['status']){
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Registration Failed', $row_verify['error']);
            return;
        }

        $row_verify = $row_verify['value'];

        if($row_verify['is_expired']){
            $this->sendResponse(null, self::HTTP_GONE, 'Registration Failed', 'Verification code has expired. Please request a new code');
            return;
        }

        if($row_verify['is_verify']){
            $this->sendResponse(null, self::HTTP_UNUSED, 'Registration Failed', 'Verification code is unused. Please request a new code');
            return;
        }

        if (!password_verify($verify_code, $row_verify['verify_code'])) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Registration Failed', 'Invalid verification code. Please request a new code');
            return;
        }

        if ($password !== $confirmPassword) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Password mismatch', 'Password and confirmation password do not match.');
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $client = $authModel->client__get($phone);

        if($client['status'] === false){
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Registration Failed', $client['error']);
            return;
        }

        $client_id = $client['value']['client_id'];
        $client_fio = $client['value']['client_fio']; // or any default value you want

        $resetResult = $authModel->client_password__reset($client_id, $hashedPassword);

        if($resetResult['status'] === false){
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Password reset Failed', $resetResult['error']);
            return;
        }

        $accessToken = $authModel->generateJwt($client_id, $client_fio);
        $refreshToken = $authModel->generateRefreshToken($client_id);

        if ($refreshToken['status'] === false) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Token Generation Failed', $refreshToken['error']);
            return;
        }

        //$this->setRefreshTokenCookie($refreshToken['refresh_token']);
        $encryptedValue = encryptCookie($refreshToken['refresh_token'], $this->refresh_key);

        $authModel->sms_verify__set($row_verify['sms_verify_id']);

        $this->sendResponse([
            'access_token' => $accessToken,
            'refresh_token' => $encryptedValue,
            'token_type' => 'bearer',
            'expires_in' => $this->access_exp
        ]);
    }

    public function refreshTokenAction() {
        $authModel = new Api_Model_DbTable_Auth();

        // Чтение зашифрованного refresh token из cookie
        $encryptedRefreshToken = $this->bearer_token ?? null;
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
            //$this->setRefreshTokenCookie($newRefreshToken['refresh_token']);

            $encryptedValue = encryptCookie($newRefreshToken['refresh_token'], $this->refresh_key);

            // Отправка новых токенов клиенту
            $this->sendResponse([
                'access_token' => $newAccessToken,
                'refresh_token' => $encryptedValue,
                'token_type' => 'bearer',
                'expires_in' => 3600
            ]);
        } else {
            $this->sendResponse(null, self::HTTP_NOT_FOUND, 'Invalid refresh token', 'The provided refresh token is invalid or expired.');
        }
    }

    public function smsSendAction(){
        $authModel = new Api_Model_DbTable_Auth();
        $rawData = $this->getRequest()->getRawBody();
        $jsonData = json_decode($rawData, true);

        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Invalid JSON format', 'The request body is not a valid JSON format.');
            return;
        }

        $phone = $jsonData['phone'] ?? null;
        $type = $jsonData['type'] ?? null;

        if (!$phone || !$type) {
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'Missing required fields', 'Phone number or type is missing.');
            return;
        }

        if(strtoupper(trim($type)) === 'RESET_PASSWD'){
            $client = $authModel->client__get($phone);

            if($client['status'] === false){
                $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'SMS send Failed', $client['error']);
                return;
            }

            if(is_null($client['value'])){
                $this->sendResponse(null, self::HTTP_BAD_REQUEST, 'SMS send Failed', 'user not found');
                return;
            }
        }

        $phone = formatPhoneNumber($phone);

        try{
            $code = $this->smsCodeGen();

            if(!$code){
                $this->sendResponse(null, self::HTTP_INTERNAL_SERVER_ERROR, 'Verify code generate error');
                return;
            }

            $hashedCode = password_hash($code, PASSWORD_DEFAULT);

            //SEND SMS METHOD
            //SEND SMS METHOD

            $result = $authModel->sms_verify__create($phone, $hashedCode);

            if(!$result['status']){
                $this->sendResponse(null, self::HTTP_BAD_REQUEST, $result['error']);
            }

            $this->sendResponse(['uuid' => $result['value']]);
        }catch (Exception $e){
            $this->sendResponse(null, self::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }

    public function getHashAction(){
        $password = "123456";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        echo $hashedPassword;
    }
}