<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class JwtEntity extends Entity
{
    public function __construct(
        public readonly string $state,
        public readonly string $code,
    ) { }
}
