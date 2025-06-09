<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Pagador\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class PagadorDto implements DtoInterface {
    public function __construct(
        private EntidadeLegalDto $entidadeLegal,
        private TelefoneDto $telefone,
        private array $emails,
        private EnderecoDto $endereco,
        private array $mensagemPagador,
    ) {}

    public static function fromRequest(object $request):self
    {
        return new self(
            $request->entidadeLegal,
            $request->telefone,
            $request->emails,
            $request->endereco,
            $request->mensagemPagador,
        );
    }

    public static function fromArray(array $data):self
    {
        return new self(
            $data["entidadeLegal"],
            $data["telefone"],
            $data["emails"],
            $data["endereco"],
            $data["mensagemPagador"]
        );
    }
}

?>