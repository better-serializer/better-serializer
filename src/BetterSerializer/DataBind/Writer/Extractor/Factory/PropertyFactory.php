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
use BetterSerializer\DataBind\Writer\Extractor\Property\PropertyExtractor;
use RuntimeException;

/**
 *
 */
final class PropertyFactory implements FactoryInterface
{

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ExtractorInterface
     * @throws RuntimeException
     */
    public function newExtractor(PropertyMetaDataInterface $metaData): ExtractorInterface
    {
        if (!$metaData instanceof ReflectionPropertyMetaDataInterface) {
            throw new RuntimeException(
                sprintf('Not a ReflectionPropertyMetaDataInterface - %s', get_class($metaData))
            );
        }

        $reflProperty = $metaData->getReflectionProperty();

        return new PropertyExtractor($reflProperty->getName(), $reflProperty->getDeclaringClass()->getName());
    }
}
