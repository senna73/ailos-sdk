<?php
namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\API\Cobranca\Pagador\Dto\PagadorDto;
use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class BoletoDto implements DtoInterface{
     public function __construct(
        public ConvenioCobrancaDto $convenioCobranca,
        public DocumentoDto $documento,
        public EmissaoDto $emissao,
        public PagadorDto $pagador,
        public VencimentoDto $vencimento,
        public InstrucoesDto $instrucoes,
        public string $valorNominal,
        public AvisoSMSDto $avisoSMS,
        public PagamentoDivergenteDto $pagamentoDivergente,
        public AvalistaDto $avalista,
        public int $indicadorRegistroNuclea,
    ) {}

    public static function fromRequest(object $request): self
    {
        return new self(
            $request->convenioCobranca,
            $request->documento,
            $request->emissao,
            $request->pagador,
            $request->vencimento,
            $request->instrucoes,
            $request->valorNominal,
            $request->avisoSMS,
            $request->pagamentoDivergente,
            $request->avalista,
            $request->indicadorRegistroNuclea,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["convenioCobranca"],
            $data["documento"],
            $data["emissao"],
            $data["pagador"],
            $data["vencimento"],
            $data["instrucoes"],
            $data["valorNominal"],
            $data["avisoSMS"],
            $data["pagamentoDivergente"],
            $data["avalista"],
            $data["indicadorRegistroNuclea"],
        );
    }

    public function toArray(): array
    {
        return [
            "convenioCobranca"=> $this->convenioCobranca,
            "documento"=> $this->documento,
            "emissao"=> $this->emissao,
            "pagador"=> $this->pagador,
            "vencimento"=> $this->vencimento,
            "instrucoes"=> $this->instrucoes,
            "valorNominal"=> $this->valorNominal,
            "avisoSMS"=> $this->avisoSMS,
            "pagamentoDivergente"=> $this->pagamentoDivergente,
            "avalista"=> $this->avalista,
            "indicadorRegistroNuclea"=> $this->indicadorRegistroNuclea
        ];
    }
}

?>