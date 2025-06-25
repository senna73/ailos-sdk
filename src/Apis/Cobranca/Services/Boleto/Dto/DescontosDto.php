<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class DescontosDto implements DtoInterface {

    public function __construct(
        private string $valor,
        private string $diasAteVencimento,
    ) {}

    public static function fromRequest(object $request): DtoInterface
    {
        return new static(
            $request->valor, 
            $request->diasAteVencimento, 
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["valor"],
            $data["diasAteVencimento"],
        );
    }
}
?>