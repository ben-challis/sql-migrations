<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use BenChallis\SqlMigrations\Migration\Migration;
use BenChallis\SqlMigrations\Migration\MigrationStuck;
use BenChallis\SqlMigrations\Migration\VersionExtractor;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\Revision20181121094934CreateATable;

/**
 * @covers \BenChallis\SqlMigrations\Migration\MigrationStuck
 */
final class MigrationStuckTest extends TestCase
{
    /**
     * @test
     */
    public function shows_the_version_of_the_stuck_migration(): void
    {
        $revision = new Revision20181121094934CreateATable();
        $e = MigrationStuck::for(
            Migration::with(
                $revision,
                Metadata::with((new VersionExtractor())->fromInstance($revision), State::Applying),
            ),
        );

        self::assertSame(
            'Migration version "20181121094934" is stuck in the applying state and needs manual intervention.',
            $e->getMessage(),
        );
    }
}
