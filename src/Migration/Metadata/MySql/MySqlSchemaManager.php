<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Metadata\MySql;

use Amp\Mysql\MysqlResult;
use Amp\Mysql\MysqlStatement;
use Amp\Sql\Executor;
use BenChallis\SqlMigrations\Migration\Metadata\SchemaManager;

/**
 * @implements SchemaManager<MysqlResult, MysqlStatement>
 */
final readonly class MySqlSchemaManager implements SchemaManager
{
    /**
     * @param non-empty-string $databaseName
     */
    public function __construct(private string $databaseName)
    {
    }

    public function isUpToDate(Executor $executor): bool
    {
        $result = $executor->execute(
            <<<SQL
                SELECT 1
                FROM information_schema.tables
                WHERE table_schema = :database_name
                AND table_name = '_migrations'
                LIMIT 1
                SQL,
            [
                'database_name' => $this->databaseName,
            ],
        );

        return $result->fetchRow() !== null;
    }

    public function updateIfRequired(Executor $executor): void
    {
        if ($this->isUpToDate($executor)) {
            return;
        }

        $executor->execute(
            <<<SQL
                CREATE TABLE _migrations(
                  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                  version BIGINT(20) NOT NULL,
                  state ENUM('applying', 'applied') NOT NULL,
                  CONSTRAINT pk_id PRIMARY KEY (id),
                  UNIQUE KEY uq_version (version)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin
                SQL
        );
    }
}
