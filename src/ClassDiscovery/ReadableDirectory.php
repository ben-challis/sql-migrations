<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\ClassDiscovery;

final readonly class ReadableDirectory
{
    private string $directory;

    private function __construct(string $directory)
    {
        \assert(\is_dir($directory));
        \assert(\is_readable($directory));
        \assert($directory[\strlen($directory) - 1] !== \DIRECTORY_SEPARATOR);

        $realPath = \realpath($directory);
        \assert(\is_string($realPath));
        $this->directory = $realPath;
    }

    public static function fromString(string $directory): self
    {
        return new self($directory);
    }

    public function toString(): string
    {
        return $this->directory;
    }

    public function subDirectory(string $path): ReadableDirectory
    {
        return new self($this->directory.\DIRECTORY_SEPARATOR.$path);
    }

    public function file(string $path): ReadableFile
    {
        return ReadableFile::fromString($this->directory.\DIRECTORY_SEPARATOR.$path);
    }
}
