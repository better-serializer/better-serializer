<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained\ChainedTypeReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionParameter;
use RuntimeException;

/**
 * Class TypeReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader
 */
class TypeReaderTest extends TestCase
{

    /**
     *
     */
    public function testGetParameterTypes(): void
    {
        $paramName = 'test';

        $reflectionParam = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflectionParam->expects(self::once())
            ->method('getName')
            ->willReturn($paramName);

        $reflectionMethod = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflectionMethod->expects(self::once())
            ->method('getParameters')
            ->willReturn([$reflectionParam]);

        $type2 = $this->getMockBuilder(TypeInterface::class)->getMock();

        $type1 = $this->getMockBuilder(TypeInterface::class)->getMock();
        $type1->expects(self::once())
            ->method('isCompatibleWith')
            ->with($type2)
            ->willReturn(true);

        $chainReader1 = $this->getMockBuilder(ChainedTypeReaderInterface::class)->getMock();
        $chainReader1->expects(self::once())
            ->method('initialize')
            ->with($reflectionMethod);
        $chainReader1->expects(self::once())
            ->method('getType')
            ->with($reflectionParam)
            ->willReturn($type1);

        $chainReader2 = $this->getMockBuilder(ChainedTypeReaderInterface::class)->getMock();
        $chainReader2->expects(self::once())
            ->method('initialize')
            ->with($reflectionMethod);
        $chainReader2->expects(self::once())
            ->method('getType')
            ->with($reflectionParam)
            ->willReturn($type2);

        /* @var $reflectionMethod ReflectionMethod */
        $reader = new TypeReader([$chainReader1, $chainReader2]);
        $types = $reader->getParameterTypes($reflectionMethod);

        self::assertInternalType('array', $types);
        self::assertArrayHasKey($paramName, $types);
        self::assertSame($type2, $types[$paramName]);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Chained type readers missing.
     */
    public function testConstructorThrowsIfChainedCombinersMissing(): void
    {
        new TypeReader([]);
    }
}
