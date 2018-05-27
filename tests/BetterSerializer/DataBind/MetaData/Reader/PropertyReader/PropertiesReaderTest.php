<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ClassPropertyMetaData;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetadata;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver\TypeResolverChainInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 *
 */
class PropertiesReaderTest extends TestCase
{

    /**
     * @throws
     */
    public function testGetPropertyMetadata(): void
    {
        $annotationReaderStub = $this->createMock(AnnotationReader::class);
        $annotationReaderStub->expects(self::exactly(2))
            ->method('getPropertyAnnotations')
            ->willReturn([]);

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
        $reflPropertyStub1->expects(self::any())
            ->method('getNativeReflProperty')
            ->willReturn($nativeReflProperty1);
        $reflPropertyStub1->expects(self::once())
            ->method('setAccessible');

        $reflPropertyStub2 = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub2->expects(self::any())
            ->method('getNativeReflProperty')
            ->willReturn($nativeReflProperty2);
        $reflPropertyStub2->expects(self::once())
            ->method('setAccessible');

        $reflClassStub = $this->createMock(ReflectionClassInterface::class);
        $reflClassStub->expects(self::once())
            ->method('getProperties')
            ->willReturn([$reflPropertyStub1, $reflPropertyStub2]);

        $typeResolverChain = $this->createMock(TypeResolverChainInterface::class);
        $typeResolverChain->expects(self::exactly(2))
            ->method('resolveType')
            ->willReturnOnConsecutiveCalls(new StringType(), new ClassType(Car::class));

        $reader = new PropertiesReader($annotationReaderStub, $typeResolverChain);
        $propertyMetadata = $reader->getPropertiesMetadata($reflClassStub);

        self::assertInternalType('array', $propertyMetadata);
        self::assertCount(2, $propertyMetadata);
        self::assertArrayHasKey('property1', $propertyMetadata);
        self::assertArrayHasKey('property2', $propertyMetadata);
        self::assertInstanceOf(PropertyMetadata::class, $propertyMetadata['property1']);
        self::assertInstanceOf(ClassPropertyMetaData::class, $propertyMetadata['property2']);
        self::assertInstanceOf(StringType::class, $propertyMetadata['property1']->getType());
        self::assertInstanceOf(ClassType::class, $propertyMetadata['property2']->getType());
    }
}
