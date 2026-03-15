<?php

declare(strict_types=1);

namespace Ailos\Sdk\Auth;

use Ailos\Sdk\Auth\Credentials\ClientCredentials;
use Ailos\Sdk\Auth\Credentials\CooperadoCredentials;
use Ailos\Sdk\Auth\Steps\AuthenticateCooperadoStep;
use Ailos\Sdk\Auth\Steps\FetchAccessTokenStep;
use Ailos\Sdk\Auth\Steps\FetchAuthIdStep;
use Ailos\Sdk\Auth\Tokens\AccessToken;
use Ailos\Sdk\Auth\Tokens\AuthId;
use Ailos\Sdk\Exceptions\AuthenticationException;
use Ailos\Sdk\Storage\Contracts\TokenStoreInterface;
use Ailos\Sdk\Storage\TokenKeys;

class AuthOrchestrator
{
    public function __construct(
        private readonly FetchAccessTokenStep     $fetchAccessTokenStep,
        private readonly FetchAuthIdStep          $fetchAuthIdStep,
        private readonly AuthenticateCooperadoStep $authenticateCooperadoStep,
        private readonly TokenStoreInterface      $tokenStore,
    ) {
    }

    public function run(
        ClientCredentials    $clientCredentials,
        CooperadoCredentials $cooperadoCredentials,
    ): void {
        $this->resolveAccessToken($clientCredentials);
        $this->resolveAuthId($cooperadoCredentials);
        $this->resolveJwt($cooperadoCredentials);
    }

    public function resolveAccessToken(ClientCredentials $credentials): void
    {
        $cached = $this->tokenStore->get(TokenKeys::ACCESS_TOKEN);

        if ($cached instanceof AccessToken && !$cached->isExpired()) {
            return;
        }

        $accessToken = $this->fetchAccessTokenStep->execute($credentials);

        $this->tokenStore->set(
            key:   TokenKeys::ACCESS_TOKEN,
            value: $accessToken
        );
    }

    public function resolveAuthId(
        CooperadoCredentials $credentials,
    ): void {

        $accessToken = $this->tokenStore->get(TokenKeys::ACCESS_TOKEN);

        $cached = $this->tokenStore->get(TokenKeys::AUTH_ID);

        if ($cached instanceof AuthId) {
            return;
        }

        $authId = $this->fetchAuthIdStep->execute($accessToken, $credentials);

        $this->tokenStore->set(
            key:   TokenKeys::AUTH_ID,
            value: $authId,
        );
    }

    public function resolveJwt(CooperadoCredentials $cooperadoCredentials): void
    {
        $accessToken = $this->tokenStore->get(TokenKeys::ACCESS_TOKEN);
        $authId = $this->tokenStore->get(TokenKeys::AUTH_ID);

        $this->authenticateCooperadoStep->execute(
            accessToken:  $accessToken,
            authId:       $authId,
            credentials:  $cooperadoCredentials,
        );

        $this->waitForJwt();
    }

    private function waitForJwt(int $timeoutMs = 10000): void
    {
        $deadline = microtime(true) + ($timeoutMs / 1000);

        while (microtime(true) < $deadline) {
            $jwt = $this->tokenStore->get(TokenKeys::JWT);

            if ($jwt && !$jwt->isExpired()) {
                return;
            }
            usleep(200_000);
        }

        throw new AuthenticationException("JWT não foi recebido no callback dentro de {$timeoutMs}ms.");
    }
}
