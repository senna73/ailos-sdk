<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class InstrucoesDto implements DtoInterface{
    public function __construct(
        public string $valorAbatimento,
        public string $tipoDesconto,
        public array $descontos,
        public int $tipoMulta,
        public string $valorMulta,
        public int $tipoJurosMora,
        public string $valorJurosMora,
        public int $diasNegativacao,
        public int $diasProtesto,
    ) {}

    public static function fromRequest(object $request): self
    {
        return new self(
            $request->valorAbatimento,
            $request->tipoDesconto,
            $request->desconto,
            $request->tipoMulta,
            $request->valorMulta,
            $request->tipoJurosMora,
            $request->valorJurosMora,
            $request->diasNegativacao,
            $request->diasProtesto,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['valorAbatimento'],
            $data['tipoDesconto'],
            $data['desconto'],
            $data['tipoMulta'],
            $data['valorMulta'],
            $data['tipoJurosMora'],
            $data['valorJurosMora'],
            $data['diasNegativacao'],
            $data['diasProtesto'],
        );
    }
}

?>