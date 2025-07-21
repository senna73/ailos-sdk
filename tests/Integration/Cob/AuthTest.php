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
        $responseToken = $this->cob->auth->accessToken(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET'],
        );

        $bodyToken = $responseToken->getBody()->getContents();
        $dataToken = json_decode($bodyToken, true);

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


}