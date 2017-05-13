<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData;

/**
 * Interface ObjectPropertyMetadataInterface
 * @package BetterSerializer\DataBind\MetaData
 */
interface ObjectPropertyMetadataInterface extends ReflectionPropertyMetaDataInterface
{

    /**
     * @return string
     */
    public function getObjectClass(): string;
}
