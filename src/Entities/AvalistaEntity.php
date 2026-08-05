<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class AvalistaEntity extends Entity
{
    public function __construct(
        public readonly LegalEntity $entidadeLegal
    ) {
    }
}
