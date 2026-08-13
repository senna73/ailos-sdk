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
    private EnviromentEntity $enviroment;

    private AuthCollection $authCollection;

    private Dotenv $dotenv;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dotenv = Dotenv::createImmutable(__DIR__ . '/../../..', '.env');
        $this->dotenv->load();

        $this->enviroment = new EnviromentEntity(
            getenv('AILOS_CONSUMER_KEY') ?: '',
            getenv('AILOS_CONSUMER_SECRET') ?: '',
            getenv('AILOS_URL_CALLBACK') ?: '',
            getenv('AILOS_API_KEY_DEVELOPER') ?: '',
            getenv('AILOS_CODIGO_COOPERATIVA') ?: '',
            getenv('AILOS_CODIGO_CONTA') ?: '',
            getenv('AILOS_SENHA') ?: '',
        );

        if ($this->enviroment->ambiente != 'homol') {
            throw new \Exception('Ambiente invalido para testes');
        }

        $this->authCollection = new AuthCollection($this->enviroment);
    }

    public function testAuthAccessTokenEndpoint(): void
    {
        $accessToken = $this->authCollection->authAccessTokenEndpoint(
            $this->enviroment->consumerKey,
            $this->enviroment->consumerSecret
        );

        $this->assertInstanceOf(AccessTokenEntity::class, $accessToken);
        $this->assertNotEmpty($accessToken->accessToken);
    }

    public function testAuthIdEndpoint(): void
    {
        $token = $this->authCollection->authAccessTokenEndpoint(
            $this->enviroment->consumerKey,
            $this->enviroment->consumerSecret
        )->accessToken;

        $id = $this->authCollection->authIdEndpoint(
            $token,
            $this->enviroment->urlCallback,
            $this->enviroment->developerKey,
            'test-success'
        );

        $this->assertNotEmpty($id);
    }

    public function testAuthJwtEndpoint(): void
    {
        $token = $this->authCollection->authAccessTokenEndpoint(
            $this->enviroment->consumerKey,
            $this->enviroment->consumerSecret
        )->accessToken;

        $id = $this->authCollection->authIdEndpoint(
            $token,
            $this->enviroment->urlCallback,
            $this->enviroment->developerKey,
            'test-success'
        );

        $this->authCollection->authJwtEndpoint(
            $token,
            $id,
            $this->enviroment->codigoCooperativa,
            $this->enviroment->codigoConta,
            $this->enviroment->senha
        );
    }
}
