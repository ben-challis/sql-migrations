<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use BenChallis\SqlMigrations\Migration\Metadata\MetadataStore;
use Psl\Collection\MutableMap;

final class MigrationsFactory
{
    private function __construct()
    {
    }

    public static function create(
        MetadataStore $metadata,
        MigrationCollector $migrationCollector,
        PhpNamespace ...$namespaces,
    ): Migrations {
        return new LazyLoadingMigrations(
            static fn (): Migrations => new MetadataUpdatingMigrations(
                new InMemoryMigrations(
                    new MutableMap(
                        $migrationCollector->collect(...$namespaces),
                    ),
                ),
                $metadata,
            ),
        );
    }
}
