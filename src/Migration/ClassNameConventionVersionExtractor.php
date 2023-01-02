<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Revision\Revision;
use Psl\Regex;
use Psl\Type;

final class ClassNameConventionVersionExtractor implements VersionExtractor
{
    private const PATTERN = '~^Revision(\d+).*~';

    /**
     * @param class-string<Revision> $className
     */
    public static function fromClass(string $className): Version
    {
        $match = Regex\first_match(
            (new \ReflectionClass($className))->getShortName(),
            self::PATTERN,
            Type\shape([0 => Type\non_empty_string(), 1 => Type\positive_int()]),
        ) ?? throw new \RuntimeException(
            \sprintf('Pattern %s did not match class name %s.', self::PATTERN, $className),
        );

        \assert($match[1] > 0); // PHPStan doesn't seem to understand fully this must be a positive-int.

        return Version::fromInteger($match[1]);
    }

    public function fromInstance(Revision $revision): Version
    {
        return self::fromClass($revision::class);
    }
}
