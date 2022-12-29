<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery;

use Amp\PHPUnit\AsyncTestCase;
use BenChallis\SqlMigrations\ClassDiscovery\ClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\DeDuplicatingClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use Tests\Helper\BenChallis\SqlMigrations\IterableHelper;
use Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery\Fixtures\Baz;
use Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery\Fixtures\Foo;

/**
 * @covers \BenChallis\SqlMigrations\ClassDiscovery\DeDuplicatingClassDiscoverer
 */
final class DeDuplicatingClassDiscovererTest extends AsyncTestCase
{
    /**
     * @test
     */
    public function it_de_duplicates_the_discovered_of_a_delegate(): void
    {
        $delegate = $this->createMock(ClassDiscoverer::class);
        $delegate
            ->expects(self::once())
            ->method('discover')
            ->willReturnCallback(
                static function (): \Generator {
                    yield Foo::class;
                    yield Foo::class;
                    yield Baz::class;
                    yield Foo::class;
                    yield Baz::class;
                },
            );

        $discoverer = new DeDuplicatingClassDiscoverer($delegate);

        $results = IterableHelper::toList($discoverer->discover(PhpNamespace::fromString('Tests')));

        self::assertSame([Foo::class, Baz::class], $results);
    }
}
