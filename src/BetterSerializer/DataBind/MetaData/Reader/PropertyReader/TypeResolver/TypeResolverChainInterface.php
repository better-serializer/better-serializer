<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 *
 */
interface TypeResolverChainInterface
{
    /**
     * @param PropertyContextInterface $context
     * @return TypeInterface
     */
    public function resolveType(PropertyContextInterface $context): TypeInterface;
}
