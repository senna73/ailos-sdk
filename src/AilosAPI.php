<?php

    namespace Senna\AilosSdkPhp;

    use Senna\AilosSdkPhp\Core\HttpClient; 
    use Senna\AilosSdkPhp\Auth\Services\AuthService;
    use Senna\AilosSdkPhp\Core\HttpClientInterface;

    class AilosAPI implements HttpClientInterface
    {
        public HttpClient $httpClient;

        public function __construct(string $baseUri, array $defaultHeaders = [], float $timeout = 10.0)
        {
            $this->httpClient = new HttpClient($baseUri, $defaultHeaders, $timeout);
        }

        public function setDefaultHeader(string $name, string $value): void
        {
            $this->httpClient->setDefaultHeader($name, $value);
        }

        public function __get(string $name)
        {
            $map = [
                'auth' => AuthService::class
            ];

            if (isset($map[$name])) {
                return new $map[$name]($this->httpClient);
            }

            throw new \Exception("Serviço [$name] não encontrado.");
        }
    }

?>