<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use PHPUnit\Framework\TestCase;

/**
 * Class FloatTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
class FloatTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $int = new FloatType();
        self::assertInstanceOf(get_class(TypeEnum::FLOAT()), $int->getType());
    }
}