<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;

/**
 * Interface PropertiesMetaDataInterface
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner
 */
interface ShrinkingPropertiesMetaDataInterface
{

    /**
     * @param string $propertyName
     * @return PropertyMetaDataInterface
     */
    public function shrinkBy(string $propertyName): PropertyMetaDataInterface;

    /**
     * @param string $propertyName
     * @return bool
     */
    public function hasProperty(string $propertyName): bool;
}
