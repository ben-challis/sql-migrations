<?php

declare(strict_types=1);

namespace Tests\Integration\BenChallis\SqlMigrations\Migration\Metadata\MySql;

use Amp\Mysql;
use Amp\Mysql\MysqlConfig;
use Amp\Mysql\MysqlResult;
use Amp\Mysql\MysqlStatement;
use Amp\Sql\Executor;
use Amp\Sql\SqlException;
use BenChallis\SqlMigrations\Migration\Metadata\MySql\MySqlSchemaManager;
use BenChallis\SqlMigrations\Migration\Metadata\SchemaManager;
use Tests\Spec\BenChallis\SqlMigrations\Metadata\SchemaManagerSpec;

/**
 * @extends SchemaManagerSpec<MysqlResult, MysqlStatement>
 * @group MySQL
 */
final class MySqlSchemaManagerTest extends SchemaManagerSpec
{
    protected function createSchemaManager(): SchemaManager
    {
        return new MySqlSchemaManager('tests');
    }

    protected function createExecutor(): Executor
    {
        return (new Mysql\SocketMysqlConnector())
            ->connect(
                MysqlConfig::fromAuthority('127.0.0.1', 'root', '', 'tests'),
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
