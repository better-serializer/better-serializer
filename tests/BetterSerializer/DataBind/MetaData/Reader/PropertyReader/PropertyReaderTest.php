<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetadata;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringTypedPropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\TypeReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;

/**
 * Class PropertyReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class PropertyReaderTest extends TestCase
{

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Type readers missing.
     */
    public function testConstructionWithEmptyTypeReadersThrowsException(): void
    {
        /* @var $annotationReaderStub AnnotationReader */
        $annotationReaderStub = $this->getMockBuilder(AnnotationReader::class)->getMock();
        /* @var $typeFactoryStub TypeFactoryInterface */
        $typeFactoryStub = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();

        new PropertyReader(
            $annotationReaderStub,
            $typeFactoryStub,
            []
        );
    }

    /**
     *
     */
    public function testGetPropertyMetadata(): void
    {
        $typedContextStub = $this->getMockBuilder(StringTypedPropertyContextInterface::class)->getMock();

        $annotationReaderStub = $this->getMockBuilder(AnnotationReader::class)->getMock();
        $annotationReaderStub->expects(self::exactly(2))
            ->method('getPropertyAnnotations')
            ->willReturn([]);

        $typeFactoryStub = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();
        $typeFactoryStub->expects(self::exactly(2))
            ->method('getType')
            ->willReturn(new StringType());
        /* @var $typeFactoryStub TypeFactoryInterface */

        $typeReaderStub = $this->getMockBuilder(TypeReaderInterface::class)->getMock();
        $typeReaderStub->expects(self::exactly(2))
            ->method('resolveType')
            ->willReturn($typedContextStub);
        /* @var $typeReaderStub TypeReaderInterface */

        $reflPropertyStub1 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflPropertyStub1->expects(self::once())
            ->method('getName')
            ->willReturn('property1');
        $reflPropertyStub1->expects(self::once())
            ->method('setAccessible');

        $reflPropertyStub2 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflPropertyStub2->expects(self::once())
            ->method('getName')
            ->willReturn('property2');
        $reflPropertyStub2->expects(self::once())
            ->method('setAccessible');

        $reflClassStub = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClassStub->expects(self::once())
            ->method('getProperties')
            ->willReturn([$reflPropertyStub1, $reflPropertyStub2]);
        $reflClassStub->expects(self::once())
            ->method('getParentClass')
            ->willReturn(false);

        /* @var $reflClassStub ReflectionClass */
        /* @var $annotationReaderStub AnnotationReader */

        $reader = new PropertyReader(
            $annotationReaderStub,
            $typeFactoryStub,
            [$typeReaderStub]
        );
        $propertyMetadata = $reader->getPropertyMetadata($reflClassStub);

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
     *
     */
    public function testGetPropertyMetadataWithParentClass(): void
    {
        $typedContextStub = $this->getMockBuilder(StringTypedPropertyContextInterface::class)->getMock();

        $annotationReaderStub = $this->getMockBuilder(AnnotationReader::class)->getMock();
        $annotationReaderStub->expects(self::exactly(3))
            ->method('getPropertyAnnotations')
            ->willReturn([]);

        $typeFactoryStub = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();
        $typeFactoryStub->expects(self::exactly(3))
            ->method('getType')
            ->willReturn(new StringType());
        /* @var $typeFactoryStub TypeFactoryInterface */

        $typeReaderStub = $this->getMockBuilder(TypeReaderInterface::class)->getMock();
        $typeReaderStub->expects(self::exactly(3))
            ->method('resolveType')
            ->willReturn($typedContextStub);
        /* @var $typeReaderStub TypeReaderInterface */

        $reflPropertyStub01 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflPropertyStub01->expects(self::once())
            ->method('getName')
            ->willReturn('property01');
        $reflPropertyStub01->expects(self::once())
            ->method('setAccessible');

        $reflClassStub01 = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClassStub01->expects(self::once())
            ->method('getProperties')
            ->willReturn([$reflPropertyStub01]);
        $reflClassStub01->expects(self::once())
            ->method('getParentClass')
            ->willReturn(false);

        $reflPropertyStub1 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflPropertyStub1->expects(self::once())
            ->method('getName')
            ->willReturn('property1');
        $reflPropertyStub1->expects(self::once())
            ->method('setAccessible');

        $reflPropertyStub2 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflPropertyStub2->expects(self::once())
            ->method('getName')
            ->willReturn('property2');
        $reflPropertyStub2->expects(self::once())
            ->method('setAccessible');

        $reflClassStub = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClassStub->expects(self::once())
            ->method('getProperties')
            ->willReturn([$reflPropertyStub1, $reflPropertyStub2]);
        $reflClassStub->expects(self::once())
            ->method('getParentClass')
            ->willReturn($reflClassStub01);

        /* @var $reflClassStub ReflectionClass */
        /* @var $annotationReaderStub AnnotationReader */

        $reader = new PropertyReader(
            $annotationReaderStub,
            $typeFactoryStub,
            [$typeReaderStub]
        );
        $propertyMetadata = $reader->getPropertyMetadata($reflClassStub);

        self::assertInternalType('array', $propertyMetadata);
        self::assertCount(3, $propertyMetadata);
        self::assertArrayHasKey('property01', $propertyMetadata);
        self::assertArrayHasKey('property1', $propertyMetadata);
        self::assertArrayHasKey('property2', $propertyMetadata);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property01']);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property1']);
        self::assertInstanceOf(ReflectionPropertyMetadata::class, $propertyMetadata['property2']);
        self::assertInstanceOf(StringType::class, $propertyMetadata['property01']->getType());
        self::assertInstanceOf(StringType::class, $propertyMetadata['property1']->getType());
        self::assertInstanceOf(StringType::class, $propertyMetadata['property2']->getType());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type declaration missing in class: [a-zA-Z0-9\\]+, property: [a-zA-Z0-9]+/
     */
    public function testGetPropertyMetadataThrowsException(): void
    {
        $propertyAnnotStub1 = $this->getMockBuilder(PropertyInterface::class)->getMock();
        $propertyAnnotStub2 = $this->getMockBuilder(PropertyInterface::class)->getMock();

        $annotationReaderStub = $this->getMockBuilder(AnnotationReader::class)->getMock();
        $annotationReaderStub->expects(self::once())
            ->method('getPropertyAnnotations')
            ->willReturnOnConsecutiveCalls([
                [$propertyAnnotStub1],
                [$propertyAnnotStub2]
            ]);
        /* @var $annotationReaderStub AnnotationReader */

        $typeFactoryStub = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();
        $typeFactoryStub->expects(self::exactly(0))
            ->method('getType');
        /* @var $typeFactoryStub TypeFactoryInterface */

        $typeReaderStub = $this->getMockBuilder(TypeReaderInterface::class)->getMock();
        $typeReaderStub->expects(self::once())
            ->method('resolveType')
            ->willReturn(null);
        /* @var $typeReaderStub TypeReaderInterface */

        $reflDeclaringClassStub = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflDeclaringClassStub->expects(self::once())
            ->method('getName')
            ->willReturn(StringType::class);

        $reflPropertyStub1 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflPropertyStub1->expects(self::exactly(2))
            ->method('getName')
            ->willReturn('property1');
        $reflPropertyStub1->expects(self::once())
            ->method('getDeclaringClass')
            ->willReturn($reflDeclaringClassStub);

        $reflPropertyStub2 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClassStub = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClassStub->expects(self::once())
            ->method('getProperties')
            ->willReturn([$reflPropertyStub1, $reflPropertyStub2]);
        $reflClassStub->expects(self::once())
            ->method('getParentClass')
            ->willReturn(false);
        /* @var $reflClassStub ReflectionClass */

        $reader = new PropertyReader($annotationReaderStub, $typeFactoryStub, [$typeReaderStub]);
        $reader->getPropertyMetadata($reflClassStub);
    }
}
