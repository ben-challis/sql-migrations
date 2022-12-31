<?php

declare(strict_types=1);

namespace Tests\Spec\BenChallis\SqlMigrations\Metadata;

use Amp\PHPUnit\AsyncTestCase;
use Amp\Sql\Executor;
use Amp\Sql\Result;
use Amp\Sql\Statement;
use BenChallis\SqlMigrations\Migration\Metadata\SchemaManager;

/**
 * @template TResult of Result
 * @template TStatement of Statement
 */
abstract class SchemaManagerSpec extends AsyncTestCase
{
    /**
     * @return SchemaManager<TResult, TStatement>
     */
    abstract protected function createSchemaManager(): SchemaManager;

    /**
     * @return Executor<TResult, TStatement>
     */
    abstract protected function createExecutor(): Executor;

    /**
     * @test
     */
    final public function indicates_the_schema_is_up_to_date_after_updating(): void
    {
        $manager = $this->createSchemaManager();
        $executor = $this->createExecutor();

        self::assertFalse($manager->isUpToDate($executor));

        $manager->updateIfRequired($executor);

        self::assertTrue($manager->isUpToDate($executor));
    }

    /**
     * @test
     */
    final public function update_if_required_is_idempotent(): void
    {
        $manager = $this->createSchemaManager();
        $executor = $this->createExecutor();

        $manager->updateIfRequired($executor);
        self::assertTrue($manager->isUpToDate($executor));

        $manager->updateIfRequired($executor);
        self::assertTrue($manager->isUpToDate($executor));

        $manager->updateIfRequired($executor);
        self::assertTrue($manager->isUpToDate($executor));
    }
}
