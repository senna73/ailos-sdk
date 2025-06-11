<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class TipoVencimentoDto implements DtoInterface{
    public function __construct(
        public int $tipoVencimento,
        public int $quantidadeXDias,
        public int $diaXDeCadaMes,
    ) {}

    public static function fromRequest(object $request): self
    {
        return new self(
            $request->tipoVencimento,
            $request->quantidadeXDias,
            $request->diaXDeCadaMes,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['tipoVencimento'],
            $data['quantidadeXDias'],
            $data['diaXDeCadaMes'],
        );
    }
}

?>