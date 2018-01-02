<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\InterfaceType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 *
 */
final class InterfaceMember extends ChainMember
{

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return bool
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function isProcessable(ContextStringFormTypeInterface $stringFormType): bool
    {
        return $stringFormType->getTypeClass() === TypeClassEnum::INTERFACE_TYPE();
    }

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return TypeInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createType(ContextStringFormTypeInterface $stringFormType): TypeInterface
    {
        return new InterfaceType($stringFormType->getStringType());
    }
}
