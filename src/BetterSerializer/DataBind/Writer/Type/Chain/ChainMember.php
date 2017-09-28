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
 * @package BetterSerializer\DataBind\MetaData\Type\Converter\Chain
 */
abstract class ChainMember implements ChainMemberInterface
{

    /**
     * @param mixed $data
     * @return TypeInterface|null
     */
    public function getType($data): ?TypeInterface
    {
        if (!$this->isProcessable($data)) {
            return null;
        }

        return $this->createType($data);
    }

    /**
     * @param mixed $data
     * @return bool
     */
    abstract protected function isProcessable($data): bool;

    /**
     * @param mixed $data
     * @return TypeInterface
     */
    abstract protected function createType($data): TypeInterface;
}
