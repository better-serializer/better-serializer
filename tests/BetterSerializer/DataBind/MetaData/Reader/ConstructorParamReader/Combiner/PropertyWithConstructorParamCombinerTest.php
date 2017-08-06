<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained\ChainedCombinerInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionParameter;
use RuntimeException;

/**
 * Class PropertyWithParamCombinerTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner
 */
class PropertyWithConstructorParamCombinerTest extends TestCase
{

    /**
     *
     */
    public function testCombine(): void
    {
        $reflectionParam = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $constructor->expects(self::once())
            ->method('getParameters')
            ->willReturn([$reflectionParam, $reflectionParam]);

        $propertyName = 'test';
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();

        $badCombiner = $this->getMockBuilder(ChainedCombinerInterface::class)->getMock();
        $badCombiner->expects(self::once())
            ->method('initialize');
        $badCombiner->expects(self::exactly(2))
            ->method('combineWithParameter')
            ->with($reflectionParam)
            ->willReturnOnConsecutiveCalls(null, null);

        $tuple = $this->getMockBuilder(Context\PropertyWithConstructorParamTupleInterface::class)->getMock();
        $goodCombiner = $this->getMockBuilder(ChainedCombinerInterface::class)->getMock();
        $goodCombiner->expects(self::once())
            ->method('initialize');
        $goodCombiner->expects(self::exactly(2))
            ->method('combineWithParameter')
            ->with($reflectionParam)
            ->willReturnOnConsecutiveCalls($tuple, null);

        /* @var ReflectionMethod $constructor */
        $combiner = new PropertyWithConstructorParamCombiner([$badCombiner, $goodCombiner]);
        $tuples = $combiner->combine($constructor, [$propertyName => $propertyMetaData]);

        self::assertInternalType('array', $tuples);
        self::assertCount(1, $tuples);
        self::assertArrayHasKey(0, $tuples);
        self::assertSame($tuple, $tuples[0]);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Chained combiners missing.
     */
    public function testConstructorThrowsIfChainedCombinersMissing(): void
    {
        new PropertyWithConstructorParamCombiner([]);
    }
}
