<?php

namespace AilosSDK\API\Cob\Services;

use AilosSDK\API\Cob\Config\Config;
use AilosSDK\API\Cob\Models\Boleto;
use AilosSDK\API\Cob\Models\ConvenioCobranca;
use AilosSDK\Common\Models\ApiResponse;
use AilosSDK\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface;

/**
 * Classe de serviço para integração com a API de cobrança Ailos.
 * Realiza operações de pagadores, boletos, carnês e arquivos de retorno.
 */
class BoletoService {
    private Config $api;

    public function __construct(Config $api) {
        $this->api = $api;
    }

    // =========================
    // BOLETOS
    // =========================

    /**
     * Gera um boleto único para o convênio.
     *
     * @param string $convenio
     * @param Boleto $boleto
     * @return ResponseInterface
     * @throws ApiException
     */
    public function gerarBoleto(string $convenio, Boleto $boleto): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->post(
                'ailos/cobranca/api/v2/boletos/gerar/boleto/convenios/' . $convenio,
                $boleto->toArray()
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao gerar boleto: {$e->getMessage()}", $e->getCode(), $e);
        } 
    }

    /**
     * Gera boleto com tratamento de resposta.
     *
     * @param string $convenio
     * @param Boleto $boleto
     * @return ApiResponse
     */
    public function getGerarBoleto(string $convenio, Boleto $boleto): ApiResponse
    {
        $response = $this->gerarBoleto($convenio, $boleto);
        return ResponseHandler::handle($response);
    }

    /**
     * Consulta um boleto pelo número e convênio.
     *
     * @param string $convenio
     * @param string $numeroBoleto
     * @return ResponseInterface
     * @throws ApiException
     */
    public function consultarBoleto(string $convenio, string $numeroBoleto): ResponseInterface 
    {
        try {
           return $this->api->getHttpClient()->get(
                "ailos/cobranca/api/v2/boletos/consultar/boleto/convenios/" . $convenio . "/" . $numeroBoleto,
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao consultar boleto: {$e->getMessage()}", $e->getCode(), $e);
        }  
    }

    /**
     * Consulta boleto com tratamento da resposta.
     *
     * @param string $convenio
     * @param string $numeroBoleto
     * @return ApiResponse
     */
    public function getConsultarBoleto(string $convenio, string $numeroBoleto): ApiResponse
    {
        $response = $this->consultarBoleto($convenio, $numeroBoleto);
        return ResponseHandler::handle($response);
    }

    /**
     * Gera um lote de boletos.
     *
     * @param string $convenio
     * @param ConvenioCobranca $convenioCobranca
     * @param Boleto[] $boletos
     * @return ResponseInterface
     * @throws ApiException
     */
    public function gerarBoletos(string $convenio, ConvenioCobranca $convenioCobranca, array $boletos): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->post(
                'ailos/cobranca/api/v2/boletos/gerar/boleto/lote/convenios/' . $convenio,
                [
                    "convenioCobranca" => $convenioCobranca->toArray(),
                    "boletos" => $boletos
                ]
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao gerar lote de boletos: {$e->getMessage()}", $e->getCode(), $e);
        }  
    }

    /**
     * Gera lote de boletos com tratamento da resposta.
     *
     * @param string $convenio
     * @param ConvenioCobranca $convenioCobranca
     * @param Boleto[] $boletos
     * @return ApiResponse
     */
    public function getGerarBoletos(string $convenio, ConvenioCobranca $convenioCobranca, array $boletos): ApiResponse
    {
        $response = $this->gerarBoletos($convenio, $convenioCobranca, $boletos);
        return ResponseHandler::handle($response);
    }

    /**
     * Consulta o retorno de boletos em lote.
     *
     * @param string $convenio
     * @param string $ticket
     * @return ResponseInterface
     * @throws ApiException
     */
    public function consultarBoletos(string $convenio, string $ticket): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->get(
                'ailos/cobranca/api/v1/boletos/consultar/boleto/lote/convenios/' . $convenio . '/' . $ticket,
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao consultar retorno do lote de boletos: {$e->getMessage()}", $e->getCode(), $e);
        }  
    }

    /**
     * Consulta lote de boletos com tratamento da resposta.
     *
     * @param string $convenio
     * @param string $ticket
     * @return ApiResponse
     */
    public function getConsultarBoletos(string $convenio, string $ticket): ApiResponse
    {
        $response = $this->consultarBoletos($convenio, $ticket);
        return ResponseHandler::handle($response);
    }
}