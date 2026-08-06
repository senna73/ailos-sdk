<?php

declare(strict_types=1);

namespace Ailos\Sdk\Tests\Integration\Collections;

use Ailos\Sdk\Entities\BoletoEntity;
use Ailos\Sdk\Entities\ConvenioCobrancaEntity;
use PHPUnit\Framework\TestCase;

class BoletoCollectionTest extends TestCase
{
    public function testGerarUnicoBoleto(): void
    {
        $boleto = BoletoEntity::new()
            ->convenioCobranca(
                ConvenioCobrancaEntity::new()
                    ->codigoCarteiraCobranca(123)
                    ->build()
            )
            ->build();

        var_dump($boleto);

        $this->assertTrue(true);
    }
}
