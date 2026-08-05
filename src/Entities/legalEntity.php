<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class LegalEntity extends Entity
{
    public function __construct(
        public readonly string $identificadorReceitaFederal,
        public readonly int $tipoPessoa,
        public readonly string $nome
    ) {
    }
}
