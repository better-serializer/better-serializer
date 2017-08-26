<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\MetaData;
use BetterSerializer\DataBind\MetaData\Reader\ClassReader\ClassReaderInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\ConstructorParamsReaderInterface;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\PropertiesReaderInterface;
use BetterSerializer\Dto\Car;
use BetterSerializer\Reflection\Factory\ReflectionClassFactoryInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;
use PHPUnit\Framework\TestCase;
use LogicException;
use ReflectionClass;

/**
 * Class ReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReaderTest extends TestCase
{

    /**
     *
     */
    public function testRead(): void
    {
        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('isUserDefined')
            ->willReturn(true);
        $reflClassFactory = $this->createMock(ReflectionClassFactoryInterface::class);
        $reflClassFactory->method('newReflectionClass')
            ->willReturn($reflectionClass);

        $classMetadata = $this->createMock(ClassMetaDataInterface::class);

        $classReader = $this->createMock(ClassReaderInterface::class);
        $classReader->expects(self::once())
            ->method('getClassMetadata')
            ->willReturn($classMetadata);

        $propertyReader = $this->createMock(PropertiesReaderInterface::class);
        $propertyReader->expects(self::once())
            ->method('getPropertiesMetadata')
            ->willReturn([]);

        $constrParamsReader = $this->createMock(ConstructorParamsReaderInterface::class);
        $constrParamsReader->expects(self::once())
            ->method('getConstructorParamsMetadata')
            ->willReturn([]);

        $reader = new Reader($reflClassFactory, $classReader, $propertyReader, $constrParamsReader);
        $metaData = $reader->read(Car::class);

        self::assertInstanceOf(MetaData::class, $metaData);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Class "[a-zA-Z0-9_]+" is not user-defined/
     */
    public function testReadSystemClassThrowsException(): void
    {
        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('isUserDefined')
            ->willReturn(false);
        $reflectionClass->method('getName')
            ->willReturn(ReflectionClass::class);
        $reflClassFactory = $this->createMock(ReflectionClassFactoryInterface::class);
        $reflClassFactory->method('newReflectionClass')
            ->willReturn($reflectionClass);
        $classReader = $this->createMock(ClassReaderInterface::class);
        $propertyReader = $this->createMock(PropertiesReaderInterface::class);
        $constrParamsReader = $this->createMock(ConstructorParamsReaderInterface::class);

        $reader = new Reader($reflClassFactory, $classReader, $propertyReader, $constrParamsReader);
        $reader->read(ReflectionClass::class);
    }
}
