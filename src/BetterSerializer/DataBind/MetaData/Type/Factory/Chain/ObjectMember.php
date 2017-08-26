<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
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
     * @param StringFormTypeInterface $stringFormType
     * @return bool
     */
    protected function isProcessable(StringFormTypeInterface $stringFormType): bool
    {
        return $stringFormType->isClass();
    }

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return TypeInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createType(StringFormTypeInterface $stringFormType): TypeInterface
    {
        return new ObjectType($stringFormType->getStringType());
    }
}
