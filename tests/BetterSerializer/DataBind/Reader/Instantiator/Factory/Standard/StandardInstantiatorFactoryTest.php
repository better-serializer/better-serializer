<?php
declare(strict_types=1);

/*
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
use BetterSerializer\Reflection\ReflectionClassInterface;
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
        $tuple = $this->createMock(PropertyWithConstructorParamTupleInterface::class);

        $reflectionClass = $this->createMock(ReflectionClassInterface::class);

        $classMetaData = $this->createMock(ClassMetaDataInterface::class);
        $classMetaData->expects(self::once())
            ->method('getReflectionClass')
            ->willReturn($reflectionClass);

        $metaData = $this->createMock(MetaDataInterface::class);
        $metaData->expects(self::once())
            ->method('getClassMetaData')
            ->willReturn($classMetaData);
        $metaData->expects(self::once())
            ->method('getPropertyWithConstructorParamTuples')
            ->willReturn([$tuple, $tuple]);

        $paramProcessor = $this->createMock(ParamProcessorInterface::class);

        $procFactory = $this->createMock(ParamProcessorFactoryInterface::class);
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
        $metaData = $this->createMock(MetaDataInterface::class);
        $metaData->expects(self::once())
            ->method('isInstantiableByConstructor')
            ->willReturn($expectedResult);

        $procFactory = $this->createMock(ParamProcessorFactoryInterface::class);

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
