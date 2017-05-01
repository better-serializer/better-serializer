<?php
declare(strict_types = 1);

/**
 * Short desc
 *
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\Extractor\Property\Factory;

use BetterSerializer\DataBind\MetaData\PropertyMetadataInterface;
use BetterSerializer\DataBind\MetaData\ReflectionPropertyMetadataInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use RuntimeException;

/**
 * Class Factory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor
 */
final class AbstractFactory implements FactoryInterface
{

    /**
     * @const string[]
     */
    private const METADATA2FACTORY_MAPPING = [
        ReflectionPropertyMetadataInterface::class => ReflectionFactory::class,
    ];

    /**
     * @var FactoryInterface[]
     */
    private static $factories = [];

    /**
     * @param PropertyMetadataInterface $metaData
     * @return ExtractorInterface
     * @throws RuntimeException
     */
    public function newExtractor(PropertyMetadataInterface $metaData): ExtractorInterface
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
