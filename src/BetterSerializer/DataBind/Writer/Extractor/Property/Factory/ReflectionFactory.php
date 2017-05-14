<?php
declare(strict_types = 1);

/**
 * Short desc
 *
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\Extractor\Property\Factory;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\ReflectionExtractor;
use RuntimeException;

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
     * @throws RuntimeException
     */
    public function newExtractor(PropertyMetaDataInterface $metaData): ExtractorInterface
    {
        if (!$metaData instanceof ReflectionPropertyMetaDataInterface) {
            throw new RuntimeException(
                sprintf('Not a ReflectionPropertyMetaDataInterface - %s', get_class($metaData))
            );
        }

        return new ReflectionExtractor($metaData->getReflectionProperty());
    }
}
