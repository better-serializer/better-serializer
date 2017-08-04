<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
abstract class ChainMember implements ChainMemberInterface
{

    /**
     * @param StringFormTypeInterface $stringType
     * @return TypeInterface|null
     */
    public function getType(StringFormTypeInterface $stringType): ?TypeInterface
    {
        if (!$this->isProcessable($stringType)) {
            return null;
        }

        return $this->createType($stringType);
    }

    /**
     * @param StringFormTypeInterface $stringType
     * @return bool
     */
    abstract protected function isProcessable(StringFormTypeInterface $stringType): bool;

    /**
     * @param StringFormTypeInterface $stringType
     * @return TypeInterface
     */
    abstract protected function createType(StringFormTypeInterface $stringType): TypeInterface;
}
