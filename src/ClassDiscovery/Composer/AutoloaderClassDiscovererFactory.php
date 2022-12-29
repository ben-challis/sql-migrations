<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\ClassDiscovery\Composer;

use Amp\File;
use BenChallis\SqlMigrations\ClassDiscovery\ClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\DeDuplicatingClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\DelegateClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\PhpNamespace;
use BenChallis\SqlMigrations\ClassDiscovery\Psr4\Psr4NamespacePrefixClassDiscoverer;
use BenChallis\SqlMigrations\ClassDiscovery\ReadableDirectory;

final class AutoloaderClassDiscovererFactory
{
    public function fromVendorDirectory(ReadableDirectory $vendorDirectory): ClassDiscoverer
    {
        return new DeDuplicatingClassDiscoverer(
            new DelegateClassDiscoverer(
                $this->createClassMapClassDiscoverer($vendorDirectory),
                ...$this->createPsr4PrefixClassDiscoverers($vendorDirectory),
            ),
        );
    }

    /**
     * @return iterable<ClassDiscoverer>
     */
    private function createPsr4PrefixClassDiscoverers(ReadableDirectory $vendorDirectory): iterable
    {
        $config = require $vendorDirectory->subDirectory('composer')->file('autoload_psr4.php')->toString();
        /** @var array<non-empty-string, array<non-empty-string>> $config */
        foreach ($config as $namespacePrefix => $directories) {
            $namespace = \substr($namespacePrefix, 0, -1);
            \assert($namespace !== '');

            yield new Psr4NamespacePrefixClassDiscoverer(
                PhpNamespace::fromString($namespace),
                ...$this->generateReadableDirectories(...$directories),
            );
        }
    }

    /**
     * @return iterable<ReadableDirectory>
     */
    private function generateReadableDirectories(string ...$directories): iterable
    {
        foreach ($directories as $directory) {
            if (File\isDirectory($directory)) {
                yield ReadableDirectory::fromString($directory);
            }
        }
    }

    private function createClassMapClassDiscoverer(ReadableDirectory $vendorDirectory): ClassDiscoverer
    {
        $config = require $vendorDirectory->subDirectory('composer')->file('autoload_classmap.php')->toString();

        /** @var array<class-string, non-empty-string> $config */

        return new ClassMapClassDiscoverer($config);
    }
}
