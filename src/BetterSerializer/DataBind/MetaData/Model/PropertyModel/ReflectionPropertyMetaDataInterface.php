<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\Reflection\ReflectionPropertyInterface;

/**
 * Interface ReflectionPropertyMetadataInterface
 * @package BetterSerializer\DataBind\MetaData
 */
interface ReflectionPropertyMetaDataInterface extends PropertyMetaDataInterface
{

    /**
     * @return ReflectionPropertyInterface
     *
     */
    public function getReflectionProperty(): ReflectionPropertyInterface;
}
