<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class BoletoLoteEntity extends Entity
{
    /**
     * @param BoletoEntity[] $boletos
     */
    public function __construct(
        public readonly ConvenioCobrancaEntity $convenioCobranca,
        public readonly array $boletos,
    ) {
    }
}
