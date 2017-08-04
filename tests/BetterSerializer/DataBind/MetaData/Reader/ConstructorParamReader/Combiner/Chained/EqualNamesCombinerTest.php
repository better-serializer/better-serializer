<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context\InitializeContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use PHPUnit\Framework\TestCase;
use ReflectionParameter;

/**
 * Class EqualNamesCombinerTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained
 */
class EqualNamesCombinerTest extends TestCase
{

    /**
     *
     */
    public function testCombineWithParameterApplicable(): void
    {
        $paramName = 'test';
        $argName = 'test';

        $propertyMetaData = $this->getMockBuilder(ReflectionPropertyMetaDataInterface::class)->getMock();

        $propertiesMetaData = $this->getMockBuilder(ShrinkingPropertiesMetaDataInterface::class)->getMock();
        $propertiesMetaData->expects(self::once())
            ->method('hasProperty')
            ->with($paramName)
            ->willReturn(true);
        $propertiesMetaData->expects(self::once())
            ->method('shrinkBy')
            ->with($paramName)
            ->willReturn($propertyMetaData);

        $context = $this->getMockBuilder(InitializeContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getPropertiesMetaData')
            ->willReturn($propertiesMetaData);

        $parameter = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parameter->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($argName);

        /* @var $context InitializeContextInterface */
        /* @var $parameter ReflectionParameter */
        $combiner = new EqualNamesCombiner();
        $combiner->initialize($context);
        $tuple = $combiner->combineWithParameter($parameter);

        self::assertInstanceOf(Context\PropertyWithConstructorParamTuple::class, $tuple);
        self::assertSame($propertyMetaData, $tuple->getClassProperty());
        self::assertSame($parameter, $tuple->getConstructorParam());
    }

    /**
     *
     */
    public function testCombineWithParameterNotApplicable(): void
    {
        $argName = 'testArg';
        $propertiesMetaData = $this->getMockBuilder(ShrinkingPropertiesMetaDataInterface::class)->getMock();

        $context = $this->getMockBuilder(InitializeContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getPropertiesMetaData')
            ->willReturn($propertiesMetaData);

        $parameter = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parameter->expects(self::once())
            ->method('getName')
            ->willReturn($argName);

        /* @var $context InitializeContextInterface */
        /* @var $parameter ReflectionParameter */
        $combiner = new EqualNamesCombiner();
        $combiner->initialize($context);
        $tuple = $combiner->combineWithParameter($parameter);

        self::assertNull($tuple);
    }
}
