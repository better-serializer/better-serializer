<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use ReflectionProperty;

/**
 * Class PropertyMetadata
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class PropertyMetadata implements PropertyMetadataInterface
{

    /**
     * @var ReflectionProperty
     */
    private $reflectionProperty;

    /**
     * @var AnnotationInterface[]
     */
    private $annotations;

    /**
     * PropertyMetadata constructor.
     * @param ReflectionProperty $reflectionProperty
     * @param AnnotationInterface[] $annotations
     */
    public function __construct(ReflectionProperty $reflectionProperty, array $annotations)
    {
        $this->reflectionProperty = $reflectionProperty;
        $this->annotations = $annotations;
    }
}
