<?php

namespace AilosSDK\Common\Utils;

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
    private static function processBody(string $rawBody)
    {
        $trimmedBody = trim($rawBody);
        
        // Se estiver vazio, retorna array vazio para manter consistência
        if (empty($trimmedBody)) {
            return [];
        }
        
        // Tenta decodificar como JSON
        $decoded = json_decode($trimmedBody, true);
        
        // Se é JSON válido, retorna o array decodificado
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        
        // Se não é JSON válido, retorna como array com chave 'data'
        // Isso mantém consistência para o código que espera array
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
}