<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Annotations\BoundToPropertyInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context\InitializeContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionParameter;

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

        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();

        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();

        $propertiesMetaData = $this->getMockBuilder(ShrinkingPropertiesMetaDataInterface::class)->getMock();
        $propertiesMetaData->expects(self::once())
            ->method('shrinkBy')
            ->with($paramName)
            ->willReturn($propertyMetaData);

        $wrongAnnotation = $this->getMockBuilder(AnnotationInterface::class)->getMock();
        $goodAnnotation = $this->getMockBuilder(BoundToPropertyInterface::class)->getMock();
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

        $context = $this->getMockBuilder(InitializeContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getConstructor')
            ->willReturn($constructor);
        $context->expects(self::once())
            ->method('getPropertiesMetaData')
            ->willReturn($propertiesMetaData);

        $parameter = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parameter->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($argName);

        /* @var $annotationReader AnnotationReader */
        /* @var $context InitializeContextInterface */
        /* @var $parameter ReflectionParameter */
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

        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();

        $propertiesMetaData = $this->getMockBuilder(ShrinkingPropertiesMetaDataInterface::class)->getMock();

        $wrongAnnotation = $this->getMockBuilder(AnnotationInterface::class)->getMock();

        $annotationReader = $this->getMockBuilder(AnnotationReader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $annotationReader->expects(self::once())
            ->method('getMethodAnnotations')
            ->willReturn([$wrongAnnotation]);

        $context = $this->getMockBuilder(InitializeContextInterface::class)->getMock();
        $context->expects(self::once())
            ->method('getConstructor')
            ->willReturn($constructor);
        $context->expects(self::once())
            ->method('getPropertiesMetaData')
            ->willReturn($propertiesMetaData);

        $parameter = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parameter->expects(self::once())
            ->method('getName')
            ->willReturn($argName);

        /* @var $annotationReader AnnotationReader */
        /* @var $context InitializeContextInterface */
        /* @var $parameter ReflectionParameter */
        $combiner = new AnnotationCombiner($annotationReader);
        $combiner->initialize($context);
        $tuple = $combiner->combineWithParameter($parameter);

        self::assertNull($tuple);
    }
}
