<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Context;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use ReflectionParameter;

/**
 * Class ConstructorParameter
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context
 */
final class ConstructorParameter implements ConstructorParameterInterface
{

    /**
     * @var ReflectionParameter
     */
    private $reflectionParameter;

    /**
     * @var TypeInterface
     */
    private $type;

    /**
     * ConstructorParameter constructor.
     * @param ReflectionParameter $reflectionParameter
     * @param TypeInterface $type
     */
    public function __construct(ReflectionParameter $reflectionParameter, TypeInterface $type)
    {
        $this->reflectionParameter = $reflectionParameter;
        $this->type = $type;
    }

    /**
     * @return ReflectionParameter
     */
    public function getReflectionParameter(): ReflectionParameter
    {
        return $this->reflectionParameter;
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }
}
