<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Interface ChainedTypeReaderInterface
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Processor\TypeReader\Chained
 */
interface ChainedTypeReaderInterface
{

    /**
     * @param ReflectionMethod $constructor
     */
    public function initialize(ReflectionMethod $constructor): void;

    /**
     * @param ReflectionParameter $parameter
     * @return TypeInterface
     */
    public function getType(ReflectionParameter $parameter): TypeInterface;
}
