<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type\Chain;

use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Type\ExtractorInterface;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Type\Chain
 */
class ArrayMemberTest extends TestCase
{

    /**
     * @dataProvider arrayProvider
     * @param mixed $data
     */
    public function testGetType($data): void
    {
        $nestedType = $this->getMockBuilder(TypeInterface::class)->getMock();
        $extractor = $this->getMockBuilder(ExtractorInterface::class)->getMock();
        $extractor->expects(self::once())
                  ->method('extract')
                  ->willReturn($nestedType);

        /* @var $extractor ExtractorInterface */
        $arrayMember = new ArrayMember($extractor);
        /* @var $type ArrayType */
        $type = $arrayMember->getType($data);

        self::assertInstanceOf(ArrayType::class, $type);
        self::assertSame($nestedType, $type->getNestedType());
    }

    /**
     * @dataProvider nonArrayProvider
     * @param mixed $data
     */
    public function testGetTypeReturnsNull($data): void
    {
        $extractor = $this->getMockBuilder(ExtractorInterface::class)->getMock();

        /* @var $extractor ExtractorInterface */
        $arrayMember = new ArrayMember($extractor);
        $type = $arrayMember->getType($data);

        self::assertNull($type);
    }

    /**
     *
     */
    public function testGetTypeOnEmptyArray(): void
    {
        $extractor = $this->getMockBuilder(ExtractorInterface::class)->getMock();

        /* @var $extractor ExtractorInterface */
        $arrayMember = new ArrayMember($extractor);
        $type = $arrayMember->getType([]);

        self::assertInstanceOf(ArrayType::class, $type);
        self::assertInstanceOf(NullType::class, $type->getNestedType());
    }

    /**
     * @return array
     */
    public function arrayProvider(): array
    {
        return [
            [[new Car('test', 'test', new Radio('test'))]],
            [[new Radio('test')]],
            [[1]],
            [[0.1]],
            [[null]],
            [['test']],
        ];
    }

    /**
     * @return array
     */
    public function nonArrayProvider(): array
    {
        return [
            [new Radio('test')],
            [true],
            [false],
            [null],
            [1],
            [0],
            [-1],
            [0.1],
            [0.0],
            [-0.1],
            ['test'],
            [''],
        ];
    }
}
