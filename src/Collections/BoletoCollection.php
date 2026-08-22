<?php

declare(strict_types=1);

namespace Ailos\Sdk\Collections;

use Ailos\Sdk\Entities\BoletoEntity;
use Ailos\Sdk\Entities\BoletoLoteEntity;
use Ailos\Sdk\Framework\Collection;
use DomainException;

readonly class BoletoCollection extends Collection
{
    public function consultarUnicoBoleto(string $convenio, string $numero): BoletoEntity
    {
        $response = $this->get(
            "/ailos/cobranca/api/v2/boletos/consultar/boleto/convenios/{$convenio}/{$numero}"
        );

        if (!($response instanceof \stdClass)) {
            throw new DomainException('Tipo de retorno incorreto');
        }

        return BoletoEntity::fromObject($response);
    }

    public function gerarUnicoBoleto(string $convenio, BoletoEntity $boleto): void
    {
        $this->post(
            "/ailos/cobranca/api/v2/boletos/gerar/boleto/convenios/{$convenio}",
            $boleto
        );
    }

    public function gerarLoteBoletos(string $convenio, BoletoLoteEntity $lote): void
    {
        $this->post(
            "/ailos/cobranca/api/v2/boletos/gerar/lote/convenios/{$convenio}",
            $lote
        );
    }


}
