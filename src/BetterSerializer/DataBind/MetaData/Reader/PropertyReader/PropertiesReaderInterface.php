<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;

/**
 * Class PropertyReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
interface PropertiesReaderInterface
{
    /**
     * @param ReflectionClassInterface $reflectionClass
     * @return PropertyMetaDataInterface[]
     */
    public function getPropertiesMetadata(ReflectionClassInterface $reflectionClass): array;
}
