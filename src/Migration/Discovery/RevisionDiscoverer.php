<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Discovery;

use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use BenChallis\SqlMigrations\Migration\Revision\Revision;
use BenChallis\SqlMigrations\Migration\Revision\RevisionFactory;

final class RevisionDiscoverer
{
    public function __construct(
        private readonly RevisionClassDiscoverer $classDiscoverer,
        private readonly RevisionFactory $factory,
    ) {
    }

    /**
     * @return iterable<Revision>
     */
    public function discover(PhpNamespace ...$namespaces): iterable
    {
        foreach ($namespaces as $namespace) {
            foreach ($this->classDiscoverer->discover($namespace) as $revisionClass) {
                yield $this->factory->create($revisionClass);
            }
        }
    }
}
