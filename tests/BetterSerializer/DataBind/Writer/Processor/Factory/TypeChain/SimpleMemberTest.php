<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Simple;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
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
        $simpleType = $this->createMock(SimpleTypeInterface::class);
        $context = $this->createMock(SerializationContextInterface::class);

        $converter = $this->createMock(ConverterInterface::class);

        $converterFactory = $this->createMock(ConverterFactoryInterface::class);
        $converterFactory->expects(self::once())
            ->method('newConverter')
            ->willReturn($converter);

        $simpleMember = new SimpleMember($converterFactory);
        $simpleProcessor = $simpleMember->create($simpleType, $context);

        self::assertInstanceOf(Simple::class, $simpleProcessor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $nonSimpletType = $this->createMock(TypeInterface::class);
        $converterFactory = $this->createMock(ConverterFactoryInterface::class);
        $context = $this->createMock(SerializationContextInterface::class);

        $simpleMember = new SimpleMember($converterFactory);
        $shouldBeNull = $simpleMember->create($nonSimpletType, $context);

        self::assertNull($shouldBeNull);
    }
}
