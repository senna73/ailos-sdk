<?php

namespace Senna\AilosSdkPhp\API\Cob;

use Senna\AilosSdkPhp\Common\HttpClient;

class Config {
    private HttpClient $httpClient;
    
    public function __construct(
        private string $baseUri = 'https://apiendpointhml.ailos.coop.br/',
        private array $defaultHeaders = [], 
        private float $timeout = 10.0
    ) 
    {
        $this->httpClient = new HttpClient(
            $this->baseUri, 
            $this->defaultHeaders, 
            $this->timeout
        );
    }

    public function setHttpClient(HttpClient $httpClient): Config 
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    public function getHttpClient(): HttpClient 
    {
        return $this->httpClient;
    }

    public function setDefaultHeaders(array $options): Config 
    {
        $this->defaultHeaders = array_merge($this->defaultHeaders, $options);
        foreach ($this->defaultHeaders as $key => $value) {
            $this->httpClient->setDefaultHeader($key, $value);
        }
        return $this;
    }
}