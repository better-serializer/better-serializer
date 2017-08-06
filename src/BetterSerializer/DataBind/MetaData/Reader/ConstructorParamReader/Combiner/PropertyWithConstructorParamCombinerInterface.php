<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use ReflectionMethod;

/**
 * Interface PropertyWithParamCombinerInterface
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner
 */
interface PropertyWithConstructorParamCombinerInterface
{

    /**
     * @param ReflectionMethod $constructor
     * @param array $propertiesMetaData
     * @return Context\PropertyWithConstructorParamTupleInterface[]
     */
    public function combine(ReflectionMethod $constructor, array $propertiesMetaData): array;
}
