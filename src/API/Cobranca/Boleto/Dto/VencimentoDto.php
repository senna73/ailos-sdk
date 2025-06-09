<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class VencimentoDto implements DtoInterface{
    public function __construct(
        public string $dataVencimento,
    ) {}

    public static function fromRequest(object $request): self
    {
        return new self($request->dataVencimento);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['dataVencimento']);
    }
}

?>