<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
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
    private $classProperty;

    /**
     * PropertyWithConstructorParamTuple constructor.
     * @param ReflectionParameter $constructorParam
     * @param PropertyMetaDataInterface $classProperty
     */
    public function __construct(
        ReflectionParameter $constructorParam,
        PropertyMetaDataInterface $classProperty
    ) {
        $this->constructorParam = $constructorParam;
        $this->classProperty = $classProperty;
    }

    /**
     * @return ReflectionParameter
     */
    public function getConstructorParam(): ReflectionParameter
    {
        return $this->constructorParam;
    }

    /**
     * @return PropertyMetaDataInterface
     */
    public function getClassProperty(): PropertyMetaDataInterface
    {
        return $this->classProperty;
    }
}
