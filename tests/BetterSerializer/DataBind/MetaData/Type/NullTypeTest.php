<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use PHPUnit\Framework\TestCase;

/**
 * Class NullTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
class NullTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $null = new NullType();
        self::assertInstanceOf(get_class(TypeEnum::NULL()), $null->getType());
    }
}
