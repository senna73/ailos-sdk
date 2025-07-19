<?php

namespace AilosSDK\Exceptions;

use Exception;
use Throwable;

class ApiException extends Exception
{
    protected ?array $responseData = null;

    /**
     * Construtor da ApiException.
     *
     * @param string         $message       Mensagem da exceção.
     * @param int            $code          Código HTTP ou personalizado.
     * @param Throwable|null $previous      Exceção anterior (encadeada).
     * @param array|null     $responseData  Dados adicionais da resposta da API, se houver.
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        Throwable $previous = null,
        ?array $responseData = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->responseData = $responseData;
    }

    /**
     * Retorna os dados da resposta da API, se informados.
     *
     * @return array|null
     */
    public function getResponseData(): ?array
    {
        return $this->responseData;
    }

    /**
     * Verifica se a exceção possui dados de resposta adicionais.
     *
     * @return bool
     */
    public function hasResponseData(): bool
    {
        return !empty($this->responseData);
    }
}
