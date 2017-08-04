<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use ReflectionParameter;

/**
 * Class ChainedCombiner
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained
 */
abstract class ChainedCombiner implements ChainedCombinerInterface
{

    /**
     * @param ReflectionParameter $parameter
     * @return Context\PropertyWithConstructorParamTupleInterface|null
     */
    public function combineWithParameter(
        ReflectionParameter $parameter
    ): ?Context\PropertyWithConstructorParamTupleInterface {
        if (!$this->isAbleToCombine($parameter)) {
            return null;
        }

        return $this->createCombinedTuple($parameter);
    }

    /**
     * @param ReflectionParameter $parameter
     * @return bool
     */
    abstract protected function isAbleToCombine(ReflectionParameter $parameter): bool;

    /**
     * @param ReflectionParameter $parameter
     * @return Context\PropertyWithConstructorParamTupleInterface
     */
    abstract protected function createCombinedTuple(
        ReflectionParameter $parameter
    ): Context\PropertyWithConstructorParamTupleInterface;
}
