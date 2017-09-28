<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type\Factory;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class TypeFactory
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
interface TypeFactoryInterface
{
    /**
     * @param StringFormTypeInterface $stringType
     * @return TypeInterface
     */
    public function getType(StringFormTypeInterface $stringType): TypeInterface;
}
