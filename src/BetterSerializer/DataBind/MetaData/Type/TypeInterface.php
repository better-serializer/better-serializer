<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Interface DataTypeInterface
 * @package BetterSerializer\DataBind\MetaData\Type
 */
interface TypeInterface
{

    /**
     * @return TypeEnum
     */
    public function getType(): TypeEnum;
}
