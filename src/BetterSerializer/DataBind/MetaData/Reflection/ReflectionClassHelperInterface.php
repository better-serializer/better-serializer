<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reflection;

use ReflectionClass;
use ReflectionProperty;

/**
 * Class ReflectionClassHelper
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reflection
 */
interface ReflectionClassHelperInterface
{
    /**
     * @param ReflectionClass $reflectionClass
     * @return ReflectionProperty[]
     */
    public function getProperties(ReflectionClass $reflectionClass): array;

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $propertyName
     * @return ReflectionProperty
     */
    public function getProperty(ReflectionClass $reflectionClass, string $propertyName): ReflectionProperty;
}
