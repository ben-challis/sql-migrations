<?php

declare(strict_types=1);

namespace Tests\Helper\BenChallis\SqlMigrations;

use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use BenChallis\SqlMigrations\Migration\Migration;
use BenChallis\SqlMigrations\Migration\Revision\Revision;
use BenChallis\SqlMigrations\Migration\Version;
use PHPUnit\Framework\Assert;

final class MigrationAsserter
{
    private function __construct(private readonly Migration $migration)
    {
    }

    public static function assertThat(Migration $migration): self
    {
        return new self($migration);
    }

    public function isInState(State $state): self
    {
        Assert::assertSame($state, $this->migration->metadata->state);

        return $this;
    }

    public function hasMetadata(Metadata $metadata): self
    {
        Assert::assertTrue($metadata->equals($this->migration->metadata));

        return $this;
    }

    public function hasVersion(Version $version): self
    {
        Assert::assertTrue($version->equals($this->migration->metadata->version));

        return $this;
    }

    public function hasRevision(Revision $revision): self
    {
        Assert::assertSame($revision, $this->migration->revision);

        return $this;
    }

    /**
     * @param class-string<Revision> $revisionClass
     */
    public function hasRevisionClass(string $revisionClass): self
    {
        Assert::assertInstanceOf($revisionClass, $this->migration->revision);

        return $this;
    }
}
