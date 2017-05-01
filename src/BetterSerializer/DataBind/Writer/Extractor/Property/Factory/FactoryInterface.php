<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property\Factory;

use BetterSerializer\DataBind\MetaData\PropertyMetadataInterface;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;

/**
 * Class Factory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor
 */
interface FactoryInterface
{
    /**
     * @param PropertyMetadataInterface $metaData
     * @return ExtractorInterface
     */
    public function newExtractor(PropertyMetadataInterface $metaData): ExtractorInterface;
}
