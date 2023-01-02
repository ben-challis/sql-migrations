<?php

declare(strict_types=1);

namespace Tests\Spec\BenChallis\SqlMigrations;

use Amp\PHPUnit\AsyncTestCase;
use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use BenChallis\SqlMigrations\Migration\Migration;
use BenChallis\SqlMigrations\Migration\MigrationDoesNotExist;
use BenChallis\SqlMigrations\Migration\Migrations;
use BenChallis\SqlMigrations\Migration\Version;
use BenChallis\SqlMigrations\Migration\VersionExtractor;
use Psl\Collection\Vector;
use Psl\Collection\VectorInterface;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\Revision20181121094934CreateATable;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\Revision20181217101234CreateAnotherTable;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\Revision20181222101234UpdateATable;
use Tests\Helper\BenChallis\SqlMigrations\MigrationAsserter;

abstract class MigrationsSpec extends AsyncTestCase
{
    abstract protected function createMigrations(): Migrations;

    /**
     * @return VectorInterface<Migration>
     */
    final protected function expectedMigrations(): VectorInterface
    {
        $versionExtractor = new VersionExtractor();

        $createATable = new Revision20181121094934CreateATable();
        $createAnotherTable = new Revision20181217101234CreateAnotherTable();
        $updateATable = new Revision20181222101234UpdateATable();

        return new Vector([
            new Migration(
                $createATable,
                Metadata::with(
                    $versionExtractor->fromInstance($createATable),
                    State::Applied,
                ),
            ),
            new Migration(
                $updateATable,
                Metadata::with(
                    $versionExtractor->fromInstance($updateATable),
                    State::Unapplied,
                ),
            ),
            new Migration(
                $createAnotherTable,
                Metadata::with(
                    $versionExtractor->fromInstance($createAnotherTable),
                    State::Unapplied,
                ),
            ),
        ]);
    }

    /**
     * @test
     */
    final public function all_migrations_can_be_retrieved(): void
    {
        $all = $this->createMigrations()->getAll();

        foreach ($this->expectedMigrations() as $expectedMigration) {
            self::assertNotNull($all->filter(static fn (Migration $migration): bool => $expectedMigration->equals($migration))->first());
        }

        $versionExtractor = new VersionExtractor();
        MigrationAsserter::assertThat($all->toArray()[0])
            ->hasVersion($versionExtractor->fromClass(Revision20181121094934CreateATable::class))
            ->hasRevisionClass(Revision20181121094934CreateATable::class)
            ->isInState(State::Applied);
        MigrationAsserter::assertThat($all->toArray()[1])
            ->hasVersion($versionExtractor->fromClass(Revision20181217101234CreateAnotherTable::class))
            ->hasRevisionClass(Revision20181217101234CreateAnotherTable::class)
            ->isInState(State::Unapplied);
        MigrationAsserter::assertThat($all->toArray()[2])
            ->hasVersion($versionExtractor->fromClass(Revision20181222101234UpdateATable::class))
            ->hasRevisionClass(Revision20181222101234UpdateATable::class)
            ->isInState(State::Unapplied);
    }

    /**
     * @test
     */
    final public function a_single_migration_can_be_retrieved_by_version(): void
    {
        $migrations = $this->createMigrations();

        foreach ($this->expectedMigrations() as $expectedMigration) {
            self::assertTrue($expectedMigration->equals($migrations->get($expectedMigration->metadata->version)));
        }
    }

    /**
     * @test
     */
    final public function migrations_that_do_not_exist_result_in_an_exception(): void
    {
        $this->expectException(MigrationDoesNotExist::class);

        $this->createMigrations()->get(Version::fromInteger(1512));
    }

    /**
     * @test
     */
    final public function a_migrations_state_can_be_changed(): void
    {
        $migrations = $this->createMigrations();

        $version = (new VersionExtractor())->fromClass(Revision20181217101234CreateAnotherTable::class);
        $updated = $migrations->changeState($version, State::Applying);

        self::assertSame(State::Applying, $updated->metadata->state);
        self::assertSame(State::Applying, $migrations->get($version)->metadata->state);
    }
}
