<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class DocumentoEntity extends Entity
{
    public function __construct(
        public readonly int $numeroDocumento,
        public readonly string $descricaoDocumento,
        public readonly int $especieDocumento
    ) {
    }
}
