<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use PHPUnit\Framework\TestCase;

class ArrayTypeTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testGetType(): void
    {
        $typeMock = $this->getMockBuilder(TypeInterface::class)->getMock();

        /* @var $typeMock TypeInterface */
        $object = new ArrayType($typeMock);
        self::assertInstanceOf(get_class(TypeEnum::ARRAY()), $object->getType());
        self::assertSame($typeMock, $object->getNestedType());
    }
}
