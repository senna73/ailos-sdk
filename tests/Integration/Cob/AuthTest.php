<?php

use AilosSDK\API\Cob\CobAPI;
use AilosSDK\Common\Models\ApiResponse;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface;

class AuthTest extends TestCase
{
    public CobAPI $cob;

    public function setUp(): void
    {
        static $loaded = false;

        if (!$loaded && file_exists(__DIR__ . '/.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->safeLoad();
            $loaded = true;
        }

        $this->cob = new CobAPI();
    }


    public function testAccessToken()
    {
        $response = $this->cob->auth->accessToken(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET'],
        );

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotEmpty($response);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('access_token', $data);
        $this->assertArrayHasKey('token_type', $data);
        $this->assertArrayHasKey('expires_in', $data);
        $this->assertNotEmpty($data['access_token']);
    }

    public function testGetAccessToken()
    {
        $apiResponse = $this->cob->auth->getAccessToken(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET'],
        );

        $data = $apiResponse->getData();

        $this->assertInstanceOf(ApiResponse::class, $apiResponse);
        $this->assertNotEmpty($apiResponse);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('access_token', $data);
        $this->assertArrayHasKey('token_type', $data);
        $this->assertArrayHasKey('expires_in', $data);
        $this->assertNotEmpty($data['access_token']);
    }

    public function testId()
    {
        $dataToken = json_decode(
            $this->cob->auth->accessToken(
                $_ENV['AILOS_CONSUMER_KEY'],
                $_ENV['AILOS_CONSUMER_SECRET'],
            )->getBody()->getContents(),
            true
        );

        $this->cob->config->setDefaultHeaders([
            'Authorization' => $dataToken['token_type'] . ' ' . $dataToken['access_token']
        ]);

        $response = $this->cob->auth->id(
            $_ENV['AILOS_CALLBACK_URL'],
            $_ENV['AILOS_API_KEY_DEVELOPER_UUID'],
            'meu-state-teste'
        );

        $body = $response->getBody()->getContents();
        $data = urlencode($body);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertIsString($data);
        $this->assertNotEmpty($data);
    }

    public function testGetId()
    {
        $this->cob->auth->getAccessToken(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET'],
            true
        );

        $apiResponse = $this->cob->auth->getId(
            $_ENV['AILOS_CALLBACK_URL'],
            $_ENV['AILOS_API_KEY_DEVELOPER_UUID'],
            'meu-state-teste'
        );

        $data = $apiResponse->getData();

        $this->assertInstanceOf(ApiResponse::class, $apiResponse);
        $this->assertArrayHasKey('data', $data);
        $this->assertIsString($data['data']);
        $this->assertNotEmpty($data['data']);
    }

    public function testAuth()
    {
        $dataToken = json_decode(
            $this->cob->auth->accessToken(
                $_ENV['AILOS_CONSUMER_KEY'],
                $_ENV['AILOS_CONSUMER_SECRET'],
            )->getBody()->getContents(),
            true
        );

        $this->cob->config->setDefaultHeaders([
            'Authorization' => $dataToken['token_type'] . ' ' . $dataToken['access_token']
        ]);


        $id = $this->cob->auth->id(
            $_ENV['AILOS_CALLBACK_URL'],
            $_ENV['AILOS_API_KEY_DEVELOPER_UUID'],
            'meu-state-teste'
        )->getBody()->getContents();

        $response = $this->cob->auth->auth(
            $id,
            $_ENV['AILOS_LOGIN_COOP'],
            $_ENV['AILOS_LOGIN_ACCOUNT'],
            $_ENV['AILOS_LOGIN_PASSWORD'],
        );
        
        $data = $response->getBody()->getContents();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertIsString($data);
        $this->assertNotEmpty($data);
    }

    public function testGetAuth()
    {
        
        $this->cob->auth->getAccessToken(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET'],
            true
        );

        $id = $this->cob->auth->getId(
            $_ENV['AILOS_CALLBACK_URL'],
            $_ENV['AILOS_API_KEY_DEVELOPER_UUID'],
            'meu-state-teste'
        )->getData()['data'];

        $response = $this->cob->auth->getAuth(
            $id,
            $_ENV['AILOS_LOGIN_COOP'],
            $_ENV['AILOS_LOGIN_ACCOUNT'],
            $_ENV['AILOS_LOGIN_PASSWORD'],
        );
        
        $data = $response->getData();

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertArrayHasKey('data', $data);
        $this->assertIsString($data['data']);
        $this->assertNotEmpty($data['data']);
    }

    public function testRefresh()
    {
        $dataToken = json_decode(
            $this->cob->auth->accessToken(
                $_ENV['AILOS_CONSUMER_KEY'],
                $_ENV['AILOS_CONSUMER_SECRET'],
            )->getBody()->getContents(),
            true
        );

        $this->cob->config->setDefaultHeaders([
            'Authorization' => $dataToken['token_type'] . ' ' . $dataToken['access_token']
        ]);


        $id = $this->cob->auth->id(
            $_ENV['AILOS_CALLBACK_URL'],
            $_ENV['AILOS_API_KEY_DEVELOPER_UUID'],
            'meu-state-teste'
        )->getBody()->getContents();

        $this->cob->auth->auth(
            $id,
            $_ENV['AILOS_LOGIN_COOP'],
            $_ENV['AILOS_LOGIN_ACCOUNT'],
            $_ENV['AILOS_LOGIN_PASSWORD'],
        );

        $response = $this->cob->auth->refresh($_ENV['AILOS_CODE']);

        $data = $response->getBody()->getContents();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertIsString($data);
        $this->assertNotEmpty($data);
    }

    public function testGetRefresh()
    {
        $this->cob->auth->getAccessToken(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET'],
            true
        );

        $id = $this->cob->auth->getId(
            $_ENV['AILOS_CALLBACK_URL'],
            $_ENV['AILOS_API_KEY_DEVELOPER_UUID'],
            'meu-state-teste'
        )->getData()['data'];

        $this->cob->auth->getAuth(
            $id,
            $_ENV['AILOS_LOGIN_COOP'],
            $_ENV['AILOS_LOGIN_ACCOUNT'],
            $_ENV['AILOS_LOGIN_PASSWORD'],
        );

        $response = $this->cob->auth->getRefresh($_ENV['AILOS_CODE']);

        $data = $response->getData();

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertArrayHasKey('data', $data);
        $this->assertIsString($data['data']);
        $this->assertNotEmpty($data['data']);
    }

    public function testGetFullAuth()
    {
        $response = $this->cob->auth->getFullAuth(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET'],
            $_ENV['AILOS_CALLBACK_URL'],
            $_ENV['AILOS_API_KEY_DEVELOPER_UUID'],
            'teste-state',
            $_ENV['AILOS_LOGIN_COOP'],
            $_ENV['AILOS_LOGIN_ACCOUNT'],
            $_ENV['AILOS_LOGIN_PASSWORD']
        );

        $data = $response->getData();

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertArrayHasKey('data', $data);
        $this->assertIsString($data['data']);
        $this->assertNotEmpty($data['data']);
    }
}