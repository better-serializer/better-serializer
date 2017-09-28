<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context\InitializeContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\Reflection\ReflectionParameterInterface;

/**
 * Interface StringTypeReaderInterface
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\StringType
 */
interface ChainedCombinerInterface
{

    /**
     * @param InitializeContextInterface $context
     */
    public function initialize(InitializeContextInterface $context): void;

    /**
     * @param ReflectionParameterInterface $parameter
     * @return Context\PropertyWithConstructorParamTupleInterface|null
     */
    public function combineWithParameter(
        ReflectionParameterInterface $parameter
    ): ?Context\PropertyWithConstructorParamTupleInterface;
}
