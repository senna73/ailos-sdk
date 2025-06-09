<?php

namespace Senna\AilosSdkPhp\Core;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

interface HttpClientInterface {

    public function __construct(string $baseUri, array $defaultHeaders = [], float $timeout = 10.0);

    /**
     * Realiza uma requisição GET.
     *
     * @param string $uri O endpoint para a requisição.
     * @param array $query Parâmetros de query string.
     * @param array $headers Headers específicos para esta requisição.
     * @return ResponseInterface A resposta da requisição.
     * @throws RequestException Em caso de erro na requisição (se http_errors for true).
     */
    public function get(string $uri, array $query = [], array $headers = []): ResponseInterface;

    /**
     * Realiza uma requisição POST.
     *
     * @param string $uri O endpoint para a requisição.
     * @param array|string $body O corpo da requisição (pode ser um array para form_params ou json, ou string).
     * @param array $headers Headers específicos para esta requisição.
     * @param string $bodyType Tipo do corpo da requisição ('json', 'form_params', 'multipart', 'body').
     * @return ResponseInterface A resposta da requisição.
     * @throws RequestException Em caso de erro na requisição.
     */
    public function post(string $uri, $body = [], array $headers = [], string $bodyType = 'json'): ResponseInterface;

    /**
     * Realiza uma requisição PUT.
     *
     * @param string $uri O endpoint para a requisição.
     * @param array|string $body O corpo da requisição.
     * @param array $headers Headers específicos para esta requisição.
     * @param string $bodyType Tipo do corpo da requisição.
     * @return ResponseInterface A resposta da requisição.
     * @throws RequestException Em caso de erro na requisição.
     */
    public function put(string $uri, $body = [], array $headers = [], string $bodyType = 'json'): ResponseInterface;

    /**
     * Realiza uma requisição DELETE.
     *
     * @param string $uri O endpoint para a requisição.
     * @param array $query Parâmetros de query string.
     * @param array $headers Headers específicos para esta requisição.
     * @return ResponseInterface A resposta da requisição.
     * @throws RequestException Em caso de erro na requisição.
     */
    public function delete(string $uri, array $query = [], array $headers = []): ResponseInterface;

    /**
     * Adiciona ou sobrescreve um header padrão.
     *
     * @param string $name Nome do header.
     * @param string $value Valor do header.
     */
    public function setDefaultHeader(string $name, string $value): void;
}
?>