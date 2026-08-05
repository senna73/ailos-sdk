<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class ConvenioCobrancaEntity extends Entity
{
    public function __construct(
        public readonly int $codigoCarteiraCobranca
    ) {
    }
}
