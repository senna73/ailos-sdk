<?php
namespace Senna\AilosSdkPhp\API\Cob\Models;

class ValorBoleto {
    public float $valorNominal;

    public function __construct(float $valorNominal) {
        $this->valorNominal = $valorNominal;
    }

    public function toArray(): array {
        return ['valorNominal' => $this->valorNominal];
    }
}
