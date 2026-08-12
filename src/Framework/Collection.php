<?php

declare(strict_types=1);

namespace Ailos\Sdk\Collections;

use Ailos\Sdk\Entities\AccessTokenEntity;
use Ailos\Sdk\Entities\EnviromentEntity;
use Ailos\Sdk\Entities\JwtEntity;
use Ailos\Sdk\Support\HttpClient;
use Ailos\Sdk\Support\Storage;
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
                    $this->enviroment->consumerKey,
                    $this->enviroment->consumerSecret
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
                    $this->enviroment->urlCallback,
                    $this->enviroment->developerKey,
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
            $this->enviroment->codigoCooperativa,
            $this->enviroment->codigoConta,
            $this->enviroment->senha
        );

        $startTime = microtime(true);

        while (true) {
            $item = $this->storage->getItem('jwt');

            if ($item->isHit()) {
                $item = $item->get();

                if (!($item instanceof JwtEntity)) {
                    throw new \RuntimeException('Item obtido com tipagem errada');
                }
                return $item;
            }

            if ((microtime(true) - $startTime) >= $timeoutSeconds) {
                throw new \RuntimeException('Timeout JWT search.');
            }

            usleep($intervalMicroseconds);
        }
    }

    /**
     * @return array{
     *  x-ailos-authentication: string,
     *  Authorization: string
     * }
     */
    public function getAuthHeader(): array
    {
        return [
            'x-ailos-authentication' => $this->getJwt()->code,
            'Authorization' => 'Bearer ' . $this->getAccessToken()->accessToken,
        ];
    }
}
