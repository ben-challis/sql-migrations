<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\Migration\Revision;

use BenChallis\SqlMigrations\Migration\Revision\CannotInstantiateRevision;
use BenChallis\SqlMigrations\Migration\Revision\ServiceLocatorRevisionFactory;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\Revision20181121094934CreateATable;
use Tests\Fixtures\BenChallis\SqlMigrations\Revision\Revision20181222101234UpdateATable;
use Tests\Helper\BenChallis\SqlMigrations\SimpleServiceLocator;

/**
 * @covers \BenChallis\SqlMigrations\Migration\Revision\ServiceLocatorRevisionFactory
 */
final class ServiceLocatorRevisionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function can_obtain_revisions_from_a_service_locator(): void
    {
        $revision = new Revision20181121094934CreateATable();
        $locator = new SimpleServiceLocator(
            [
                $revision::class => $revision,
            ],
        );

        $factory = new ServiceLocatorRevisionFactory($locator);

        self::assertSame($revision, $factory->create($revision::class));
    }

    /**
     * @test
     */
    public function throws_if_locator_does_not_have_service(): void
    {
        $this->expectException(CannotInstantiateRevision::class);

        $locator = new SimpleServiceLocator([]);

        (new ServiceLocatorRevisionFactory($locator))->create(Revision20181121094934CreateATable::class);
    }

    /**
     * @test
     */
    public function throws_if_returned_instance_type_from_locator_differs_to_expected(): void
    {
        $this->expectException(CannotInstantiateRevision::class);

        $locator = new SimpleServiceLocator(
            [
                Revision20181121094934CreateATable::class => new Revision20181222101234UpdateATable(),
            ],
        );

        (new ServiceLocatorRevisionFactory($locator))->create(Revision20181121094934CreateATable::class);
    }
}
