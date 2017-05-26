<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
final class ObjectMember extends ChainMember
{

    /**
     * @param string $stringType
     * @return bool
     */
    protected function isProcessable(string $stringType): bool
    {
        return class_exists($stringType);
    }

    /**
     * @param string $stringType
     * @return TypeInterface
     */
    protected function createType(string $stringType): TypeInterface
    {
        return new ObjectType($stringType);
    }
}
