<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Discovery;

use BenChallis\SqlMigrations\ClassDiscovery\ClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use BenChallis\SqlMigrations\Migration\Revision\Revision;

/**
 * Discovers {@see Revision} implementing classes from classes that are yielded from another {@see ClassDiscoverer}.
 */
final readonly class RevisionClassDiscoverer implements ClassDiscoverer
{
    public function __construct(private ClassDiscoverer $delegate)
    {
    }

    /**
     * @return iterable<class-string<Revision>>
     */
    public function discover(PhpNamespace $namespace): iterable
    {
        foreach ($this->delegate->discover($namespace) as $className) {
            $reflection = (new \ReflectionClass($className));

            if (!$reflection->isAbstract() && $reflection->implementsInterface(Revision::class)) {
                /** @var class-string<Revision> $className */
                yield $className;
            }
        }
    }
}
