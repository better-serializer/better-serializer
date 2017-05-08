<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Helper\DataBind\MetaData;

use BetterSerializer\DataBind\MetaData\PropertyMetadataInterface;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class FakePropertyMetadata
 * @author mfris
 * @package BetterSerializer\Helper\DataBind\MetaData
 */
final class FakePropertyMetadata implements PropertyMetadataInterface
{

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return new NullType();
    }

    /**
     * @return bool
     */
    public function isObject(): bool
    {
        return false;
    }
}
