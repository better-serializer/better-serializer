<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;

/**
 * Class TypeReaderInterface
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Processor\TypeReader
 */
interface TypeReaderInterface
{

    /**
     * @param ReflectionMethodInterface $constructor
     * @return TypeInterface[]
     */
    public function getParameterTypes(ReflectionMethodInterface $constructor): array;
}
