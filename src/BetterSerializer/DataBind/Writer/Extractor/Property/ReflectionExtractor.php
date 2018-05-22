<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Property;

use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use ReflectionProperty as NativeReflectionProperty;

/**
 *
 */
final class ReflectionExtractor implements ExtractorInterface
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
     * ReflectionExtractor constructor.
     * @param ReflectionPropertyInterface $reflectionProperty
     */
    public function __construct(ReflectionPropertyInterface $reflectionProperty)
    {
        $this->reflectionProperty = $reflectionProperty;
        $this->nativeReflectionProperty = $reflectionProperty->getNativeReflProperty();
    }

    /**
     * @param object|null $object
     * @return mixed
     */
    public function extract(?object $object)
    {
        if ($object === null) {
            return null;
        }

        return $this->nativeReflectionProperty->getValue($object);
    }

    /**
     *
     */
    public function __wakeup()
    {
        $this->nativeReflectionProperty = $this->reflectionProperty->getNativeReflProperty();
    }
}
