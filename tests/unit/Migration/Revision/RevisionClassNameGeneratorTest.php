<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration\Revision;

use BenChallis\SqlMigrations\Migration\Revision\RevisionClassNameGenerator;
use Lendable\Clock\FixedClock;
use Lendable\Clock\SystemClock;
use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;

/**
 * @covers \BenChallis\SqlMigrations\Migration\Revision\RevisionClassNameGenerator
 */
final class RevisionClassNameGeneratorTest extends TestCase
{
    /**
     * @return iterable<array{non-empty-string}>
     */
    public function provideInvalidDescriptions(): iterable
    {
        yield ['-ff'];
        yield ['f-f'];
        yield ['with spaces'];
        yield ['with other characters $$'];
    }

    /**
     * @test
     * @dataProvider provideInvalidDescriptions
     *
     * @param non-empty-string $invalidDescription
     */
    public function description_cannot_contain_characters_outside_alphabet(string $invalidDescription): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $generator = new RevisionClassNameGenerator(new SystemClock());
        $generator->generate($invalidDescription);
    }

    /**
     * @test
     */
    public function will_create_a_class_name_with_the_current_timestamp_and_description(): void
    {
        $clock = new FixedClock(new DateTimeImmutable('2022-10-11 12:32:41'));
        $generator = new RevisionClassNameGenerator($clock);

        $className = $generator->generate('FooBarBaz');

        self::assertSame('Revision20221011123241FooBarBaz', $className);

        $className = $generator->generate('BarBaz');

        self::assertSame('Revision20221011123241BarBaz', $className);
    }
}
