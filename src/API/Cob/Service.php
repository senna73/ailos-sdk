<?php

namespace AilosSDK\API\Cob;

use Psr\Http\Message\ResponseInterface;
use AilosSDK\API\Cob\Models\ConvenioCobranca;
use AilosSDK\API\Cob\Models\Pagador;
use AilosSDK\Exceptions\ApiException;
use AilosSDK\Common\ResponseHandler;
use AilosSDK\Common\Models\ApiResponse;
use AilosSDK\API\Cob\Models\Boleto;
use AilosSDK\API\Cob\Models\Carne;
use AilosSDK\Common\Utils\DocumentoValidator;

/**
 * Classe de serviço para integração com a API de cobrança Ailos.
 * Realiza operações de pagadores, boletos, carnês e arquivos de retorno.
 */
class Service {
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

    // =========================
    // ARQ. RETORNO
    // =========================

    /**
     * Solicita um ticket para geração do arquivo de retorno.
     * Esse ticket é utilizado posteriormente para baixar o arquivo de retorno com os registros de liquidações.
     *
     * @param string $convenio Código do convênio.
     * @param string $data Data da solicitação no formato YYYY-MM-DD.
     * @return ResponseInterface
     * @throws ApiException
     */
    public function ticketArqRetorno(string $convenio, string $data): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->get(
                'ailos/cobranca/api/v1/boletos/solicitar/arquivo/retorno/convenios/' . $convenio . '/' . $data,
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao gerar ticket do arq. retorno: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Solicita o ticket de arquivo de retorno e trata a resposta.
     *
     * @param string $convenio Código do convênio.
     * @param string $data Data da solicitação no formato YYYY-MM-DD.
     * @return ApiResponse
     * @throws ApiException
     */
    public function getTicketArqRetorno(string $convenio, string $data): ApiResponse
    {
        $response = $this->ticketArqRetorno($convenio, $data);
        return ResponseHandler::handle($response);
    }

    /**
     * Baixa o arquivo de retorno utilizando um ticket válido.
     *
     * @param string $convenio Código do convênio.
     * @param string $ticket Ticket gerado previamente pela solicitação.
     * @return ResponseInterface
     * @throws ApiException
     */
    public function arqRetorno(string $convenio, string $ticket): ResponseInterface
    {
        try {
           return $this->api->getHttpClient()->get(
                'ailos/cobranca/api/v1/boletos/baixar/arquivo/retorno/convenios/' . $convenio . '/' . $ticket,
            );

        } catch (\Throwable $e) {
            throw new ApiException("Erro ao resgatar arq. retorno: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Baixa o arquivo de retorno com tratamento da resposta.
     *
     * @param string $convenio Código do convênio.
     * @param string $ticket Ticket gerado previamente pela solicitação.
     * @return ApiResponse
     * @throws ApiException
     */
    public function getArqRetorno(string $convenio, string $ticket): ApiResponse
    {
        $response = $this->arqRetorno($convenio, $ticket);
        return ResponseHandler::handle($response);
    }
}