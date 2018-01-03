<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;

/**
 *
 */
interface ResultInterface
{

    /**
     * @return string
     */
    public function getTypeName(): string;

    /**
     * @return TypeClassEnumInterface
     */
    public function getTypeClass(): TypeClassEnumInterface;
}
