<?php

declare(strict_types=1);

namespace Ailos\Sdk\Collections;

use Ailos\Sdk\Entities\AccessTokenEntity;
use Ailos\Sdk\Entities\EnviromentEntity;
use Ailos\Sdk\Entities\JwtEntity;
use Ailos\Sdk\Support\HttpClient;
use Ailos\Sdk\Support\Storage;
use InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

abstract readonly class Collection
{
    protected FilesystemAdapter $storage;
    
    protected HttpClient $httpClient;

    protected AuthCollection $authCollection;

    public function __construct(
        protected EnviromentEntity $enviroment
    ) {
        $this->storage = Storage::storage();
        $this->httpClient = new HttpClient();
        $this->authCollection = new AuthCollection($enviroment);
    }

    public function getBaseUrl(): string
    {
        return $this->enviroment->getBaseUrl();
    }

    private function getAccessToken(): AccessTokenEntity
    {
        return $this->storage->get(
            'access_token',
            function (ItemInterface $item) {
                $accessToken = $this->authCollection->authAccessTokenEndpoint(
                    $_ENV['AILOS_CONSUMER_KEY'],
                    $_ENV['AILOS_CONSUMER_SECRET']
                );
                $item->expiresAfter($accessToken->expiresIn);
                return $accessToken;
            }
        );
    }

    private function getId(): string
    {
        return $this->storage->get(
            'id',
            function (ItemInterface $item) {
                $id = $this->authCollection->authIdEndpoint(
                    $this->getAccessToken()->accessToken, 
                    $_ENV['AILOS_URL_CALLBACK'], 
                    $_ENV['AILOS_API_KEY_DEVELOPER'],
                    ''
                );
                $item->expiresAfter(3600);
                return $id;
            }
        );
    }

    private function getJwt(int $timeoutSeconds = 30, int $intervalMicroseconds = 200000): JwtEntity
    {
        $this->authCollection->authJwtEndpoint(
            $this->getAccessToken()->accessToken,
            $this->getId(),
            $_ENV['AILOS_CODIGO_COOPERATIVA'],
            $_ENV['AILOS_CODIGO_CONTA'],
            $_ENV['AILOS_SENHA']
        );

        $startTime = microtime(true);

        while (true) {
            $item = $this->storage->getItem('jwt');

            if ($item->isHit()) {
                return $item->get();
            }

            if ((microtime(true) - $startTime) >= $timeoutSeconds) {
                throw new \RuntimeException('Timeout JWT search.');
            }

            usleep($intervalMicroseconds);
        }
    }

    public function getAuthHeader(): array
    {
        return [
            'x-ailos-authentication' => $this->getJwt()->code,
            'Authorization' => 'Bearer ' . $this->getAccessToken()->accessToken,
        ];
    }
}