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
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\SimpleParamProcessor;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleParamProcessorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain
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
        $tuple = $this->getMockBuilder(PropertyWithConstructorParamTupleInterface::class)->getMock();
        $tuple->expects(self::once())
            ->method('getOutputKey')
            ->willReturn($key);

        /* @var $tuple PropertyWithConstructorParamTupleInterface */
        $simpleFactory = new SimpleParamProcessorFactory();
        $simpleProcessor = $simpleFactory->newChainedParamProcessorFactory($tuple);

        self::assertInstanceOf(SimpleParamProcessor::class, $simpleProcessor);
    }

    /**
     * @dataProvider isApplicableDataProvider
     * @param TypeInterface $type
     * @param bool $expectedResult
     */
    public function testIsApplicable(TypeInterface $type, bool $expectedResult): void
    {
        $tuple = $this->getMockBuilder(PropertyWithConstructorParamTupleInterface::class)->getMock();
        $tuple->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        /* @var $tuple PropertyWithConstructorParamTupleInterface */
        $simpleFactory = new SimpleParamProcessorFactory();
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
            [new ObjectType(Car::class), false],
            [new StringType(), true],
        ];
    }
}
