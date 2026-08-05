<?php

declare(strict_types=1);

namespace Ailos\Sdk\Collections;

use Ailos\Sdk\Entities\AccessTokenEntity;

readonly class AuthCollection extends Collection
{
    public function authAccessTokenEndpoint(string $consumerKey, string $consumerSecret): AccessTokenEntity
    {
        /** @var \stdClass $response */
        $response = $this->httpClient->post(
            $this->getBaseUrl() . '/token',
            [
                'Authorization' => 'Basic ' . base64_encode("{$consumerKey}:{$consumerSecret}"),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            [
                'grant_type' => 'client_credentials'
            ]
        );

        return AccessTokenEntity::fromObject($response);
    }
    
    public function authIdEndpoint(string $accessToken, string $urlCallback, string $ailosApiKeyDeveloper, string $state): string
    {
        /** @var string $response */
        $response = $this->httpClient->post(
            $this->getBaseUrl() . '/ailos/identity/api/v1/autenticacao/login/obter/id',
            [
                'Content-Type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $accessToken
            ],
            [
                'urlCallback' => $urlCallback,
                'ailosApiKeyDeveloper' => $ailosApiKeyDeveloper,
                'state' => $state
            ]
        );

        return $response;
    }

    public function authJwtEndpoint(string $accessToken, string $id, int $loginCodigoCooperativa, string $loginCodigoConta, string $loginSenha): void
    {
        /** @var string $response */
        $response = $this->httpClient->post(
            $this->getBaseUrl() . "/ailos/identity/api/v1/login/index?id={$id}",
            [
                'Authorization' => 'Bearer ' . $accessToken
            ],
            [
                'Login.CodigoCooperativa' => $loginCodigoCooperativa,
                'Login.CodigoConta' => $loginCodigoConta,
                'Login.Senha' => $loginSenha
            ]
        );

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML($response);

        $xpath = new \DOMXPath($dom);

        $nodes = $xpath->query("//div[contains(@class,'validation-summary-errors')]//li");

        if ($nodes !== null && $nodes->length > 0 && $nodes->item(0) !== null) {
            $message = trim($nodes->item(0)->textContent);

            if ($message !== '') {
                throw new \RuntimeException('Erro ao tentar gerar o JWT. ' . $message);
            }
        }
    }
}

