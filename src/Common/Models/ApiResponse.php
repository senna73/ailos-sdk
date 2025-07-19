<?php

namespace AilosSDK\Common\Models;

use Psr\Http\Message\ResponseInterface;

class ApiResponse
{
    private int $statusCode;
    private array $data;
    private array $headers;

    public function __construct(ResponseInterface $response, array $data)
    {
        $this->statusCode = $response->getStatusCode();
        $this->headers = $response->getHeaders();
        $this->data = $data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function isSuccess(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
}
