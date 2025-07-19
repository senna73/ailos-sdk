<?php
namespace AilosSDK\API\Cob\Models;

class Telefone {
    public string $ddi;
    public string $ddd;
    public string $numero;

    public function __construct(string $ddi, string $ddd, string $numero) {
        $this->ddi = $ddi;
        $this->ddd = $ddd;
        $this->numero = $numero;
    }

    public function toArray(): array {
        return [
            'ddi' => $this->ddi,
            'ddd' => $this->ddd,
            'numero' => $this->numero
        ];
    }
}
