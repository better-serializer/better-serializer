<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\Dto\CarImpl;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class TypeFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
class TypeFactoryTest extends TestCase
{

    /**
     * @dataProvider typeMappingProvider
     * @param string $stringType
     * @param string $typeClassName
     */
    public function testGetType(string $stringType, string $typeClassName): void
    {
        $typeFactory = new TypeFactory();
        $typeObject = $typeFactory->getType($stringType);

        self::assertInstanceOf($typeClassName, $typeObject);
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

    /**
     *
     */
    public function testGetTypeObject(): void
    {
        $typeFactory = new TypeFactory();
        /* @var $typeObject ObjectType */
        $typeObject = $typeFactory->getType(CarImpl::class);

        self::assertInstanceOf(ObjectType::class, $typeObject);
        self::assertSame($typeObject->getClassName(), CarImpl::class);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Unknown type - '[a-zA-Z0-9]+'+/
     */
    public function testGetTypeThrowsException(): void
    {
        $typeFactory = new TypeFactory();
        $typeFactory->getType('abcd');
    }
}
