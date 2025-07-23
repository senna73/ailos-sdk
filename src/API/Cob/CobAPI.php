<?php

namespace AilosSDK\API\Cob;

use AilosSDK\API\Cob\Auth\Auth;
use AilosSDK\API\Cob\Config\Config;
use AilosSDK\API\Cob\Services\ArqRetornoService;
use AilosSDK\API\Cob\Services\BoletoService;
use AilosSDK\API\Cob\Services\CarneService;
use AilosSDK\API\Cob\Services\PagadorService;

/**
 * Classe principal para integração com a API de Cobrança da Ailos.
 *
 * Esta classe centraliza os serviços disponíveis para facilitar o uso da SDK.
 * Ao instanciar a CobAPI, todos os serviços são automaticamente inicializados.
 *
 * @package AilosSDK\API\Cob
 */
class CobAPI {

    /**
     * Instância da configuração da SDK (base URI, headers, timeout).
     *
     * @var Config
     */
    public Config $config;

    /**
     * Serviço de autenticação com a API da Ailos.
     *
     * @var Auth
     */
    public Auth $auth;

    /**
     * Serviço relacionado ao pagador (consulta, cadastro, etc.).
     *
     * @var PagadorService
     */
    public PagadorService $pagador;

    /**
     * Serviço para emissão e manipulação de boletos.
     *
     * @var BoletoService
     */
    public BoletoService $boleto;

    /**
     * Serviço para emissão e manipulação de carnês.
     *
     * @var CarneService
     */
    public CarneService $carne;

    /**
     * Serviço para recuperação de arquivos de retorno.
     *
     * @var ArqRetornoService
     */
    public ArqRetornoService $arqRetorno;

    /**
     * Construtor da CobAPI.
     *
     * Inicializa todos os serviços da API de Cobrança com as configurações fornecidas.
     *
     * @param string $baseUri        URL base da API (default: ambiente de homologação).
     * @param array $defaultHeaders  Cabeçalhos padrão a serem enviados nas requisições.
     * @param float $timeout         Tempo máximo de espera para requisições, em segundos.
     */
    public function __construct(
        string $baseUri = 'https://apiendpointhml.ailos.coop.br/',
        array $defaultHeaders = [], 
        float $timeout = 10.0
    ) {
        $this->config = new Config($baseUri, $defaultHeaders, $timeout);
        $this->auth = new Auth($this->config);
        $this->pagador = new PagadorService($this->config);
        $this->boleto = new BoletoService($this->config);
        $this->carne = new CarneService($this->config);
        $this->arqRetorno = new ArqRetornoService($this->config);
    }
}