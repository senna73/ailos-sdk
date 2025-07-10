<?php

namespace Senna\AilosSdkPhp\API\Cob;

use Psr\Http\Message\ResponseInterface;
use Senna\AilosSdkPhp\Exceptions\ApiException;
use Senna\AilosSdkPhp\Common\ResponseHandler;
use Senna\AilosSdkPhp\Common\Models\ApiResponse;
use Senna\AilosSdkPhp\API\Cob\Models\Boleto;

class CobService {
    public function __construct(private Config $api) {}

    public function consultarBoleto(string $convenio, string $numero): ResponseInterface 
    {
        try {
           return $this->api->getHttpClient()->get(
                "ailos/cobranca/api/v2/boletos/consultar/boleto/convenios/",
                [
                    $convenio,
                    $numero
                ]
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao consultar boleto: {$e->getMessage()}", $e->getCode(), $e);
        }  
    }

    public function getConsultarBoleto(string $convenio, string $numero): ApiResponse
    {
        $response = $this->consultarBoleto($convenio, $numero);
        return ResponseHandler::handle($response);
    }

    public function gerarBoleto(string $convenio, Boleto $boleto): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->post(
                'ailos/cobranca/api/v2/boletos/gerar/boleto/convenios/' . $convenio,
                $boleto->toArray()
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao consultar boleto: {$e->getMessage()}", $e->getCode(), $e);
        } 
    }

    public function getGerarBoleto(string $convenio, Boleto $boleto): ApiResponse
    {
        $response = $this->gerarBoleto($convenio, $boleto);
        return ResponseHandler::handle($response);
    }

    public function gerarLote(string $convenio, array $boletos): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->post(
                'ailos/cobranca/api/v2/boletos/gerar/boleto/lote/convenios/' . $convenio,
                $boletos
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao consultar boleto: {$e->getMessage()}", $e->getCode(), $e);
        }  
    }

    public function getGerarLote(string $convenio, array $boletos): ApiResponse
    {
        $response = $this->gerarLote($convenio, $boletos);
        return ResponseHandler::handle($response);
    }
}