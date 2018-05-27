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
use BetterSerializer\Extension\Registry\RegistryInterface;
use Doctrine\Common\Cache\Cache;
use InvalidArgumentException;
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
     * @var RegistryInterface
     */
    private $extensionRegistry;

    /**
     * @var bool
     */
    private $extensionsRegistered = false;

    /**
     * @var string[]
     */
    private $internalExtensions;

    /**
     * @var string[]
     */
    private static $namingStrategies = [
        NamingStrategy::IDENTITY => IdenticalTranslator::class,
        NamingStrategy::CAMEL_CASE => CamelCaseTranslator::class,
        NamingStrategy::SNAKE_CASE => SnakeCaseTranslator::class,
    ];

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
        $this->internalExtensions = $this->container['InternalExtensions'];
    }

    /**
     * @return Serializer
     * @throws UnknownIdentifierException
     */
    public function createSerializer(): Serializer
    {
        $this->registerExtensions();

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
    public function enableFilesystemCache(string $directory): void
    {
        $this->getCacheFactory()->enableFileSystemCache($directory);
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
        $this->internalExtensions[] = $extensionClass;
    }

    /**
     * @param NamingStrategyInterface $namingStrategy
     * @throws InvalidArgumentException
     */
    public function setNamingStrategy(NamingStrategyInterface $namingStrategy): void
    {
        $nsValue = $namingStrategy->getValue();

        if (!isset(self::$namingStrategies[$nsValue])) {
            throw new InvalidArgumentException(
                sprintf('Unknown naming strategy: %s', $nsValue)
            );
        }

        $this->container['NamingStrategyTranslator'] = self::$namingStrategies[$nsValue];
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
        if ($this->extensionsRegistered) {
            return;
        }

        $registry = $this->getExtensionRegistry();

        foreach ($this->internalExtensions as $extensionClass) {
            $registry->registerExtension($extensionClass);
        }

        $this->extensionsRegistered = true;
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
     * @return RegistryInterface
     */
    private function getExtensionRegistry(): RegistryInterface
    {
        if ($this->extensionRegistry === null) {
            $this->extensionRegistry = $this->container[RegistryInterface::class];
        }

        return $this->extensionRegistry;
    }
}
