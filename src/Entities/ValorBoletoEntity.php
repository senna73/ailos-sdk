<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class ValorBoletoEntity extends Entity
{
    public function __construct(
        public readonly int $valorNominal
    ) {
    }
}
