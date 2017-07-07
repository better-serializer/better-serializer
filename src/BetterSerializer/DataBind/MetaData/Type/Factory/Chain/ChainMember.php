<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringTypedPropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
abstract class ChainMember implements ChainMemberInterface
{

    /**
     * @param StringTypedPropertyContextInterface $context
     * @return TypeInterface|null
     */
    public function getType(StringTypedPropertyContextInterface $context): ?TypeInterface
    {
        if (!$this->isProcessable($context)) {
            return null;
        }

        return $this->createType($context);
    }

    /**
     * @param StringTypedPropertyContextInterface $context
     * @return bool
     */
    abstract protected function isProcessable(StringTypedPropertyContextInterface $context): bool;

    /**
     * @param StringTypedPropertyContextInterface $context
     * @return TypeInterface
     */
    abstract protected function createType(StringTypedPropertyContextInterface $context): TypeInterface;
}
