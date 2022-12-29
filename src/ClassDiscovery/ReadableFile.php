<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\ClassDiscovery;

final class ReadableFile
{
    private readonly string $filePath;

    private function __construct(string $filePath)
    {
        \assert(\is_file($filePath));
        \assert(\is_readable($filePath));

        $this->filePath = $filePath;
    }

    public static function fromString(string $filePath): self
    {
        return new self($filePath);
    }

    public function toString(): string
    {
        return $this->filePath;
    }
}
