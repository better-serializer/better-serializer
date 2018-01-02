<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ResultTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testEverything(): void
    {
        $typeName = 'string';
        $typeClass = $this->createMock(TypeClassEnumInterface::class);

        $result = new Result($typeName, $typeClass);

        self::assertSame($typeName, $result->getTypeName());
        self::assertSame($typeClass, $result->getTypeClass());
    }
}
