<?php

declare(strict_types=1);

namespace Ailos\Sdk;

use Ailos\Sdk\Collections\BoletoCollection;
use Ailos\Sdk\Entities\BoletoEntity;
use Ailos\Sdk\Entities\JwtEntity;
use Ailos\Sdk\Support\Storage;

class Ailos
{
    public static function handleJwtCallback(\stdClass $payload): void
    {
        $storage = Storage::storage();

        $jwt = JwtEntity::fromObject($payload);

        $item = $storage->getItem('jwt');
        $item->set($jwt);
        $item->expiresAfter(3600);
        $storage->save($item);
    }

    public static function consultarUnicoBoleto(string $convenio, string $numero): BoletoEntity 
    {
        return new BoletoCollection()->consultarUnicoBoleto($convenio, $numero);
    }
}
