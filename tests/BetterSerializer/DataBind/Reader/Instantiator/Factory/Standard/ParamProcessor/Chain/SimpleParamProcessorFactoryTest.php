<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain;

use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\BooleanType;
use BetterSerializer\DataBind\MetaData\Type\FloatType;
use BetterSerializer\DataBind\MetaData\Type\IntegerType;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\SimpleParamProcessor;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SimpleParamProcessorFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewParamProcessor(): void
    {
        $key = 'test';
        $tuple = $this->createMock(PropertyWithConstructorParamTupleInterface::class);
        $nameTranslator = $this->createMock(TranslatorInterface::class);
        $nameTranslator->expects(self::once())
            ->method('translate')
            ->willReturn($key);

        $simpleFactory = new SimpleParamProcessorFactory($nameTranslator);
        $simpleProcessor = $simpleFactory->newChainedParamProcessorFactory($tuple);

        self::assertInstanceOf(SimpleParamProcessor::class, $simpleProcessor);
    }

    /**
     * @dataProvider isApplicableDataProvider
     * @param TypeInterface $type
     * @param bool $expectedResult
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \ReflectionException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testIsApplicable(TypeInterface $type, bool $expectedResult): void
    {
        $tuple = $this->createMock(PropertyWithConstructorParamTupleInterface::class);
        $tuple->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $nameTranslator = $this->createMock(TranslatorInterface::class);

        $simpleFactory = new SimpleParamProcessorFactory($nameTranslator);
        $result = $simpleFactory->isApplicable($tuple);

        self::assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function isApplicableDataProvider(): array
    {
        return [
            [new ArrayType(new StringType()), false],
            [new BooleanType(), true],
            [new FloatType(), true],
            [new IntegerType(), true],
            [new NullType(), true],
            [new ClassType(Car::class), false],
            [new StringType(), true],
        ];
    }
}
