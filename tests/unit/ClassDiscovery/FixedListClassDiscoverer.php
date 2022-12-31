<?php

declare(strict_types=1);

namespace Tests\Unit\BenChallis\SqlMigrations\ClassDiscovery;

use BenChallis\SqlMigrations\ClassDiscovery\ClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;

final class FixedListClassDiscoverer implements ClassDiscoverer
{
    /**
     * @var list<class-string>
     */
    private readonly array $classes;

    /**
     * @param class-string ...$classes
     */
    public function __construct(string ...$classes)
    {
        $this->classes = \array_values($classes);
    }

    public function discover(PhpNamespace $namespace): iterable
    {
        foreach ($this->classes as $class) {
            if ($namespace->contains($class)) {
                yield $class;
            }
        }
    }
}
