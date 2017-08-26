<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained\ChainedCombinerInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;
use PHPUnit\Framework\TestCase;
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
        $reflectionParam = $this->createMock(ReflectionParameterInterface::class);

        $constructor = $this->createMock(ReflectionMethodInterface::class);
        $constructor->expects(self::once())
            ->method('getParameters')
            ->willReturn([$reflectionParam, $reflectionParam]);

        $propertyName = 'test';
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);

        $badCombiner = $this->createMock(ChainedCombinerInterface::class);
        $badCombiner->expects(self::once())
            ->method('initialize');
        $badCombiner->expects(self::exactly(2))
            ->method('combineWithParameter')
            ->with($reflectionParam)
            ->willReturnOnConsecutiveCalls(null, null);

        $tuple = $this->createMock(Context\PropertyWithConstructorParamTupleInterface::class);
        $goodCombiner = $this->createMock(ChainedCombinerInterface::class);
        $goodCombiner->expects(self::once())
            ->method('initialize');
        $goodCombiner->expects(self::exactly(2))
            ->method('combineWithParameter')
            ->with($reflectionParam)
            ->willReturnOnConsecutiveCalls($tuple, null);

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
