<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\AbstractCollectionType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\UnknownType;
use BetterSerializer\Reflection\ReflectionClassInterface;
use PHPUnit\Framework\TestCase;
use LogicException;
use RuntimeException;

/**
 * Class MixedMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
class MixedMemberTest extends TestCase
{

    /**
     *
     */
    public function testGetType(): void
    {
        $objStringType = 'Radio[]';
        $stringTypeString = $objStringType . '|null';

        $reflectionClass = $this->createMock(ReflectionClassInterface::class);

        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringType->method('getStringType')
            ->willReturn($stringTypeString);
        $stringType->method('getReflectionClass')
            ->willReturn($reflectionClass);

        $arrayType = $this->createMock(AbstractCollectionType::class);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->method('getType')
            ->willReturn($arrayType);

        $mixedMember = new MixedMember($typeFactory);
        $typeObject = $mixedMember->getType($stringType);

        self::assertSame($arrayType, $typeObject);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage String form type needs to be of ContextStringFormTypeInterface.
     */
    public function testGetTypeThrowsRuntimeException(): void
    {
        $stringTypeString = 'Radio[]|null';
        $stringType = $this->createMock(StringFormTypeInterface::class);
        $stringType->method('getStringType')
            ->willReturn($stringTypeString);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);

        $mixedMember = new MixedMember($typeFactory);
        $mixedMember->getType($stringType);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Invalid mixed type: [a-zA-Z0-9_|]+/
     */
    public function testGetTypeThrowsLogicException(): void
    {
        $stringTypeString = 'mixed|null';

        $reflectionClass = $this->createMock(ReflectionClassInterface::class);

        $stringType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringType->method('getStringType')
            ->willReturn($stringTypeString);
        $stringType->method('getReflectionClass')
            ->willReturn($reflectionClass);

        $unknownType = new UnknownType();
        $nullType = new NullType();

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->method('getType')
            ->willReturnOnConsecutiveCalls($unknownType, $nullType);

        $mixedMember = new MixedMember($typeFactory);
        $mixedMember->getType($stringType);
    }
}
