<?php

declare(strict_types=1);

namespace Ailos\Sdk\Collections;

use Ailos\Sdk\Entities\BoletoEntity;
use Ailos\Sdk\Entities\BoletoLoteEntity;

readonly class BoletoCollection extends Collection
{
    public function consultarUnicoBoleto(string $convenio, string $numero): BoletoEntity
    {
        /** @var \stdClass $response */
        $response = $this->httpClient->get(
            $this->getBaseUrl() . "/ailos/cobranca/api/v2/boletos/consultar/boleto/convenios/{$convenio}/{$numero}",
            $this->getAuthHeader()
        );

        return BoletoEntity::fromObject($response);
    }

    public function gerarUnicoBoleto(string $convenio, BoletoEntity $boleto): void
    {
        $this->httpClient->post(
            $this->getBaseUrl() . "/ailos/cobranca/api/v2/boletos/gerar/boleto/convenios/{$convenio}",
            $this->getAuthHeader(),
            json_decode(json_encode($boleto), true)
        );
    }

    public function gerarLoteBoletos(string $convenio, BoletoLoteEntity $lote): void
    {
        $this->httpClient->post(
            $this->getBaseUrl() . "/ailos/cobranca/api/v2/boletos/gerar/boleto/convenios/{$convenio}",
            $this->getAuthHeader(),
            json_decode(json_encode($lote), true)
        );
    }


}
