<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use ReflectionProperty;

/**
 * Interface PropertyMetadataInterface
 *
 * @package BetterSerializer\DataBind\MetaData
 */
interface PropertyMetadataInterface
{

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * @return bool
     */
    public function isObject(): bool;
}
