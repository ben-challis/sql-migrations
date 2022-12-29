<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use BenChallis\SqlMigrations\Migration\Migration;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\BenChallis\SqlMigrations\RevisionFixtures;
use Tests\Fixtures\BenChallis\SqlMigrations\VersionFixtures;
use Tests\Helper\BenChallis\SqlMigrations\MigrationAsserter;

/**
 * @covers \BenChallis\SqlMigrations\Migration\Migration
 */
final class MigrationTest extends TestCase
{
    /**
     * @test
     */
    public function changes_state(): void
    {
        $migration = Migration::with(
            RevisionFixtures::dummy(),
            Metadata::with(
                VersionFixtures::random(),
                State::Unapplied,
            ),
        );

        $changedState = $migration->withState(State::Applying);

        MigrationAsserter::assertThat($changedState)
            ->isInState(State::Applying)
            ->hasVersion($migration->metadata->version)
            ->hasRevision($migration->revision);
    }
}
