<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class EmissaoEntity extends Entity
{
    public function __construct(
        public readonly int $formaEmissao,
        public readonly string $dataEmissaoDocumento
    ) {
    }
}
