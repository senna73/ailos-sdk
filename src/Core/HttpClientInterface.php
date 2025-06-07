<?php
    namespace Senna\AilosSdkPhp\Core;
    
    use Senna\AilosSdkPhp\Core\HttpClient;

    interface HttpClientInterface
    {
        public function __construct(string $baseUri, array $defaultHeaders = [], float $timeout = 10.0);
        
        public function setDefaultHeader(string $name, string $value): void;
    }
?>