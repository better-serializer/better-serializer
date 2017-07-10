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
use BetterSerializer\DataBind\MetaData\Reflection\ReflectionClassHelperInterface;
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
class PropertiesReaderTest extends TestCase
{

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Type readers missing.
     */
    public function testConstructionWithEmptyTypeReadersThrowsException(): void
    {
        /* @var $reflClassHelper ReflectionClassHelperInterface */
        $reflClassHelper = $this->getMockBuilder(ReflectionClassHelperInterface::class)->getMock();

        /* @var $annotationReaderStub AnnotationReader */
        $annotationReaderStub = $this->getMockBuilder(AnnotationReader::class)->getMock();
        /* @var $typeFactoryStub TypeFactoryInterface */
        $typeFactoryStub = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();

        new PropertiesReader(
            $reflClassHelper,
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

        $reflClassHelper = $this->getMockBuilder(ReflectionClassHelperInterface::class)->getMock();
        $reflClassHelper->expects(self::once())
            ->method('getProperties')
            ->with($reflClassStub)
            ->willReturn([$reflPropertyStub1, $reflPropertyStub2]);

        /* @var $reflClassHelper ReflectionClassHelperInterface */
        /* @var $reflClassStub ReflectionClass */
        /* @var $annotationReaderStub AnnotationReader */

        $reader = new PropertiesReader(
            $reflClassHelper,
            $annotationReaderStub,
            $typeFactoryStub,
            [$typeReaderStub]
        );
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

        $reflClassHelper = $this->getMockBuilder(ReflectionClassHelperInterface::class)->getMock();
        $reflClassHelper->expects(self::once())
            ->method('getProperties')
            ->with($reflClassStub)
            ->willReturn([$reflPropertyStub1, $reflPropertyStub2]);

        /* @var $reflClassHelper ReflectionClassHelperInterface */
        /* @var $reflClassStub ReflectionClass */

        $reader = new PropertiesReader($reflClassHelper, $annotationReaderStub, $typeFactoryStub, [$typeReaderStub]);
        $reader->getPropertiesMetadata($reflClassStub);
    }
}
