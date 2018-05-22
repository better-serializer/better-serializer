<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;

/**
 *
 */
final class PropertyWithConstructorParamTuple implements PropertyWithConstructorParamTupleInterface
{

    /**
     * @var ReflectionParameterInterface
     */
    private $constructorParam;

    /**
     * @var PropertyMetaDataInterface
     */
    private $propertyMetaData;

    /**
     * @param ReflectionParameterInterface $constructorParam
     * @param PropertyMetaDataInterface $propertyMetaData
     */
    public function __construct(
        ReflectionParameterInterface $constructorParam,
        PropertyMetaDataInterface $propertyMetaData
    ) {
        $this->constructorParam = $constructorParam;
        $this->propertyMetaData = $propertyMetaData;
    }

    /**
     * @return ReflectionParameterInterface
     */
    public function getConstructorParam(): ReflectionParameterInterface
    {
        return $this->constructorParam;
    }

    /**
     * @return string
     */
    public function getParamName(): string
    {
        return $this->constructorParam->getName();
    }

    /**
     * @return PropertyMetaDataInterface
     */
    public function getPropertyMetaData(): PropertyMetaDataInterface
    {
        return $this->propertyMetaData;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyMetaData->getName();
    }

    /**
     * @return TypeInterface
     */
    public function getPropertyType(): TypeInterface
    {
        return $this->propertyMetaData->getType();
    }
}
