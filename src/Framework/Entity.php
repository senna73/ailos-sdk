<?php

declare(strict_types=1);

namespace Ailos\Sdk\Framework;

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
            $data = json_encode($data);
            if (!$data) {
                throw new \RuntimeException('Falha na codificação dos dados');
            }
            return self::mapper()->map(static::class, Source::json($data));
        } catch (MappingError $error) {
            throw new \InvalidArgumentException($error->getMessage(), previous: $error);
        }
    }

    /**
     * @param array<string, mixed> $data
     */
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

    /**
     * @return array<mixed, mixed>
     */
    public static function toArray(): array
    {
        $data = json_encode(static::class);
        if ($data) {
            return (array) json_decode($data, true);
        }
        throw new \RuntimeException('Erro ao converter a entidade para array');
    }

    private static function mapper(): TreeMapper
    {
        return self::$mapper ??= (new MapperBuilder())
            ->allowSuperfluousKeys()
            ->configureWith(new MapKeysToCamelCase())
            ->mapper();
    }
}
