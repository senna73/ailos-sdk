<?php

namespace AilosSDK\API\Cob\Auth;

use AilosSDK\API\Cob\Config\Config;
use Psr\Http\Message\ResponseInterface;
use AilosSDK\Exceptions\ApiException;
use AilosSDK\Common\Utils\ResponseHandler;
use AilosSDK\Common\Models\ApiResponse;

class Auth {

    public Config $api;

    public function __construct(Config $api)
    {
        $this->api = $api;
    }


    /**
     * Faz a requisição de access token (1º token) utilizando o fluxo client_credentials.
     *
     * @param string $consumerKey  Chave fornecida pela Ailos.
     * @param string $consumerSecret  Segredo fornecido pela Ailos.
     *
     * @throws ApiException Em caso de erro na requisição.
     *
     * @return ResponseInterface Resposta bruta da API (PSR-7).
     */
    public function accessToken(string $consumerKey, string $consumerSecret): ResponseInterface
    {
        $credentials = base64_encode(sprintf('%s:%s', $consumerKey, $consumerSecret));
    
        $headers = [
            'Authorization' => "Basic {$credentials}",
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ];
    
        $body = ['grant_type' => 'client_credentials'];
    
        try {
            return $this->api->getHttpClient()->post(
                'token',
                $body,
                $headers,
                'form_params'
            );
    
        } catch (\Throwable $e) {
            throw new ApiException("Erro ao solicitar access token: {$e->getMessage()}", $e->getCode(), $e);
        }        
    }

    /**
     * Obtém e trata o access token da API (1º token).
     * 
     * Caso $setHeader seja true, injeta o token como Authorization nos headers da instância Config.
     *
     * @param string $consumerKey  Chave fornecida pela Ailos.
     * @param string $consumerSecret  Segredo fornecido pela Ailos.
     * @param bool $setHeader  Define se o token será adicionado ao header da configuração.
     *
     * @throws ApiException Se a resposta estiver malformada ou sem token.
     *
     * @return ApiResponse Objeto tratado contendo os dados da resposta.
     */
    public function getAccessToken(string $consumerKey, string $consumerSecret, bool $setHeader = false): ApiResponse
    {

        if (trim($consumerKey) === '' || trim($consumerSecret) === '') {
            throw new ApiException("consumerKey e consumerSecret não podem ser strings vazias.");
        }
        
        $response = $this->accessToken($consumerKey, $consumerSecret);
        $responseHandled = ResponseHandler::handle($response);
        $responseHandledData = $responseHandled->getData();

        if (!isset($responseHandledData['access_token'], $responseHandledData['token_type'])) {
            throw new ApiException("Resposta de token inválida ou malformada.");
        }

        if ($setHeader) {
            $this->api->setDefaultHeaders([
                'Authorization' => $responseHandledData['token_type'] . ' ' . $responseHandledData['access_token']
            ]);
        }

        return $responseHandled;
    }


    /**
     * Gera o ID.
     * 
     * @param string $urlCallback Url que retornará o último token após a 3º etapa (Tela de Autenticação);
     * @param string $ailosApiKeyDeveloper UUID fornecido pela Ailos.
     * @param string $state Identificação da chamada.
     * 
     * @throws ApiException Em caso de erro na requisição.
     *
     * @return ResponseInterface Resposta bruta da API (PSR-7).
     */
    public function id(string $urlCallback, string $ailosApiKeyDeveloper, string $state): ResponseInterface
    {
        $uri = 'ailos/identity/api/v1/autenticacao/login/obter/id';
        
        $body = [
            'urlCallback' => $urlCallback,
            'ailosApiKeyDeveloper' => $ailosApiKeyDeveloper,
            'state' => $state
        ];

        try {
            return $this->api->getHttpClient()->post(
                $uri,
                $body 
            );
            
        } catch (\Throwable $e) {
            throw new ApiException("Erro ao gerar o id: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Gera o ID.
     * 
     * @param string $urlCallback Url que retornará o último token após a 3º etapa (Tela de Autenticação);
     * @param string $ailosApiKeyDeveloper UUID fornecido pela Ailos.
     * @param string $state Identificação da chamada.
     * 
     * @throws ApiException Em caso de erro na requisição.
     *
     * @return ApiResponse Objeto tratado contendo os dados da resposta.
     */
    public function getId(string $urlCallback, string $ailosApiKeyDeveloper, string $state): ApiResponse
    {
        $response = $this->id($urlCallback, $ailosApiKeyDeveloper, $state);
        $responseHandled = ResponseHandler::handle($response, [200]);
        
        $data = $responseHandled->getData();
        $data['data'] = urlencode($data['data'] ?? '');
        $responseHandled->setData($data);

        return $responseHandled;
    }

    /**
     * Envia para a Url de Callback passada no ID o token final para realizar as proximas requisições.
     * 
     * @param string $id ID gerado com a URL de Callback;
     * @param string $coopCode Código da cooperativa fornecido pela Ailos.
     * @param string $accountCode Código da conta fornecido pela Ailos.
     * @param string $password Senha da conta fornecida pela Ailos.
     *  
     * @throws ApiException Em caso de erro na requisição.
     *
     * @return ResponseInterface Resposta bruta da API (PSR-7).
     */
    public function auth(string $id, string $loginCoopCode, string $loginAccountCode, string $loginPassword): ResponseInterface
    {
        $uri = 'ailos/identity/api/v1/login/index?id=' . urlencode($id);

        $body = [
            [
                'name'     => 'Login.CodigoCooperativa',
                'contents' => $loginCoopCode
            ],
            [
                'name'     => 'Login.CodigoConta',
                'contents' => $loginAccountCode
            ],
            [
                'name'     => 'Login.Senha',
                'contents' => $loginPassword
            ]
        ];

        try {
            return $this->api->getHttpClient()->post(
                $uri,
                $body, 
                [],
                'multipart'
            );
            
        } catch (\Throwable $e) {
            throw new ApiException("Erro ao autenticar o token: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Envia para a Url de Callback passada no ID o token final para realizar as proximas requisições.
     * 
     * @param string $id ID gerado com a URL de Callback;
     * @param string $coopCode Código da cooperativa fornecido pela Ailos.
     * @param string $accountCode Código da conta fornecido pela Ailos.
     * @param string $password Senha da conta fornecida pela Ailos.
     * 
     * @throws ApiException Em caso de erro na requisição.
     *
     * @return ApiResponse Objeto tratado contendo os dados da resposta.
     */
    public function getAuth(string $id, string $loginCoopCode, string $loginAccountCode, string $loginPassword): ApiResponse
    {
        $response = $this->auth($id, $loginCoopCode, $loginAccountCode, $loginPassword);
        return ResponseHandler::handle($response, [200]);
    }

    /**
     * Faz o refresh do token setado no header.
     * 
     * @param string $id ID gerado com a URL de Callback;
     *  
     * @throws ApiException Em caso de erro na requisição.
     *
     * @return ResponseInterface Resposta bruta da API (PSR-7).
     */
    public function refresh($id): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->get(
                "ailos/identity/api/v1/autenticacao/token/refresh",
                [
                    "code" => $id
                ]
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao fazer o refresh: {$e->getMessage()}", $e->getCode(), $e);
        } 
    }

    /**
     * Faz o refresh do token setado no header.
     * 
     * @param string $id ID gerado com a URL de Callback;
     * 
     * @throws ApiException Em caso de erro na requisição.
     *
     * @return ApiResponse Objeto tratado contendo os dados da resposta.
     */
    public function getRefresh($id): ApiResponse
    {
        $return = $this->refresh($id);
        return ResponseHandler::handle($return);
    }

    /**
     * Realiza todo o processo de autenticação, obtendo um token de acesso,
     * recuperando um ID e autenticando o usuário com as credenciais fornecidas.
     *
     * @param string $consumerKey Chave do consumidor para autenticação na API.
     * @param string $consumerSecret Segredo do consumidor.
     * @param string $urlCallback URL de callback usada no processo de autenticação.
     * @param string $ailosApiKeyDeveloper Chave do desenvolvedor fornecida pela Ailos.
     * @param string $state Identificador único da chamada.
     * @param string $loginCoopCode Código da cooperativa do usuário.
     * @param string $loginAccountCode Código da conta do usuário.
     * @param string $loginPassword Senha do usuário.
     * 
     * @throws ApiException Se qualquer etapa do processo de autenticação falhar.
     *
     * @return ApiResponse Resultado final do processo de autenticação.
     */
    public function getFullAuth(string $consumerKey, string $consumerSecret, string $urlCallback, string $ailosApiKeyDeveloper, string $state, string $loginCoopCode, string $loginAccountCode, string $loginPassword): ApiResponse
    {
        try {
            $this->getAccessToken($consumerKey, $consumerSecret, true);

            $idResponse = $this->getId($urlCallback, $ailosApiKeyDeveloper, $state);
            $idData = $idResponse->getData();

            if (empty($idData['id'])) {
                throw new ApiException("ID de autenticação ausente ou malformado na resposta da API.");
            }

            return $this->getAuth($idData['id'], $loginCoopCode, $loginAccountCode, $loginPassword);

        } catch (ApiException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ApiException("Erro inesperado no processo de autenticação completa: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

}