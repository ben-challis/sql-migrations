<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration\Revision;

use BenChallis\SqlMigrations\Migration\Revision\CannotInstantiateRevision;
use BenChallis\SqlMigrations\Migration\Revision\SimpleRevisionFactory;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\Revision20181121094934CreateATable;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\RevisionWithConstructorHavingParameters;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\RevisionWithPrivateConstructor;

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

    /**
     * @test
     */
    public function throws_if_revision_has_parameters_in_public_constructor(): void
    {
        $this->expectException(CannotInstantiateRevision::class);

        (new SimpleRevisionFactory())->create(RevisionWithConstructorHavingParameters::class);
    }

    /**
     * @test
     */
    public function throws_if_revision_does_not_have_public_constructor(): void
    {
        $this->expectException(CannotInstantiateRevision::class);

        (new SimpleRevisionFactory())->create(RevisionWithPrivateConstructor::class);
    }
}
