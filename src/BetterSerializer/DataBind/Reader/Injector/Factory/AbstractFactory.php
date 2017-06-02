<?php
declare(strict_types = 1);

/**
 * Short desc
 *
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Reader\Injector\Factory;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use RuntimeException;

/**
 * Class Factory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Injector
 */
final class AbstractFactory implements AbstractFactoryInterface
{

    /**
     * @const string[]
     */
    private const METADATA2FACTORY_MAPPING = [
        ReflectionPropertyMetaDataInterface::class => ReflectionFactory::class,
    ];

    /**
     * @var FactoryInterface[]
     */
    private static $factories = [];

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return InjectorInterface
     * @throws RuntimeException
     */
    public function newInjector(PropertyMetaDataInterface $metaData): InjectorInterface
    {
        $foundFactoryClass = '';

        foreach (self::METADATA2FACTORY_MAPPING as $metaDataClass => $factoryClass) {
            if ($metaData instanceof $metaDataClass) {
                $foundFactoryClass = $factoryClass;
                break;
            }
        }

        if ($foundFactoryClass === '') {
            throw new RuntimeException(sprintf('Unexpected class: %s', get_class($metaData)));
        }

        $factory = $this->getFactory($foundFactoryClass);

        return $factory->newInjector($metaData);
    }

    /**
     * @param string $factoryClass
     * @return FactoryInterface
     */
    private function getFactory(string $factoryClass): FactoryInterface
    {
        if (!array_key_exists($factoryClass, self::$factories)) {
            self::$factories[$factoryClass] = new $factoryClass();
        }

        return self::$factories[$factoryClass];
    }
}
