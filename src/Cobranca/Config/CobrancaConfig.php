<?php

declare(strict_types=1);

namespace Ailos\Sdk\Cobranca\Config;

use Ailos\Sdk\Http\CurlHttp;
use Ailos\Sdk\Http\IHttp;
use Ailos\Sdk\Storage\IStorage;
use Ailos\Sdk\Storage\Storage;

enum Ambiente: string
{
    case Homologacao = 'homol';
    case Producao    = 'prod';

    public function baseUrl(): string
    {
        return match ($this) {
            self::Homologacao => 'https://apiendpointhml.ailos.coop.br',
            self::Producao    => 'https://apiendpoint.ailos.coop.br',
        };
    }
}

final readonly class CobrancaConfig
{
    public function __construct(
        public string $consumerKey,
        #[\SensitiveParameter] public string $consumerSecret,
        public string $urlCallback,
        public string $developerKey,
        public string $codigoCooperativa,
        public string $codigoConta,
        #[\SensitiveParameter] public string $senha,
        public Ambiente $ambiente = Ambiente::Homologacao,
        public IStorage $storage = new Storage(),
        public IHttp $http = new CurlHttp(),
        public bool $catcherService = false,
        public ?string $catcherUrl = null,
        #[\SensitiveParameter] public ?string $catcherSecret = null,
    ) {
    }

    public function baseUrl(): string
    {
        return $this->ambiente->baseUrl();
    }
}
