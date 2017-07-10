<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reflection;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Class ReflectionClassHelperTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reflection
 */
class ReflectionClassHelperTest extends TestCase
{

    /**
     *
     */
    public function testGetProperties(): void
    {
        $reflParentProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflParentClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflParentClass->expects(self::once())
            ->method('getParentClass')
            ->willReturn(false);
        $reflParentClass->expects(self::once())
            ->method('getProperties')
            ->willReturn([$reflParentProperty]);

        $reflProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass->expects(self::once())
            ->method('getParentClass')
            ->willReturn($reflParentClass);
        $reflClass->expects(self::once())
            ->method('getProperties')
            ->willReturn([$reflProperty]);

        /* @var $reflClass ReflectionClass */
        $reflClassHelper = new ReflectionClassHelper();
        $properties = $reflClassHelper->getProperties($reflClass);

        self::assertInternalType('array', $properties);
        self::assertCount(2, $properties);
        self::assertSame($reflParentProperty, $properties[0]);
        self::assertSame($reflProperty, $properties[1]);
    }

    /**
     *
     */
    public function testGetProperty(): void
    {
        $propertyName = 'test';

        $reflParentProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflParentClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflParentClass->expects(self::once())
            ->method('getProperty')
            ->with($propertyName)
            ->willReturn($reflParentProperty);

        $reflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass->expects(self::once())
            ->method('getParentClass')
            ->willReturn($reflParentClass);
        $reflClass->expects(self::once())
            ->method('getProperty')
            ->with($propertyName)
            ->willThrowException(new ReflectionException('Invalid property.'));

        /* @var $reflClass ReflectionClass */
        $reflClassHelper = new ReflectionClassHelper();
        $property = $reflClassHelper->getProperty($reflClass, $propertyName);

        self::assertSame($reflParentProperty, $property);
    }

    /**
     * @expectedException ReflectionException
     * @expectedExceptionMessageRegExp /Reflection property not found: '[a-zA-Z0-9]+'./
     */
    public function testGetPropertyThrowsException(): void
    {
        $propertyName = 'test';
        $reflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass->expects(self::once())
            ->method('getParentClass')
            ->willReturn(false);
        $reflClass->expects(self::once())
            ->method('getProperty')
            ->with($propertyName)
            ->willThrowException(new ReflectionException('Invalid property.'));

        /* @var $reflClass ReflectionClass */
        $reflClassHelper = new ReflectionClassHelper();
        $reflClassHelper->getProperty($reflClass, $propertyName);
    }
}
