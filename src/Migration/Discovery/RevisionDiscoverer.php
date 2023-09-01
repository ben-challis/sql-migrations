<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Discovery;

use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use BenChallis\SqlMigrations\Migration\Revision\Revision;
use BenChallis\SqlMigrations\Migration\Revision\RevisionFactory;

/**
 * Discovers {@see Revision}s via a {@see RevisionClassDiscoverer} to discover classes, and a {@see RevisionFactory}
 * to instantiate them.
 */
final readonly class RevisionDiscoverer
{
    public function __construct(
        private RevisionClassDiscoverer $classDiscoverer,
        private RevisionFactory $factory,
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
