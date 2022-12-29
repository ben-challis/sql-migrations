<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\InMemoryMigrations;
use BenChallis\SqlMigrations\Migration\Migrations;
use Psl\Collection\MutableMap;
use Tests\Spec\BenChallis\SqlMigrations\MigrationsSpec;

/**
 * @covers \BenChallis\SqlMigrations\Migration\InMemoryMigrations
 */
final class InMemoryMigrationsTest extends MigrationsSpec
{
    protected function createMigrations(): Migrations
    {
        $map = new MutableMap([]);

        foreach ($this->expectedMigrations() as $migration) {
            $map->add($migration->metadata->version->toInteger(), $migration);
        }

        return new InMemoryMigrations($map);
    }
}
