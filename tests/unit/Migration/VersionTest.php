<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Version;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BenChallis\SqlMigrations\Migration\Version
 */
final class VersionTest extends TestCase
{
    /**
     * @test
     */
    public function equals_other_version_with_same_value(): void
    {
        $version = Version::fromInteger(1);
        $sameValue = Version::fromInteger(1);
        $differentValue = Version::fromInteger(2);

        self::assertTrue($version->equals($version));
        self::assertTrue($version->equals($sameValue));
        self::assertTrue($sameValue->equals($version));
        self::assertFalse($version->equals($differentValue));
        self::assertFalse($differentValue->equals($version));
    }

    /**
     * @test
     */
    public function converts_to_an_integer(): void
    {
        self::assertSame(10, Version::fromInteger(10)->toInteger());
    }

    /**
     * @test
     */
    public function compares_sequencing_to_other_versions(): void
    {
        $v1 = Version::fromInteger(1);
        $v2 = Version::fromInteger(2);
        $v3 = Version::fromInteger(3);

        self::assertTrue($v1->isBefore($v2));
        self::assertTrue($v1->isBefore($v3));
        self::assertFalse($v1->isBefore($v1));
        self::assertFalse($v2->isBefore($v1));
        self::assertTrue($v2->isBefore($v3));
        self::assertFalse($v2->isBefore($v2));
        self::assertFalse($v3->isBefore($v1));
        self::assertFalse($v3->isBefore($v2));
        self::assertFalse($v3->isBefore($v3));

        self::assertFalse($v1->isAfter($v2));
        self::assertFalse($v1->isAfter($v3));
        self::assertFalse($v1->isAfter($v1));
        self::assertTrue($v2->isAfter($v1));
        self::assertFalse($v2->isAfter($v3));
        self::assertFalse($v2->isAfter($v2));
        self::assertTrue($v3->isAfter($v1));
        self::assertTrue($v3->isAfter($v2));
        self::assertFalse($v3->isAfter($v3));
    }
}
