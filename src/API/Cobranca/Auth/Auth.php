<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Auth;

use Psr\Http\Message\ResponseInterface;
use Senna\AilosSdkPhp\API\AilosAPI;

class Auth {
    public function __construct(private AilosAPI $api) {}

    /**
     * Gera o 1º token.
     * 
     * @param string $consumerKey Chave fornecida pela Ailos.
     * @param string $consumerSecret Segredo fornecido pela Ailos.
     * @param bool $setHeader Informa se a função deve inserir o Access Token no Header.
     */
    public function accessToken(string $consumerKey, string $consumerSecret, bool $setHeader = false): ResponseInterface 
    {
        $keySecret = base64_encode(string: "$consumerKey:$consumerSecret");

        $response = $this->api->getHttpClient()->post(
            uri: 'token',
            body: ['grant_type' => 'client_credentials'],
            headers: [
                'Authorization' => "Basic $keySecret",
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            bodyType: 'form_params'
        );

        $bodyStream = $response->getBody();
        $body = $bodyStream->getContents();
        $data = json_decode(json: $body, associative: true);

        if ($setHeader) {
            $this->api->getHttpClient()->setDefaultHeader(name: 'Authorization', value: "{$data['token_type']} {$data['access_token']}");
        }

        $bodyStream->rewind();

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
        return $this->api->getHttpClient()->post(
            uri: 'ailos/identity/api/v1/autenticacao/login/obter/id',
            body: [
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
    public function auth(string $id, string $loginCoopCode, string $loginAccountCode, string $loginPassword): ResponseInterface
    {
        return $this->api->getHttpClient()->post(
            uri: 'ailos/identity/api/v1/login/index?id=' . urlencode($id),
            body: [
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
            ],
            headers: [],
            bodyType: 'multipart'
        );
    }
}
?>