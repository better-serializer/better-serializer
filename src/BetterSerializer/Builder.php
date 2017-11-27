<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer;

use BetterSerializer\Cache\Factory;
use BetterSerializer\Cache\FactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\CustomTypeMember as CustomTypeFactory;
use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\ExtensibleChainMemberInterface as ExtensibleTypeFactory;
// @codingStandardsIgnoreStart
use BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\{
    CustomTypeMember as CustomTypeReadProcessorFactory
};
use BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\{
    ExtensibleChainMemberInterface as ExtReadChainMemberInterface
};
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\{
    CustomTypeMember as CustomTypeWriteProcessorFactory
};
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\{
    ExtensibleChainMemberInterface as ExtWriteChainMemberInterface
};
// @codingStandardsIgnoreEnd
use Doctrine\Common\Cache\Cache;
use Pimple\Container;
use Pimple\Exception\UnknownIdentifierException;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class Builder
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @var Factory
     */
    private $cacheFactory;

    /**
     * @var ExtensibleTypeFactory
     */
    private $extTypeFactory;

    /**
     * @var CustomTypeWriteProcessorFactory
     */
    private $extWriteProcessorFactory;

    /**
     * @var CustomTypeReadProcessorFactory
     */
    private $extReadProcessorFactory;

    /**
     * Builder constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container = null)
    {
        if (!$container) {
            $container = $this->initContainer();
        }

        $this->container = $container;
    }

    /**
     * @return Serializer
     * @throws UnknownIdentifierException
     */
    public function createSerializer(): Serializer
    {
        return $this->container->offsetGet(Serializer::class);
    }

    /**
     *
     */
    public function enableApcuCache(): void
    {
        $this->getCacheFactory()->enableApcuCache();
    }

    /**
     * @param string $directory
     * @throws RuntimeException
     */
    public function setCacheDir(string $directory): void
    {
        $this->getCacheFactory()->setCacheDir($directory);
    }

    /**
     *
     */
    public function clearCache(): void
    {
        $this->container[Cache::class]->deleteAll();
    }

    /**
     * @param string $extensionClass
     * @throws RuntimeException
     */
    public function addExtension(string $extensionClass): void
    {
        $customTypeFactory = $this->getCustomTypeFactory();
        $customTypeFactory->addCustomTypeHandlerClass($extensionClass);
        $extWriteProcessorFactory = $this->getExtWriteProcessorFactory();
        $extWriteProcessorFactory->addCustomHandlerClass($extensionClass);
        $extReadProcessorFactory = $this->getExtReadProcessorFactory();
        $extReadProcessorFactory->addCustomHandlerClass($extensionClass);
    }

    /**
     * @return Container
     */
    private function initContainer(): Container
    {
        return require dirname(__DIR__) . '/../config/di.pimple.php';
    }

    /**
     * @return FactoryInterface
     */
    private function getCacheFactory(): FactoryInterface
    {
        if (!$this->cacheFactory) {
            $this->cacheFactory = $this->container[Factory::class];
        }

        return $this->cacheFactory;
    }

    /**
     * @return ExtensibleTypeFactory
     */
    private function getCustomTypeFactory(): ExtensibleTypeFactory
    {
        if ($this->extTypeFactory === null) {
            $this->extTypeFactory = $this->container[CustomTypeFactory::class];
        }

        return $this->extTypeFactory;
    }

    /**
     * @return ExtWriteChainMemberInterface
     */
    private function getExtWriteProcessorFactory(): ExtWriteChainMemberInterface
    {
        if ($this->extWriteProcessorFactory === null) {
            $this->extWriteProcessorFactory = $this->container[CustomTypeWriteProcessorFactory::class];
        }

        return $this->extWriteProcessorFactory;
    }

    /**
     * @return ExtReadChainMemberInterface
     */
    private function getExtReadProcessorFactory(): ExtReadChainMemberInterface
    {
        if ($this->extReadProcessorFactory === null) {
            $this->extReadProcessorFactory = $this->container[CustomTypeReadProcessorFactory::class];
        }

        return $this->extReadProcessorFactory;
    }
}
