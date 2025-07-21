<?php

use AilosSDK\API\Cob\CobAPI;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

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

        $this->assertNotEmpty($response);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('access_token', $data);
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

        $this->assertNotEmpty($apiResponse);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('access_token', $data);
        $this->assertArrayHasKey('expires_in', $data);
        $this->assertNotEmpty($data['access_token']);
    }


}