<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Detector\DetectorInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Detector\ResultInterface as DetectorResultInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ParserChainInterface as FormatParserInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ContextInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResolverChainInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ResultInterface as ResolverResultInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;
use BetterSerializer\Dto\Car;
use BetterSerializer\Reflection\ReflectionClassInterface;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class StringTypeParserTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testParseWithParentContext(): void
    {
        $type = Collection::class;
        $typeClass = $this->createMock(TypeClassEnumInterface::class);
        $valueType = 'string';
        $valueTypeClass = $this->createMock(TypeClassEnumInterface::class);
        $keyType = 'int';
        $keyTypeClass = $this->createMock(TypeClassEnumInterface::class);
        $namespace = 'Test';
        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->expects(self::once())
            ->method('getNamespaceName')
            ->willReturn($namespace);
        $paramString = 'param=1';

        $formatResult1 = $this->createMock(FormatResultInterface::class);
        $formatResult1->expects(self::exactly(2))
            ->method('getParameters')
            ->willReturn($paramString);
        $formatResult1->expects(self::exactly(2))
            ->method('getNestedValueType')
            ->willReturn($valueType);
        $formatResult1->expects(self::exactly(2))
            ->method('getNestedKeyType')
            ->willReturn($keyType);

        $formatResult2 = $this->createMock(FormatResultInterface::class);
        $formatResult2->expects(self::once())
            ->method('getParameters')
            ->willReturn(null);
        $formatResult2->expects(self::once())
            ->method('getNestedValueType')
            ->willReturn(null);
        $formatResult2->expects(self::once())
            ->method('getNestedKeyType')
            ->willReturn(null);

        $formatResult3 = $this->createMock(FormatResultInterface::class);
        $formatResult3->expects(self::once())
            ->method('getParameters')
            ->willReturn(null);
        $formatResult3->expects(self::once())
            ->method('getNestedValueType')
            ->willReturn(null);
        $formatResult3->expects(self::once())
            ->method('getNestedKeyType')
            ->willReturn(null);

        $detectorResult = $this->createMock(DetectorResultInterface::class);
        $detectorResult->expects(self::exactly(3))
            ->method('getFormatResult')
            ->willReturnOnConsecutiveCalls($formatResult1, $formatResult2, $formatResult3);

        $typeResult1 = $this->createMock(ResolverResultInterface::class);
        $typeResult1->expects(self::once())
            ->method('getTypeName')
            ->willReturn($type);
        $typeResult1->expects(self::once())
            ->method('getTypeClass')
            ->willReturn($typeClass);

        $typeResult2 = $this->createMock(ResolverResultInterface::class);
        $typeResult2->expects(self::once())
            ->method('getTypeName')
            ->willReturn($valueType);
        $typeResult2->expects(self::once())
            ->method('getTypeClass')
            ->willReturn($valueTypeClass);

        $typeResult3 = $this->createMock(ResolverResultInterface::class);
        $typeResult3->expects(self::once())
            ->method('getTypeName')
            ->willReturn($keyType);
        $typeResult3->expects(self::once())
            ->method('getTypeClass')
            ->willReturn($keyTypeClass);

        $detectorResult->expects(self::exactly(3))
            ->method('getResolverResult')
            ->willReturnOnConsecutiveCalls($typeResult1, $typeResult2, $typeResult3);

        $detector = $this->createMock(DetectorInterface::class);
        $detector->expects(self::exactly(3))
            ->method('detectType')
            ->withConsecutive(
                [$type, $this->isInstanceOf(ContextInterface::class)],
                [$valueType, $this->isInstanceOf(ContextInterface::class)],
                [$keyType, $this->isInstanceOf(ContextInterface::class)]
            )
            ->willReturn($detectorResult);

        $parameters = $this->createMock(ParametersInterface::class);
        $parametersParser = $this->createMock(ParametersParserInterface::class);
        $parametersParser->expects(self::once())
            ->method('parseParameters')
            ->with($paramString)
            ->willReturn($parameters);

        $parser = new StringTypeParser($detector, $parametersParser);
        $stringFormType = $parser->parseWithParentContext($type, $reflectionClass);

        self::assertSame($type, $stringFormType->getStringType());
        self::assertSame($namespace, $stringFormType->getNamespace());
        self::assertSame($typeClass, $stringFormType->getTypeClass());
        self::assertSame($parameters, $stringFormType->getParameters());

        $nestedValue = $stringFormType->getCollectionValueType();

        self::assertNotNull($nestedValue);
        self::assertInstanceOf(ContextStringFormTypeInterface::class, $nestedValue);
        self::assertSame($valueType, $nestedValue->getStringType());
        self::assertSame($namespace, $nestedValue->getNamespace());
        self::assertSame($valueTypeClass, $nestedValue->getTypeClass());
        self::assertNull($nestedValue->getParameters());
        self::assertNull($nestedValue->getCollectionValueType());
        self::assertNull($nestedValue->getCollectionKeyType());

        $nestedKey = $stringFormType->getCollectionKeyType();

        self::assertNotNull($nestedKey);
        self::assertInstanceOf(ContextStringFormTypeInterface::class, $nestedKey);
        self::assertSame($keyType, $nestedKey->getStringType());
        self::assertSame($namespace, $nestedKey->getNamespace());
        self::assertSame($keyTypeClass, $nestedKey->getTypeClass());
        self::assertNull($nestedKey->getParameters());
        self::assertNull($nestedKey->getCollectionValueType());
        self::assertNull($nestedKey->getCollectionKeyType());
    }

    /**
     *
     */
    public function testParseSimple(): void
    {
        $type = Car::class;
        $namespace = '';
        $typeClass = $this->createMock(TypeClassEnumInterface::class);
        $valueType = null;
        $keyType = null;
        $paramString = 'param=1';

        $formatResult = $this->createMock(FormatResultInterface::class);
        $formatResult->expects(self::exactly(2))
            ->method('getParameters')
            ->willReturn($paramString);
        $formatResult->expects(self::once())
            ->method('getNestedValueType')
            ->willReturn($valueType);
        $formatResult->expects(self::once())
            ->method('getNestedKeyType')
            ->willReturn($keyType);

        $typeResult = $this->createMock(ResolverResultInterface::class);
        $typeResult->expects(self::once())
            ->method('getTypeName')
            ->willReturn($type);
        $typeResult->expects(self::once())
            ->method('getTypeClass')
            ->willReturn($typeClass);

        $detectorResult = $this->createMock(DetectorResultInterface::class);
        $detectorResult->expects(self::once())
            ->method('getFormatResult')
            ->willReturn($formatResult);
        $detectorResult->expects(self::once())
            ->method('getResolverResult')
            ->willReturn($typeResult);

        $detector = $this->createMock(DetectorInterface::class);
        $detector->expects(self::once())
            ->method('detectType')
            ->with($type, $this->isInstanceOf(ContextInterface::class))
            ->willReturn($detectorResult);

        $parameters = $this->createMock(ParametersInterface::class);
        $parametersParser = $this->createMock(ParametersParserInterface::class);
        $parametersParser->expects(self::once())
            ->method('parseParameters')
            ->with($paramString)
            ->willReturn($parameters);

        $parser = new StringTypeParser($detector, $parametersParser);
        $stringFormType = $parser->parseSimple($type);

        self::assertSame($type, $stringFormType->getStringType());
        self::assertSame($namespace, $stringFormType->getNamespace());
        self::assertSame($typeClass, $stringFormType->getTypeClass());
        self::assertSame($parameters, $stringFormType->getParameters());
        self::assertNull($stringFormType->getCollectionValueType());
        self::assertNull($stringFormType->getCollectionKeyType());
    }
}
