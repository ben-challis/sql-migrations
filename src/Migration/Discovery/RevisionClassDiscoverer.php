<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Discovery;

use BenChallis\SqlMigrations\ClassDiscovery\ClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use BenChallis\SqlMigrations\Migration\Revision\Revision;

final class RevisionClassDiscoverer implements ClassDiscoverer
{
    public function __construct(private readonly ClassDiscoverer $delegate)
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
