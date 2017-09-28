<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Converter\Chain
 */
abstract class ChainMember implements ChainMemberInterface
{

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return TypeInterface|null
     */
    public function getType(StringFormTypeInterface $stringFormType): ?TypeInterface
    {
        if (!$this->isProcessable($stringFormType)) {
            return null;
        }

        return $this->createType($stringFormType);
    }

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return bool
     */
    abstract protected function isProcessable(StringFormTypeInterface $stringFormType): bool;

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return TypeInterface
     */
    abstract protected function createType(StringFormTypeInterface $stringFormType): TypeInterface;
}
