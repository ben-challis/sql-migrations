<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Metadata;

use Amp\Sql\Executor;
use Amp\Sql\Result;
use Amp\Sql\Statement;
use BenChallis\SqlMigrations\Migration\Version;
use Psl\Collection\MapInterface;

final class SchemaUpdatingMetadataStore implements MetadataStore
{
    private bool $haveCheckedSchema = false;

    /**
     * @template TResult of Result
     * @template TStatement of Statement
     *
     * @param SchemaManager<TResult, TStatement> $schemaManager
     * @param Executor<TResult, TStatement> $executor
     */
    public function __construct(
        private readonly SchemaManager $schemaManager,
        private readonly Executor $executor,
        private readonly MetadataStore $metadata,
    ) {
    }

    public function save(Metadata $metadata): void
    {
        if (!$this->haveCheckedSchema) {
            $this->schemaManager->updateIfRequired($this->executor);
            $this->haveCheckedSchema = true;
        }

        $this->metadata->save($metadata);
    }

    public function fetch(Version $version): Metadata
    {
        if (!$this->haveCheckedSchema) {
            $this->schemaManager->updateIfRequired($this->executor);
            $this->haveCheckedSchema = true;
        }

        return $this->metadata->fetch($version);
    }

    public function fetchAll(): MapInterface
    {
        if (!$this->haveCheckedSchema) {
            $this->schemaManager->updateIfRequired($this->executor);
            $this->haveCheckedSchema = true;
        }

        return $this->metadata->fetchAll();
    }
}
