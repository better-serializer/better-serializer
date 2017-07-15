<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\ExcludePropertiesMetaData;
use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorResultInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\ParamProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\ParamProcessor\ParamProcessorInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Standard\StandardInstantiator;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class StandardInstantiatorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard
 */
class StandardInstantiatorFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewInstantiator(): void
    {
        $clasName = Car::class;
        $tuple = $this->getMockBuilder(PropertyWithConstructorParamTupleInterface::class)->getMock();
        $classMetaData = $this->getMockBuilder(ClassMetaDataInterface::class)->getMock();
        $classMetaData->expects(self::once())
            ->method('getClassName')
            ->willReturn($clasName);

        $metaData = $this->getMockBuilder(MetaDataInterface::class)->getMock();
        $metaData->expects(self::once())
            ->method('getClassMetaData')
            ->willReturn($classMetaData);
        $metaData->expects(self::once())
            ->method('getPropertyWithConstructorParamTuples')
            ->willReturn([$tuple, $tuple]);

        $paramProcessor = $this->getMockBuilder(ParamProcessorInterface::class)->getMock();

        $procFactory = $this->getMockBuilder(ParamProcessorFactoryInterface::class)->getMock();
        $procFactory->expects(self::exactly(2))
            ->method('newParamProcessor')
            ->with($tuple)
            ->willReturn($paramProcessor);

        /* @var $procFactory ParamProcessorFactoryInterface */
        /* @var $metaData MetaDataInterface */
        $factory = new StandardInstantiatorFactory($procFactory);
        $result = $factory->newInstantiator($metaData);

        self::assertInstanceOf(InstantiatorResultInterface::class, $result);
        self::assertInstanceOf(StandardInstantiator::class, $result->getInstantiator());
        self::assertInstanceOf(ExcludePropertiesMetaData::class, $result->getProcessedMetaData());
    }

    /**
     * @dataProvider getIsApplicableDataProvider
     * @param bool $expectedResult
     */
    public function testIsApplicable(bool $expectedResult): void
    {
        $metaData = $this->getMockBuilder(MetaDataInterface::class)->getMock();
        $metaData->expects(self::once())
            ->method('isInstantiableByConstructor')
            ->willReturn($expectedResult);

        $procFactory = $this->getMockBuilder(ParamProcessorFactoryInterface::class)->getMock();

        /* @var $procFactory ParamProcessorFactoryInterface */
        /* @var $metaData MetaDataInterface */
        $factory = new StandardInstantiatorFactory($procFactory);
        $result = $factory->isApplicable($metaData);

        self::assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function getIsApplicableDataProvider(): array
    {
        return [
            [true],
            [false],
        ];
    }
}
