<?php

declare(strict_types=1);

namespace Ailos\Sdk\Collections;

use Ailos\Sdk\Entities\AccessTokenEntity;
use Ailos\Sdk\Entities\JwtEntity;
use Ailos\Sdk\Support\HttpClient;
use Ailos\Sdk\Support\Storage;
use InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

abstract readonly class Collection
{
    private const array URLS = [
        'homol' => 'https://apiendpointhml.ailos.coop.br',
        'prod'  => 'https://apiendpoint.ailos.coop.br',
    ];

    protected FilesystemAdapter $storage;
    
    protected AuthCollection $authCollection;

    protected HttpClient $httpClient;

    protected string $environment;

    public function __construct()
    {
        $this->storage = Storage::storage();
        $this->authCollection = new AuthCollection();
        $this->httpClient = new HttpClient();

        $this->environment = $_ENV['AILOS_ENVIRONMENT'] ?? 'homol';

        if (!array_key_exists($this->environment, self::URLS)) {
            throw new InvalidArgumentException(
                "Invalid environment '{$this->environment}'. Allowed: " . implode(', ', array_keys(self::URLS))
            );
        }
    }

    public function getBaseUrl(): string
    {
        return self::URLS[$this->environment];
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