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
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ComplexParamProcessor;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleParamProcessorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ComplexParamProcessorFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewParamProcessor(): void
    {
        $processor = $this->getMockBuilder(ProcessorInterface::class)->getMock();
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        $tuple = $this->getMockBuilder(PropertyWithConstructorParamTupleInterface::class)->getMock();
        $tuple->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        $processorFactory->expects(self::once())
            ->method('createFromType')
            ->with($type)
            ->willReturn($processor);

        /* @var $tuple PropertyWithConstructorParamTupleInterface */
        /* @var $processorFactory ProcessorFactoryInterface */
        $simpleFactory = new ComplexParamProcessorFactory($processorFactory);
        $simpleProcessor = $simpleFactory->newChainedParamProcessorFactory($tuple);

        self::assertInstanceOf(ComplexParamProcessor::class, $simpleProcessor);
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

        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();

        /* @var $tuple PropertyWithConstructorParamTupleInterface */
        /* @var $processorFactory ProcessorFactoryInterface */
        $simpleFactory = new ComplexParamProcessorFactory($processorFactory);
        $result = $simpleFactory->isApplicable($tuple);

        self::assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function isApplicableDataProvider(): array
    {
        return [
            [new ArrayType(new StringType()), true],
            [new BooleanType(), false],
            [new FloatType(), false],
            [new IntegerType(), false],
            [new NullType(), false],
            [new ClassType(Car::class), true],
            [new StringType(), false],
        ];
    }
}
