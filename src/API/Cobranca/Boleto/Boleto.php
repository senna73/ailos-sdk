<?php
namespace Senna\AilosSdkPhp\API\Cobranca\Boleto;

use Psr\Http\Message\ResponseInterface;
use Senna\AilosSdkPhp\API\AilosAPI;
use Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto\BoletoDto;
use Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto\LoteBoletosDto;

class Boleto {
    
    public function __construct(private AilosAPI $api) {}

    /**
     * Gera um lote de boletos para o convênio especificado.
     *
     * @param string $convenio
     * @param LoteBoletosDto $boletos
     * @return ResponseInterface
     * @throws InvalidArgumentException
     */
    public function gerarLote(string $convenio, LoteBoletosDto $boletos): ResponseInterface
    {
        foreach ($boletos->boletos as $boleto) {
            if (!$boleto instanceof BoletoDto) {
                throw new \InvalidArgumentException("Instancia de boleto com tipo incorreto.");
            }
        }

        return $this->api->getHttpClient()->post(
            'ailos/cobranca/api/v2/boletos/gerar/boleto/lote/convenios/' . $convenio,
            $boletos->toArray()
        );
    }
}

?>