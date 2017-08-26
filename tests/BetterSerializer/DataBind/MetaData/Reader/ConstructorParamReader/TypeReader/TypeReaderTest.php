<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\Chained\ChainedTypeReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;
use PHPUnit\Framework\TestCase;
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

        $reflectionParam = $this->createMock(ReflectionParameterInterface::class);
        $reflectionParam->expects(self::once())
            ->method('getName')
            ->willReturn($paramName);

        $reflectionMethod = $this->createMock(ReflectionMethodInterface::class);
        $reflectionMethod->expects(self::once())
            ->method('getParameters')
            ->willReturn([$reflectionParam]);

        $type2 = $this->createMock(TypeInterface::class);

        $type1 = $this->createMock(TypeInterface::class);
        $type1->expects(self::once())
            ->method('isCompatibleWith')
            ->with($type2)
            ->willReturn(true);

        $chainReader1 = $this->createMock(ChainedTypeReaderInterface::class);
        $chainReader1->expects(self::once())
            ->method('initialize')
            ->with($reflectionMethod);
        $chainReader1->expects(self::once())
            ->method('getType')
            ->with($reflectionParam)
            ->willReturn($type1);

        $chainReader2 = $this->createMock(ChainedTypeReaderInterface::class);
        $chainReader2->expects(self::once())
            ->method('initialize')
            ->with($reflectionMethod);
        $chainReader2->expects(self::once())
            ->method('getType')
            ->with($reflectionParam)
            ->willReturn($type2);

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
