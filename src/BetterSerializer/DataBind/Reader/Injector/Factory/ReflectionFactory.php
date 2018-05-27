<?php
declare(strict_types = 1);

/**
 * Short desc
 *
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Reader\Injector\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\DataBind\Reader\Injector\Property\ReflectionInjector;

/**
 *
 */
final class ReflectionFactory implements FactoryInterface
{

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return InjectorInterface
     */
    public function newInjector(PropertyMetaDataInterface $metaData): InjectorInterface
    {
        return new ReflectionInjector($metaData->getReflectionProperty());
    }
}
