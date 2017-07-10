<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\MetaData;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ClassReader\ClassReader;
use BetterSerializer\DataBind\MetaData\Reader\ClassReader\ClassReaderInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\ConstructorParamsReaderInterface;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\PropertiesReader;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\PropertiesReaderInterface;
use BetterSerializer\Dto\Car;
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
        $classMetadata = $this->getMockBuilder(ClassMetaDataInterface::class)->getMock();

        $classReader = $this->getMockBuilder(ClassReaderInterface::class)->getMock();
        $classReader->expects(self::once())
            ->method('getClassMetadata')
            ->willReturn($classMetadata);
        /* @var $classReader ClassReader */

        $propertyReader = $this->getMockBuilder(PropertiesReaderInterface::class)->getMock();
        $propertyReader->expects(self::once())
            ->method('getPropertiesMetadata')
            ->willReturn([]);
        /* @var $propertyReader PropertiesReader */

        $constrParamsReader = $this->getMockBuilder(ConstructorParamsReaderInterface::class)->getMock();
        $constrParamsReader->expects(self::once())
            ->method('getConstructorParamsMetadata')
            ->willReturn([]);
        /* @var $constrParamsReader ConstructorParamsReaderInterface */

        $reader = new Reader($classReader, $propertyReader, $constrParamsReader);
        $metaData = $reader->read(Car::class);

        self::assertInstanceOf(MetaData::class, $metaData);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Class "[a-zA-Z0-9_]+" is not user-defined/
     */
    public function testReadSystemClassThrowsException(): void
    {
        $classReader = $this->getMockBuilder(ClassReaderInterface::class)->getMock();
        /* @var $classReader ClassReader */

        $propertyReader = $this->getMockBuilder(PropertiesReaderInterface::class)->getMock();
        /* @var $propertyReader PropertiesReader */

        $constrParamsReader = $this->getMockBuilder(ConstructorParamsReaderInterface::class)->getMock();
        /* @var $constrParamsReader ConstructorParamsReaderInterface */

        $reader = new Reader($classReader, $propertyReader, $constrParamsReader);
        $reader->read(ReflectionClass::class);
    }
}
