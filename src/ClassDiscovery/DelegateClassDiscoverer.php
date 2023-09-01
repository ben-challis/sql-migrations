<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\ClassDiscovery;

final readonly class DelegateClassDiscoverer implements ClassDiscoverer
{
    /**
     * @var list<ClassDiscoverer>
     */
    private array $discoverers;

    public function __construct(ClassDiscoverer ...$discoverers)
    {
        $this->discoverers = \array_values($discoverers);
    }

    public function discover(PhpNamespace $namespace): iterable
    {
        foreach ($this->discoverers as $discoverer) {
            yield from $discoverer->discover($namespace);
        }
    }
}
