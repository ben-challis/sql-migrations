<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery;

use Amp\PHPUnit\AsyncTestCase;
use BenChallis\SqlMigrations\ClassDiscovery\DelegateClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use Tests\Helper\BenChallis\SqlMigrations\IterableHelper;
use Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery\Fixtures\Bar;
use Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery\Fixtures\Baz;
use Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery\Fixtures\Foo;

/**
 * @covers \BenChallis\SqlMigrations\ClassDiscovery\DelegateClassDiscoverer
 */
final class DelegateClassDiscovererTest extends AsyncTestCase
{
    /**
     * @test
     */
    public function it_discovers_sequentially_from_delegates(): void
    {
        $delegate = new FixedListClassDiscoverer(Foo::class, Baz::class);
        $delegate2 = new FixedListClassDiscoverer(Bar::class);
        $discoverer = new DelegateClassDiscoverer($delegate, $delegate2);

        $results = IterableHelper::toList($discoverer->discover(PhpNamespace::fromString('Tests')));

        self::assertSame([Foo::class, Baz::class, Bar::class], $results);
    }
}
