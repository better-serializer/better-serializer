<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Interface NativeTypeFactoryInterface
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory
 */
interface NativeTypeFactoryInterface
{

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return TypeInterface
     */
    public function getType(StringFormTypeInterface $stringFormType): TypeInterface;
}
