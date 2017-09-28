<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Injector\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;

/**
 * Class Factory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Injector
 */
interface FactoryInterface
{
    /**
     * @param PropertyMetaDataInterface $metaData
     * @return InjectorInterface
     */
    public function newInjector(PropertyMetaDataInterface $metaData): InjectorInterface;
}
