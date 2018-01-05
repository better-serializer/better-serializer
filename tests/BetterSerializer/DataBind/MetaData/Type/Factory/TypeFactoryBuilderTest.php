<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory;

use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\ChainMemberInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class TypeFactoryBuilderTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testRegisterMembers(): void
    {
        $chainMember = $this->createMock(ChainMemberInterface::class);
        $typeFactory = $this->createMock(ChainedTypeFactoryInterface::class);

        $newTypeFactory = TypeFactoryBuilder::build($typeFactory, [$chainMember]);

        self::assertInstanceOf(TypeFactoryInterface::class, $newTypeFactory);
        self::assertSame($typeFactory, $newTypeFactory);
    }
}
