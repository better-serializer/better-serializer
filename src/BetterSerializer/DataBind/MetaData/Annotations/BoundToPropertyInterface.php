<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Annotations;

/**
 * Interface PropertyWithArgumentInterface
 * @package BetterSerializer\DataBind\MetaData\Annotations
 */
interface BoundToPropertyInterface extends AnnotationInterface
{

    /**
     * @return string
     */
    public function getPropertyName(): string;

    /**
     * @return string
     */
    public function getArgumentName(): string;
}
