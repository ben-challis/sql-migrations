<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\InMemoryMigrations;
use BenChallis\SqlMigrations\Migration\Metadata\MetadataStore;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use BenChallis\SqlMigrations\Migration\MetadataUpdatingMigrations;
use BenChallis\SqlMigrations\Migration\Migration;
use BenChallis\SqlMigrations\Migration\Migrations;
use Psl\Collection\MutableMap;
use Tests\Fixtures\BenChallis\SqlMigrations\Migration\Metadata\InMemoryMetadataStore;
use Tests\Spec\BenChallis\SqlMigrations\MigrationsSpec;

/**
 * @covers \BenChallis\SqlMigrations\Migration\MetadataUpdatingMigrations
 */
final class MetadataUpdatingMigrationsTest extends MigrationsSpec
{
    private MetadataStore $metadata;

    protected function createMigrations(): Migrations
    {
        $expectedMigrations = $this->expectedMigrations();
        $migrationsMap = new MutableMap([]);
        $metadataMap = new MutableMap([]);

        foreach ($expectedMigrations as $migration) {
            $migrationsMap->add($migration->metadata->version->toInteger(), $migration);
            $metadataMap->add(
                $migration->metadata->version->toInteger(),
                $migration->metadata,
            );
        }


        return new MetadataUpdatingMigrations(
            new InMemoryMigrations($migrationsMap),
            $this->metadata = new InMemoryMetadataStore($metadataMap),
        );
    }

    /**
     * @test
     */
    public function changing_state_updates_metadata(): void
    {
        $migrations = $this->createMigrations();

        $migration = $migrations->getAll()->first();
        \assert($migration instanceof Migration);

        $migrations->changeState($migration->metadata->version, State::Unapplied);
        self::assertSame(State::Unapplied, $this->metadata->fetch($migration->metadata->version)->state);
    }
}
