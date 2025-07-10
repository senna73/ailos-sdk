<?php
namespace Senna\AilosSdkPhp\API\Cob\Models;

class Pagador {
    public EntidadeLegal $entidadeLegal;
    public Telefone $telefone;
    public array $emails; // array de ['endereco' => string]
    public Endereco $endereco;
    public array $mensagemPagador; // array de strings

    public function __construct(EntidadeLegal $entidadeLegal, Telefone $telefone, array $emails, Endereco $endereco, array $mensagemPagador) {
        $this->entidadeLegal = $entidadeLegal;
        $this->telefone = $telefone;
        $this->emails = $emails;
        $this->endereco = $endereco;
        $this->mensagemPagador = $mensagemPagador;
    }

    public function toArray(): array {
        return [
            'entidadeLegal' => $this->entidadeLegal->toArray(),
            'telefone' => $this->telefone->toArray(),
            'emails' => $this->emails,
            'endereco' => $this->endereco->toArray(),
            'mensagemPagador' => $this->mensagemPagador
        ];
    }
}
