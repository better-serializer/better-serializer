<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;

/**
 *
 */
interface TypeClassResolverInterface
{

    /**
     * @param string $potentialHigherType
     * @return TypeClassEnumInterface|null
     */
    public function resolveTypeClass(string $potentialHigherType): ?TypeClassEnumInterface;
}
