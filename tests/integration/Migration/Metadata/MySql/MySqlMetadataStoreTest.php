<?php

declare(strict_types=1);

namespace Tests\Integration\BenChallis\SqlMigrations\Migration\Metadata\MySql;

use Amp\Mysql;
use Amp\Mysql\MysqlConfig;
use Amp\Sql\Executor;
use Amp\Sql\SqlException;
use BenChallis\SqlMigrations\Migration\Metadata\MetadataStore;
use BenChallis\SqlMigrations\Migration\Metadata\MySql\MySqlMetadataStore;
use BenChallis\SqlMigrations\Migration\Metadata\MySql\MySqlSchemaManager;
use Tests\Spec\BenChallis\SqlMigrations\Metadata\MetadataStoreSpec;

/**
 * @covers \BenChallis\SqlMigrations\Migration\Metadata\MySql\MySqlMetadataStore
 * @group MySQL
 */
final class MySqlMetadataStoreTest extends MetadataStoreSpec
{
    protected function createStore(): MetadataStore
    {
        $executor = $this->createExecutor();
        $store = new MySqlMetadataStore($executor);
        $schemaManager = new MySqlSchemaManager('tests');
        $schemaManager->updateIfRequired($executor);

        foreach ($this->expectedMetadata() as $metadata) {
            $store->save($metadata);
        }

        return $store;
    }

    /**
     * @return Executor<Mysql\MysqlResult, Mysql\MysqlStatement>
     */
    private function createExecutor(): Executor
    {
        return (new Mysql\SocketMysqlConnector())
            ->connect(
                MysqlConfig::fromAuthority('mysql', 'root', '', 'tests'),
            );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        try {
            $this->createExecutor()->execute('DROP TABLE _migrations');
        } catch (SqlException) {
        }
    }
}
