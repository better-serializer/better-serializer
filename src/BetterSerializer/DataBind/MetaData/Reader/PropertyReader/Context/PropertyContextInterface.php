<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;

/**
 * Class PropertyContext
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
interface PropertyContextInterface
{
    /**
     * @return ReflectionClassInterface
     */
    public function getReflectionClass(): ReflectionClassInterface;

    /**
     * @return ReflectionPropertyInterface
     */
    public function getReflectionProperty(): ReflectionPropertyInterface;

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
