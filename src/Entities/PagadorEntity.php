<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class PagadorEntity extends Entity
{
    /**
     * @param list<array{endereco: string}> $emails
     * @param list<string> $mensagemPagador
     */
    public function __construct(
        public readonly LegalEntity $entidadeLegal,
        public readonly TelefoneEntity $telefone,
        public readonly array $emails,
        public readonly EnderecoEntity $endereco,
        public readonly array $mensagemPagador,
        public readonly bool $dda
    ) {
    }
}
