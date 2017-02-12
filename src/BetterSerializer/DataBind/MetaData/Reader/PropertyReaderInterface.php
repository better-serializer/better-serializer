<?php
/**
 * @author  mfris
 */
declare(strict_types = 1);
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\PropertyMetadataInterface;
use ReflectionClass;

/**
 * Class PropertyReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
interface PropertyReaderInterface
{
    /**
     * @param ReflectionClass $reflectionClass
     * @return PropertyMetadataInterface[]
     */
    public function getPropertyMetadata(ReflectionClass $reflectionClass): array;
}
