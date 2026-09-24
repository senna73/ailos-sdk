<?php

declare(strict_types=1);

namespace Ailos\Sdk;

use Ailos\Sdk\Cobranca\Cobranca;
use Ailos\Sdk\Cobranca\Config\CobrancaConfig;

final class Ailos
{
    public function cobranca(CobrancaConfig $context): Cobranca
    {
        return new Cobranca($context);
    }
}
