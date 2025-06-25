<?php

namespace Senna\AilosSdkPhp\API\Cobranca;

use Senna\AilosSdkPhp\Core\AilosHttpClient;
use Senna\AilosSdkPhp\API\Cobranca\Auth\Auth;
use Senna\AilosSdkPhp\API\Cobranca\Boleto\Boleto;

class AilosApiCobranca {
    private AilosHttpClient $httpClient;

    public function __construct(string $baseUri, array $defaultHeaders = [], float $timeout = 10.0) {
        $this->httpClient = new AilosHttpClient($baseUri, $defaultHeaders, $timeout);
    }

    public function __get(string $name)
    {
        $map = [
            'auth' => Auth::class,
            'boleto' => Boleto::class,
        ];

        if (isset($map[$name])) {
            return new $map[$name]($this->api);
        }

        throw new \Exception("Serviço [$name] não encontrado.");
    }
}

?>