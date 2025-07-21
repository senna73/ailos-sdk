<?php

namespace AilosSDK\API\Cob\Services;

use AilosSDK\API\Cob\Config\Config;
use AilosSDK\API\Cob\Models\Carne;
use AilosSDK\API\Cob\Models\ConvenioCobranca;
use AilosSDK\Common\Models\ApiResponse;
use AilosSDK\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface;

/**
 * Classe de serviço para integração com a API de cobrança Ailos.
 * Realiza operações de pagadores, boletos, carnês e arquivos de retorno.
 */
class CarneService {
    private Config $api;

    public function __construct(Config $api) {
        $this->api = $api;
    }

    // =========================
    // CARNÊS
    // =========================

    /**
     * Gera um carnê com múltiplas parcelas.
     *
     * @param string $convenio
     * @param Carne $carne
     * @return ResponseInterface
     * @throws ApiException
     */
    public function gerarCarne(string $convenio, Carne $carne): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->post(
                'ailos/cobranca/api/v1/boletos/gerar/carne/convenios/' . $convenio,
                $carne->toArray()
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao gerar carne: {$e->getMessage()}", $e->getCode(), $e);
        } 
    }

    /**
     * Gera carnê com tratamento de resposta.
     *
     * @param string $convenio
     * @param Carne $carne
     * @return ApiResponse
     */
    public function getGerarCarne(string $convenio, Carne $carne): ApiResponse
    {
        $response = $this->gerarCarne($convenio, $carne);
        return ResponseHandler::handle($response);
    }

    /**
     * Gera lote de carnês.
     *
     * @param string $convenio
     * @param ConvenioCobranca $convenioCobranca
     * @param Carne[] $carnes
     * @return ResponseInterface
     * @throws ApiException
     */
    public function gerarCarnes(string $convenio, ConvenioCobranca $convenioCobranca, array $carnes): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->post(
                'ailos/cobranca/api/v2/boletos/gerar/carne/lote/convenios/' . $convenio,
                [
                    "convenioCobranca" => $convenioCobranca->toArray(),
                    "carnes" => $carnes
                ]
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao gerar lote de carnes: {$e->getMessage()}", $e->getCode(), $e);
        }   
    }

    /**
     * Gera lote de carnês com tratamento da resposta.
     *
     * @param string $convenio
     * @param ConvenioCobranca $convenioCobranca
     * @param Carne[] $carnes
     * @return ApiResponse
     */
    public function getGerarCarnes(string $convenio, ConvenioCobranca $convenioCobranca, array $carnes): ApiResponse
    {
        $response = $this->gerarCarnes($convenio, $convenioCobranca, $carnes);
        return ResponseHandler::handle($response);
    }

    /**
     * Consulta o retorno do lote de carnês.
     *
     * @param string $convenio
     * @param string $ticket
     * @return ResponseInterface
     * @throws ApiException
     */
    public function consultarCarnes(string $convenio, $ticket): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->get(
                'ailos/cobranca/api/v1/boletos/consultar/carne/lote/convenios/' . $convenio . '/' . $ticket,
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao consultar retorno do lote de carnes: {$e->getMessage()}", $e->getCode(), $e);
        } 
    }

    /**
     * Consulta carnês em lote com tratamento da resposta.
     *
     * @param string $convenio
     * @param string $ticket
     * @return ApiResponse
     */
    public function getConsultarCarnes(string $convenio, $ticket): ApiResponse
    {
        $response = $this->consultarCarnes($convenio, $ticket);
        return ResponseHandler::handle($response);
    }
}