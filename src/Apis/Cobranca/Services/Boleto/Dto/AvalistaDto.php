<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\API\Cobranca\Pagador\Dto\EntidadeLegalDto;
use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class AvalistaDto implements DtoInterface{
    public function __construct(
        public EntidadeLegalDto $entidadeLegal,
    ) {}

    public static function fromRequest(object $request): self
    {
        return new self($request->entidadeLegal);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['entidadeLegal']);
    }
}

?>