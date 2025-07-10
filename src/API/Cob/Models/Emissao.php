<?php
namespace Senna\AilosSdkPhp\API\Cob\Models;

class Emissao {
    public int $formaEmissao;
    public string $dataEmissaoDocumento;

    public function __construct(int $formaEmissao, string $dataEmissaoDocumento) {
        $this->formaEmissao = $formaEmissao;
        $this->dataEmissaoDocumento = $dataEmissaoDocumento;
    }

    public function toArray(): array {
        return [
            'formaEmissao' => $this->formaEmissao,
            'dataEmissaoDocumento' => $this->dataEmissaoDocumento
        ];
    }
}
