<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer;

use BetterSerializer\Cache\Factory;
use BetterSerializer\Cache\FactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\CustomTypeMember as CustomTypeFactory;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\ExtensibleChainMemberInterface as CustomTypeFactoryInterface;
// @codingStandardsIgnoreStart
use BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\{
    CustomTypeMember as ReaderProcessorFactory
};
use BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\{
    ExtensibleChainMemberInterface as ReaderProcessorFactoryInterface
};
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\{
    CustomTypeMember as WriterProcessorFactory
};
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\{
    ExtensibleChainMemberInterface as WriterProcessorFactoryInterface
};
// @codingStandardsIgnoreEnd
use BetterSerializer\Helper\CustomTypeMockFactory;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ChainCache;
use PHPUnit\Framework\TestCase;
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
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testAddExtensionWillIncludeItToCustomTypeFactories(): void
    {
        $extension = CustomTypeMockFactory::createCustomTypeExcensionMock('CustomType');
        $extensionClass = get_class($extension);

        $container = require dirname(__DIR__, 2) . '/config/di.pimple.php';

        $customTypeFactory = $this->createMock(CustomTypeFactoryInterface::class);
        $customTypeFactory->expects(self::once())
            ->method('addCustomTypeHandlerClass')
            ->with($extensionClass);

        $readProcFactory = $this->createMock(ReaderProcessorFactoryInterface::class);
        $readProcFactory->expects(self::once())
            ->method('addCustomHandlerClass')
            ->with($extensionClass);

        $writeProcFactory = $this->createMock(WriterProcessorFactoryInterface::class);
        $writeProcFactory->expects(self::once())
            ->method('addCustomHandlerClass')
            ->with($extensionClass);

        $container[CustomTypeFactory::class] = $customTypeFactory;
        $container[ReaderProcessorFactory::class] = $readProcFactory;
        $container[WriterProcessorFactory::class] = $writeProcFactory;

        $builder = new Builder($container);
        $builder->addExtension($extensionClass);
    }
}
