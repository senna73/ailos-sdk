<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class VencimentoEntity extends Entity
{
    public function __construct(
        public readonly string $dataVencimento
    ) {
    }
}
