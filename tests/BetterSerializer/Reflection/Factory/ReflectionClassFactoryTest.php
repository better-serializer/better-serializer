<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\Factory;

use BetterSerializer\Dto\Car;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\UseStatement\UseStatementsExtractorInterface;
use BetterSerializer\Reflection\UseStatement\UseStatementsInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass as NativeReflectionClass;

/**
 * Class ReflectionClassFactoryTest
 * @author mfris
 * @package BetterSerializer\Reflection\Factory
 */
class ReflectionClassFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewReflectionClass(): void
    {
        $class = Car::class;
        $useStatements = $this->createMock(UseStatementsInterface::class);
        $useStmtsExtractor = $this->createMock(UseStatementsExtractorInterface::class);
        $useStmtsExtractor->method('newUseStatements')
            ->willReturn($useStatements);

        $factory = new ReflectionClassFactory($useStmtsExtractor);
        $reflectionClass = $factory->newReflectionClass($class);

        self::assertInstanceOf(ReflectionClassInterface::class, $reflectionClass);
        $nativeReflClass = $reflectionClass->getNativeReflClass();
        self::assertSame($class, $nativeReflClass->getName());
    }

    /**
     *
     */
    public function testNewReflectionClassFromNativeWithParent(): void
    {
        $nativeParentClass = $this->getMockBuilder(NativeReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeParentClass->method('getParentClass')
            ->willReturn(null);

        $nativeReflClass = $this->getMockBuilder(NativeReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflClass->method('getParentClass')
            ->willReturn($nativeParentClass);

        $useStatements = $this->createMock(UseStatementsInterface::class);
        $useStmtsExtractor = $this->createMock(UseStatementsExtractorInterface::class);
        $useStmtsExtractor->method('newUseStatements')
            ->willReturn($useStatements);

        /* @var  $nativeReflClass NativeReflectionClass */
        $factory = new ReflectionClassFactory($useStmtsExtractor);
        $reflectionClass = $factory->newReflectionClassFromNative($nativeReflClass);

        self::assertInstanceOf(ReflectionClassInterface::class, $reflectionClass);
        self::assertSame($nativeReflClass, $reflectionClass->getNativeReflClass());
    }
}
