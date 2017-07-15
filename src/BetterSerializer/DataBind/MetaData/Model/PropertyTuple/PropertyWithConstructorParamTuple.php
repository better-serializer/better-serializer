<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyTuple;

use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class PropertyWithConstructorParamTuple
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Model\PropertyTuple
 */
final class PropertyWithConstructorParamTuple implements PropertyWithConstructorParamTupleInterface
{

    /**
     * @var PropertyMetaDataInterface
     */
    private $propertyMetaData;

    /**
     * @var ConstructorParamMetaDataInterface
     */
    private $constructorParamMetaData;

    /**
     * PropertyWithConstructorParamTuple constructor.
     * @param PropertyMetaDataInterface $propertyMetaData
     * @param ConstructorParamMetaDataInterface $constrParamMetaData
     */
    public function __construct(
        PropertyMetaDataInterface $propertyMetaData,
        ConstructorParamMetaDataInterface $constrParamMetaData
    ) {
        $this->propertyMetaData = $propertyMetaData;
        $this->constructorParamMetaData = $constrParamMetaData;
    }

    /**
     * @return PropertyMetaDataInterface
     */
    public function getPropertyMetaData(): PropertyMetaDataInterface
    {
        return $this->propertyMetaData;
    }

    /**
     * @return ConstructorParamMetaDataInterface
     */
    public function getConstructorParamMetaData(): ConstructorParamMetaDataInterface
    {
        return $this->constructorParamMetaData;
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->propertyMetaData->getType();
    }

    /**
     * @return string
     */
    public function getOutputKey(): string
    {
        return $this->propertyMetaData->getOutputKey();
    }
}
