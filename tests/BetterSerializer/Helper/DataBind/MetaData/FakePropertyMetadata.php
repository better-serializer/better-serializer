<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Helper\DataBind\MetaData;

use BetterSerializer\DataBind\MetaData\Annotations\Groups;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class FakePropertyMetadata
 * @author mfris
 * @package BetterSerializer\Helper\DataBind\MetaData
 */
final class FakePropertyMetaData implements PropertyMetaDataInterface
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

    /**
     * @return string
     */
    public function getOutputKey(): string
    {
        return 'fake';
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return [Groups::DEFAULT_GROUP];
    }
}
