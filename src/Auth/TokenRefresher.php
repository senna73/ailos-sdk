<?php

declare(strict_types=1);

namespace Ailos\Sdk\Auth;

use Ailos\Sdk\Auth\Credentials\ClientCredentials;
use Ailos\Sdk\Auth\Credentials\CooperadoCredentials;
use Ailos\Sdk\Auth\Tokens\JwtToken;
use Ailos\Sdk\Exceptions\AuthenticationException;
use Ailos\Sdk\Storage\Contracts\TokenStoreInterface;
use Ailos\Sdk\Storage\TokenKeys;

class TokenRefresher
{
    public function __construct(
        private readonly TokenStoreInterface  $tokenStore,
        private readonly AuthOrchestrator     $orchestrator,
    ) {
    }

    public function getValidJwt(
        ClientCredentials    $clientCredentials,
        CooperadoCredentials $cooperadoCredentials,
    ): JwtToken {
        $jwt = $this->tokenStore->get(TokenKeys::JWT);

        if (!$jwt instanceof JwtToken) {
            throw AuthenticationException::withMessage(
                'No JWT found in store. Call authenticate() first.'
            );
        }

        if (!$jwt->needsRefresh()) {
            return $jwt;
        }

        if ($jwt->canRefresh()) {
            return $this->refresh($cooperadoCredentials);
        }

        return $this->forceReAuthentication($clientCredentials, $cooperadoCredentials);
    }

    public function refresh(
        CooperadoCredentials $cooperadoCredentials,
    ): JwtToken {
        $this->orchestrator->resolveJwt($cooperadoCredentials);
        return $this->tokenStore->get(TokenKeys::JWT);
    }

    private function forceReAuthentication(
        ClientCredentials    $clientCredentials,
        CooperadoCredentials $cooperadoCredentials,
    ): JwtToken {
        $this->orchestrator->run($clientCredentials, $cooperadoCredentials);

        $jwt = $this->tokenStore->get(TokenKeys::JWT);

        if (!$jwt instanceof JwtToken) {
            throw AuthenticationException::withMessage(
                'Re-authentication completed but JWT was not stored. Ensure the callback has been handled.'
            );
        }

        return $jwt;
    }
}
