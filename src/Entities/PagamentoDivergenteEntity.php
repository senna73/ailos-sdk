<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class PagamentoDivergenteEntity extends Entity
{
    public function __construct(
        public readonly int $tipoPagamentoDivergente,
        public readonly int $valorMinimoPagamentoDivergente
    ) {
    }
}
