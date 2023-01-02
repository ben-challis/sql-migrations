<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\VersionCouldNotBeExtracted;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\RevisionNotMatchingExpectedClassNamingConvention;

/**
 * @covers \BenChallis\SqlMigrations\Migration\VersionCouldNotBeExtracted
 */
final class VersionCouldNotBeExtractedTest extends TestCase
{
    /**
     * @test
     */
    public function can_provide_a_detail(): void
    {
        $expectedMessage = 'Could not extract a version from revision class "Tests\Fixtures\BenChallis\SqlMigrations\Revision\RevisionNotMatchingExpectedClassNamingConvention". Does not match convention.';

        $e = VersionCouldNotBeExtracted::fromRevisionClass(
            RevisionNotMatchingExpectedClassNamingConvention::class,
            'Does not match convention.',
        );

        self::assertSame($expectedMessage, $e->getMessage());

        $e = VersionCouldNotBeExtracted::fromRevision(
            new RevisionNotMatchingExpectedClassNamingConvention(),
            'Does not match convention.',
        );

        self::assertSame($expectedMessage, $e->getMessage());
    }

    /**
     * @test
     */
    public function providing_a_detail_is_not_required(): void
    {
        $expectedMessage = 'Could not extract a version from revision class "Tests\Fixtures\BenChallis\SqlMigrations\Revision\RevisionNotMatchingExpectedClassNamingConvention".';

        $e = VersionCouldNotBeExtracted::fromRevisionClass(RevisionNotMatchingExpectedClassNamingConvention::class);

        self::assertSame($expectedMessage, $e->getMessage());

        $e = VersionCouldNotBeExtracted::fromRevision(new RevisionNotMatchingExpectedClassNamingConvention());

        self::assertSame($expectedMessage, $e->getMessage());
    }
}
