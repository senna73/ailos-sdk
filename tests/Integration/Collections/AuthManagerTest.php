<?php

declare(strict_types=1);

namespace Ailos\Sdk\Tests\Integration\Collections;

use Ailos\Sdk\Entities\EnviromentEntity;
use Ailos\Sdk\Framework\AuthManager;
use Ailos\Sdk\Framework\HttpClient;
use Dotenv\Dotenv;
use Dotenv\Repository\RepositoryBuilder;
use PHPUnit\Framework\TestCase;

class AuthManagerTest extends TestCase
{
    private AuthManager $authManager;

    protected function setUp(): void
    {
        parent::setUp();

        $repository = RepositoryBuilder::createWithDefaultAdapters()->make();

        $dotenv = Dotenv::create($repository, __DIR__ . '/../../..', '.env');
        $dotenv->safeLoad();

        $enviroment = new EnviromentEntity(
            (string) ($repository->get('AILOS_CONSUMER_KEY') ?? ''),
            (string) ($repository->get('AILOS_CONSUMER_SECRET') ?? ''),
            (string) ($repository->get('AILOS_URL_CALLBACK') ?? ''),
            (string) ($repository->get('AILOS_API_KEY_DEVELOPER') ?? ''),
            (string) ($repository->get('AILOS_CODIGO_COOPERATIVA') ?? ''),
            (string) ($repository->get('AILOS_CODIGO_CONTA') ?? ''),
            (string) ($repository->get('AILOS_SENHA') ?? ''),
        );

        if ($enviroment->ambiente != 'homol') {
            throw new \Exception('Ambiente invalido para testes');
        }

        $this->authManager = new AuthManager($enviroment, new HttpClient());
    }

    public function testAuthManager(): void
    {
        $this->authManager->auth();

        $this->assertNotNull($this->authManager->accessToken);
        $this->assertNotNull($this->authManager->id);
    }
}
