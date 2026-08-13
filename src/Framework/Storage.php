<?php

declare(strict_types=1);

namespace Ailos\Sdk\Framework;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class Storage
{
    public static function storage(): FilesystemAdapter
    {
        return new FilesystemAdapter('ailos', 0, __DIR__ . '../../storage');
    }
}
