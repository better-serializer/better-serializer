<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer;

use BetterSerializer\Cache\Factory;
use BetterSerializer\Cache\FactoryInterface;
use BetterSerializer\Common\NamingStrategy;
use BetterSerializer\Common\NamingStrategyInterface;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\CamelCaseTranslator;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\IdenticalTranslator;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\SnakeCaseTranslator;
use BetterSerializer\Dto\Car;
use BetterSerializer\Extension\Registry\RegistryInterface;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ChainCache;
use PHPUnit\Framework\TestCase;
use Pimple\Container;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.StaticAccess)
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
            ->method('enableFileSystemCache')
            ->with($cacheDir);

        $cache = $this->createMock(ChainCache::class);
        $cache->expects(self::once())
            ->method('deleteAll');

        $container[Factory::class] = $cacheFactory;
        $container[Cache::class] = $cache;

        $builder = new Builder($container);
        $builder->enableFilesystemCache($cacheDir);
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
        $builder->enableFilesystemCache(__DIR__ . '/xxxzzz');
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

        $extensionRegistry = $this->createMock(RegistryInterface::class);
        $extensionRegistry->expects(self::once())
            ->method('registerExtension')
            ->with($extensionClass);

        $container[RegistryInterface::class] = $extensionRegistry;
        $container['InternalExtensions'] = [];

        $builder = new Builder($container);
        $builder->addExtension($extensionClass);

        $builder->createSerializer();
        // test that extensions are registered only once
        $builder->createSerializer();
    }

    /**
     * @param NamingStrategyInterface $namingStrategy
     * @param string $nsTranslator
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Pimple\Exception\UnknownIdentifierException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @dataProvider namingStrategies
     */
    public function testSetNamingStrategy(NamingStrategyInterface $namingStrategy, string $nsTranslator): void
    {
        $container = require dirname(__DIR__, 2) . '/config/di.pimple.php';
        $builder = new Builder($container);

        $builder->setNamingStrategy($namingStrategy);
        $builder->createSerializer();

        self::assertSame($nsTranslator, $container['NamingStrategyTranslator']);
    }

    /**
     * @return array
     */
    public function namingStrategies(): array
    {
        return [
            [NamingStrategy::IDENTITY(), IdenticalTranslator::class],
            [NamingStrategy::CAMEL_CASE(), CamelCaseTranslator::class],
            [NamingStrategy::SNAKE_CASE(), SnakeCaseTranslator::class],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /Unknown naming strategy: [a-zA-Z0-9\/_\-]+/
     */
    public function testSetNamingStrategyThrowsException(): void
    {
        $container = require dirname(__DIR__, 2) . '/config/di.pimple.php';

        $namingStrategy = $this->createMock(NamingStrategyInterface::class);
        $namingStrategy->expects(self::once())
            ->method('getValue')
            ->willReturn('unknown');

        $builder = new Builder($container);
        $builder->setNamingStrategy($namingStrategy);
    }
}
