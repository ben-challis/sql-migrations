<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration\Revision;

use BenChallis\SqlMigrations\Migration\Revision\SimpleRevisionFactory;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\Revision20181121094934CreateATable;

/**
 * @covers \BenChallis\SqlMigrations\Migration\Revision\SimpleRevisionFactory
 */
final class SimpleRevisionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function directly_instantiates_revisions(): void
    {
        $factory = new SimpleRevisionFactory();

        $instance = $factory->create(Revision20181121094934CreateATable::class);

        self::assertInstanceOf(Revision20181121094934CreateATable::class, $instance); // @phpstan-ignore-line want to verify the behaviour despite types.
    }
}
