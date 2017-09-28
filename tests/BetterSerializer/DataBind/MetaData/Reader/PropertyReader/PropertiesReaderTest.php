<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetadata;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\TypeReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use ReflectionProperty;

/**
 * Class PropertyReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class PropertiesReaderTest extends TestCase
{

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Type readers missing.
     */
    public function testConstructionWithEmptyTypeReadersThrowsException(): void
    {
        $annotationReaderStub = $this->createMock(AnnotationReader::class);
        $typeFactoryStub = $this->createMock(TypeFactoryInterface::class);

        new PropertiesReader($annotationReaderStub, $typeFactoryStub, []);
    }

    /**
     *
     */
    public function testGetPropertyMetadata(): void
    {
        $stringTypeStub = $this->createMock(StringFormTypeInterface::class);

        $annotationReaderStub = $this->createMock(AnnotationReader::class);
        $annotationReaderStub->expects(self::exactly(2))
            ->method('getPropertyAnnotations')
            ->willReturn([]);

        $typeFactoryStub = $this->createMock(TypeFactoryInterface::class);
        $typeFactoryStub->expects(self::exactly(2))
            ->method('getType')
            ->willReturn(new StringType());

        $typeReaderStub = $this->createMock(TypeReaderInterface::class);
        $typeReaderStub->expects(self::exactly(2))
            ->method('resolveType')
            ->willReturn($stringTypeStub);

        $nativeReflProperty1 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflProperty1->method('getName')
            ->willReturn('property1');

        $nativeReflProperty2 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflProperty2->method('getName')
            ->willReturn('property2');

        $reflPropertyStub1 = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub1->expects(self::once())
            ->method('getNativeReflProperty')
            ->willReturn($nativeReflProperty1);
        $reflPropertyStub1->expects(self::once())
            ->method('setAccessible');

        $reflPropertyStub2 = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub2->expects(self::once())
            ->method('getNativeReflProperty')
            ->willReturn($nativeReflProperty2);
        $reflPropertyStub2->expects(self::once())
            ->method('setAccessible');

        $reflClassStub = $this->createMock(ReflectionClassInterface::class);
        $reflClassStub->expects(self::once())
            ->method('getProperties')
            ->willReturn([$reflPropertyStub1, $reflPropertyStub2]);

        $reader = new PropertiesReader($annotationReaderStub, $typeFactoryStub, [$typeReaderStub]);
        $propertyMetadata = $reader->getPropertiesMetadata($reflClassStub);

        self::assertInternalType('array', $propertyMetadata);
        self::assertCount(2, $propertyMetadata);
        self::assertArrayHasKey('property1', $propertyMetadata);
        self::assertArrayHasKey('property2', $propertyMetadata);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property1']);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property2']);
        self::assertInstanceOf(StringType::class, $propertyMetadata['property1']->getType());
        self::assertInstanceOf(StringType::class, $propertyMetadata['property2']->getType());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type declaration missing in class: [a-zA-Z0-9\\]+, property: [a-zA-Z0-9]+/
     */
    public function testGetPropertyMetadataThrowsException(): void
    {
        $propertyAnnotStub1 = $this->createMock(PropertyInterface::class);
        $propertyAnnotStub2 = $this->createMock(PropertyInterface::class);

        $annotationReaderStub = $this->createMock(AnnotationReader::class);
        $annotationReaderStub->expects(self::once())
            ->method('getPropertyAnnotations')
            ->willReturnOnConsecutiveCalls([
                [$propertyAnnotStub1],
                [$propertyAnnotStub2]
            ]);

        $typeFactoryStub = $this->createMock(TypeFactoryInterface::class);
        $typeFactoryStub->expects(self::exactly(0))
            ->method('getType');

        $typeReaderStub = $this->createMock(TypeReaderInterface::class);
        $typeReaderStub->expects(self::once())
            ->method('resolveType')
            ->willReturn(null);

        $reflDeclaringClassStub = $this->createMock(ReflectionClassInterface::class);
        $reflDeclaringClassStub->expects(self::once())
            ->method('getName')
            ->willReturn(StringType::class);

        $nativeReflProperty1 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nativeReflProperty1->method('getName')
            ->willReturn('property1');

        $reflPropertyStub1 = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub1->method('getName')
            ->willReturn('property1');
        $reflPropertyStub1->expects(self::once())
            ->method('getNativeReflProperty')
            ->willReturn($nativeReflProperty1);
        $reflPropertyStub1->expects(self::once())
            ->method('getDeclaringClass')
            ->willReturn($reflDeclaringClassStub);

        $reflPropertyStub2 = $this->createMock(ReflectionPropertyInterface::class);
        $reflClassStub = $this->createMock(ReflectionClassInterface::class);
        $reflClassStub->expects(self::once())
            ->method('getProperties')
            ->willReturn([$reflPropertyStub1, $reflPropertyStub2]);

        $reader = new PropertiesReader($annotationReaderStub, $typeFactoryStub, [$typeReaderStub]);
        $reader->getPropertiesMetadata($reflClassStub);
    }
}
