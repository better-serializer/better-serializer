<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader;

use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reflection\ReflectionClassHelperInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionType;
use LogicException;

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
        $name1 = 'name1';
        $name2 = 'name2';

        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $type->expects(self::once())
            ->method('equals')
            ->with($type)
            ->willReturn(true);

        $typeFactory = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();
        $typeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $reflType = $this->getMockBuilder(ReflectionType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $param1 = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $param1->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name1);
        $param1->expects(self::once())
            ->method('getType')
            ->willReturn($reflType);

        $param2 = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $param2->expects(self::once())
            ->method('getName')
            ->willReturn($name2);

        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $constructor->expects(self::once())
            ->method('getParameters')
            ->willReturn([$param1, $param2]);

        $property1 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass->expects(self::once())
            ->method('getConstructor')
            ->willReturn($constructor);

        $propertyMetaData1 = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $propertyMetaData1->expects(self::once())
            ->method('getOutputKey')
            ->willReturn($name1);
        $propertyMetaData1->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $reflClassHelper = $this->getMockBuilder(ReflectionClassHelperInterface::class)->getMock();
        $reflClassHelper->expects(self::once())
            ->method('getProperty')
            ->with($reflClass, $name1)
            ->willReturn($property1);

        /* @var $reflClassHelper ReflectionClassHelperInterface */
        /* @var $typeFactory TypeFactoryInterface */
        /* @var $reflClass ReflectionClass */
        $reader = new ConstructorParamsReader($reflClassHelper, $typeFactory);
        $constrParamsMetaData = $reader->getConstructorParamsMetadata($reflClass, [$name1 => $propertyMetaData1]);

        self::assertCount(1, $constrParamsMetaData);
        self::assertArrayHasKey($name1, $constrParamsMetaData);
        $paramMetaData = $constrParamsMetaData[$name1];
        self::assertInstanceOf(ConstructorParamMetaDataInterface::class, $paramMetaData);
        self::assertSame($name1, $paramMetaData->getName());
        self::assertSame($name1, $paramMetaData->getPropertyName());
        self::assertSame($type, $paramMetaData->getType());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Instantiator parameter type missing for '[a-zA-Z0-9]+'./
     */
    public function testGetConstructorParamsMetadataThrowsOnReflTypeMissing(): void
    {
        $name1 = 'name1';

        $typeFactory = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();

        $param1 = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $param1->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name1);
        $param1->expects(self::once())
            ->method('getType')
            ->willReturn(null);

        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $constructor->expects(self::once())
            ->method('getParameters')
            ->willReturn([$param1]);

        $property1 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass->expects(self::once())
            ->method('getConstructor')
            ->willReturn($constructor);

        $propertyMetaData1 = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();

        $reflClassHelper = $this->getMockBuilder(ReflectionClassHelperInterface::class)->getMock();
        $reflClassHelper->expects(self::once())
            ->method('getProperty')
            ->with($reflClass, $name1)
            ->willReturn($property1);

        /* @var $reflClassHelper ReflectionClassHelperInterface */

        /* @var $typeFactory TypeFactoryInterface */
        /* @var $reflClass ReflectionClass */
        $reader = new ConstructorParamsReader($reflClassHelper, $typeFactory);
        $reader->getConstructorParamsMetadata($reflClass, [$name1 => $propertyMetaData1]);
    }

    /**
     * @expectedException LogicException
     */
    public function testGetConstructorParamsMetadataThrowsOnNontMatchingTypes(): void
    {
        $msg = "/Types don't match for constructor argument '[a-zA-Z0-9]+' and class parameter '[a-zA-Z0-9]+'./";
        $this->expectExceptionMessageRegExp($msg);
        $name1 = 'name1';

        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $type->expects(self::once())
            ->method('equals')
            ->with($type)
            ->willReturn(false);

        $typeFactory = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();
        $typeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $reflType = $this->getMockBuilder(ReflectionType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $param1 = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $param1->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name1);
        $param1->expects(self::once())
            ->method('getType')
            ->willReturn($reflType);

        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $constructor->expects(self::once())
            ->method('getParameters')
            ->willReturn([$param1]);

        $property1 = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflClass->expects(self::once())
            ->method('getConstructor')
            ->willReturn($constructor);

        $propertyMetaData1 = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $propertyMetaData1->expects(self::once())
            ->method('getOutputKey')
            ->willReturn($name1);
        $propertyMetaData1->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $reflClassHelper = $this->getMockBuilder(ReflectionClassHelperInterface::class)->getMock();
        $reflClassHelper->expects(self::once())
            ->method('getProperty')
            ->with($reflClass, $name1)
            ->willReturn($property1);

        /* @var $reflClassHelper ReflectionClassHelperInterface */

        /* @var $typeFactory TypeFactoryInterface */
        /* @var $reflClass ReflectionClass */
        $reader = new ConstructorParamsReader($reflClassHelper, $typeFactory);
        $reader->getConstructorParamsMetadata($reflClass, [$name1 => $propertyMetaData1]);
    }
}
