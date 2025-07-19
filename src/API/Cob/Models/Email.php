<?php

namespace AilosSDK\API\Cob\Models;

class Email
{
    public string $endereco;

    public function __construct(string $endereco)
    {
        $this->endereco = $endereco;
    }

    public function toArray(): array
    {
        return [
            'endereco' => $this->endereco
        ];
    }
}

?>