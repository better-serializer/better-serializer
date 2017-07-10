<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader;

use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use ReflectionClass;

/**
 * Interface ConstructorParamReaderInterface
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader
 */
interface ConstructorParamsReaderInterface
{

    /**
     * @param ReflectionClass $reflectionClass
     * @param PropertyMetaDataInterface[] $propertiesMetaData
     * @return ConstructorParamMetaDataInterface[]
     */
    public function getConstructorParamsMetadata(ReflectionClass $reflectionClass, array $propertiesMetaData): array;
}
