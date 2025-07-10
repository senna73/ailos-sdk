<?php
namespace Senna\AilosSdkPhp\API\Cob\Models;

class Avalista {
    public EntidadeLegal $entidadeLegal;

    public function __construct(EntidadeLegal $entidadeLegal) {
        $this->entidadeLegal = $entidadeLegal;
    }

    public function toArray(): array {
        return [
            'entidadeLegal' => $this->entidadeLegal->toArray()
        ];
    }
}
