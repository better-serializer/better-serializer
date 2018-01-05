<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory;

use BetterSerializer\DataBind\MetaData\Type\Factory\Chain\ChainMemberInterface;

/**
 *
 */
final class TypeFactoryBuilder
{

    /**
     * @param ChainedTypeFactoryInterface $typeFactory
     * @param ChainMemberInterface[] $chainMembers
     * @return TypeFactoryInterface
     */
    public static function build(ChainedTypeFactoryInterface $typeFactory, array $chainMembers): TypeFactoryInterface
    {
        foreach ($chainMembers as $chainMember) {
            $typeFactory->addChainMember($chainMember);
        }

        return $typeFactory;
    }
}
