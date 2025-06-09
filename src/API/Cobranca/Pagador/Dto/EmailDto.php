<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class EmailDto implements DtoInterface{
    public function __construct(
        public string $endereco,
    ) {}

    public static function fromRequest(object $request): self
    {
        return new self($request->endereco);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['endereco']);
    }
}

?>