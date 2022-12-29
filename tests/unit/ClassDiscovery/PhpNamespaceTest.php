<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery;

use Amp\PHPUnit\AsyncTestCase;
use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use Tests\Fixtures\BenChallis\SqlMigrations\ClassDiscovery as FixtureRoot;

/**
 * @covers \BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace
 */
final class PhpNamespaceTest extends AsyncTestCase
{
    /**
     * @test
     */
    public function it_exposes_the_namespace_as_a_string(): void
    {
        $namespace = PhpNamespace::fromString('Tests\\Foo');

        self::assertSame('Tests\\Foo', $namespace->toString());
    }

    /**
     * @test
     */
    public function it_equals_another_namespace_with_the_same_value(): void
    {
        $namespace = PhpNamespace::fromString('Tests\\Foo');
        $namespace2 = PhpNamespace::fromString('Tests\\Foo');

        self::assertTrue($namespace->equals($namespace2));
        self::assertTrue($namespace2->equals($namespace));

        $namespace = PhpNamespace::fromString('Tests\\Bar');
        $namespace2 = PhpNamespace::fromString('Tests\\Foo');

        self::assertFalse($namespace->equals($namespace2));
        self::assertFalse($namespace2->equals($namespace));
    }

    /**
     * @return iterable<array{PhpNamespace, PhpNamespace, bool}>
     */
    public function exampleNamespaceSubLevelComparisons(): iterable
    {
        yield [
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            PhpNamespace::fromString('Tests'),
            true,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Foo\\Bar\\Baz'),
            PhpNamespace::fromString('Tests\\Foo'),
            true,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            PhpNamespace::fromString('Tests\\Foo'),
            true,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Fo\\Bar\\Baz'),
            PhpNamespace::fromString('Tests\\Foo'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Fo'),
            PhpNamespace::fromString('Tests\\Foo'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Tests'),
            PhpNamespace::fromString('Tests\\Foo'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            PhpNamespace::fromString('Tests\\Fo'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            PhpNamespace::fromString('Tests\\F'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            PhpNamespace::fromString('Tests\\Bar'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            PhpNamespace::fromString('Tests\\Bar\\Baz'),
            false,
        ];
    }

    /**
     * @test
     * @dataProvider exampleNamespaceSubLevelComparisons
     */
    public function it_can_inform_if_it_is_a_sub_level_of_another_namespace(
        PhpNamespace $namespace,
        PhpNamespace $candidate,
        bool $expectedResult,
    ): void {
        self::assertSame($expectedResult, $namespace->isSubLevelOf($candidate));
    }

    /**
     * @return iterable<array{PhpNamespace, PhpNamespace, bool}>
     */
    public function exampleNamespaceParentLevelComparisons(): iterable
    {
        yield [
            PhpNamespace::fromString('Tests'),
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            true,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Foo'),
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            true,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Foo\\Bar\\Baz'),
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Foo'),
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Tests\\F'),
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Fo'),
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            false,
        ];
        yield [
            PhpNamespace::fromString('Tests\\Bar'),
            PhpNamespace::fromString('Tests\\Foo\\Bar'),
            false,
        ];
    }

    /**
     * @test
     * @dataProvider exampleNamespaceParentLevelComparisons
     */
    public function it_can_inform_if_it_is_a_parent_level_of_another_namespace(
        PhpNamespace $namespace,
        PhpNamespace $candidate,
        bool $expectedResult,
    ): void {
        self::assertSame($expectedResult, $namespace->isParentLevelOf($candidate));
    }

    /**
     * @return iterable<array{PhpNamespace, class-string, bool}>
     */
    public function exampleNamespaceContainsContainsChecks(): iterable
    {
        $namespace = PhpNamespace::fromString('Tests\Fixtures\BenChallis\SqlMigrations\ClassDiscovery');
        yield [
            $namespace->subLevel('Foo'),
            FixtureRoot\Foo\Bar::class,
            true,
        ];
        yield [
            $namespace,
            FixtureRoot\Foo\Bar\Baz::class,
            true,
        ];
        yield [
            $namespace->subLevel('Foo'),
            FixtureRoot\Foo\Bar\Baz::class,
            true,
        ];
        yield [
            $namespace,
            FixtureRoot\Foo::class,
            true,
        ];
        yield [
            $namespace,
            FixtureRoot\Foo\Bar::class,
            true,
        ];
        yield [
            $namespace->subLevel('Foo'),
            FixtureRoot\Foo::class,
            false,
        ];
        yield [
            $namespace->subLevel('Foo'),
            FixtureRoot\Fo\Bar::class,
            false,
        ];
        yield [
            $namespace->subLevel('Foo'),
            FixtureRoot\F\Bar::class,
            false,
        ];
        yield [
            $namespace->subLevel('Foo'),
            FixtureRoot\Bar::class,
            false,
        ];
        yield [
            $namespace->subLevel('Bar'),
            FixtureRoot\Foo::class,
            false,
        ];
        yield [
            $namespace,
            \stdClass::class,
            false,
        ];
    }

    /**
     * @test
     * @dataProvider exampleNamespaceContainsContainsChecks
     *
     * @param class-string $fqcn
     */
    public function it_can_inform_if_a_fqcn_is_within_it(
        PhpNamespace $namespace,
        string $fqcn,
        bool $expectedResult,
    ): void {
        self::assertSame(
            $expectedResult,
            $namespace->contains($fqcn),
            \sprintf(
                'Namespace %s %s %s.',
                $namespace->toString(),
                $expectedResult ? 'contained' : 'did not contain',
                $fqcn,
            ),
        );
    }
}
