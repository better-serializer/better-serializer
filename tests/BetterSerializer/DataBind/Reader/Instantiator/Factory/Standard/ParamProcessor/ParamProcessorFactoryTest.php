<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor;

use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use LogicException;

/**
 * Class ParamProcessorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor
 */
class ParamProcessorFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewParamProcessor(): void
    {
        $tuple = $this->getMockBuilder(PropertyWithConstructorParamTupleInterface::class)->getMock();
        $paramProcessor = $this->getMockBuilder(ParamProcessorInterface::class)->getMock();

        $chainedFactory = $this->getMockBuilder(Chain\ChainedParamProcessorFactoryInterface::class)
            ->getMock();
        $chainedFactory->expects(self::once())
            ->method('isApplicable')
            ->with($tuple)
            ->willReturn(true);
        $chainedFactory->expects(self::once())
            ->method('newChainedParamProcessorFactory')
            ->with($tuple)
            ->willReturn($paramProcessor);

        /* @var $tuple PropertyWithConstructorParamTupleInterface */
        $factory = new ParamProcessorFactory([$chainedFactory]);
        $returnedProcessor = $factory->newParamProcessor($tuple);

        self::assertSame($paramProcessor, $returnedProcessor);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Unable to create constructor param processor for type: '[a-zA-Z0-9_]+'\./
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testNewParamProcessorThrowsRuntimeException(): void
    {
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $type->expects(self::once())
            ->method('__toString')
            ->willReturn('testType');

        $tuple = $this->getMockBuilder(PropertyWithConstructorParamTupleInterface::class)->getMock();
        $tuple->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $chainedFactory = $this->getMockBuilder(Chain\ChainedParamProcessorFactoryInterface::class)
            ->getMock();
        $chainedFactory->expects(self::once())
            ->method('isApplicable')
            ->with($tuple)
            ->willReturn(false);

        /* @var $tuple PropertyWithConstructorParamTupleInterface */
        $factory = new ParamProcessorFactory([$chainedFactory]);
        $factory->newParamProcessor($tuple);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Chained factories not provided./
     */
    public function testConstructionWithoutProcessorsThrowsLogicException(): void
    {
        new ParamProcessorFactory([]);
    }
}
