<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class AvisoSmsEntity extends Entity
{
    public function __construct(
        public readonly int $enviarAvisoVencimentoSms,
        public readonly bool $enviarAvisoVencimentoSmsAntesVencimento,
        public readonly bool $enviarAvisoVencimentoSmsDiaVencimento,
        public readonly bool $enviarAvisoVencimentoSmsAposVencimento
    ) {
    }
}
