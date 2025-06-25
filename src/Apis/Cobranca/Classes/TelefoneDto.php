<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Pagador\Dto;

use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class TelefoneDto implements DtoInterface {
    public function __construct(
        private string $ddi,
        private string $ddd,
        private string $numero,
    ) {}

    public static function fromRequest(object $request):self
    {
        return new self(
            $request->ddi,
            $request->ddd,
            $request->numero,
        );
    }

    public static function fromArray(array $data):self
    {
        return new self(
            $data["ddi"],
            $data["ddd"],
            $data["numero"],
        );
    }
}

?>