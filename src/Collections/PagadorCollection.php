<?php

declare(strict_types=1);

namespace Ailos\Sdk\Collections;

use Ailos\Sdk\Entities\PagadorEntity;
use Ailos\Sdk\Framework\Collection;

readonly class PagadorCollection extends Collection
{
    public function cadastrarPagador(PagadorEntity $pagador): void
    {
        $this->post(
            '/ailos/cobranca/api/v1/pagadores/cadastrar',
            $pagador
        );
    }
}
