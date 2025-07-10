<?php

    namespace Senna\AilosSdkPhp\Common;

    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\RequestException;
    use Psr\Http\Message\ResponseInterface;


    /**
     * Classe AilosHttpClient
     *
     * Esta classe oferece métodos para fazer requisições HTTP de forma simplificada, usando as operações básicas
     * como GET, POST, PUT e DELETE. O cliente HTTP pode ser configurado com uma URI base, cabeçalhos padrão e 
     * tempo limite para requisições. Além disso, é possível alterar os cabeçalhos padrão e passar parâmetros 
     * adicionais durante as requisições.
     */
    class HttpClient
    {
        /**
         * @var Client Instância do cliente HTTP (por exemplo, Guzzle).
         */
        private Client $client;

        /**
         * @var array Armazena as opções padrão de configuração para as requisições.
         */
        private array $defaultOptions;

        /**
         * Construtor da classe AilosHttpClient.
         *
         * @param string $baseUri URI base para as requisições HTTP.
         * @param array $defaultHeaders Cabeçalhos padrão a serem enviados nas requisições.
         * @param float $timeout Tempo limite para a requisição (em segundos). O valor padrão é 10.0 segundos.
         */
        public function __construct(string $baseUri, array $defaultHeaders = [], float $timeout = 10.0)
        {
            // Configura as opções padrão com base na URI, tempo limite e cabeçalhos
            $this->defaultOptions = [
                'base_uri' => $baseUri,
                'timeout'  => $timeout,
                'headers'  => $defaultHeaders,
            ];

            // Cria a instância do cliente HTTP usando as opções padrão
            $this->client = new Client($this->defaultOptions);
        }

        /**
         * Altera o cabeçalho padrão do cliente.
         *
         * @param string $name Nome do cabeçalho a ser alterado.
         * @param string $value Valor do cabeçalho.
         */
        public function setDefaultHeader(string $name, string $value): void
        {
            // Adiciona ou modifica o cabeçalho específico
            $this->defaultOptions['headers'][$name] = $value;

            // Atualiza a instância do cliente com as novas opções
            $this->client = new Client($this->defaultOptions);
        }

        /**
         * Método genérico para enviar requisições HTTP com os métodos especificados (GET, POST, PUT, DELETE).
         *
         * @param string $method Método HTTP (GET, POST, PUT, DELETE, etc).
         * @param string $uri URI para a requisição.
         * @param array $options Opções adicionais para a requisição (incluindo cabeçalhos, corpo, etc).
         * @return ResponseInterface Retorna a resposta da requisição HTTP.
         * @throws RequestException Lança exceção em caso de erro na requisição.
         */
        public function request(string $method, string $uri, array $options = []): ResponseInterface
        {
            try {
                // Mescla os cabeçalhos específicos da requisição com os cabeçalhos padrão
                if (isset($options['headers'])) {
                    $options['headers'] = array_merge($this->defaultOptions['headers'] ?? [], $options['headers']);
                }

                // Realiza a requisição usando o cliente HTTP
                return $this->client->request($method, $uri, $options);
            } catch (RequestException $e) {
                throw $e;
            }
        }

        /**
         * Realiza uma requisição HTTP GET.
         *
         * @param string $uri URI adicional para a requisição.
         * @param array $query Parâmetros de consulta (query parameters) para a URL.
         * @param array $headers Cabeçalhos específicos para esta requisição.
         * @return ResponseInterface Retorna a resposta da requisição HTTP.
         */
        public function get(string $uri, array $query = [], array $headers = []): ResponseInterface
        {
            return $this->request('GET', $uri, ['query' => $query, 'headers' => $headers]);
        }

        /**
         * Realiza uma requisição HTTP POST.
         *
         * @param string $uri URI adicional para a requisição.
         * @param mixed $body Corpo da requisição (pode ser um array ou outro tipo de dado).
         * @param array $headers Cabeçalhos específicos para esta requisição.
         * @param string $bodyType Tipo de conteúdo do corpo da requisição (por padrão, 'json').
         * @return ResponseInterface Retorna a resposta da requisição HTTP.
         */
        public function post(string $uri, $body = [], array $headers = [], string $bodyType = 'json'): ResponseInterface
        {
            $body = http_build_query($body);
            $options = [$bodyType => $body, 'headers' => $headers];
            return $this->request('POST', $uri, $options);
        }

        /**
         * Realiza uma requisição HTTP PUT.
         *
         * @param string $uri URI adicional para a requisição.
         * @param mixed $body Corpo da requisição (pode ser um array ou outro tipo de dado).
         * @param array $headers Cabeçalhos específicos para esta requisição.
         * @param string $bodyType Tipo de conteúdo do corpo da requisição (por padrão, 'json').
         * @return ResponseInterface Retorna a resposta da requisição HTTP.
         */
        public function put(string $uri, $body = [], array $headers = [], string $bodyType = 'json'): ResponseInterface
        {
            $body = http_build_query($body);
            $options = [$bodyType => $body, 'headers' => $headers];
            return $this->request('PUT', $uri, $options);
        }

        /**
         * Realiza uma requisição HTTP DELETE.
         *
         * @param string $uri URI adicional para a requisição.
         * @param array $query Parâmetros de consulta (query parameters) para a URL.
         * @param array $headers Cabeçalhos específicos para esta requisição.
         * @return ResponseInterface Retorna a resposta da requisição HTTP.
         */
        public function delete(string $uri, array $query = [], array $headers = []): ResponseInterface
        {
            return $this->request('DELETE', $uri, ['query' => $query, 'headers' => $headers]);
        }
    }

?>