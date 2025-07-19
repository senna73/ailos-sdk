<?php
namespace AilosSDK\API\Cob\Models;

class PagamentoDivergente {
    public int $tipoPagamentoDivergente;
    public float $valorMinimoPagamentoDivergente;

    public function __construct(int $tipo, float $valorMinimo) {
        $this->tipoPagamentoDivergente = $tipo;
        $this->valorMinimoPagamentoDivergente = $valorMinimo;
    }

    public function toArray(): array {
        return [
            'tipoPagamentoDivergente' => $this->tipoPagamentoDivergente,
            'valorMinimoPagamentoDivergente' => $this->valorMinimoPagamentoDivergente
        ];
    }
}
