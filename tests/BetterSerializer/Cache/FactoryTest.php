<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Cache;

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ChainCache;
use Doctrine\Common\Cache\FilesystemCache;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;

/**
 *
 */
class FactoryTest extends TestCase
{

    /**
     *
     */
    public function testApcu(): void
    {
        $factory = new Factory();
        $factory->enableApcuCache();

        $cache = $factory->getCache();

        self::assertInstanceOf(ChainCache::class, $cache);

        $reflClass = new ReflectionClass($cache);
        $cacheProvidersProp = $reflClass->getProperty('cacheProviders');
        $cacheProvidersProp->setAccessible(true);

        $cacheProviders = $cacheProvidersProp->getValue($cache);

        self::assertInternalType('array', $cacheProviders);
        self::assertCount(2, $cacheProviders);
        self::assertInstanceOf(ArrayCache::class, $cacheProviders[0]);
        self::assertInstanceOf(ApcuCache::class, $cacheProviders[1]);

        $factory->disableCache();
        $cache = $factory->getCache();
        $cacheProviders = $cacheProvidersProp->getValue($cache);

        self::assertInternalType('array', $cacheProviders);
        self::assertCount(1, $cacheProviders);
        self::assertInstanceOf(ArrayCache::class, $cacheProviders[0]);
    }

    /**
     *
     */
    public function testFileSystem(): void
    {
        $factory = new Factory();
        $factory->enableFileSystemCache(__DIR__);

        $cache = $factory->getCache();

        self::assertInstanceOf(ChainCache::class, $cache);

        $reflClass = new ReflectionClass($cache);
        $cacheProvidersProp = $reflClass->getProperty('cacheProviders');
        $cacheProvidersProp->setAccessible(true);

        $cacheProviders = $cacheProvidersProp->getValue($cache);

        self::assertInternalType('array', $cacheProviders);
        self::assertCount(2, $cacheProviders);
        self::assertInstanceOf(ArrayCache::class, $cacheProviders[0]);
        self::assertInstanceOf(FilesystemCache::class, $cacheProviders[1]);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testFileSystemThrowsExceptionOnInvalidCacheDir(): void
    {
        $factory = new Factory();
        $factory->enableFileSystemCache(__FILE__);
    }
}
