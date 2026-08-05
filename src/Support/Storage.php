<?php

declare(strict_types=1);

namespace Ailos\Sdk\Support;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

readonly class Storage
{
    public FilesystemAdapter $storage;

    public static function storage(): FilesystemAdapter
    {
        return new FilesystemAdapter('ailos', 0, __DIR__ . '/storage');
    }
}
