<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader;

use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\TypeReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use PHPUnit\Framework\TestCase;
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
        $type = $this->createMock(TypeInterface::class);
        $constructor = $this->createMock(ReflectionMethodInterface::class);
        $propertiesMetaData = [];

        $reflClass = $this->createMock(ReflectionClassInterface::class);
        $reflClass->expects(self::once())
            ->method('getConstructor')
            ->willReturn($constructor);

        $propertyType = $this->createMock(TypeInterface::class);
        $propertyType->expects(self::once())
            ->method('isCompatibleWith')
            ->with($type)
            ->willReturn(true);

        $tuple = $this->createMock(Combiner\Context\PropertyWithConstructorParamTupleInterface::class);
        $tuple->expects(self::exactly(2))
            ->method('getParamName')
            ->willReturn($name);
        $tuple->expects(self::exactly(2))
            ->method('getPropertyName')
            ->willReturn($name);
        $tuple->expects(self::once())
            ->method('getPropertyType')
            ->willReturn($propertyType);

        $combiner = $this->createMock(Combiner\PropertyWithConstructorParamCombinerInterface::class);
        $combiner->expects(self::once())
            ->method('combine')
            ->with($constructor, $propertiesMetaData)
            ->willReturn([$tuple]);

        $typeReader = $this->createMock(TypeReaderInterface::class);
        $typeReader->expects(self::once())
            ->method('getParameterTypes')
            ->with($constructor)
            ->willReturn([$name => $type]);

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
     *
     */
    public function testGetConstructorParamsMetadataReturnsEmptyWhenNoConstructor(): void
    {
        $propertiesMetaData = [];

        $reflClass = $this->createMock(ReflectionClassInterface::class);
        $reflClass->expects(self::once())
            ->method('getConstructor')
            ->willReturn(null);

        $combiner = $this->createMock(Combiner\PropertyWithConstructorParamCombinerInterface::class);
        $typeReader = $this->createMock(TypeReaderInterface::class);

        $reader = new ConstructorParamsReader($combiner, $typeReader);
        $constrParamsMetaData = $reader->getConstructorParamsMetadata($reflClass, $propertiesMetaData);

        self::assertInternalType('array', $constrParamsMetaData);
        self::assertCount(0, $constrParamsMetaData);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Parameter type missing for '[a-zA-Z0-9]+'/
     */
    public function testGetConstructorParamsMetadataWillThrowRuntimeException(): void
    {
        $name = 'name';
        $constructor = $this->createMock(ReflectionMethodInterface::class);
        $propertiesMetaData = [];

        $reflClass = $this->createMock(ReflectionClassInterface::class);
        $reflClass->expects(self::once())
            ->method('getConstructor')
            ->willReturn($constructor);

        $tuple = $this->createMock(Combiner\Context\PropertyWithConstructorParamTupleInterface::class);
        $tuple->expects(self::once())
            ->method('getParamName')
            ->willReturn($name);

        $combiner = $this->createMock(Combiner\PropertyWithConstructorParamCombinerInterface::class);
        $combiner->expects(self::once())
            ->method('combine')
            ->with($constructor, $propertiesMetaData)
            ->willReturn([$tuple]);

        $typeReader = $this->createMock(TypeReaderInterface::class);
        $typeReader->expects(self::once())
            ->method('getParameterTypes')
            ->with($constructor)
            ->willReturn([]);

        $reader = new ConstructorParamsReader($combiner, $typeReader);
        $reader->getConstructorParamsMetadata($reflClass, $propertiesMetaData);
    }
}
