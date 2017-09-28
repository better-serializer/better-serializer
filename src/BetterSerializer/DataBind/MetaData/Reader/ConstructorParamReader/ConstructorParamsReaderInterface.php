<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader;

use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;

/**
 * Interface ConstructorParamReaderInterface
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader
 */
interface ConstructorParamsReaderInterface
{

    /**
     * @param ReflectionClassInterface $reflectionClass
     * @param PropertyMetaDataInterface[] $propertiesMetaData
     * @return ConstructorParamMetaDataInterface[]
     */
    public function getConstructorParamsMetadata(
        ReflectionClassInterface $reflectionClass,
        array $propertiesMetaData
    ): array;
}
