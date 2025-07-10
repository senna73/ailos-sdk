<?php

namespace Senna\AilosSdkPhp\API\Cob;

use Psr\Http\Message\ResponseInterface;
use Senna\AilosSdkPhp\API\Cob\Models\Pagador;
use Senna\AilosSdkPhp\Exceptions\ApiException;
use Senna\AilosSdkPhp\Common\ResponseHandler;
use Senna\AilosSdkPhp\Common\Models\ApiResponse;
use Senna\AilosSdkPhp\API\Cob\Models\Boleto;
use Senna\AilosSdkPhp\Common\Utils\DocumentoValidator;

class Service {
    public function __construct(private Config $api) {}

    # PAGADORES
    public function cadastrarPagador(Pagador $pagador): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->post(
                'ailos/cobranca/api/v1/pagadores/cadastrar',
                $pagador->toArray()
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao cadastrar pagador: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    public function getCadastrarPagador(Pagador $pagador): ApiResponse
    {
        $response = $this->cadastrarPagador($pagador);
        return ResponseHandler::handle($response);
    }

    public function alterarPagador(Pagador $pagador): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->put(
                'ailos/cobranca/api/v1/pagadores/alterar',
                $pagador->toArray()
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao alterar pagador: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    public function getAlterarPagador(Pagador $pagador): ApiResponse
    {
        $response = $this->alterarPagador($pagador);
        return ResponseHandler::handle($response);
    }

    public function consultarPagador(string $documento): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->get(
                'ailos/cobranca/api/v1/pagadores/consultar',
                [
                    $documento
                ]
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao consultar pagador: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    public function getConsultarPagador(string $documento): ApiResponse
    {
        $validador = new DocumentoValidator($documento);
        if (!$validador->validar()) {
            throw new ApiException("Documento inserido para consultar o pagador é inválido");
        }

        $response = $this->consultarPagador($documento);
        return ResponseHandler::handle($response);
    }

    public function listarPagadores(): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->get(
                'ailos/cobranca/api/v1/pagadores/listar',
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao listar pagadores: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    public function getListarPagadores(): ApiResponse
    {
        $response = $this->listarPagadores();
        return ResponseHandler::handle($response);
    }

    public function totalizarPagadores(): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->get(
                'ailos/cobranca/api/v1/pagadores/totalizar/21/1.1.1.1',
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao totalizar pagadores: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    public function getTotalizarPagadores(): ApiResponse
    {
        $response = $this->totalizarPagadores();
        return ResponseHandler::handle($response);
    }

    public function exportarPagadores($tipoArquivo, $flagArquivoModelo): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->get(
                'ailos/cobranca/api/v1/pagadores/exportar/21/1.1.1.1',
                [
                    'tipoArquivo' => $tipoArquivo,
                    'flagArquivoModelo'=> $flagArquivoModelo
                ]
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao exportar pagadores: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    public function getExportarPagadores($tipoArquivo, $flagArquivoModelo): ApiResponse
    {
        $response = $this->exportarPagadores($tipoArquivo, $flagArquivoModelo);
        return ResponseHandler::handle($response);
    }

    public function importarPagadores($arquivo, $codigoCanal, $ipAcionamento): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->post(
                'ailos/cobranca/api/v1/pagadores/importar',
                [
                    'arquivo' => $arquivo,
                    'codigoCanal'=> $codigoCanal,
                    'ipAcionamento'=> $ipAcionamento
                ]
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao importar pagadores: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    public function getImportarPagadores($arquivo, $codigoCanal, $ipAcionamento): ApiResponse
    {
        $response = $this->importarPagadores($arquivo, $codigoCanal, $ipAcionamento);
        return ResponseHandler::handle($response);
    }

    #BOLETOS

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