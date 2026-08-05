<?php

declare(strict_types=1);

namespace Ailos\Sdk\Tests\Integration\Collections;

use Ailos\Sdk\Collections\AuthCollection;
use Ailos\Sdk\Entities\AccessTokenEntity;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class AuthCollectionTest extends TestCase
{
    private Dotenv $dotenv;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dotenv = Dotenv::createImmutable(__DIR__ . '/../../..', '.env.test');
        $this->dotenv->load();
    }

    public function testAuthAccessTokenEndpoint(): void
    {
        $authCollection = new AuthCollection();

        $accessToken = $authCollection->authAccessTokenEndpoint(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET']
        );

        $this->assertInstanceOf(AccessTokenEntity::class, $accessToken);
        $this->assertNotEmpty($accessToken->accessToken);
    }

    public function testAuthIdEndpoint(): void
    {
        $authCollection = new AuthCollection();
        $token = $authCollection->authAccessTokenEndpoint(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET']
        )->accessToken;

        $id = $authCollection->authIdEndpoint(
            $token,
            $_ENV['AILOS_URL_CALLBACK'],
            $_ENV['AILOS_API_KEY_DEVELOPER'],
            'test-success'
        );

        $this->assertIsString($id);
        $this->assertNotEmpty($id);
    }

    public function testAuthJwtEndpoint(): void
    {
        $authCollection = new AuthCollection();
        $token = $authCollection->authAccessTokenEndpoint(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET']
        )->accessToken;

        $id = $authCollection->authIdEndpoint(
            $token,
            $_ENV['AILOS_URL_CALLBACK'],
            $_ENV['AILOS_API_KEY_DEVELOPER'],
            'test-success'
        );

        $authCollection->authJwtEndpoint(
            $token,
            $id,
            (int) $_ENV['AILOS_CODIGO_COOPERATIVA'],
            $_ENV['AILOS_CODIGO_CONTA'],
            $_ENV['AILOS_SENHA']
        );

        $this->assertTrue(true);
    }
}
