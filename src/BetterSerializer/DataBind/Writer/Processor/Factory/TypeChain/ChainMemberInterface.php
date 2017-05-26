<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\Chain
 */
interface ChainMemberInterface
{
    /**
     * @param TypeInterface $type
     * @return ProcessorInterface|null
     */
    public function create(TypeInterface $type): ?ProcessorInterface;
}
