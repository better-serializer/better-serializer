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
interface ChainedTypeFactoryInterface extends TypeFactoryInterface
{

    /**
     * @param ChainMemberInterface $chainMember
     */
    public function addChainMember(ChainMemberInterface $chainMember): void;
}
