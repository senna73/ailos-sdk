<?php
namespace AilosSDK\API\Cob\Models;

class ConvenioCobranca {
    public int $codigoCarteiraCobranca;

    public function __construct(int $codigoCarteiraCobranca) {
        $this->codigoCarteiraCobranca = $codigoCarteiraCobranca;
    }

    public function toArray(): array {
        return ['codigoCarteiraCobranca' => $this->codigoCarteiraCobranca];
    }
}
