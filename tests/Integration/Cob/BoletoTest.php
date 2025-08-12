
<?php

use AilosSDK\API\Cob\CobAPI;
use AilosSDK\API\Cob\Models\AvisoSms;
use AilosSDK\API\Cob\Models\Boleto;
use AilosSDK\API\Cob\Models\ConvenioCobranca;
use AilosSDK\API\Cob\Models\Documento;
use AilosSDK\API\Cob\Models\Emissao;
use AilosSDK\API\Cob\Models\Endereco;
use AilosSDK\API\Cob\Models\EntidadeLegal;
use AilosSDK\API\Cob\Models\Instrucoes;
use AilosSDK\API\Cob\Models\Pagador;
use AilosSDK\API\Cob\Models\PagamentoDivergente;
use AilosSDK\API\Cob\Models\Telefone;
use AilosSDK\API\Cob\Models\ValorBoleto;
use AilosSDK\API\Cob\Models\Vencimento;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;


class AuthTest extends TestCase
{
    public CobAPI $cob;

    public function setUp(): void
    {
        static $loaded = false;

        if (!$loaded && file_exists(__DIR__ . '/.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->safeLoad();
            $loaded = true;
        }

        $this->cob = new CobAPI();
        $this->cob->auth->getFullAuth(
            $_ENV['AILOS_CONSUMER_KEY'],
            $_ENV['AILOS_CONSUMER_SECRET'],
            $_ENV['AILOS_CALLBACK_URL'],
            $_ENV['AILOS_API_KEY_DEVELOPER_UUID'],
            'teste-state',
            $_ENV['AILOS_LOGIN_COOP'],
            $_ENV['AILOS_LOGIN_ACCOUNT'],
            $_ENV['AILOS_LOGIN_PASSWORD']
        );
    }

    public function testGerarBoleto(): void 
    {
        $this->cob->boleto->gerarBoleto(
            "0001",
            $boleto = new Boleto(
                new ConvenioCobranca(),
                new Documento(),
                new Emissao(),
                new Pagador(
                    new EntidadeLegal(),
                    new Telefone(),
                    [],
                    new Endereco(),
                    [],
                    false
                ),
                new Vencimento(),
                new Instrucoes(),
                new ValorBoleto(),
                new AvisoSms(),
                new PagamentoDivergente(),
                new Avalista(),
                0
            ),
        );
    }
}