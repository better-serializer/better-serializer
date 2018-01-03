<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\SimpleProcessor;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class SimpleMemberTest extends TestCase
{

    /**
     *
     */
    public function testCreate(): void
    {
        $type = $this->createMock(SimpleTypeInterface::class);
        $converter = $this->createMock(ConverterInterface::class);
        $converterFactory = $this->createMock(ConverterFactoryInterface::class);
        $converterFactory->expects(self::once())
            ->method('newConverter')
            ->with($type)
            ->willReturn($converter);

        $simpleMember = new SimpleMember($converterFactory);
        $simpleProcessor = $simpleMember->create($type);

        self::assertInstanceOf(SimpleProcessor::class, $simpleProcessor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $nonSimpleType = $this->createMock(TypeInterface::class);
        $converterFactory = $this->createMock(ConverterFactoryInterface::class);

        $simpleMember = new SimpleMember($converterFactory);
        $shouldBeNull = $simpleMember->create($nonSimpleType);

        self::assertNull($shouldBeNull);
    }
}
