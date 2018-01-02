<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;

/**
 *
 */
final class InterfaceResolver implements TypeClassResolverInterface
{

    /**
     * @param string $potentialHigherType
     * @return TypeClassEnumInterface|null
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function resolveTypeClass(string $potentialHigherType): ?TypeClassEnumInterface
    {
        if (!interface_exists($potentialHigherType, false) && !interface_exists($potentialHigherType)) {
            return null;
        }

        return TypeClassEnum::INTERFACE_TYPE();
    }
}
