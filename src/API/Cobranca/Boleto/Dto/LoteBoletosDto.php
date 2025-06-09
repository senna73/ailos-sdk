<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class LoteBoletosDto implements DtoInterface{

    /**
     * Gera um lote de boletos para o convênio especificado.
     *
     * @param BoletoDto[] $boletos
     */
    public function __construct(
        public array $boletos,
    ) {}

    public static function fromRequest(object $request): self
    {
        return new self($request->boletos);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['boletos']);
    }

    public function toArray(): array
    {
        return array_map(fn(BoletoDto $boleto) => $boleto->toArray(), $this->boletos);
    }
}

?>