<?php

namespace AilosSDK\API\Cob\Services;

use AilosSDK\API\Cob\Config\Config;
use AilosSDK\API\Cob\Models\Pagador;
use AilosSDK\Common\Models\ApiResponse;
use AilosSDK\Common\Utils\DocumentoValidator;
use AilosSDK\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface;

/**
 * Classe de serviço para integração com a API de cobrança Ailos.
 * Realiza operações de pagadores.
 */
class PagadorService {
    private Config $api;

    public function __construct(Config $api) {
        $this->api = $api;
    }

    // =========================
    // PAGADORES
    // =========================

    /**
     * Cadastra um novo pagador.
     *
     * @param Pagador $pagador
     * @return ResponseInterface
     * @throws ApiException
     */
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

    /**
     * Cadastra um pagador e trata a resposta.
     *
     * @param Pagador $pagador
     * @return ApiResponse
     */
    public function getCadastrarPagador(Pagador $pagador): ApiResponse
    {
        $response = $this->cadastrarPagador($pagador);
        return ResponseHandler::handle($response);
    }

    /**
     * Altera um pagador já cadastrado.
     *
     * @param Pagador $pagador
     * @return ResponseInterface
     * @throws ApiException
     */
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

    /**
     * Altera um pagador com tratamento de resposta.
     *
     * @param Pagador $pagador
     * @return ApiResponse
     */
    public function getAlterarPagador(Pagador $pagador): ApiResponse
    {
        $response = $this->alterarPagador($pagador);
        return ResponseHandler::handle($response);
    }

    /**
     * Consulta um pagador a partir de seu CPF/CNPJ.
     *
     * @param string $documento
     * @return ResponseInterface
     * @throws ApiException
     */
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

    /**
     * Consulta e valida documento do pagador com tratamento da resposta.
     *
     * @param string $documento
     * @return ApiResponse
     * @throws ApiException
     */
    public function getConsultarPagador(string $documento): ApiResponse
    {
        $validador = new DocumentoValidator($documento);
        if (!$validador->validar()) {
            throw new ApiException("Documento inserido para consultar o pagador é inválido");
        }

        $response = $this->consultarPagador($documento);
        return ResponseHandler::handle($response);
    }

    /**
     * Lista todos os pagadores cadastrados.
     *
     * @return ResponseInterface
     * @throws ApiException
     */
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

    /**
     * Lista pagadores com tratamento de resposta.
     *
     * @return ApiResponse
     */
    public function getListarPagadores(): ApiResponse
    {
        $response = $this->listarPagadores();
        return ResponseHandler::handle($response);
    }

    /**
     * Totaliza os pagadores cadastrados.
     *
     * @return ResponseInterface
     * @throws ApiException
     */
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

    /**
     * Totaliza os pagadores com tratamento de resposta.
     *
     * @return ApiResponse
     */
    public function getTotalizarPagadores(): ApiResponse
    {
        $response = $this->totalizarPagadores();
        return ResponseHandler::handle($response);
    }

    /**
     * Exporta os pagadores cadastrados.
     *
     * @param string $tipoArquivo
     * @param string $flagArquivoModelo
     * @return ResponseInterface
     * @throws ApiException
     */
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

    /**
     * Exporta pagadores com tratamento da resposta.
     *
     * @param string $tipoArquivo
     * @param string $flagArquivoModelo
     * @return ApiResponse
     */
    public function getExportarPagadores($tipoArquivo, $flagArquivoModelo): ApiResponse
    {
        $response = $this->exportarPagadores($tipoArquivo, $flagArquivoModelo);
        return ResponseHandler::handle($response);
    }

    /**
     * Importa pagadores a partir de um arquivo.
     *
     * @param mixed $arquivo
     * @param string $codigoCanal
     * @param string $ipAcionamento
     * @return ResponseInterface
     * @throws ApiException
     */
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

    /**
     * Importa pagadores com tratamento da resposta.
     *
     * @param mixed $arquivo
     * @param string $codigoCanal
     * @param string $ipAcionamento
     * @return ApiResponse
     */
    public function getImportarPagadores($arquivo, $codigoCanal, $ipAcionamento): ApiResponse
    {
        $response = $this->importarPagadores($arquivo, $codigoCanal, $ipAcionamento);
        return ResponseHandler::handle($response);
    }

}