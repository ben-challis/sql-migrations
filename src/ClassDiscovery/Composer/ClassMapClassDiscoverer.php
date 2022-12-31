<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\ClassDiscovery\Composer;

use BenChallis\SqlMigrations\ClassDiscovery\ClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;

final class ClassMapClassDiscoverer implements ClassDiscoverer
{
    /**
     * @param array<class-string, non-empty-string> $config
     */
    public function __construct(private readonly array $config)
    {
    }

    public function discover(PhpNamespace $namespace): iterable
    {
        foreach (\array_keys($this->config) as $fqcn) {
            if ($namespace->contains($fqcn)) {
                yield $fqcn;
            }
        }
    }
}
