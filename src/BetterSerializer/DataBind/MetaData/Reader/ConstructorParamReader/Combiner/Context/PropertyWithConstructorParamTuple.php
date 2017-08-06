<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use ReflectionParameter;

/**
 * Class PropertyWithConstructorParamTuple
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Context
 */
final class PropertyWithConstructorParamTuple implements PropertyWithConstructorParamTupleInterface
{

    /**
     * @var ReflectionParameter
     */
    private $constructorParam;

    /**
     * @var PropertyMetaDataInterface
     */
    private $propertyMetaData;

    /**
     * PropertyWithConstructorParamTuple constructor.
     * @param ReflectionParameter $constructorParam
     * @param PropertyMetaDataInterface $propertyMetaData
     */
    public function __construct(
        ReflectionParameter $constructorParam,
        PropertyMetaDataInterface $propertyMetaData
    ) {
        $this->constructorParam = $constructorParam;
        $this->propertyMetaData = $propertyMetaData;
    }

    /**
     * @return ReflectionParameter
     */
    public function getConstructorParam(): ReflectionParameter
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
        return $this->propertyMetaData->getOutputKey();
    }

    /**
     * @return TypeInterface
     */
    public function getPropertyType(): TypeInterface
    {
        return $this->propertyMetaData->getType();
    }
}
