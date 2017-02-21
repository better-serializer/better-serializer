<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use PHPUnit\Framework\TestCase;

/**
 * Class IntegerTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
class IntegerTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $int = new IntegerType();
        self::assertInstanceOf(get_class(TypeEnum::INTEGER()), $int->getType());
    }
}
