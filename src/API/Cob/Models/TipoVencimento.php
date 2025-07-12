<?php
namespace Senna\AilosSdkPhp\API\Cob\Models;

class TipoVencimento {
    public string $tipoVencimento;
    public string $quantidadeXDias;
    public string $diaXDeCadaMes;

    public function __construct(
        int $tipoVencimento, 
        int $quantidadeXDias, 
        int $diaXDeCadaMes
    ) {
        $this->tipoVencimento = $tipoVencimento;
        $this->quantidadeXDias = (int) $quantidadeXDias;
        $this->diaXDeCadaMes = (int) $diaXDeCadaMes;
    }

    public function toArray(): array {
        return [
            'tipoVencimento' => $this->tipoVencimento,
            'quantidadeXDias'=> $this->quantidadeXDias,
            'diaXDeCadaMes'=> $this->diaXDeCadaMes
        ];
    }
}
