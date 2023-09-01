<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel();
    $rectorConfig->paths([__DIR__.'/src', __DIR__.'/tests']);
    $rectorConfig->phpVersion(PhpVersion::PHP_82);
    $rectorConfig->phpstanConfig(__DIR__.'/phpstan-rector.neon');
    $rectorConfig->cacheClass(FileCacheStorage::class);
    $rectorConfig->cacheDirectory(__DIR__.'/tmp/rector');

    $rectorConfig->skip([
        FirstClassCallableRector::class, // Creates too many issues with Symfony container configuration.
        NullToStrictStringFuncCallArgRector::class,
    ]);

    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        LevelSetList::UP_TO_PHP_82,
        PHPUnitSetList::PHPUNIT_91,
    ]);
};
