<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class AvisoSMSDto implements DtoInterface{
    public function __construct(
        public string $enviarAvisoVencimentoSms,
        public bool $enviarAvisoVencimentoSmsAntesVencimento,
        public bool $enviarAvisoVencimentoSmsDiaVencimento,
        public bool $enviarAvisoVencimentoSmsAposVencimento,
    ) {}

    public static function fromRequest(object $request): self
    {
        return new self(
            $request->enviarAvisoVencimentoSms,
            $request->enviarAvisoVencimentoSmsAntesVencimento,
            $request->enviarAvisoVencimentoSmsDiaVencimento,
            $request->enviarAvisoVencimentoSmsAposVencimento,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['enviarAvisoVencimentoSms'],
            $data['enviarAvisoVencimentoSmsAntesVencimento'],
            $data['enviarAvisoVencimentoSmsDiaVencimento'],
            $data['enviarAvisoVencimentoSmsAposVencimento'],
        );
    }
}

?>