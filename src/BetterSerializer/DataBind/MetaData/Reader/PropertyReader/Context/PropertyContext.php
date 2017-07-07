<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use ReflectionClass;
use ReflectionProperty;

/**
 * Class PropertyContext
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class PropertyContext implements PropertyContextInterface
{

    /**
     * @var ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var ReflectionProperty
     */
    private $reflectionProperty;

    /**
     * @var AnnotationInterface[]
     */
    private $annotations;

    /**
     * PropertyContext constructor.
     * @param ReflectionClass $reflectionClass
     * @param ReflectionProperty $reflectionProperty
     * @param AnnotationInterface[] $annotations
     */
    public function __construct(
        ReflectionClass $reflectionClass,
        ReflectionProperty $reflectionProperty,
        array $annotations
    ) {
        $this->reflectionClass = $reflectionClass;
        $this->reflectionProperty = $reflectionProperty;
        $this->annotations = $annotations;
    }

    /**
     * @return ReflectionClass
     */
    public function getReflectionClass(): ReflectionClass
    {
        return $this->reflectionClass;
    }

    /**
     * @return ReflectionProperty
     */
    public function getReflectionProperty(): ReflectionProperty
    {
        return $this->reflectionProperty;
    }

    /**
     * @return AnnotationInterface[]
     */
    public function getAnnotations(): array
    {
        return $this->annotations;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->reflectionClass->getNamespaceName();
    }

    /**
     * @return PropertyInterface|null
     */
    public function getPropertyAnnotation(): ?PropertyInterface
    {
        $propertyAnnotation = null;

        foreach ($this->annotations as $annotation) {
            if ($annotation instanceof PropertyInterface) {
                $propertyAnnotation = $annotation;
                break;
            }
        }

        return $propertyAnnotation;
    }
}
