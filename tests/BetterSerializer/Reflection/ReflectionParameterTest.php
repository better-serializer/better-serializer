<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection;

use PHPUnit\Framework\TestCase;
use ReflectionClass as NativeReflectionClass;
use ReflectionParameter as NativeReflectionParameter;
use ReflectionType;
use ReflectionFunctionAbstract;

/**
 * Class ReflectionParameterTest
 * @author mfris
 * @package BetterSerializer\Reflection
 */
class ReflectionParameterTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $name = 'test';
        $type = $this->getMockBuilder(ReflectionType::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hasType = true;
        $isPassedByReference = true;
        $canBePassedByValue = false;
        $reflFunctionAbstract = $this->getMockBuilder(ReflectionFunctionAbstract::class)
            ->disableOriginalConstructor()
            ->getMock();
        $class = $this->getMockBuilder(NativeReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $isArray = false;
        $isCallable = false;
        $isOptional = false;
        $position = 0;
        $allowsNull = false;
        $isDefaultValAvail = false;
        $defaultValue = null;
        $isDefaultValueConst = false;
        $defValConst = 'cnst';
        $isVariadic = false;

        $nativeParam = $this->getMockBuilder(NativeReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeParam->method('getName')
            ->willReturn($name);
        $nativeParam->method('getType')
            ->willReturn($type);
        $nativeParam->method('hasType')
            ->willReturn($hasType);
        $nativeParam->method('isPassedByReference')
            ->willReturn($isPassedByReference);
        $nativeParam->method('canBePassedByValue')
            ->willReturn($canBePassedByValue);
        $nativeParam->method('getDeclaringFunction')
            ->willReturn($reflFunctionAbstract);
        $nativeParam->method('getClass')
            ->willReturn($class);
        $nativeParam->method('isArray')
            ->willReturn($isArray);
        $nativeParam->method('isCallable')
            ->willReturn($isCallable);
        $nativeParam->method('allowsNull')
            ->willReturn($allowsNull);
        $nativeParam->method('getPosition')
            ->willReturn($position);
        $nativeParam->method('isOptional')
            ->willReturn($isOptional);
        $nativeParam->method('isDefaultValueAvailable')
            ->willReturn($isDefaultValAvail);
        $nativeParam->method('getDefaultValue')
            ->willReturn($defaultValue);
        $nativeParam->method('isDefaultValueConstant')
            ->willReturn($isDefaultValueConst);
        $nativeParam->method('getDefaultValueConstantName')
            ->willReturn($defValConst);
        $nativeParam->method('isVariadic')
            ->willReturn($isVariadic);

        $reflClass = $this->createMock(ReflectionClassInterface::class);

        /* @var $nativeParam NativeReflectionParameter */
        $refLParam = new ReflectionParameter($nativeParam, $reflClass);

        self::assertSame($nativeParam, $refLParam->getNativeReflParameter());
        self::assertSame($name, $refLParam->getName());
        self::assertSame($type, $refLParam->getType());
        self::assertSame($hasType, $refLParam->hasType());
        self::assertSame($isPassedByReference, $refLParam->isPassedByReference());
        self::assertSame($canBePassedByValue, $refLParam->canBePassedByValue());
        self::assertSame($reflFunctionAbstract, $refLParam->getDeclaringFunction());
        self::assertSame($reflClass, $refLParam->getDeclaringClass());
        self::assertSame($class, $refLParam->getClass());
        self::assertSame($isArray, $refLParam->isArray());
        self::assertSame($isCallable, $refLParam->isCallable());
        self::assertSame($allowsNull, $refLParam->allowsNull());
        self::assertSame($position, $refLParam->getPosition());
        self::assertSame($isOptional, $refLParam->isOptional());
        self::assertSame($isDefaultValAvail, $refLParam->isDefaultValueAvailable());
        self::assertSame($defaultValue, $refLParam->getDefaultValue());
        self::assertSame($isDefaultValueConst, $refLParam->isDefaultValueConstant());
        self::assertSame($defValConst, $refLParam->getDefaultValueConstantName());
        self::assertSame($isVariadic, $refLParam->isVariadic());
    }
}
