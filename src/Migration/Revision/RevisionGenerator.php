<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Revision;

use Amp\File;

final class RevisionGenerator
{
    public function __construct(private readonly RevisionClassNameGenerator $classNameGenerator)
    {
    }

    /**
     * @param non-empty-string $description
     *
     * @return non-empty-string
     */
    public function generate(string $namespace, string $description, string $outputDirectory): string
    {
        $className = $this->classNameGenerator->generate($description);

        File\write(
            $outputDirectory.\DIRECTORY_SEPARATOR.$className.'.php',
            \strtr(
                File\read(__DIR__.'/revision_template.php.template'),
                ['%NAMESPACE%' => $namespace, '%CLASS_NAME%' => $className],
            ),
        );

        return $className;
    }
}
