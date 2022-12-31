<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\ClassDiscovery;

interface ClassDiscoverer
{
    /**
     * @return iterable<class-string>
     */
    public function discover(PhpNamespace $namespace): iterable;
}
