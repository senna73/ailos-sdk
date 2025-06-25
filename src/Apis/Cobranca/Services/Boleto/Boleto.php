<?php
namespace Senna\AilosSdkPhp\API\Cobranca\Boleto;

use Psr\Http\Message\ResponseInterface;
use Senna\AilosSdkPhp\API\AilosAPI;
use Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto\BoletoDto;
use Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto\LoteBoletosDto;
use InvalidArgumentException;
use Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto\LoteCarnesDto;

class Boleto {
    
    public function __construct(private AilosAPI $api) {}


    /**
     * Consulta um boleto único.
     *
     * @param string $convenio Número do Convenio
     * @param string $numero Número do Boleto
     * @return ResponseInterface
     * @throws InvalidArgumentException
     */
    public function consultaBoletoUnico(string $convenio, string $numero): ResponseInterface 
    {
        return $this->api->getHttpClient()->get(
            "ailos/cobranca/api/v2/boletos/consultar/boleto/convenios/",
            [
                $convenio,
                $numero
            ]
        );
    }

    /**
     * Gera um boleto para o convênio especificado.
     *
     * @param string $convenio Número do Convênio
     * @param BoletoDto $boleto
     * @return ResponseInterface
     * @throws InvalidArgumentException
     */
    public function gerarUnico(string $convenio, LoteBoletosDto $boleto): ResponseInterface
    {
        return $this->api->getHttpClient()->post(
            'ailos/cobranca/api/v2/boletos/gerar/boleto/convenios/' . $convenio,
            $boleto->toArray()
        );
    }

    /**
     * Gera um lote de boletos para o convênio especificado.
     *
     * @param string $convenio Número do Convenio
     * @param LoteBoletosDto $boletos
     * @return ResponseInterface
     * @throws InvalidArgumentException
     */
    public function gerarLote(string $convenio, LoteBoletosDto $boletos): ResponseInterface
    {
        return $this->api->getHttpClient()->post(
            'ailos/cobranca/api/v2/boletos/gerar/boleto/lote/convenios/' . $convenio,
            $boletos->toArray()
        );
    }

    /**
     * Gera um lote de boletos para o convênio especificado.
     *
     * @param string $convenio Número do Convenio
     * @param LoteCarnesDto $carnes
     * @return ResponseInterface
     * @throws InvalidArgumentException
     */
    public function gerarCarnesLote(string $convenio, LoteCarnesDto $carnes): ResponseInterface
    {
        return $this->api->getHttpClient()->post(
            'ailos/cobranca/api/v2/boletos/gerar/carne/lote/convenios/'. $convenio,
            $carnes->toArray()
        );
    }
}

?>