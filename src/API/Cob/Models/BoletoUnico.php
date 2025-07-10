<?php
namespace Senna\AilosSdkPhp\API\Cob\Models;

class BoletoUnico {
    public ConvenioCobranca $convenioCobranca;
    public Documento $documento;
    public Emissao $emissao;
    public Pagador $pagador;
    public Vencimento $vencimento;
    public Instrucoes $instrucoes;
    public ValorBoleto $valorBoleto;
    public AvisoSms $avisoSms;
    public PagamentoDivergente $pagamentoDivergente;
    public Avalista $avalista;
    public int $indicadorRegistroNuclea;

    public function __construct(
        ConvenioCobranca $convenioCobranca,
        Documento $documento,
        Emissao $emissao,
        Pagador $pagador,
        Vencimento $vencimento,
        Instrucoes $instrucoes,
        ValorBoleto $valorBoleto,
        AvisoSms $avisoSms,
        PagamentoDivergente $pagamentoDivergente,
        Avalista $avalista,
        int $indicadorRegistroNuclea
    ) {
        $this->convenioCobranca = $convenioCobranca;
        $this->documento = $documento;
        $this->emissao = $emissao;
        $this->pagador = $pagador;
        $this->vencimento = $vencimento;
        $this->instrucoes = $instrucoes;
        $this->valorBoleto = $valorBoleto;
        $this->avisoSms = $avisoSms;
        $this->pagamentoDivergente = $pagamentoDivergente;
        $this->avalista = $avalista;
        $this->indicadorRegistroNuclea = $indicadorRegistroNuclea;
    }

    public function toArray(): array {
        return [
            'convenioCobranca' => $this->convenioCobranca->toArray(),
            'documento' => $this->documento->toArray(),
            'emissao' => $this->emissao->toArray(),
            'pagador' => $this->pagador->toArray(),
            'vencimento' => $this->vencimento->toArray(),
            'instrucoes' => $this->instrucoes->toArray(),
            'valorBoleto' => $this->valorBoleto->toArray(),
            'avisoSms' => $this->avisoSms->toArray(),
            'pagamentoDivergente' => $this->pagamentoDivergente->toArray(),
            'avalista' => $this->avalista->toArray(),
            'indicadorRegistroNuclea' => $this->indicadorRegistroNuclea,
        ];
    }
}
