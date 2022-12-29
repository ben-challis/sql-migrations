<?php

declare(strict_types=1);

namespace Tests\Fixtures\BenChallis\SqlMigrations\Migration\Metadata;

use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\MetadataDoesNotExist;
use BenChallis\SqlMigrations\Migration\Metadata\MetadataStore;
use BenChallis\SqlMigrations\Migration\Version;
use Psl\Collection\Map;
use Psl\Collection\MapInterface;
use Psl\Collection\MutableMap;
use Psl\Collection\MutableMapInterface;

final class InMemoryMetadataStore implements MetadataStore
{
    /**
     * @var MutableMapInterface<int, Metadata>
     */
    private readonly MutableMapInterface $map;

    /**
     * @param MapInterface<int, Metadata> $map
     */
    public function __construct(MapInterface $map)
    {
        $this->map = new MutableMap($map->toArray());
    }

    public function save(Metadata $metadata): void
    {
        $this->map->add($metadata->version->toInteger(), $metadata);
    }

    public function fetch(Version $version): Metadata
    {
        return $this->map->get($version->toInteger())
            ?? throw MetadataDoesNotExist::forVersion($version);
    }

    public function fetchAll(): MapInterface
    {
        return new Map($this->map->toArray());
    }
}
