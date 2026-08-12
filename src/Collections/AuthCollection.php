<?php

declare(strict_types=1);

namespace Ailos\Sdk\Collections;

use Ailos\Sdk\Entities\AccessTokenEntity;
use Ailos\Sdk\Framework\Collection;
use DomainException;

readonly class AuthCollection extends Collection
{
    public function authAccessTokenEndpoint(string $consumerKey, string $consumerSecret): AccessTokenEntity
    {
        $response = $this->httpClient->post(
            $this->getBaseUrl() . '/token',
            [
                'Authorization' => 'Basic ' . base64_encode("{$consumerKey}:{$consumerSecret}"),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            [
                'grant_type' => 'client_credentials',
            ]
        );

        if (!($response instanceof \stdClass)) {
            throw new DomainException('Tipo de retorno incorreto');
        }

        return AccessTokenEntity::fromObject($response);
    }

    public function authIdEndpoint(string $accessToken, string $urlCallback, string $ailosApiKeyDeveloper, string $state): string
    {
        $response = $this->httpClient->post(
            $this->getBaseUrl() . '/ailos/identity/api/v1/autenticacao/login/obter/id',
            [
                'Content-Type' => 'application/json',
                'Accept' => 'text/plain',
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            [
                'urlCallback' => $urlCallback,
                'ailosApiKeyDeveloper' => $ailosApiKeyDeveloper,
                'state' => $state,
            ]
        );

        if (!is_string($response)) {
            throw new DomainException('Tipo de retorno incorreto');
        }

        return $response;
    }

    public function authJwtEndpoint(string $accessToken, string $id, string $loginCodigoCooperativa, string $loginCodigoConta, string $loginSenha): void
    {
        $response = $this->httpClient->post(
            $this->getBaseUrl() . "/ailos/identity/api/v1/login/index?id={$id}",
            [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            [
                'Login.CodigoCooperativa' => $loginCodigoCooperativa,
                'Login.CodigoConta' => $loginCodigoConta,
                'Login.Senha' => $loginSenha,
            ]
        );

        if (!is_string($response)) {
            throw new DomainException('Tipo de retorno incorreto');
        }

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML($response);

        $xpath = new \DOMXPath($dom);

        $nodes = $xpath->query("//div[contains(@class,'validation-summary-errors')]//li");

        // Fix: Valida se $nodes é uma instância válida de DOMNodeList e possui itens
        if ($nodes instanceof \DOMNodeList && $nodes->length > 0) {
            $firstNode = $nodes->item(0);

            // Fix: Garante para o PHPStan que o nó retornado é do tipo DOMNode
            if ($firstNode instanceof \DOMNode) {
                $message = trim($firstNode->textContent);

                if ($message !== '') {
                    throw new \RuntimeException('Erro ao tentar gerar o JWT. ' . $message);
                }
            }
        }
    }
}
