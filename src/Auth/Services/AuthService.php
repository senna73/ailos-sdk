<?php
    namespace Senna\AilosSdkPhp\Auth\Services;

    use Psr\Http\Message\ResponseInterface;
    use Senna\AilosSdkPhp\Core\ServiceInterface;
    use Senna\AilosSdkPhp\Core\HttpClient;

    /**
     * Classe responsavel pelas rotas de autenticação.
     */
    class AuthService implements ServiceInterface
    {

        public function __construct(private HttpClient $httpClient){}

        /**
         * Gera o 1º token. Este, gerado através das duas chaves repassadas ao cooperado (key e secret).
         * 
         * @param string $consumerKey Chave fornecida pela Ailos.
         * @param string $consumerSecret Segredo fornecido pela Ailos.
         */
        public function accessToken(string $consumerKey, string $consumerSecret): ResponseInterface
        {
            $keySecret = base64_encode("$consumerKey:$consumerSecret");

            $response = $this->httpClient->post(
                'token',
                ['grant_type' => 'client_credentials'],
                [
                    'Authorization' => "Basic $keySecret",
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params'
            );

            return $response;
        }


        /**
         * Gera o ID.
         * 
         * @param string $urlCallback Url que retornará o último token após a 3º etapa (Tela de Autenticação);
         * @param string $ailosApiKeyDeveloper UUID fornecido pela Ailos.
         * @param string $state Identificação da chamada.
         */
        public function id(string $urlCallback, string $ailosApiKeyDeveloper, string $state): ResponseInterface
        {
            return $this->httpClient->post(
                'ailos/identity/api/v1/autenticacao/login/obter/id',
                [
                    'urlCallback' => $urlCallback,
                    'ailosApiKeyDeveloper' => $ailosApiKeyDeveloper,
                    'state' => $state
                ]
            );
        }

        /**
         * Envia para a Url de Callback passada no ID o token final para realizar as proximas requisições.
         * 
         * @param string $id ID gerado com a URL de Callback;
         * @param string $coopCode Código da cooperativa fornecido pela Ailos.
         * @param string $accountCode Código da conta fornecido pela Ailos.
         * @param string $password Senha da conta fornecida pela Ailos.
         */
        public function auth(string $id, string $coopCode, string $accountCode, string $password): ResponseInterface
        {
            return $this->httpClient->post(
                'ailos/identity/api/v1/login/index?id=' . urlencode($id),
                [
                    [
                        'name'     => 'Login.CodigoCooperativa',
                        'contents' => $coopCode
                    ],
                    [
                        'name'     => 'Login.CodigoConta',
                        'contents' => $accountCode
                    ],
                    [
                        'name'     => 'Login.Senha',
                        'contents' => $password
                    ]
                ],
                [],
                'multipart'
            );
        }
    }
?>