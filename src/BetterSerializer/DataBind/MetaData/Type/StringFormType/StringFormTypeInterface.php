<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;

/**
 *
 */
interface StringFormTypeInterface
{

    /**
     * @return string
     */
    public function getNamespace(): string;

    /**
     * @return string
     */
    public function getStringType(): string;

    /**
     * @return TypeClassEnumInterface
     */
    public function getTypeClass(): TypeClassEnumInterface;
}
