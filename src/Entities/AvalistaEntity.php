<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

use Ailos\Sdk\Framework\Entity;

class AvalistaEntity extends Entity
{
    public function __construct(
        public readonly LegalEntity $entidadeLegal
    ) {
    }
}
