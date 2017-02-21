<?php
declare(strict_types = 1);

/**
 * Short desc
 *
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\Extractor;

use BetterSerializer\DataBind\MetaData\PropertyMetadata;

/**
 * Class ExtractorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor
 */
final class ExtractorFactory
{
    public function getInstance(PropertyMetadata $metadata): ExtractorInterface
    {
    }
}
