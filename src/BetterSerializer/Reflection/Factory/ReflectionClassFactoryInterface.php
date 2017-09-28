<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\Factory;

use BetterSerializer\Reflection\ReflectionClassInterface;
use LogicException;
use ReflectionClass as NativeReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * Class ReflectionClassFactory
 * @author mfris
 * @package BetterSerializer\Reflection\Factory
 */
interface ReflectionClassFactoryInterface
{
    /**
     * @param string $className
     * @return ReflectionClassInterface
     * @throws LogicException
     * @throws RuntimeException
     * @throws ReflectionException
     */
    public function newReflectionClass(string $className): ReflectionClassInterface;

    /**
     * @param NativeReflectionClass $reflectionClass
     * @return ReflectionClassInterface
     * @throws LogicException
     * @throws RuntimeException
     * @throws ReflectionException
     */
    public function newReflectionClassFromNative(NativeReflectionClass $reflectionClass): ReflectionClassInterface;
}
