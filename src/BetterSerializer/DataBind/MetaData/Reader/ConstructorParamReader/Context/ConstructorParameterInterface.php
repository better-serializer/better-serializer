<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Context;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use ReflectionParameter;

/**
 * Interface ConstructorParameterInterface
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Processor\Context
 */
interface ConstructorParameterInterface
{

    /**
     * @return ReflectionParameter
     */
    public function getReflectionParameter(): ReflectionParameter;

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;
}
