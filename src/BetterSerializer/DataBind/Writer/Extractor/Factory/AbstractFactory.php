<?php
declare(strict_types = 1);

/**
 * Short desc
 *
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\Extractor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use RuntimeException;

/**
 * Class Factory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor
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
     * @return ExtractorInterface
     * @throws RuntimeException
     */
    public function newExtractor(PropertyMetaDataInterface $metaData): ExtractorInterface
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

        return $factory->newExtractor($metaData);
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
