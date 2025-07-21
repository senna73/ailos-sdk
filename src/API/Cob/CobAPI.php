<?php

namespace AilosSDK\API\Cob;

use AilosSDK\API\Cob\Auth\Auth;
use AilosSDK\API\Cob\Config\Config;
use AilosSDK\API\Cob\Services\ArqRetornoService;
use AilosSDK\API\Cob\Services\BoletoService;
use AilosSDK\API\Cob\Services\CarneService;
use AilosSDK\API\Cob\Services\PagadorService;

class CobAPI {

    public Config $config;
    public Auth $auth;
    public PagadorService $pagador;
    public BoletoService $boleto;
    public CarneService $carne;
    public ArqRetornoService $arqRetorno;

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