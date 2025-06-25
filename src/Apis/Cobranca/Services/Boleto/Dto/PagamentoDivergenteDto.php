<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class PagamentoDivergenteDto implements DtoInterface{
    public function __construct(
        public string $tipoPagamentoDivergente,
        public bool $valorMinimoPagamentoDivergente,
    ) {}

    public static function fromRequest(object $request): self
    {
        return new self(
            $request->tipoPagamentoDivergente,
            $request->valorMinimoPagamentoDivergente,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['tipoPagamentoDivergente'],
            $data['valorMinimoPagamentoDivergente'],
        );
    }
}

?>