<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Reader\StringTypedPropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\BooleanType;
use BetterSerializer\DataBind\MetaData\Type\FloatType;
use BetterSerializer\DataBind\MetaData\Type\IntegerType;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Class SimpleMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class SimpleMemberTest extends TestCase
{

    /**
     *
     */
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @dataProvider typeMappingProvider
     * @param string $stringType
     * @param string $typeClassName
     */
    public function testGetType(string $stringType, string $typeClassName): void
    {
        $context = Mockery::mock(StringTypedPropertyContextInterface::class);
        $context->shouldReceive('getStringType')
            ->twice()
            ->andReturn($stringType)
            ->getMock();
        /* @var $context StringTypedPropertyContextInterface */

        $simpleMember = new SimpleMember();
        $typeObject = $simpleMember->getType($context);

        self::assertInstanceOf($typeClassName, $typeObject);
    }

    /**
     *
     */
    public function testGetTypeReturnsNull(): void
    {
        $context = Mockery::mock(StringTypedPropertyContextInterface::class);
        $context->shouldReceive('getStringType')
            ->once()
            ->andReturn(Car::class)
            ->getMock();
        /* @var $context StringTypedPropertyContextInterface */

        $simpleMember = new SimpleMember();
        $shouldBeNull = $simpleMember->getType($context);

        self::assertNull($shouldBeNull);
    }

    /**
     * @return array
     */
    public function typeMappingProvider(): array
    {
        return [
            [TypeEnum::BOOLEAN, BooleanType::class],
            [TypeEnum::NULL, NullType::class],
            [TypeEnum::INTEGER, IntegerType::class],
            [TypeEnum::FLOAT, FloatType::class],
            [TypeEnum::STRING, StringType::class],
        ];
    }
}
