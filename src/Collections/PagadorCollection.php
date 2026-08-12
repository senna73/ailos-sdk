<?php

declare(strict_types=1);

namespace Ailos\Sdk\Collections;

use Ailos\Sdk\Entities\PagadorEntity;

readonly class PagadorCollection extends Collection
{
    public function cadastrarPagador(string $accessToken, string $jwt, PagadorEntity $pagador): void
    {
        $this->httpClient->post(
            $this->getBaseUrl() . '/ailos/cobranca/api/v1/pagadores/cadastrar',
            [
                'x-ailos-authentication' => $jwt,
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            $pagador::toArray()
        );
    }
}
