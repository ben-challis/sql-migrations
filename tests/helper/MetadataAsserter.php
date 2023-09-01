<?php

declare(strict_types=1);

namespace Tests\Helper\BenChallis\SqlMigrations;

use BenChallis\SqlMigrations\Migration\Metadata\Metadata;
use BenChallis\SqlMigrations\Migration\Metadata\State;
use BenChallis\SqlMigrations\Migration\Version;
use PHPUnit\Framework\Assert;

final readonly class MetadataAsserter
{
    private function __construct(private Metadata $metadata)
    {
    }

    public static function assertThat(Metadata $metadata): self
    {
        return new self($metadata);
    }

    public function hasVersion(Version $version): self
    {
        Assert::assertTrue($version->equals($this->metadata->version));

        return $this;
    }

    public function hasState(State $state): self
    {
        Assert::assertSame($state, $this->metadata->state);

        return $this;
    }
}
