<?php
require 'jwt/vendor/autoload.php';
use Jose\Component\Core\JWK;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\Serializer\CompactSerializer;

class Api_Model_DbTable_Auth extends Application_Model_DbTable_Parent {
    public function __construct() {
        parent::__construct();
    }

    public function generateJwt($client_id, $client_fio, $cart_id = null) {
        try {
            $algorithmManager = new \Jose\Component\Core\AlgorithmManager([new HS256()]);
            $jwsBuilder = new JWSBuilder($algorithmManager);

            $payload = json_encode([
                'iss' => 'https://i-health.kz',  // Issuer
                'aud' => 'iHealth_Users',  // Audience
                'exp' => time() + $this->access_exp,
                'iat' => time(),
                'nbf' => time(),  // Not Before
                'sub' => $client_id,  // Subject
                'role' => 'customer',  // Role
                'client_id' => $client_id,
                'client_fio' => $client_fio,
                'cart_id' => $cart_id
            ]);

            $jwk = JWK::createFromJson(json_encode(['k' => $this->secret_key, 'kty' => 'oct']));

            $jws = $jwsBuilder
                ->create()->withPayload($payload)
                ->addSignature($jwk, ['alg' => 'HS256', 'typ' => 'JWT'])
                ->build();

            $serializer = new CompactSerializer();
            return $serializer->serialize($jws, 0);
        } catch (\Exception $e) {
            return 'An error occurred while generating the JWT: ' . $e->getMessage();
        }
    }

    public function validateJwt($token) {
        try {
            $algorithmManager = new \Jose\Component\Core\AlgorithmManager([new HS256()]);
            $jwsVerifier = new JWSVerifier($algorithmManager);

            $serializer = new CompactSerializer();
            $jws = $serializer->unserialize($token);

            $jwk = JWK::createFromJson(json_encode(['k' => $this->secret_key, 'kty' => 'oct']));

            if ($jwsVerifier->verifyWithKey($jws, $jwk, 0)) {
                $payload = json_decode($jws->getPayload(), true);

                // Проверка времени истечения
                if (isset($payload['exp']) && time() >= $payload['exp']) {
                    return null; // Токен истек
                }

                return $payload;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public function generateRefreshToken($client_id) {
        $refreshToken = bin2hex(random_bytes(32));
        // Здесь нужно сохранить refreshToken в базу данных и привязать его к client_id
        $result = $this->client__login($client_id, $refreshToken);
        if($result['status']){
            $result['refresh_token'] = $refreshToken;
        }
        return $result;
    }

    /*public function validateRefreshToken($refreshToken) {
        // Здесь нужно проверить refreshToken в базе данных и, если он валиден, вернуть соответствующий client_id
    }*/

    public function client__get($phone){
        $p['phone'] = $phone;
        $result = $this->getSP(__FUNCTION__, "public.client__get('cur', :phone)", $p);
        return $result;
    }

    public function client_by_token__get($refresh_token){
        $p['refresh_token'] = $refresh_token;
        $result = $this->getSP(__FUNCTION__, "public.client_by_token__get('cur', :refresh_token)", $p);
        return $result;
    }
    public function client__login($client_id, $refresh_token){
        $p['client_id'] = $client_id;
        $p['refresh_token'] = $refresh_token;
        $result = $this->execSP(__FUNCTION__, "public.client__login(:client_id, :refresh_token) id", $p, 'id');
        return $result;
    }
    public function client__register($fio, $phone, $passwd){
        $p['fio'] = $fio;
        $p['phone'] = $phone;
        $p['passwd'] = $passwd;
        $result = $this->execSP(__FUNCTION__, "public.client__register(:fio, :phone, :passwd) id", $p, 'id');
        return $result;
    }
}