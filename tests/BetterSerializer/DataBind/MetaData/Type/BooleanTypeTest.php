<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use PHPUnit\Framework\TestCase;

/**
 * Class BooleanTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
class BooleanTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $bool = new BooleanType();
        self::assertInstanceOf(get_class(TypeEnum::BOOLEAN()), $bool->getType());
    }
}
