<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory\Chain
 */
interface ChainMemberInterface
{
    /**
     * @param TypeInterface $type
     * @return ProcessorInterface|null
     */
    public function create(TypeInterface $type): ?ProcessorInterface;
}
