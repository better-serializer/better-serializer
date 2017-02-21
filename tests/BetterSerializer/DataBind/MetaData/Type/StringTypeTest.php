<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use PHPUnit\Framework\TestCase;

/**
 * Class StringTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
class StringTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $string = new StringType();
        self::assertInstanceOf(get_class(TypeEnum::STRING()), $string->getType());
    }
}
