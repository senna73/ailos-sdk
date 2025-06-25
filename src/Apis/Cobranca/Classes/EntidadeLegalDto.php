<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Pagador\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class EntidadeLegalDto implements DtoInterface {
    public function __construct(
        private string $identificadorReceitaFederal,
        private string $tipoPessoa,
        private string $nome,
    ) {}

    public static function fromRequest(object $request):self
    {
        return new self(
            $request->identificadorReceitaFederal,
            $request->tipoPessoa,
            $request->nome,
        );
    }

    public static function fromArray(array $data):self
    {
        return new self(
            $data["identificadorReceitaFederal"],
            $data["tipoPessoa"],
            $data["nome"],
        );
    }
}

?>