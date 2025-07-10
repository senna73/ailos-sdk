<?php
namespace Senna\AilosSdkPhp\API\Cob\Models;

class AvisoSms {
    public int $enviarAvisoVencimentoSms;
    public bool $enviarAvisoVencimentoSmsAntesVencimento;
    public bool $enviarAvisoVencimentoSmsDiaVencimento;
    public bool $enviarAvisoVencimentoSmsAposVencimento;

    public function __construct(int $enviar, bool $antes, bool $noDia, bool $apos) {
        $this->enviarAvisoVencimentoSms = $enviar;
        $this->enviarAvisoVencimentoSmsAntesVencimento = $antes;
        $this->enviarAvisoVencimentoSmsDiaVencimento = $noDia;
        $this->enviarAvisoVencimentoSmsAposVencimento = $apos;
    }

    public function toArray(): array {
        return [
            'enviarAvisoVencimentoSms' => $this->enviarAvisoVencimentoSms,
            'enviarAvisoVencimentoSmsAntesVencimento' => $this->enviarAvisoVencimentoSmsAntesVencimento,
            'enviarAvisoVencimentoSmsDiaVencimento' => $this->enviarAvisoVencimentoSmsDiaVencimento,
            'enviarAvisoVencimentoSmsAposVencimento' => $this->enviarAvisoVencimentoSmsAposVencimento
        ];
    }
}
