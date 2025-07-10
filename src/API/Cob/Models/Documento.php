<?php
namespace Senna\AilosSdkPhp\API\Cob\Models;

class Documento {
    public int $numeroDocumento;
    public string $descricaoDocumento;
    public int $especieDocumento;

    public function __construct(int $numeroDocumento, string $descricaoDocumento, int $especieDocumento) {
        $this->numeroDocumento = $numeroDocumento;
        $this->descricaoDocumento = $descricaoDocumento;
        $this->especieDocumento = $especieDocumento;
    }

    public function toArray(): array {
        return [
            'numeroDocumento' => $this->numeroDocumento,
            'descricaoDocumento' => $this->descricaoDocumento,
            'especieDocumento' => $this->especieDocumento
        ];
    }
}
