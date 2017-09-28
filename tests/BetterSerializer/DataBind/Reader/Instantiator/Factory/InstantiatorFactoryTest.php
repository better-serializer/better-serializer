<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use LogicException;
use RuntimeException;

/**
 * Class InstantiatorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory
 */
class InstantiatorFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewInstantiator(): void
    {
        $metaData = $this->getMockBuilder(MetaDataInterface::class)->getMock();
        $instantiatorResult = $this->getMockBuilder(InstantiatorResultInterface::class)->getMock();

        $chainedFactory = $this->getMockBuilder(ChainedInstantiatorFactoryInterface::class)
            ->getMock();
        $chainedFactory->expects(self::once())
            ->method('isApplicable')
            ->with($metaData)
            ->willReturn(true);
        $chainedFactory->expects(self::once())
            ->method('newInstantiator')
            ->with($metaData)
            ->willReturn($instantiatorResult);

        /* @var $metaData MetaDataInterface */
        $factory = new InstantiatorFactory([$chainedFactory]);
        $result = $factory->newInstantiator($metaData);

        self::assertInstanceOf(InstantiatorResultInterface::class, $result);
        self::assertSame($instantiatorResult, $result);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Unable to create instantiator for class: '[a-zA-Z0-9_\\]+'\./
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testNewParamProcessorThrowsRuntimeException(): void
    {
        $classMetaData = $this->getMockBuilder(ClassMetaDataInterface::class)->getMock();
        $classMetaData->expects(self::once())
            ->method('getClassName')
            ->willReturn(Car::class);

        $metaData = $this->getMockBuilder(MetaDataInterface::class)->getMock();
        $metaData->expects(self::once())
            ->method('getClassMetaData')
            ->willReturn($classMetaData);

        $chainedFactory = $this->getMockBuilder(ChainedInstantiatorFactoryInterface::class)
            ->getMock();
        $chainedFactory->expects(self::once())
            ->method('isApplicable')
            ->with($metaData)
            ->willReturn(false);

        /* @var $metaData MetaDataInterface */
        $factory = new InstantiatorFactory([$chainedFactory]);
        $factory->newInstantiator($metaData);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Chained factories not provided./
     */
    public function testConstructionWithoutProcessorsThrowsLogicException(): void
    {
        new InstantiatorFactory([]);
    }
}
