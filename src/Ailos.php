<?php

declare(strict_types=1);

namespace Ailos\Sdk;

use Ailos\Sdk\Collections\BoletoCollection;
use Ailos\Sdk\Entities\BoletoEntity;
use Ailos\Sdk\Entities\EnviromentEntity;
use Ailos\Sdk\Entities\JwtEntity;
use Ailos\Sdk\Framework\Storage;

class Ailos
{
    public function __construct(private readonly EnviromentEntity $enviroment)
    {
    }

    public static function handleJwtCallback(\stdClass $payload): void
    {
        $storage = Storage::storage();

        $jwt = JwtEntity::fromObject($payload);

        $item = $storage->getItem('jwt');
        $item->set($jwt);
        $item->expiresAfter(3600);
        $storage->save($item);
    }

    public function consultarUnicoBoleto(string $convenio, string $numero): BoletoEntity
    {
        return new BoletoCollection($this->enviroment)->consultarUnicoBoleto($convenio, $numero);
    }
}
