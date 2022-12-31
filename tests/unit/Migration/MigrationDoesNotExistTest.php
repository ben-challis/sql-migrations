<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\MigrationDoesNotExist;
use BenChallis\SqlMigrations\Migration\Version;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BenChallis\SqlMigrations\Migration\MigrationDoesNotExist
 */
final class MigrationDoesNotExistTest extends TestCase
{
    /**
     * @test
     */
    public function shows_the_correct_version_when_created_for_a_version(): void
    {
        $e = MigrationDoesNotExist::forVersion(Version::fromInteger(2022_10_10_12_10_15));

        self::assertSame('Migration for version "20221010121015" does not exist.', $e->getMessage());
    }
}
