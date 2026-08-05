<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

use InvalidArgumentException;

class EnviromentEntity extends Entity
{
    private const array URLS = [
        'homol' => 'https://apiendpointhml.ailos.coop.br',
        'prod'  => 'https://apiendpoint.ailos.coop.br',
    ];

    public function __construct(
        private readonly string $consumerKey,
        private readonly string $consumerSecret,
        private readonly string $urlCallback,
        private readonly string $developerKey,
        private readonly string $codigoCooperativa,
        private readonly string $codigoConta,
        private readonly string $senha,
        private readonly string $ambiente = 'homol'
    ) {
        if (!array_key_exists($this->ambiente, self::URLS)) {
            throw new InvalidArgumentException(
                "Ambiente inválido '{$this->ambiente}'. Permitido: " . implode(', ', array_keys(self::URLS))
            );
        }
    }

    public function getBaseUrl(): string
    {
        return self::URLS[$this->ambiente];
    }
}
