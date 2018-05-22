<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;

/**
 *
 */
interface TypeResolverInterface
{
    /**
     * @param PropertyContextInterface $context
     * @return ContextStringFormTypeInterface|null
     */
    public function resolveType(PropertyContextInterface $context): ?ContextStringFormTypeInterface;
}
