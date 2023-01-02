<?php

declare(strict_types=1);

namespace Tests\Helper\BenChallis\SqlMigrations;

use Psr\Container\NotFoundExceptionInterface;

final class ServiceNotFound extends \RuntimeException implements NotFoundExceptionInterface
{
}
