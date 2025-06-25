<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class ConvenioCobrancaDto implements DtoInterface{
    public function __construct(
        public string $codigoCarteiraCobranca,
    ) {}

    public static function fromRequest(object $request): self
    {
        return new self($request->codigoCarteiraCobranca);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['codigoCarteiraCobranca']);
    }
}

?>