<?php

declare(strict_types=1);

namespace Ailos\Sdk\Support;

use Curl\Curl;

readonly class HttpClient
{
    private Curl $curl;

    public function __construct(
    ) {
        $this->curl = new Curl();
    }

    public function post(string $url, array $headers = [], array $data = []): mixed
    {
        foreach ($headers as $name => $value) {
            $this->curl->setHeader($name, $value);
        }

        $this->curl->post($url, $data);

        if ($this->curl->error) {
            throw new \RuntimeException($this->curl->errorMessage);
        }

        return $this->curl->response;
    }

    public function get(string $url, array $headers = [], array $query = []): mixed
    {
        foreach ($headers as $name => $value) {
            $this->curl->setHeader($name, $value);
        }

        $this->curl->get($url, $query);

        if ($this->curl->error) {
            throw new \RuntimeException($this->curl->errorMessage);
        }

        return $this->curl->response;
    }
}
