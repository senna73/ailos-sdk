<?php

namespace Senna\AilosSdkPhp\API;

use Senna\AilosSdkPhp\Core\HttpClient;
use Senna\AilosSdkPhp\API\Cobranca\Cobranca;
use Senna\AilosSdkPhp\API\Pix\Pix;
use Senna\AilosSdkPhp\Core\HttpClientInterface;

class AilosAPI
{
    private HttpClientInterface $httpClient;

    public function __construct(string $baseUri, array $defaultHeaders = [], float $timeout = 10.0) {
        $this->httpClient = new HttpClient($baseUri, $defaultHeaders, $timeout);
    }

    public function __get(string $name)
    {
        $map = [
            'cobranca' => Cobranca::class,
            'pix' => Pix::class,
        ];

        if (isset($map[$name])) {
            return new $map[$name]($this);
        }

        throw new \Exception("Serviço [$name] não encontrado.");
    }

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }
}

?>