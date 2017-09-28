<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type\Chain;

use BetterSerializer\DataBind\MetaData\Type\BooleanType;
use BetterSerializer\DataBind\MetaData\Type\FloatType;
use BetterSerializer\DataBind\MetaData\Type\IntegerType;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Type
 */
class SimpleMemberTest extends TestCase
{

    /**
     * @dataProvider typeMappingProvider
     * @param mixed $data
     * @param string $typeClassName
     */
    public function testGetType($data, string $typeClassName): void
    {
        $simpleMember = new SimpleMember();
        $type = $simpleMember->getType($data);

        self::assertInstanceOf($typeClassName, $type);
    }

    /**
     * @dataProvider typeWrongMappingProvider
     * @param mixed $data
     */
    public function testGetTypeReturnsNull($data): void
    {
        $simpleMember = new SimpleMember();
        $type = $simpleMember->getType($data);

        self::assertNull($type);
    }

    /**
     * @return array
     */
    public function typeMappingProvider(): array
    {
        return [
            [true, BooleanType::class],
            [false, BooleanType::class],
            [null, NullType::class],
            [1, IntegerType::class],
            [0, IntegerType::class],
            [-1, IntegerType::class],
            [0.1, FloatType::class],
            [0.0, FloatType::class],
            [-0.1, FloatType::class],
            ['test', StringType::class],
            ['', StringType::class],
        ];
    }

    /**
     * @return array
     */
    public function typeWrongMappingProvider(): array
    {
        return [
            [[1]],
            [new Car('test', 'test', new Radio('test'))],
        ];
    }
}
