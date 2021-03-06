<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type\Factory;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 *
 */
interface TypeFactoryInterface
{
    /**
     * @param ContextStringFormTypeInterface $stringType
     * @return TypeInterface
     */
    public function getType(ContextStringFormTypeInterface $stringType): TypeInterface;
}
