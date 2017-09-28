<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Annotations\BoundToPropertyInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context\InitializeContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;

/**
 * Class AnnotationCombinerTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained
 */
class AnnotationCombinerTest extends TestCase
{

    /**
     *
     */
    public function testCombineWithParameterApplicable(): void
    {
        $paramName = 'test';
        $argName = 'testArg';

        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $constructor = $this->createMock(ReflectionMethodInterface::class);

        $propertiesMetaData = $this->createMock(ShrinkingPropertiesMetaDataInterface::class);
        $propertiesMetaData->expects(self::once())
            ->method('shrinkBy')
            ->with($paramName)
            ->willReturn($propertyMetaData);

        $wrongAnnotation = $this->createMock(AnnotationInterface::class);
        $goodAnnotation = $this->createMock(BoundToPropertyInterface::class);
        $goodAnnotation->expects(self::once())
            ->method('getArgumentName')
            ->willReturn($argName);
        $goodAnnotation->expects(self::once())
            ->method('getPropertyName')
            ->willReturn($paramName);

        $annotationReader = $this->getMockBuilder(AnnotationReader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $annotationReader->expects(self::once())
            ->method('getMethodAnnotations')
            ->willReturn([$wrongAnnotation, $goodAnnotation]);

        $context = $this->createMock(InitializeContextInterface::class);
        $context->expects(self::once())
            ->method('getConstructor')
            ->willReturn($constructor);
        $context->expects(self::once())
            ->method('getPropertiesMetaData')
            ->willReturn($propertiesMetaData);

        $parameter = $this->createMock(ReflectionParameterInterface::class);
        $parameter->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($argName);

        /* @var $annotationReader AnnotationReader */
        $combiner = new AnnotationCombiner($annotationReader);
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

        $constructor = $this->createMock(ReflectionMethodInterface::class);
        $propertiesMetaData = $this->createMock(ShrinkingPropertiesMetaDataInterface::class);
        $wrongAnnotation = $this->createMock(AnnotationInterface::class);

        $annotationReader = $this->getMockBuilder(AnnotationReader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $annotationReader->expects(self::once())
            ->method('getMethodAnnotations')
            ->willReturn([$wrongAnnotation]);

        $context = $this->createMock(InitializeContextInterface::class);
        $context->expects(self::once())
            ->method('getConstructor')
            ->willReturn($constructor);
        $context->expects(self::once())
            ->method('getPropertiesMetaData')
            ->willReturn($propertiesMetaData);

        $parameter = $this->createMock(ReflectionParameterInterface::class);
        $parameter->expects(self::once())
            ->method('getName')
            ->willReturn($argName);

        /* @var $annotationReader AnnotationReader */
        $combiner = new AnnotationCombiner($annotationReader);
        $combiner->initialize($context);
        $tuple = $combiner->combineWithParameter($parameter);

        self::assertNull($tuple);
    }
}
