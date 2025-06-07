<?php

    namespace Senna\AilosSdkPhp\Core;

    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\RequestException;
    use Psr\Http\Message\ResponseInterface;

    /**
     * Classe responsavel por gerenciar as operações HTTP.
     */
    class HttpClient
    {
        private Client $client;
        private array $defaultOptions;

        /**
         * Construtor da classe HttpClient.
         *
         * @param string $baseUri A URI base para as requisições. Ex: https://api.example.com
         * @param array $defaultHeaders Headers padrão para todas as requisições.
         * @param float $timeout Timeout padrão para as requisições (em segundos).
         */
        public function __construct(string $baseUri, array $defaultHeaders = [], float $timeout = 10.0)
        {
            $this->defaultOptions = [
                'base_uri' => $baseUri,
                'timeout'  => $timeout,
                'headers'  => $defaultHeaders,
            ];
            $this->client = new Client($this->defaultOptions);
        }

        /**
         * Adiciona ou sobrescreve um header padrão.
         *
         * @param string $name Nome do header.
         * @param string $value Valor do header.
         */
        public function setDefaultHeader(string $name, string $value): void
        {
            $this->defaultOptions['headers'][$name] = $value;
            $this->client = new Client($this->defaultOptions);
        }

        /**
         * Realiza uma requisição GET.
         *
         * @param string $uri O endpoint para a requisição.
         * @param array $query Parâmetros de query string.
         * @param array $headers Headers específicos para esta requisição.
         * @return ResponseInterface A resposta da requisição.
         * @throws RequestException Em caso de erro na requisição (se http_errors for true).
         */
        public function get(string $uri, array $query = [], array $headers = []): ResponseInterface
        {
            return $this->request('GET', $uri, ['query' => $query, 'headers' => $headers]);
        }

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
        public function post(string $uri, $body = [], array $headers = [], string $bodyType = 'json'): ResponseInterface
        {
            $options = [$bodyType => $body, 'headers' => $headers];
            return $this->request('POST', $uri, $options);
        }

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
        public function put(string $uri, $body = [], array $headers = [], string $bodyType = 'json'): ResponseInterface
        {
            $options = [$bodyType => $body, 'headers' => $headers];
            return $this->request('PUT', $uri, $options);
        }

        /**
         * Realiza uma requisição DELETE.
         *
         * @param string $uri O endpoint para a requisição.
         * @param array $query Parâmetros de query string.
         * @param array $headers Headers específicos para esta requisição.
         * @return ResponseInterface A resposta da requisição.
         * @throws RequestException Em caso de erro na requisição.
         */
        public function delete(string $uri, array $query = [], array $headers = []): ResponseInterface
        {
            return $this->request('DELETE', $uri, ['query' => $query, 'headers' => $headers]);
        }

        /**
         * Método genérico para realizar requisições.
         *
         * @param string $method O método HTTP (GET, POST, PUT, DELETE, etc.).
         * @param string $uri O endpoint para a requisição.
         * @param array $options Opções adicionais para Guzzle (query, body, headers, etc.).
         * @return ResponseInterface A resposta da requisição.
         * @throws RequestException Em caso de erro na requisição.
         */
        public function request(string $method, string $uri, array $options = []): ResponseInterface
        {
            try {
                // Mescla headers específicos da requisição com os headers padrão
                if (isset($options['headers'])) {
                    $options['headers'] = array_merge($this->defaultOptions['headers'] ?? [], $options['headers']);
                }

                return $this->client->request($method, $uri, $options);
            } catch (RequestException $e) {
                throw $e;
            }
        }
    }

?>