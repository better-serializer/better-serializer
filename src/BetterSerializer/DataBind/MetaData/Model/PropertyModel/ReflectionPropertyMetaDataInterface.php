<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use ReflectionProperty;

/**
 * Interface ReflectionPropertyMetadataInterface
 * @package BetterSerializer\DataBind\MetaData
 */
interface ReflectionPropertyMetaDataInterface extends PropertyMetaDataInterface
{

    /**
     * @return ReflectionProperty
     *
     */
    public function getReflectionProperty(): ReflectionProperty;
}
