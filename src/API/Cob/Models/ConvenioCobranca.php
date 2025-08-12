<?php
namespace AilosSDK\API\Cob\Models;

class ConvenioCobranca {
    public int $numeroConvenioCobranca;
    public int $codigoCarteiraCobranca;

    public function __construct(int $numeroConvenioCobranca, int $codigoCarteiraCobranca) {
        $this->numeroConvenioCobranca = $numeroConvenioCobranca;
        $this->codigoCarteiraCobranca = $codigoCarteiraCobranca;
    }

    public function toArray(): array {
        return [
            'numeroConvenioCobranca' => $this->numeroConvenioCobranca,
            'codigoCarteiraCobranca' => $this->codigoCarteiraCobranca
        ];
    }
}
