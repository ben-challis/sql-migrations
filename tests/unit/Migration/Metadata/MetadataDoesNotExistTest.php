<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration\Metadata;

use BenChallis\SqlMigrations\Migration\Metadata\MetadataDoesNotExist;
use BenChallis\SqlMigrations\Migration\Version;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BenChallis\SqlMigrations\Migration\Metadata\MetadataDoesNotExist
 */
final class MetadataDoesNotExistTest extends TestCase
{
    /**
     * @test
     */
    public function shows_the_version_of_the_metadata_that_does_not_exist(): void
    {
        $e = MetadataDoesNotExist::forVersion(Version::fromInteger(4));

        self::assertSame('Metadata for revision "4" does not exist.', $e->getMessage());
    }
}
