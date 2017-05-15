<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use ReflectionProperty;
use RuntimeException;

/**
 * Class DocBlockPropertyTypeReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
interface DocBlockPropertyTypeReaderInterface
{
    /**
     * @param ReflectionProperty $reflectionProperty
     * @return TypeInterface
     * @throws RuntimeException
     */
    public function getType(ReflectionProperty $reflectionProperty): TypeInterface;
}
