<?php

declare(strict_types=1);

namespace Ailos\Sdk\Collections;

use Ailos\Sdk\Entities\BoletoEntity;
use Ailos\Sdk\Entities\BoletoLoteEntity;
use DomainException;

readonly class BoletoCollection extends Collection
{
    public function consultarUnicoBoleto(string $convenio, string $numero): BoletoEntity
    {
        $response = $this->httpClient->get(
            $this->getBaseUrl() . "/ailos/cobranca/api/v2/boletos/consultar/boleto/convenios/{$convenio}/{$numero}",
            $this->getAuthHeader()
        );

        if (!($response instanceof \stdClass)) {
            throw new DomainException('Tipo de retorno incorreto');
        }

        return BoletoEntity::fromObject($response);
    }

    public function gerarUnicoBoleto(string $convenio, BoletoEntity $boleto): void
    {
        $this->httpClient->post(
            $this->getBaseUrl() . "/ailos/cobranca/api/v2/boletos/gerar/boleto/convenios/{$convenio}",
            $this->getAuthHeader(),
            $boleto::toArray()
        );
    }

    public function gerarLoteBoletos(string $convenio, BoletoLoteEntity $lote): void
    {
        $this->httpClient->post(
            $this->getBaseUrl() . "/ailos/cobranca/api/v2/boletos/gerar/boleto/convenios/{$convenio}",
            $this->getAuthHeader(),
            $lote::toArray()
        );
    }


}
