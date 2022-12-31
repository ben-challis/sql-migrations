<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\InMemoryMigrations;
use BenChallis\SqlMigrations\Migration\LazyLoadingMigrations;
use BenChallis\SqlMigrations\Migration\Migrations;
use Psl\Collection\MutableMap;
use Tests\Spec\BenChallis\SqlMigrations\MigrationsSpec;

/**
 * @covers \BenChallis\SqlMigrations\Migration\LazyLoadingMigrations
 */
final class LazyLoadingMigrationsTest extends MigrationsSpec
{
    protected function createMigrations(): Migrations
    {
        $expectedMigrations = $this->expectedMigrations();
        $map = new MutableMap([]);

        foreach ($expectedMigrations as $migration) {
            $map->add($migration->metadata->version->toInteger(), $migration);
        }

        return new LazyLoadingMigrations(static fn (): Migrations => new InMemoryMigrations($map));
    }
}
