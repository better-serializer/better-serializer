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
interface PropertyContextInterface
{
    /**
     * @return ReflectionClass
     */
    public function getReflectionClass(): ReflectionClass;

    /**
     * @return ReflectionProperty
     */
    public function getReflectionProperty(): ReflectionProperty;

    /**
     * @return AnnotationInterface[]
     */
    public function getAnnotations(): array;

    /**
     * @return string
     */
    public function getNamespace(): string;

    /**
     * @return PropertyInterface|null
     */
    public function getPropertyAnnotation(): ?PropertyInterface;
}
