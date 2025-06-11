<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class LoteCarnesDto implements DtoInterface{

    /**
     * Gera um lote de carnes para o convênio especificado.
     *
     * @param CarneDto[] $carnes
     */
    public function __construct(
        public array $carnes,
    ) {}

    public static function fromRequest(object $request): self
    {
        return new self($request->carnes);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['carnes']);
    }

    public function toArray(): array
    {
        return array_map(fn(CarneDto $carnes) => $carnes->toArray(), $this->carnes);
    }
}

?>