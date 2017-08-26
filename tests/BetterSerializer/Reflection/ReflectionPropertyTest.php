<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection;

use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty as NativeReflectionProperty;

/**
 * Class ReflectionPropertyTest
 * @author mfris
 * @package BetterSerializer\Reflection
 */
class ReflectionPropertyTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $propertyName = 'test';
        $object = $this->createMock(CarInterface::class);
        $objectValue = 'value';
        $isPublic = true;
        $isPrivate = false;
        $isProtected = false;
        $isStatic = false;
        $isDefault = true;
        $modifiers = 3;
        $docComment = '/** asd */';
        $accessible = true;

        $nativeReflProperty = $this->getMockBuilder(NativeReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflProperty->method('getName')
            ->willReturn($propertyName);
        $nativeReflProperty->method('getValue')
            ->with($object)
            ->willReturn($objectValue);
        $nativeReflProperty->expects(self::once())
            ->method('setValue')
            ->with($object, $objectValue);
        $nativeReflProperty->method('isPublic')
            ->willReturn($isPublic);
        $nativeReflProperty->method('isPrivate')
            ->willReturn($isPrivate);
        $nativeReflProperty->method('isProtected')
            ->willReturn($isProtected);
        $nativeReflProperty->method('isStatic')
            ->willReturn($isStatic);
        $nativeReflProperty->method('isDefault')
            ->willReturn($isDefault);
        $nativeReflProperty->method('getModifiers')
            ->willReturn($modifiers);
        $nativeReflProperty->method('getDocComment')
            ->willReturn($docComment);
        $nativeReflProperty->expects(self::once())
            ->method('setAccessible');

        $reflClass = $this->createMock(ReflectionClassInterface::class);

        /* @var $nativeReflProperty NativeReflectionProperty */
        $reflProperty = new ReflectionProperty($nativeReflProperty, $reflClass);

        self::assertSame($nativeReflProperty, $reflProperty->getNativeReflProperty());
        self::assertSame($propertyName, $reflProperty->getName());
        self::assertSame($objectValue, $reflProperty->getValue($object));
        $reflProperty->setValue($object, $objectValue);
        self::assertSame($isPublic, $reflProperty->isPublic());
        self::assertSame($isPrivate, $reflProperty->isPrivate());
        self::assertSame($isProtected, $reflProperty->isProtected());
        self::assertSame($isStatic, $reflProperty->isStatic());
        self::assertSame($isDefault, $reflProperty->isDefault());
        self::assertSame($modifiers, $reflProperty->getModifiers());
        self::assertSame($reflClass, $reflProperty->getDeclaringClass());
        self::assertSame($isStatic, $reflProperty->isStatic());
        self::assertSame($docComment, $reflProperty->getDocComment());
        $reflProperty->setAccessible($accessible);
    }
}
