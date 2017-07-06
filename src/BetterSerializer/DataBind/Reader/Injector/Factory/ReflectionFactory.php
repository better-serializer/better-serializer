<?php
declare(strict_types = 1);

/**
 * Short desc
 *
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Reader\Injector\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\DataBind\Reader\Injector\Property\ReflectionInjector;
use RuntimeException;

/**
 * Class Factory
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Injector
 */
final class ReflectionFactory implements FactoryInterface
{

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return InjectorInterface
     * @throws RuntimeException
     */
    public function newInjector(PropertyMetaDataInterface $metaData): InjectorInterface
    {
        if (!$metaData instanceof ReflectionPropertyMetaDataInterface) {
            throw new RuntimeException(
                sprintf('Not a ReflectionPropertyMetaDataInterface - %s', get_class($metaData))
            );
        }

        return new ReflectionInjector($metaData->getReflectionProperty());
    }
}
