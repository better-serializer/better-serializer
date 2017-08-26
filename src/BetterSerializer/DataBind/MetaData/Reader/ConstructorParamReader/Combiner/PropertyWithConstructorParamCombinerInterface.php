<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\Reflection\ReflectionMethodInterface;

/**
 * Interface PropertyWithParamCombinerInterface
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner
 */
interface PropertyWithConstructorParamCombinerInterface
{

    /**
     * @param ReflectionMethodInterface $constructor
     * @param array $propertiesMetaData
     * @return Context\PropertyWithConstructorParamTupleInterface[]
     */
    public function combine(ReflectionMethodInterface $constructor, array $propertiesMetaData): array;
}
