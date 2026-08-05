<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

class BoletoEntity extends Entity
{
    public function __construct(
        public readonly ConvenioCobrancaEntity $convenioCobranca,
        public readonly DocumentoEntity $documento,
        public readonly EmissaoEntity $emissao,
        public readonly PagadorEntity $pagador,
        public readonly VencimentoEntity $vencimento,
        public readonly InstrucoesEntity $instrucoes,
        public readonly ValorBoletoEntity $valorBoleto,
        public readonly AvisoSmsEntity $avisoSms,
        public readonly PagamentoDivergenteEntity $pagamentoDivergente,
        public readonly AvalistaEntity $avalista,
        public readonly int $indicadorRegistroNuclea,
    ) {
    }
}
