<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\ClassDiscovery;

use Psl\Regex;

final readonly class PhpNamespace
{
    /**
     * @param non-empty-string $namespace
     */
    private function __construct(private string $namespace)
    {
        \assert($namespace[\strlen($namespace) - 1] !== '\\');
    }

    /**
     * @param non-empty-string $namespace
     */
    public static function fromString(string $namespace): self
    {
        return new self($namespace);
    }

    public function toString(): string
    {
        return $this->namespace;
    }

    public function equals(PhpNamespace $candidate): bool
    {
        return $this->namespace === $candidate->namespace;
    }

    public function isSubLevelOf(PhpNamespace $candidate): bool
    {
        return \str_starts_with($this->namespace, $candidate->namespace.'\\');
    }

    public function isParentLevelOf(PhpNamespace $candidate): bool
    {
        return \str_starts_with($candidate->namespace, $this->namespace.'\\');
    }

    /**
     * @param class-string $fqcn
     */
    public function contains(string $fqcn): bool
    {
        \assert(Regex\matches($fqcn, '~^[\\a-zA-Z_\x7f-\xff][\\a-zA-Z0-9_\x7f-\xff]*$~'));
        \assert(!\str_contains($fqcn, '\\\\'));
        \assert($fqcn[0] !== '\\' && $fqcn[\strlen($fqcn) - 1] !== '\\');

        $delimiterPosition = \strrpos($fqcn, '\\');

        // Root namespace, cannot contain.
        if ($delimiterPosition === false) {
            return false;
        }

        \assert($delimiterPosition > 0);

        $namespace = self::fromString(\substr($fqcn, 0, $delimiterPosition));

        return $this->equals($namespace) || $this->isParentLevelOf($namespace);
    }

    public function subLevel(string $relativeLevel): self
    {
        return new self($this->namespace.'\\'.$relativeLevel);
    }

    public function parentLevel(): self
    {
        $delimiter = \strrpos($this->namespace, '\\');

        if ($delimiter === false) {
            throw new \LogicException('Cannot get parent of a top level namespace.');
        }

        \assert($delimiter > 0);

        return new self(\substr($this->namespace, 0, $delimiter));
    }
}
