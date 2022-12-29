<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\ClassDiscovery\Psr4;

use Amp\File;
use BenChallis\SqlMigrations\ClassDiscovery\ClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use BenChallis\SqlMigrations\ClassDiscovery\ReadableDirectory;
use Symfony\Component\Finder\Finder;

final class Psr4NamespacePrefixClassDiscoverer implements ClassDiscoverer
{
    /**
     * @var list<ReadableDirectory>
     */
    private readonly array $directories;

    public function __construct(private readonly PhpNamespace $namespacePrefix, ReadableDirectory ...$directories)
    {
        $this->directories = \array_values($directories);
    }

    public function discover(PhpNamespace $namespace): iterable
    {
        if (!$namespace->isSubLevelOf($this->namespacePrefix) && !$namespace->equals($this->namespacePrefix)) {
            return;
        }

        foreach ($this->directories as $directory) {
            if (!$namespace->equals($this->namespacePrefix)) {
                $directoryPath = $directory->toString()
                    .\DIRECTORY_SEPARATOR
                    .\str_replace(
                        '\\',
                        \DIRECTORY_SEPARATOR,
                        \substr(
                            $namespace->toString(),
                            \strlen($this->namespacePrefix->toString()) + 1,
                        ),
                    );

                if (!File\isDirectory($directoryPath)) {
                    return;
                }

                $searchDirectory = ReadableDirectory::fromString($directoryPath);
            } else {
                $searchDirectory = $directory;
            }

            // todo use amphp/file
            $finder = Finder::create()->in($searchDirectory->toString())->files()->name('*.php');

            foreach ($finder->getIterator() as $file) {
                $realPath = $file->getRealPath();
                \assert(\is_string($realPath));
                $candidate = \substr( // Removes .php
                    \str_replace( // Directory separators to namespace level delimiters.
                        \DIRECTORY_SEPARATOR,
                        '\\',
                        $this->namespacePrefix->toString().\substr(
                            $realPath,
                            \strlen($directory->toString()),
                        ), // Hybrid of namespace and file path on the end for fixing up.
                    ),
                    0,
                    -4,
                );

                // As we're going off file names, may have non-class defining files.
                if (\class_exists($candidate)) {
                    yield $candidate;
                }
            }
        }
    }
}
