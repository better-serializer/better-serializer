<?php
/**
 * @author  mfris
 */
declare(strict_types = 1);
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\PropertyMetadataInterface;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use ReflectionClass;

/**
 * Class PropertyReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class PropertyReader implements PropertyReaderInterface
{

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * PropertyReader constructor.
     * @param AnnotationReader $annotationReader
     */
    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return PropertyMetadataInterface[]
     */
    public function getPropertyMetadata(ReflectionClass $reflectionClass): array
    {
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $propertyAnnotations = $this->annotationReader->getPropertyAnnotations($reflectionProperty);
            dump($propertyAnnotations);
        }

        return[];
    }
}
