<?php

namespace Senna\AilosSdkPhp\API\Cob;

use Psr\Http\Message\ResponseInterface;
use Senna\AilosSdkPhp\Exceptions\ApiException;
use Senna\AilosSdkPhp\Common\ResponseHandler;
use Senna\AilosSdkPhp\Common\Models\ApiResponse;

class Auth {

    public function __construct(private Config $api){}


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
        $credentials = base64_encode("$consumerKey:$consumerSecret");

        $headers = [
            'Authorization' => "Basic {$credentials}",
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ];

        $body = ['grant_type' => 'client_credentials'];

        try {
            return $this->api->getHttpClient()->post(
                'token',
                $body,
                $headers
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
    public function getAccessToken(string $clientId, string $clientSecret, bool $setHeader = false): ApiResponse
    {
        $response = $this->accessToken($clientId, $clientSecret);
        $responseHandled = ResponseHandler::handle($response);
        $responseHandledData = $responseHandled->getData();

        if (!isset($responseHandledData['access_token'], $responseHandledData['token_type'])) {
            throw new ApiException("Resposta de token inválida ou malformada.");
        }

        if ($setHeader) {
            $this->api->setDefaultHeaders([
                'Authorization' => "{$responseHandledData['token_type']} {$responseHandledData['access_token']}"
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
        return ResponseHandler::handle($response, [200]);
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

}