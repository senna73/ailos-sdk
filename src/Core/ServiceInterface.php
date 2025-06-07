<?php
    namespace Senna\AilosSdkPhp\Core;
    
    use Senna\AilosSdkPhp\Core\HttpClient;

    interface ServiceInterface
    {
        public function __construct(HttpClient $httpClient);
    }
?>