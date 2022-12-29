<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration\Revision;

final class IrrevertibleRevision extends \RuntimeException
{
    public function __construct(Revision $revision, string $message = '')
    {
        parent::__construct(
            \sprintf(
                'Cannot revert revision %s.%s',
                $revision::class,
                $message === '' ? '' : (' '.$message),
            ),
        );
    }
}
