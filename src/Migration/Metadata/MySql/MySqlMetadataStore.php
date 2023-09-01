<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Metadata\MySql;

use Amp\Mysql\MysqlResult;
use Amp\Mysql\MysqlStatement;
use Amp\Sql\Executor;
use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\MetadataDoesNotExist;
use BenChallis\SqlMigrations\Migration\Metadata\MetadataStore;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use BenChallis\SqlMigrations\Migration\Version;
use Psl\Collection\Map;
use Psl\Collection\MapInterface;
use Psl\Type;

final readonly class MySqlMetadataStore implements MetadataStore
{
    /**
     * @param Executor<MysqlResult, MysqlStatement> $executor
     */
    public function __construct(private Executor $executor)
    {
    }

    public function save(Metadata $metadata): void
    {
        // todo instead load and verify can change state.
        $this->executor->execute(
            <<<SQL
                INSERT INTO _migrations(version, state) VALUES(?, ?) 
                ON DUPLICATE KEY UPDATE state = ?
                SQL,
            [
                $metadata->version->toInteger(),
                $metadata->state->value,
                $metadata->state->value,
            ],
        );
    }

    public function fetch(Version $version): Metadata
    {
        $resultSet = $this->executor->execute(
            <<<SQL
                SELECT state
                FROM _migrations 
                WHERE version = :version
                SQL,
            [
                'version' => $version->toInteger(),
            ],
        );

        $row = $resultSet->fetchRow();

        $type = Type\union(
            Type\null(),
            Type\shape(['state' => Type\backed_enum(State::class)]),
        );

        $value = $type->coerce($row) ?? throw MetadataDoesNotExist::forVersion($version);

        return Metadata::with($version, $value['state']);
    }

    public function fetchAll(): MapInterface
    {
        $result = $this->executor->execute(
            <<<SQL
                SELECT version, state
                FROM _migrations
                SQL
        );

        $type = Type\shape([
            'version' => Type\positive_int(),
            'state' => Type\backed_enum(State::class),
        ]);

        $results = [];

        foreach ($result as $row) {
            $value = $type->coerce($row);
            $metadata = Metadata::with(
                Version::fromInteger($value['version']),
                $value['state'],
            );
            $results[$metadata->version->toInteger()] = $metadata;
        }

        return new Map($results);
    }
}
