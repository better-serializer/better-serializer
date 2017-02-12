<?php
/**
 * @author  mfris
 */
declare(strict_types = 1);
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\PropertyMetadata;
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
        $metaData = [];

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            $annotations = $this->annotationReader->getPropertyAnnotations($reflectionProperty);
            $metaData[$propertyName] = new PropertyMetadata($reflectionProperty, $annotations);
        }

        return $metaData;
    }
}
