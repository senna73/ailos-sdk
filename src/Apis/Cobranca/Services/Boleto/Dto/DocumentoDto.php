<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class DocumentoDto implements DtoInterface {

    public function __construct(
        private string $numeroDocumento,
        private string $descricaoDocumento,
        private string $especieDocumento,
    ) {}

    public static function fromRequest(object $request): DtoInterface
    {
        return new static(
            $request->numeroDocumento, 
            $request->descricaoDocumento, 
            $request->especieDocumento
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["numeroDocumento"],
            $data["descricaoDocumento"],
            $data["especieDocumento"],
        );
    }
}
?>