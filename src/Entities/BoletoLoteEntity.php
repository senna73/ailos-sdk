<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

use Ailos\Sdk\Framework\Entity;

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
