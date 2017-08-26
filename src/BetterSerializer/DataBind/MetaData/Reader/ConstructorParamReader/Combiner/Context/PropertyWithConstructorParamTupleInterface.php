<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;

/**
 * Class PropertyWithConsturctorParamTupleInterface
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Context
 */
interface PropertyWithConstructorParamTupleInterface
{

    /**
     * @return ReflectionParameterInterface
     */
    public function getConstructorParam(): ReflectionParameterInterface;

    /**
     * @return string
     */
    public function getParamName(): string;

    /**
     * @return PropertyMetaDataInterface
     */
    public function getPropertyMetaData(): PropertyMetaDataInterface;

    /**
     * @return string
     */
    public function getPropertyName(): string;

    /**
     * @return TypeInterface
     */
    public function getPropertyType(): TypeInterface;
}
