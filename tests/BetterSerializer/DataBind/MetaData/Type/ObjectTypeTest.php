<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
class ObjectTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $object = new ObjectType(Car::class);
        self::assertInstanceOf(get_class(TypeEnum::OBJECT()), $object->getType());
        self::assertSame(Car::class, $object->getClassName());
    }
}
