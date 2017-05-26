<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
abstract class ChainMember implements ChainMemberInterface
{

    /**
     * @param string $stringType
     * @return TypeInterface|null
     */
    public function getType(string $stringType): ?TypeInterface
    {
        if (!$this->isProcessable($stringType)) {
            return null;
        }

        return $this->createType($stringType);
    }

    /**
     * @param string $stringType
     * @return bool
     */
    abstract protected function isProcessable(string $stringType): bool;

    /**
     * @param string $stringType
     * @return TypeInterface
     */
    abstract protected function createType(string $stringType): TypeInterface;
}
