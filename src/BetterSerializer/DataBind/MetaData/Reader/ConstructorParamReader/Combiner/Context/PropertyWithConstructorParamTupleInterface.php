<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use ReflectionParameter;

/**
 * Class PropertyWithConsturctorParamTupleInterface
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Context
 */
interface PropertyWithConstructorParamTupleInterface
{

    /**
     * @return ReflectionParameter
     */
    public function getConstructorParam(): ReflectionParameter;

    /**
     * @return PropertyMetaDataInterface
     */
    public function getClassProperty(): PropertyMetaDataInterface;
}
