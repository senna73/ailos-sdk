<?php

namespace Senna\AilosSdkPhp\Common\Utils;

class DocumentoValidator
{
    protected string $documento;
    protected string $tipo;

    public function __construct(string $documento)
    {
        $this->documento = preg_replace('/\D/', '', $documento);
        $this->tipo = $this->definirTipo();
    }

    protected function definirTipo(): string
    {
        $length = strlen($this->documento);
        switch ($length) {
            case 11:
                return 'CPF';
            case 14:
                return 'CNPJ';
            default:
                return 'INVÁLIDO';
        }
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function validar(): bool
    {
        switch ($this->tipo) {
            case 'CPF':
                return $this->validarCpf();
            case 'CNPJ':
                return $this->validarCnpj();
            default:
                return false;
        }
    }

    protected function validarCpf(): bool
    {
        $cpf = $this->documento;

        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    protected function validarCnpj(): bool
    {
        $cnpj = $this->documento;

        if (strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        $pesos1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $pesos2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 2; $i++) {
            $soma = 0;
            $pesos = $i === 0 ? $pesos1 : $pesos2;
            $len = count($pesos);

            for ($j = 0; $j < $len; $j++) {
                $soma += $cnpj[$j] * $pesos[$j];
            }

            $digito = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);

            if ($cnpj[$len] != $digito) {
                return false;
            }
        }

        return true;
    }
}
