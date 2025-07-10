<?php
namespace Senna\AilosSdkPhp\API\Cob\Models;

class Instrucoes {
    public float $valorAbatimento;
    public int $tipoDesconto;
    public array $descontos; // array de ['valor' => float, 'diasAteVencimento' => int]
    public int $tipoMulta;
    public float $valorMulta;
    public int $tipoJurosMora;
    public float $valorJurosMora;
    public int $diasNegativacao;
    public int $diasProtesto;

    public function __construct(
        float $valorAbatimento,
        int $tipoDesconto,
        array $descontos,
        int $tipoMulta,
        float $valorMulta,
        int $tipoJurosMora,
        float $valorJurosMora,
        int $diasNegativacao,
        int $diasProtesto
    ) {
        $this->valorAbatimento = $valorAbatimento;
        $this->tipoDesconto = $tipoDesconto;
        $this->descontos = $descontos;
        $this->tipoMulta = $tipoMulta;
        $this->valorMulta = $valorMulta;
        $this->tipoJurosMora = $tipoJurosMora;
        $this->valorJurosMora = $valorJurosMora;
        $this->diasNegativacao = $diasNegativacao;
        $this->diasProtesto = $diasProtesto;
    }

    public function toArray(): array {
        return [
            'valorAbatimento' => $this->valorAbatimento,
            'tipoDesconto' => $this->tipoDesconto,
            'descontos' => $this->descontos,
            'tipoMulta' => $this->tipoMulta,
            'valorMulta' => $this->valorMulta,
            'tipoJurosMora' => $this->tipoJurosMora,
            'valorJurosMora' => $this->valorJurosMora,
            'diasNegativacao' => $this->diasNegativacao,
            'diasProtesto' => $this->diasProtesto
        ];
    }
}
