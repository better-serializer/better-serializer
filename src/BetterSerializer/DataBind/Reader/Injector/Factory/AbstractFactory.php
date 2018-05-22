<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Reader\Injector\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use RuntimeException;

/**
 *
 */
final class AbstractFactory implements AbstractFactoryInterface
{

    /**
     * @const string[]
     */
    private const METADATA2FACTORY_MAPPING = [
        PropertyMetaDataInterface::class => ReflectionFactory::class,
    ];

    /**
     * @var FactoryInterface[]
     */
    private static $factories = [];

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return InjectorInterface
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
