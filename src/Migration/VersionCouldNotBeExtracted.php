<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Revision\Revision;

final class VersionCouldNotBeExtracted extends \RuntimeException
{
    /**
     * @param non-empty-string|null $detail
     */
    private function __construct(string $message, ?string $detail = null)
    {
        parent::__construct($message.($detail === null ? '' : ' '.$detail));
    }

    public static function fromRevision(Revision $revision): self
    {
        return self::fromRevisionClass($revision::class);
    }

    /**
     * @param class-string<Revision> $class
     * @param non-empty-string|null $detail
     */
    public static function fromRevisionClass(string $class, ?string $detail = null): self
    {
        return new self(\sprintf('Could not extract a version from revision class "%s".', $class), $detail);
    }
}
