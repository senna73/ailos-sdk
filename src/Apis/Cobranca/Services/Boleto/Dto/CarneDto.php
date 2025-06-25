<?php

namespace Senna\AilosSdkPhp\API\Cobranca\Boleto\Dto;

use Senna\AilosSdkPhp\API\Cobranca\Pagador\Dto\PagadorDto;
use Senna\AilosSdkPhp\Core\DtoInterface;

readonly class CarneDto implements DtoInterface {
    public function __construct(
        public ConvenioCobrancaDto $convenioCobranca,
        public DocumentoDto $documento,
        public EmissaoDto $emissao,
        public PagadorDto $pagador,
        public VencimentoDto $vencimento,
        public InstrucoesDto $instrucoes,
        public string $valorNominal,
        public AvisoSMSDto $avisoSms,
        public PagamentoDivergenteDto $pagamentoDivergente,
        public AvalistaDto $avalista,
        public int $indicadorRegistroNuclea,
        public int $numeroParcela,
        public TipoVencimentoDto $tipoVencimento
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
            $request->avisoSms,
            $request->pagamentoDivergente,
            $request->avalista,
            $request->indicadorRegistroNuclea,
            $request->numeroParcela,
            $request->tipoVencimento,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['convenioCobranca'],
            $data['documento'],
            $data['emissao'],
            $data['pagador'],
            $data['vencimento'],
            $data['instrucoes'],
            $data['valorNominal'],
            $data['avisoSms'],
            $data['pagamentoDivergente'],
            $data['avalista'],
            $data['indicadorRegistroNuclea'],
            $data['numeroParcela'],
            $data['tipoVencimento'],
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
            "avisoSms"=> $this->avisoSms,
            "pagamentoDivergente"=> $this->pagamentoDivergente,
            "avalista"=> $this->avalista,
            "indicadorRegistroNuclea"=> $this->indicadorRegistroNuclea,
            "numeroParcela" => $this->numeroParcela,
            "tipoVencimento" => $this->tipoVencimento,
        ];
    }
}

?>