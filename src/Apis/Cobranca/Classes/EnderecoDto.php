<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Pagador\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class EnderecoDto implements DtoInterface {
    public function __construct(
        private string $cep,
        private string $logradouro,
        private string $numero,
        private string $complemento,
        private string $bairro,
        private string $cidade,
        private string $uf,
    ) {}

    public static function fromRequest(object $request):self
    {
        return new self(
            $request->cep,
            $request->logradouro,
            $request->numero,
            $request->complemento,
            $request->bairro,
            $request->cidade,
            $request->uf,
        );
    }

    public static function fromArray(array $data):self
    {
        return new self(
            $data["cep"],
            $data["logradouro"],
            $data["numero"],
            $data["complemento"],
            $data["bairro"],
            $data["cidade"],
            $data["uf"]
        );
    }
}

?>