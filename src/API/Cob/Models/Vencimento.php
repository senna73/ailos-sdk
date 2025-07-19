<?php
namespace AilosSDK\API\Cob\Models;

class Vencimento {
    public string $dataVencimento;

    public function __construct(string $dataVencimento) {
        $this->dataVencimento = $dataVencimento;
    }

    public function toArray(): array {
        return ['dataVencimento' => $this->dataVencimento];
    }
}
