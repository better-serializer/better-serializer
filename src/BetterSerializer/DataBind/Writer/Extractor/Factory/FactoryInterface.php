<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Factory;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;

/**
 * Class Factory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor
 */
interface FactoryInterface
{
    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ExtractorInterface
     */
    public function newExtractor(PropertyMetaDataInterface $metaData): ExtractorInterface;
}
