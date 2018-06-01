<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\PrimitiveType;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Format\ResultInterface as FormatResultInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class AliasedFormatResultTest extends TestCase
{

    /**
     *
     */
    public function testEverythingUnaliased(): void
    {
        $type = TypeEnum::INTEGER_TYPE;

        $delegate = $this->createMock(FormatResultInterface::class);
        $delegate->expects(self::once())
            ->method('getType')
            ->willReturn($type);
        $delegate->expects(self::once())
            ->method('getParameters')
            ->willReturn(null);
        $delegate->expects(self::once())
            ->method('getNestedValueType')
            ->willReturn(null);
        $delegate->expects(self::once())
            ->method('getNestedKeyType')
            ->willReturn(null);

        $aliased = new AliasedFormatResult($delegate);

        self::assertEquals($type, $aliased->getType());
        self::assertNull($aliased->getParameters());
        self::assertNull($aliased->getNestedValueType());
        self::assertNull($aliased->getNestedKeyType());
    }

    /**
     *
     */
    public function testAliasedType(): void
    {
        $type = 'integer';

        $delegate = $this->createMock(FormatResultInterface::class);
        $delegate->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $aliased = new AliasedFormatResult($delegate);

        self::assertEquals(TypeEnum::INTEGER_TYPE, $aliased->getType());
    }
}
