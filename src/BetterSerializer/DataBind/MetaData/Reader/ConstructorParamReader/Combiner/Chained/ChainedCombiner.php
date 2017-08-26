<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\Reflection\ReflectionParameterInterface;

/**
 * Class ChainedCombiner
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained
 */
abstract class ChainedCombiner implements ChainedCombinerInterface
{

    /**
     * @param ReflectionParameterInterface $parameter
     * @return Context\PropertyWithConstructorParamTupleInterface|null
     */
    public function combineWithParameter(
        ReflectionParameterInterface $parameter
    ): ?Context\PropertyWithConstructorParamTupleInterface {
        if (!$this->isAbleToCombine($parameter)) {
            return null;
        }

        return $this->createCombinedTuple($parameter);
    }

    /**
     * @param ReflectionParameterInterface $parameter
     * @return bool
     */
    abstract protected function isAbleToCombine(ReflectionParameterInterface $parameter): bool;

    /**
     * @param ReflectionParameterInterface $parameter
     * @return Context\PropertyWithConstructorParamTupleInterface
     */
    abstract protected function createCombinedTuple(
        ReflectionParameterInterface $parameter
    ): Context\PropertyWithConstructorParamTupleInterface;
}
