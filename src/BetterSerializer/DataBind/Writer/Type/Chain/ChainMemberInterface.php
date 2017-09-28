<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type\Chain;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
interface ChainMemberInterface
{
    /**
     * @param mixed $data
     * @return TypeInterface|null
     */
    public function getType($data): ?TypeInterface;
}
