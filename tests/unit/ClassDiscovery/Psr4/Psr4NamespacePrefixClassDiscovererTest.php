<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery\Psr4;

use Amp\PHPUnit\AsyncTestCase;
use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use BenChallis\SqlMigrations\ClassDiscovery\Psr4\Psr4NamespacePrefixClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\ReadableDirectory;
use Psl\Iter;
use Tests\Helper\BenChallis\SqlMigrations\IterableHelper;
use Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery\Fixtures\Bar;
use Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery\Fixtures\Baz;
use Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery\Fixtures\Foo;

/**
 * @covers \BenChallis\SqlMigrations\ClassDiscovery\Psr4\Psr4NamespacePrefixClassDiscoverer
 */
final class Psr4NamespacePrefixClassDiscovererTest extends AsyncTestCase
{
    /**
     * @test
     */
    public function it_discovers_from_a_psr4_prefix(): void
    {
        $namespacePrefix = PhpNamespace::fromString('Tests\\Unit\\BenChallis\\SqlMigrations\\ClassDiscovery\\Fixtures');
        $discoverer = new Psr4NamespacePrefixClassDiscoverer(
            $namespacePrefix,
            ReadableDirectory::fromString(__DIR__.'/../Fixtures'),
        );

        $results = IterableHelper::toList($discoverer->discover($namespacePrefix));

        self::assertCount(4, $results);
        self::assertContains(Foo::class, $results);
        self::assertContains(Bar::class, $results);
        self::assertContains(Baz::class, $results);
        self::assertContains(Bar\SubBar::class, $results);

        $results = \iterator_to_array(
            Iter\to_iterator($discoverer->discover($namespacePrefix->subLevel('Bar'))),
        );

        self::assertSame([Bar\SubBar::class], $results);

        $results = \iterator_to_array(
            Iter\to_iterator($discoverer->discover($namespacePrefix->subLevel('Baz'))),
        );
        self::assertEmpty($results);
        $results = IterableHelper::toList($discoverer->discover(PhpNamespace::fromString('Foo\\Bar')));
        self::assertCount(0, $results);
    }
}
