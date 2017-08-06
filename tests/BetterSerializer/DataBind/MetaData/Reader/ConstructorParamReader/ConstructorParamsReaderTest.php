<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader;

use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\TypeReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;

/**
 * Class ConstructorParamReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader
 */
class ConstructorParamsReaderTest extends TestCase
{

    /**
     *
     */
    public function testGetConstructorParamsMetadata(): void
    {
        $name = 'name';

        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();

        $propertiesMetaData = [];

        $reflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass->expects(self::once())
            ->method('getConstructor')
            ->willReturn($constructor);

        $propertyType = $this->getMockBuilder(TypeInterface::class)->getMock();
        $propertyType->expects(self::once())
            ->method('isCompatibleWith')
            ->with($type)
            ->willReturn(true);

        $tuple = $this->getMockBuilder(Combiner\Context\PropertyWithConstructorParamTupleInterface::class)
            ->getMock();
        $tuple->expects(self::exactly(2))
            ->method('getParamName')
            ->willReturn($name);
        $tuple->expects(self::once())
            ->method('getPropertyName')
            ->willReturn($name);
        $tuple->expects(self::once())
            ->method('getPropertyType')
            ->willReturn($propertyType);

        $combiner = $this->getMockBuilder(Combiner\PropertyWithConstructorParamCombinerInterface::class)
            ->getMock();
        $combiner->expects(self::once())
            ->method('combine')
            ->with($constructor, $propertiesMetaData)
            ->willReturn([$tuple]);

        $typeReader = $this->getMockBuilder(TypeReaderInterface::class)->getMock();
        $typeReader->expects(self::once())
            ->method('getParameterTypes')
            ->with($constructor)
            ->willReturn([$name => $type]);

        /* @var $combiner Combiner\PropertyWithConstructorParamCombinerInterface */
        /* @var $typeReader TypeReaderInterface */
        /* @var $reflClass ReflectionClass */
        $reader = new ConstructorParamsReader($combiner, $typeReader);
        $constrParamsMetaData = $reader->getConstructorParamsMetadata($reflClass, $propertiesMetaData);

        self::assertCount(1, $constrParamsMetaData);
        self::assertArrayHasKey($name, $constrParamsMetaData);
        $paramMetaData = $constrParamsMetaData[$name];
        self::assertInstanceOf(ConstructorParamMetaDataInterface::class, $paramMetaData);
        self::assertSame($name, $paramMetaData->getName());
        self::assertSame($name, $paramMetaData->getPropertyName());
        self::assertSame($type, $paramMetaData->getType());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Parameter type missing for '[a-zA-Z0-9]+'/
     */
    public function testGetConstructorParamsMetadataWillThrowRuntimeException(): void
    {
        $name = 'name';

        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();

        $propertiesMetaData = [];

        $reflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass->expects(self::once())
            ->method('getConstructor')
            ->willReturn($constructor);

        $tuple = $this->getMockBuilder(Combiner\Context\PropertyWithConstructorParamTupleInterface::class)
            ->getMock();
        $tuple->expects(self::once())
            ->method('getParamName')
            ->willReturn($name);

        $combiner = $this->getMockBuilder(Combiner\PropertyWithConstructorParamCombinerInterface::class)
            ->getMock();
        $combiner->expects(self::once())
            ->method('combine')
            ->with($constructor, $propertiesMetaData)
            ->willReturn([$tuple]);

        $typeReader = $this->getMockBuilder(TypeReaderInterface::class)->getMock();
        $typeReader->expects(self::once())
            ->method('getParameterTypes')
            ->with($constructor)
            ->willReturn([]);

        /* @var $combiner Combiner\PropertyWithConstructorParamCombinerInterface */
        /* @var $typeReader TypeReaderInterface */
        /* @var $reflClass ReflectionClass */
        $reader = new ConstructorParamsReader($combiner, $typeReader);
        $reader->getConstructorParamsMetadata($reflClass, $propertiesMetaData);
    }
}
