<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Annotations;

/**
 * Class AbstractAnnotation
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Annotations
 */
abstract class AbstractAnnotation implements AnnotationInterface
{

    /**
     * @const string
     */
    public const ANNOTATION_NAME = '';

    /**
     * @return string
     */
    public function getAnnotationName(): string
    {
        return static::ANNOTATION_NAME;
    }
}
