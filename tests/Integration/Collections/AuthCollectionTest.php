<?php

declare(strict_types=1);

namespace Ailos\Sdk\Tests\Integration\Collections;

use Ailos\Sdk\Collections\AuthCollection;
use Ailos\Sdk\Entities\AccessTokenEntity;
use Ailos\Sdk\Entities\EnviromentEntity;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class AuthCollectionTest extends TestCase
{
    /*

    private AuthCollection $authCollection;

    private Dotenv $dotenv;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dotenv = Dotenv::createImmutable(__DIR__ . '/../../..', '.env.test');
        $this->dotenv->load();

        $this->authCollection = new AuthCollection(
            new EnviromentEntity(
                $_ENV['AILOS_CONSUMER_KEY'],
                $_ENV['AILOS_CONSUMER_SECRET'],
                $_ENV['AILOS_URL_CALLBACK'],
                $_ENV['AILOS_API_KEY_DEVELOPER'],
                $_ENV['AILOS_CODIGO_COOPERATIVA'],
                $_ENV['AILOS_CODIGO_CONTA'],
                $_ENV['AILOS_SENHA']
            )
        );
    }

    public function testAuthAccessTokenEndpoint(): void
    {
        $accessToken = $this->authCollection->authAccessTokenEndpoint(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET']
        );

        $this->assertInstanceOf(AccessTokenEntity::class, $accessToken);
        $this->assertNotEmpty($accessToken->accessToken);
    }

    public function testAuthIdEndpoint(): void
    {
        $token = $this->authCollection->authAccessTokenEndpoint(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET']
        )->accessToken;

        $id = $this->authCollection->authIdEndpoint(
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
        $token = $this->authCollection->authAccessTokenEndpoint(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET']
        )->accessToken;

        $id = $this->authCollection->authIdEndpoint(
            $token,
            $_ENV['AILOS_URL_CALLBACK'],
            $_ENV['AILOS_API_KEY_DEVELOPER'],
            'test-success'
        );

        $this->authCollection->authJwtEndpoint(
            $token,
            $id,
            (int) $_ENV['AILOS_CODIGO_COOPERATIVA'],
            $_ENV['AILOS_CODIGO_CONTA'],
            $_ENV['AILOS_SENHA']
        );

        $this->assertTrue(true);
    }
         */
}
