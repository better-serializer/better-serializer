<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Injector\Property;

use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use ReflectionProperty as NativeReflectionProperty;

/**
 *
 */
final class ReflectionInjector implements InjectorInterface
{

    /**
     * @var ReflectionPropertyInterface
     */
    private $reflectionProperty;

    /**
     * @var NativeReflectionProperty
     */
    private $nativeReflectionProperty;

    /**
     * @param ReflectionPropertyInterface $reflectionProperty
     */
    public function __construct(ReflectionPropertyInterface $reflectionProperty)
    {
        $this->reflectionProperty = $reflectionProperty;
        $this->nativeReflectionProperty = $reflectionProperty->getNativeReflProperty();
    }

    /**
     * @param object $object
     * @param mixed $data
     * @return mixed
     */
    public function inject(object $object, $data): void
    {
        $this->nativeReflectionProperty->setValue($object, $data);
    }

    /**
     *
     */
    public function __wakeup()
    {
        $this->nativeReflectionProperty = $this->reflectionProperty->getNativeReflProperty();
    }
}
