<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context\InitializeContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;
use PHPUnit\Framework\TestCase;

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

        $propertyMetaData = $this->createMock(ReflectionPropertyMetaDataInterface::class);

        $propertiesMetaData = $this->createMock(ShrinkingPropertiesMetaDataInterface::class);
        $propertiesMetaData->expects(self::once())
            ->method('hasProperty')
            ->with($paramName)
            ->willReturn(true);
        $propertiesMetaData->expects(self::once())
            ->method('shrinkBy')
            ->with($paramName)
            ->willReturn($propertyMetaData);

        $context = $this->createMock(InitializeContextInterface::class);
        $context->expects(self::once())
            ->method('getPropertiesMetaData')
            ->willReturn($propertiesMetaData);

        $parameter = $this->createMock(ReflectionParameterInterface::class);
        $parameter->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($argName);

        /* @var $context InitializeContextInterface */
        $combiner = new EqualNamesCombiner();
        $combiner->initialize($context);
        $tuple = $combiner->combineWithParameter($parameter);

        self::assertInstanceOf(Context\PropertyWithConstructorParamTuple::class, $tuple);
        self::assertSame($propertyMetaData, $tuple->getPropertyMetaData());
        self::assertSame($parameter, $tuple->getConstructorParam());
    }

    /**
     *
     */
    public function testCombineWithParameterNotApplicable(): void
    {
        $argName = 'testArg';
        $propertiesMetaData = $this->createMock(ShrinkingPropertiesMetaDataInterface::class);

        $context = $this->createMock(InitializeContextInterface::class);
        $context->expects(self::once())
            ->method('getPropertiesMetaData')
            ->willReturn($propertiesMetaData);

        $parameter = $this->createMock(ReflectionParameterInterface::class);
        $parameter->expects(self::once())
            ->method('getName')
            ->willReturn($argName);

        /* @var $context InitializeContextInterface */
        $combiner = new EqualNamesCombiner();
        $combiner->initialize($context);
        $tuple = $combiner->combineWithParameter($parameter);

        self::assertNull($tuple);
    }
}
