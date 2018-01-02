<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Converter\Chain
 */
abstract class ChainMember implements ChainMemberInterface
{

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return TypeInterface|null
     */
    public function getType(ContextStringFormTypeInterface $stringFormType): ?TypeInterface
    {
        if (!$this->isProcessable($stringFormType)) {
            return null;
        }

        return $this->createType($stringFormType);
    }

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return bool
     */
    abstract protected function isProcessable(ContextStringFormTypeInterface $stringFormType): bool;

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return TypeInterface
     */
    abstract protected function createType(ContextStringFormTypeInterface $stringFormType): TypeInterface;
}
