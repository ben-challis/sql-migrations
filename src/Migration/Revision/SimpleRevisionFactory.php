<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Revision;

/**
 * Just instantiates the revision, assuming a zero-argument constructor exists.
 */
final class SimpleRevisionFactory implements RevisionFactory
{
    public function create(string $revisionClass): Revision
    {
        $constructor = (new \ReflectionClass($revisionClass))->getConstructor();

        if ($constructor instanceof \ReflectionMethod && ($constructor->getNumberOfParameters() > 0 || !$constructor->isPublic())) {
            throw CannotInstantiateRevision::forClass(
                $revisionClass,
                new \RuntimeException('The class does not have a zero parameter public constructor.'),
            );
        }

        return new $revisionClass();
    }
}
