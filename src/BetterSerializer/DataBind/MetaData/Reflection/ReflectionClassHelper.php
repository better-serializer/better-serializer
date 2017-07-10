<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reflection;

use ReflectionClass;
use ReflectionProperty;
use ReflectionException;

/**
 * Class ReflectionClassHelper
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reflection
 */
final class ReflectionClassHelper implements ReflectionClassHelperInterface
{

    /**
     *
     * @param ReflectionClass $reflectionClass
     * @return ReflectionProperty[]
     */
    public function getProperties(ReflectionClass $reflectionClass): array
    {
        $properties = [];
        $parentClass = $reflectionClass->getParentClass();

        if ($parentClass) {
            $properties = $this->getProperties($parentClass);
        }

        foreach ($reflectionClass->getProperties() as $property) {
            $properties[] = $property;
        }

        return $properties;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $propertyName
     * @return ReflectionProperty
     * @throws ReflectionException
     */
    public function getProperty(ReflectionClass $reflectionClass, string $propertyName): ReflectionProperty
    {
        try {
            return $reflectionClass->getProperty($propertyName);
        } catch (ReflectionException $e) {
        }

        $parentClass = $reflectionClass->getParentClass();

        if ($parentClass) {
            return $this->getProperty($parentClass, $propertyName);
        }

        throw new ReflectionException(sprintf("Reflection property not found: '%s'.", $propertyName));
    }
}
