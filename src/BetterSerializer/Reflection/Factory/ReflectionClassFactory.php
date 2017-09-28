<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\Factory;

use BetterSerializer\Reflection\ReflectionClass;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\UseStatement\UseStatementsExtractorInterface;
use ReflectionClass as NativeReflectionClass;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class ReflectionClassFactory
 * @author mfris
 * @package BetterSerializer\Reflection\Factory
 */
final class ReflectionClassFactory implements ReflectionClassFactoryInterface
{

    /**
     * @var UseStatementsExtractorInterface
     */
    private $useStatementsExtractor;

    /**
     * ReflectionClassFactory constructor.
     * @param UseStatementsExtractorInterface $useStatementsExtr
     */
    public function __construct(UseStatementsExtractorInterface $useStatementsExtr)
    {
        $this->useStatementsExtractor = $useStatementsExtr;
    }

    /**
     * @param string $className
     * @return ReflectionClassInterface
     * @throws LogicException
     * @throws RuntimeException
     * @throws ReflectionException
     */
    public function newReflectionClass(string $className): ReflectionClassInterface
    {
        $reflectionClass = new NativeReflectionClass($className);

        return $this->newReflectionClassFromNative($reflectionClass);
    }

    /**
     * @param NativeReflectionClass $reflectionClass
     * @return ReflectionClassInterface
     * @throws LogicException
     * @throws RuntimeException
     * @throws ReflectionException
     */
    public function newReflectionClassFromNative(NativeReflectionClass $reflectionClass): ReflectionClassInterface
    {
        $useStatements = $this->useStatementsExtractor->newUseStatements($reflectionClass);
        $parentClass = null;
        $nativeParentClass = $reflectionClass->getParentClass();

        if ($nativeParentClass) {
            $parentClass = $this->newReflectionClassFromNative($nativeParentClass);
        }

        return new ReflectionClass($reflectionClass, $useStatements, $parentClass);
    }
}
