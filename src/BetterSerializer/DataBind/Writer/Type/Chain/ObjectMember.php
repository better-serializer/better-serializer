<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type\Chain;

use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Type
 */
final class ObjectMember extends ChainMember
{

    /**
     * @param mixed $data
     * @return bool
     */
    protected function isProcessable($data): bool
    {
        return is_object($data);
    }

    /**
     * @param mixed $data
     * @return TypeInterface
     */
    protected function createType($data): TypeInterface
    {
        return new ObjectType(get_class($data));
    }
}
