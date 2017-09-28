<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;

/**
 * Interface ChainedTypeReaderInterface
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Processor\TypeReader\Chained
 */
interface ChainedTypeReaderInterface
{

    /**
     * @param ReflectionMethodInterface $constructor
     */
    public function initialize(ReflectionMethodInterface $constructor): void;

    /**
     * @param ReflectionParameterInterface $parameter
     * @return TypeInterface
     */
    public function getType(ReflectionParameterInterface $parameter): TypeInterface;
}
