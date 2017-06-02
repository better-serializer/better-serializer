<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData;

/**
 * Interface ObjectPropertyMetaDataInterface
 * @package BetterSerializer\DataBind\MetaData
 */
interface ObjectPropertyMetaDataInterface extends ReflectionPropertyMetaDataInterface
{

    /**
     * @return string
     */
    public function getObjectClass(): string;
}
