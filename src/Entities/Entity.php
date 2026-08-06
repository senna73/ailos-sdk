<?php

declare(strict_types=1);

namespace Ailos\Sdk\Entities;

use CuyZ\Valinor\Mapper\Configurator\MapKeysToCamelCase;
use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;

abstract class Entity
{
    private static ?TreeMapper $mapper = null;

    public static function fromObject(object $data): static
    {
        try {
            return self::mapper()->map(static::class, Source::json(json_encode($data)));
        } catch (MappingError $error) {
            throw new \InvalidArgumentException($error->getMessage(), previous: $error);
        }
    }

    public static function fromArray(array $data): static
    {
        try {
            return self::mapper()->map(static::class, $data);
        } catch (MappingError $error) {
            throw new \InvalidArgumentException(
                (string) $error->getMessage(),
                previous: $error,
            );
        }
    }

    private static function mapper(): TreeMapper
    {
        return self::$mapper ??= (new MapperBuilder())
            ->allowSuperfluousKeys()
            ->configureWith(new MapKeysToCamelCase())
            ->mapper();
    }
}
