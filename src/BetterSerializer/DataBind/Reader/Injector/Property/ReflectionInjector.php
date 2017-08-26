<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Injector\Property;

use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use ReflectionProperty;

/**
 * Class ReflectionInjector
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Injector\Property
 */
final class ReflectionInjector implements InjectorInterface
{

    /**
     * @var ReflectionProperty
     */
    private $reflectionProperty;

    /**
     * ReflectionInjector constructor.
     * @param ReflectionPropertyInterface $reflectionProperty
     */
    public function __construct(ReflectionPropertyInterface $reflectionProperty)
    {
        $this->reflectionProperty = $reflectionProperty->getNativeReflProperty();
    }

    /**
     * @param object $object
     * @param mixed $data
     * @return mixed
     */
    public function inject($object, $data): void
    {
        $this->reflectionProperty->setValue($object, $data);
    }
}
