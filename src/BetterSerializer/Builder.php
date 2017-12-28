<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer;

use BetterSerializer\Cache\Factory;
use BetterSerializer\Cache\FactoryInterface;
use BetterSerializer\Extension\DoctrineCollection;
use BetterSerializer\Extension\Registry\ExtensionRegistryInterface;
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
     * @var ExtensionRegistryInterface
     */
    private $extensionRegistry;

    /**
     * @var string[]
     */
    private static $internalExtensions = [];

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
        $this->registerExtensions();
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
     */
    public function addExtension(string $extensionClass): void
    {
        $this->getExtensionRegistry()->registerExtension($extensionClass);
    }

    /**
     * @return Container
     */
    private function initContainer(): Container
    {
        return require dirname(__DIR__) . '/../config/di.pimple.php';
    }

    /**
     *
     */
    private function registerExtensions(): void
    {
        self::$internalExtensions = $this->container['InternalExtensions'];

        foreach (self::$internalExtensions as $extensionClass) {
            $this->addExtension($extensionClass);
        }
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
     * @return ExtensionRegistryInterface
     */
    private function getExtensionRegistry(): ExtensionRegistryInterface
    {
        if ($this->extensionRegistry === null) {
            $this->extensionRegistry = $this->container[ExtensionRegistryInterface::class];
        }

        return $this->extensionRegistry;
    }
}
