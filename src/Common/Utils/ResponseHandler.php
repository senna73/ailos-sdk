<?php

namespace AilosSDK\Common\Utils;

use DOMDocument;
use DOMXPath;
use Psr\Http\Message\ResponseInterface;
use AilosSDK\Exceptions\ApiException;
use AilosSDK\Common\Models\ApiResponse;

/**
 * Classe responsável por tratar respostas da API.
 * Lida com status HTTP esperados e extrai corpo da resposta de forma segura.
 */
class ResponseHandler
{
    /**
     * Manipula a resposta da API, validando o status HTTP e decodificando o corpo.
     *
     * @param ResponseInterface $response A resposta da requisição HTTP.
     * @param array<int> $expectedStatusCodes Lista de status HTTP considerados válidos (default: 200, 201).
     * @return ApiResponse Objeto contendo a resposta processada e o corpo decodificado.
     *
     * @throws ApiException Caso o código de status não esteja entre os esperados.
     */
    public static function handle(ResponseInterface $response, array $expectedStatusCodes = [200, 201]): ApiResponse
    {
        $statusCode = $response->getStatusCode();
        $bodyStream = $response->getBody();
        $bodyStream->rewind();
        $rawBody = $bodyStream->getContents();
        
        // Processa o body - JSON válido ou string simples
        $decoded = self::processBody($rawBody);
        
        if (!in_array($statusCode, $expectedStatusCodes)) {
            $message = self::extractErrorMessage($decoded);
            throw new ApiException($message, $statusCode, null, $decoded);
        }
        
        return new ApiResponse($response, $decoded);
    }
    
    /**
     * Processa o corpo bruto da resposta, tentando decodificar como JSON.
     *
     * @param string $rawBody Corpo bruto da resposta HTTP.
     * @return array<string, mixed> Corpo decodificado como array associativo.
     */
   private static function processBody(string $rawBody): array
    {
        $trimmedBody = trim($rawBody);

        if (empty($trimmedBody)) {
            return [];
        }

        $decoded = json_decode($trimmedBody, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            if (is_array($decoded)) {
                return $decoded;
            }
            return ['data' => $decoded];
        }

        return ['data' => $rawBody];
    }


    /**
     * Extrai uma mensagem de erro do corpo decodificado da resposta.
     *
     * @param mixed $decoded Corpo já processado (normalmente array).
     * @return string Mensagem de erro extraída, ou mensagem genérica se não encontrada.
     */
    private static function extractErrorMessage($decoded): string
    {
        // Se é array e tem mensagem de erro
        if (is_array($decoded) && isset($decoded['message'])) {
            return $decoded['message'];
        }
        
        // Se é array com 'data' contendo string (nosso formato para não-JSON)
        if (is_array($decoded) && isset($decoded['data']) && is_string($decoded['data'])) {
            return $decoded['data'];
        }
        
        // Se é array com outros campos, tenta extrair informação útil
        if (is_array($decoded)) {
            // Procura por outras chaves comuns de erro
            $errorKeys = ['error', 'erro', 'msg', 'description', 'detail'];
            foreach ($errorKeys as $key) {
                if (isset($decoded[$key])) {
                    return (string) $decoded[$key];
                }
            }
            
            // Se não encontrou nada específico, serializa o array
            return 'Erro na API: ' . json_encode($decoded);
        }
        
        // Fallback padrão
        return 'Erro na chamada da API.';
    }

    /**
     * Valida o conteúdo HTML retornado após a autenticação na página do Ailos.
     * 
     * Essa função analisa o HTML e verifica:
     *  - Se a autenticação foi bem-sucedida (título "Sucesso - Ailos").
     *  - Se existe alguma mensagem de erro retornada pela página (dentro de uma <div> com a classe
     *    "text-danger validation-summary-errors" e elementos <li>).
     * 
     * Em caso de erro, uma exceção do tipo ApiException será lançada com a mensagem extraída da página.
     * Caso não seja possível determinar o sucesso ou erro, lança uma exceção genérica.
     *
     * @param string $html O HTML retornado pela página de autenticação.
     * 
     * @throws ApiException Se for detectado um erro na autenticação ou o HTML for inesperado.
     * 
     * @return void
     */
    public static function validateAuthPage(string $html): void
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $tituloElement = $dom->getElementsByTagName('title')->item(0);
        $titulo = $tituloElement ? trim($tituloElement->textContent) : '';

        if ($titulo === 'Sucesso - Ailos') {
            return;
        }

        $xpath = new \DOMXPath($dom);
        $errorDivs = $xpath->query('//div[@class="text-danger validation-summary-errors"]');

        if ($errorDivs->length > 0) {
            $lis = $errorDivs->item(0)->getElementsByTagName('li');
            if ($lis->length > 0) {
                throw new ApiException(trim($lis->item(0)->textContent));
            }
        }

        throw new ApiException('Resposta HTML não reconhecida: falha ao validar página de autenticação.');
    }

}