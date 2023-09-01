<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\ClassDiscovery;

final readonly class DeDuplicatingClassDiscoverer implements ClassDiscoverer
{
    public function __construct(private ClassDiscoverer $delegate)
    {
    }

    public function discover(PhpNamespace $namespace): iterable
    {
        $seen = [];

        foreach ($this->delegate->discover($namespace) as $className) {
            if (!($seen[$className] ?? false)) {
                $seen[$className] = true;
                yield $className;
            }
        }
    }
}
