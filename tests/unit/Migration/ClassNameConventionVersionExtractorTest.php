<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Version;
use BenChallis\SqlMigrations\Migration\ClassNameConventionVersionExtractor;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\Revision20181121094934CreateATable;
use Tests\Fixtures\BenChallis\SqlMigrations\RevisionFixtures;

/**
 * @covers \BenChallis\SqlMigrations\Migration\ClassNameConventionVersionExtractor
 */
final class ClassNameConventionVersionExtractorTest extends TestCase
{
    /**
     * @test
     */
    public function extracts_a_version_from_a_class_name(): void
    {
        $extractor = new ClassNameConventionVersionExtractor();
        $expected = Version::fromInteger(2018_11_21_09_49_34);

        self::assertTrue($expected->equals($extractor::fromClass(Revision20181121094934CreateATable::class)));
        self::assertTrue($expected->equals($extractor->fromInstance(new Revision20181121094934CreateATable())));
    }

    /**
     * @test
     */
    public function throws_if_class_name_doesnt_conform_to_naming_convention_by_class(): void
    {
        $this->expectException(\RuntimeException::class);

        ClassNameConventionVersionExtractor::fromClass(RevisionFixtures::notMatchingExpectedNamingConvention()::class);
    }

    /**
     * @test
     */
    public function throws_if_class_name_doesnt_conform_to_naming_convention_by_instance(): void
    {
        $this->expectException(\RuntimeException::class);

        (new ClassNameConventionVersionExtractor())->fromInstance(RevisionFixtures::notMatchingExpectedNamingConvention());
    }
}
