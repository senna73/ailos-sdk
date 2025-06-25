<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class EmissaoDto implements DtoInterface {
    public function __construct(
        private string $formaEmissao,
        private string $dataEmissaoDocumento,
    ) {}

    public static function fromRequest(object $request):self
    {
        return new self(
            $request->formaEmissao, 
            $request->dataEmissaoDocumento,
        );
    }
    public static function fromArray(array $data):self
    {
        return new self(
            $data["formaEmissao"],
            $data["dataEmissaoDocumento"],
        );
    }
}

?>