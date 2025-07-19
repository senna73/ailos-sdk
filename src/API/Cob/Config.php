<?php

namespace AilosSDK\API\Cob;

use AilosSDK\Common\HttpClient;

class Config {
    private HttpClient $httpClient;
    private string $baseUri;
    private array $defaultHeaders;
    private float $timeout;
    
    public function __construct(
        string $baseUri = 'https://apiendpointhml.ailos.coop.br/',
        array $defaultHeaders = [], 
        float $timeout = 10.0
    ) 
    {
        $this->baseUri = $baseUri;
        $this->defaultHeaders = $defaultHeaders;
        $this->timeout = $timeout;
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