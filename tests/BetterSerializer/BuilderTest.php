<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer;

use BetterSerializer\Cache\Factory;
use BetterSerializer\Cache\FactoryInterface;
use BetterSerializer\Dto\Car;
use BetterSerializer\Extension\Registry\ExtensionRegistryInterface;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ChainCache;
use PHPUnit\Framework\TestCase;
use Pimple\Container;
use RuntimeException;

/**
 * Class BuilderTest
 * @author mfris
 * @package BetterSerializer
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BuilderTest extends TestCase
{

    /**
     *
     */
    public function testBuild(): void
    {
        $builder = new Builder();
        $serializer = $builder->createSerializer();

        self::assertInstanceOf(Serializer::class, $serializer);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testBuildWithFileCacheAndClearCache(): void
    {
        $cacheDir = dirname(__DIR__, 2) . '/cache/better-serializer';
        $container = require dirname(__DIR__, 2) . '/config/di.pimple.php';

        $cacheFactory = $this->createMock(FactoryInterface::class);
        $cacheFactory->expects(self::once())
            ->method('setCacheDir')
            ->with($cacheDir);

        $cache = $this->createMock(ChainCache::class);
        $cache->expects(self::once())
            ->method('deleteAll');

        $container[Factory::class] = $cacheFactory;
        $container[Cache::class] = $cache;

        $builder = new Builder($container);
        $builder->setCacheDir($cacheDir);
        $builder->clearCache();
        $serializer = $builder->createSerializer();

        self::assertInstanceOf(Serializer::class, $serializer);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Invalid directory: [a-zA-Z0-9\/_\-]+/
     */
    public function testSetCacheDirThrows(): void
    {
        $builder = new Builder();
        $builder->setCacheDir(__DIR__ . '/xxxzzz');
    }

    /**
     *
     */
    public function testBuildWithApcuCache(): void
    {
        $container = require dirname(__DIR__, 2) . '/config/di.pimple.php';

        $cacheFactory = $this->createMock(FactoryInterface::class);
        $cacheFactory->expects(self::once())
            ->method('enableApcuCache');

        $cache = $this->createMock(ChainCache::class);
        $cache->expects(self::once())
            ->method('deleteAll');

        $container[Factory::class] = $cacheFactory;
        $container[Cache::class] = $cache;

        $builder = new Builder($container);
        $builder->enableApcuCache();
        $builder->clearCache();
        $serializer = $builder->createSerializer();

        self::assertInstanceOf(Serializer::class, $serializer);
    }

    /**
     *
     */
    public function testAddExtensionWillIncludeItToCustomTypeFactories(): void
    {
        $extensionClass = Car::class;

        $container = require dirname(__DIR__, 2) . '/config/di.pimple.php';

        $extensionRegistry = $this->createMock(ExtensionRegistryInterface::class);
        $extensionRegistry->expects(self::once())
            ->method('registerExtension')
            ->with($extensionClass);

        $container[ExtensionRegistryInterface::class] = $extensionRegistry;
        $container['InternalExtensions'] = [];

        $builder = new Builder($container);
        $builder->addExtension($extensionClass);
    }
}
