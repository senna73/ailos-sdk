<?php

namespace AilosSDK\Common;

use Psr\Http\Message\ResponseInterface;
use AilosSDK\Exceptions\ApiException;
use AilosSDK\Common\Models\ApiResponse;

class ResponseHandler
{
    public static function handle(ResponseInterface $response, array $expectedStatusCodes = [200, 201]): ApiResponse
    {
        $statusCode = $response->getStatusCode();
        $rawBody = $response->getBody()->getContents();

        $decoded = json_decode($rawBody, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException('Resposta da API não é um JSON válido.', $statusCode);
        }

        if (!in_array($statusCode, $expectedStatusCodes)) {
            $message = $decoded['message'] ?? 'Erro na chamada da API.';
            throw new ApiException($message, $statusCode, null, $decoded);
        }

        return new ApiResponse($response, $decoded);
    }
}

?>