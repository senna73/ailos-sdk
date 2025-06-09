<?php

namespace Senna\AilosSdkPhp\API\Cobranca;

use Senna\AilosSdkPhp\API\AilosAPI;
use Senna\AilosSdkPhp\API\Cobranca\Auth\Auth;
use Senna\AilosSdkPhp\API\Cobranca\Boleto\Boleto;

class Cobranca {
    public function __construct(private AilosAPI $api){}

    public function __get(string $name)
    {
        $map = [
            'auth' => Auth::class,
            'boleto' => Boleto::class,
        ];

        if (isset($map[$name])) {
            return new $map[$name]($this->api);
        }

        throw new \Exception("Serviço [$name] não encontrado.");
    }
}

?>