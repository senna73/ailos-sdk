<?php
namespace Senna\AilosSdkPhp\API\Cob\Models;

class Endereco {
    public string $cep;
    public string $logradouro;
    public string $numero;
    public string $complemento;
    public string $bairro;
    public string $cidade;
    public string $uf;

    public function __construct(string $cep, string $logradouro, string $numero, string $complemento, string $bairro, string $cidade, string $uf) {
        $this->cep = $cep;
        $this->logradouro = $logradouro;
        $this->numero = $numero;
        $this->complemento = $complemento;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->uf = $uf;
    }

    public function toArray(): array {
        return [
            'cep' => $this->cep,
            'logradouro' => $this->logradouro,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'uf' => $this->uf
        ];
    }
}
