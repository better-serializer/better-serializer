<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * ClassModel PropertyMetadataTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReflectionPropertyMetaDataTest extends TestCase
{

    /**
     *
     */
    public function testGetType(): void
    {
        $reflProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflProperty->expects(self::once())
            ->method('setAccessible');
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        /* @var $reflProperty ReflectionProperty */
        /* @var $type TypeInterface */
        $metaData = new ReflectionPropertyMetadata($reflProperty, [], $type);
        self::assertSame($type, $metaData->getType());
        self::assertSame($reflProperty, $metaData->getReflectionProperty());
    }

    /**
     *
     */
    public function testGetOutputKeyFromReflProperty(): void
    {
        $reflProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflProperty->expects(self::once())
            ->method('getName')
            ->willReturn('test');
        $reflProperty->expects(self::once())
            ->method('setAccessible');
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        /* @var $reflProperty ReflectionProperty */
        /* @var $type TypeInterface */
        $metaData = new ReflectionPropertyMetadata($reflProperty, [], $type);
        self::assertSame('test', $metaData->getOutputKey());
    }

    /**
     *
     */
    public function testGetOutputKeyFromReflPropertyWhenInvalidPropertyAnnotationProvided(): void
    {
        $reflProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflProperty->expects(self::once())
            ->method('getName')
            ->willReturn('test');
        $reflProperty->expects(self::once())
            ->method('setAccessible');
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $propertyAnnotation = $this->getMockBuilder(PropertyInterface::class)->getMock();
        $propertyAnnotation->expects(self::once())
            ->method('getName')
            ->willReturn('');

        /* @var $reflProperty ReflectionProperty */
        /* @var $type TypeInterface */
        $metaData = new ReflectionPropertyMetadata($reflProperty, [$propertyAnnotation], $type);
        self::assertSame('test', $metaData->getOutputKey());
    }

    /**
     *
     */
    public function testGetOutputKeyFromPropertyAnnotation(): void
    {
        $reflProperty = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflProperty->expects(self::exactly(0))
            ->method('getName');
        $reflProperty->expects(self::once())
            ->method('setAccessible');
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $propertyAnnotation = $this->getMockBuilder(PropertyInterface::class)->getMock();
        $propertyAnnotation->expects(self::once())
            ->method('getName')
            ->willReturn('test2');

        /* @var $reflProperty ReflectionProperty */
        /* @var $type TypeInterface */
        $metaData = new ReflectionPropertyMetadata($reflProperty, [$propertyAnnotation], $type);
        self::assertSame('test2', $metaData->getOutputKey());
    }
}
