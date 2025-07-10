<?php
namespace Senna\AilosSdkPhp\API\Cob\Models;

class EntidadeLegal {
    public string $identificadorReceitaFederal;
    public int $tipoPessoa;
    public string $nome;

    public function __construct(string $identificadorReceitaFederal, int $tipoPessoa, string $nome) {
        $this->identificadorReceitaFederal = $identificadorReceitaFederal;
        $this->tipoPessoa = $tipoPessoa;
        $this->nome = $nome;
    }

    public function toArray(): array {
        return [
            'identificadorReceitaFederal' => $this->identificadorReceitaFederal,
            'tipoPessoa' => $this->tipoPessoa,
            'nome' => $this->nome
        ];
    }
}
