<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData;

use ReflectionProperty;

/**
 * Interface PropertyMetadataInterface
 *
 * @package BetterSerializer\DataBind\MetaData
 */
interface ReflectionPropertyMetadataInterface extends PropertyMetadataInterface
{

    /**
     * @return ReflectionProperty
     */
    public function getReflectionProperty() : ReflectionProperty;
}
