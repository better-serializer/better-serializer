<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Injector\Property;

use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
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
     * @param ReflectionProperty $reflectionProperty
     */
    public function __construct(ReflectionProperty $reflectionProperty)
    {
        $this->reflectionProperty = $reflectionProperty;
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
