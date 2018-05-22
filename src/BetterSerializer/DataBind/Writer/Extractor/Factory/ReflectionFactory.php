<?php
declare(strict_types = 1);

/**
 * Short desc
 *
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\Extractor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\ReflectionExtractor;

/**
 *
 */
final class ReflectionFactory implements FactoryInterface
{

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ExtractorInterface
     */
    public function newExtractor(PropertyMetaDataInterface $metaData): ExtractorInterface
    {
        return new ReflectionExtractor($metaData->getReflectionProperty());
    }
}
