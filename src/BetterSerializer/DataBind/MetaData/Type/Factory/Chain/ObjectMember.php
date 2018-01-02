<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 *
 */
final class ObjectMember extends ChainMember
{

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return bool
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function isProcessable(ContextStringFormTypeInterface $stringFormType): bool
    {
        return $stringFormType->getTypeClass() === TypeClassEnum::CLASS_TYPE();
    }

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return TypeInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createType(ContextStringFormTypeInterface $stringFormType): TypeInterface
    {
        return new ObjectType($stringFormType->getStringType());
    }
}
