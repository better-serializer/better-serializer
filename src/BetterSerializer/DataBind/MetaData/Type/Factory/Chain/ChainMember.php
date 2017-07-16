<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringType\StringTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
abstract class ChainMember implements ChainMemberInterface
{

    /**
     * @param StringTypeInterface $stringType
     * @return TypeInterface|null
     */
    public function getType(StringTypeInterface $stringType): ?TypeInterface
    {
        if (!$this->isProcessable($stringType)) {
            return null;
        }

        return $this->createType($stringType);
    }

    /**
     * @param StringTypeInterface $stringType
     * @return bool
     */
    abstract protected function isProcessable(StringTypeInterface $stringType): bool;

    /**
     * @param StringTypeInterface $stringType
     * @return TypeInterface
     */
    abstract protected function createType(StringTypeInterface $stringType): TypeInterface;
}
