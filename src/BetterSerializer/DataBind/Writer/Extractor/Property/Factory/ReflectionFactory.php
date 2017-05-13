<?php
declare(strict_types = 1);

/**
 * Short desc
 *
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\Extractor\Property\Factory;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\ReflectionExtractor;

/**
 * Class Factory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor
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
