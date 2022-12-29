<?php

declare(strict_types=1);

namespace BenChallis\SqlMigrations\Migration;

use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use BenChallis\SqlMigrations\Migration\Revision\Revision;

final class Migration
{
    public function __construct(public readonly Revision $revision, public readonly Metadata $metadata)
    {
    }

    public static function with(Revision $revision, Metadata $metadata): self
    {
        return new self($revision, $metadata);
    }

    public function withState(State $state): Migration
    {
        return new self($this->revision, $this->metadata->withState($state));
    }

    public function equals(self $other): bool
    {
        // todo is instanceof check here enough? maybe a version check is sufficient instead of this method altogether?
        return $this->revision instanceof $other->revision
            && $this->metadata->equals($other->metadata);
    }
}
