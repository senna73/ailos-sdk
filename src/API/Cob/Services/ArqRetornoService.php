<?php

namespace AilosSDK\API\Cob\Services;

use AilosSDK\API\Cob\Config\Config;
use AilosSDK\Common\Models\ApiResponse;
use AilosSDK\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface;

/**
 * Classe de serviço para integração com a API de cobrança Ailos.
 * Realiza operações de pagadores, boletos, carnês e arquivos de retorno.
 */
class ArqRetornoService {
    private Config $api;

    public function __construct(Config $api) {
        $this->api = $api;
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