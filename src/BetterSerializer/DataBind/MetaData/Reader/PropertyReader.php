<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\ObjectPropertyMetadata;
use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\ReflectionPropertyMetadata;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;

/**
 * Class PropertyReader
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class PropertyReader implements PropertyReaderInterface
{

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var AnnotationPropertyTypeReaderInterface
     */
    private $annotPropTypeReader;

    /**
     * @var DocBlockPropertyTypeReaderInterface
     */
    private $docBlkPropTypeReader;

    /**
     * PropertyReader constructor.
     * @param AnnotationReader $annotationReader
     * @param AnnotationPropertyTypeReaderInterface $annotPropTypeReader
     * @param DocBlockPropertyTypeReaderInterface $docBlkPropTypeReader
     */
    public function __construct(
        AnnotationReader $annotationReader,
        AnnotationPropertyTypeReaderInterface $annotPropTypeReader,
        DocBlockPropertyTypeReaderInterface $docBlkPropTypeReader
    ) {
        $this->annotationReader = $annotationReader;
        $this->annotPropTypeReader = $annotPropTypeReader;
        $this->docBlkPropTypeReader = $docBlkPropTypeReader;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return PropertyMetaDataInterface[]
     * @throws RuntimeException
     */
    public function getPropertyMetadata(ReflectionClass $reflectionClass): array
    {
        $metaData = [];

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            $annotations = $this->annotationReader->getPropertyAnnotations($reflectionProperty);
            $type = $this->getType($reflectionProperty, $annotations);
            $propertyClassName = $type instanceof ObjectType ?
                                    ObjectPropertyMetadata::class : ReflectionPropertyMetadata::class;

            $metaData[$propertyName] = new $propertyClassName($reflectionProperty, $annotations, $type);
        }

        return $metaData;
    }

    /**
     * @param ReflectionProperty    $reflectionProperty
     * @param AnnotationInterface[] $annotations
     * @return TypeInterface
     * @throws RuntimeException
     */
    private function getType(ReflectionProperty $reflectionProperty, array $annotations): TypeInterface
    {
        try {
            return $this->annotPropTypeReader->getType($annotations);
        } catch (RuntimeException $e) {
        }

        try {
            return $this->docBlkPropTypeReader->getType($reflectionProperty);
        } catch (RuntimeException $e) {
        }

        throw new RuntimeException(
            sprintf(
                'Type declaration missing in class: %s, property: %s. Either define a Property annotation'
                        . ' or define a @var tag in doc block comment.',
                $reflectionProperty->getDeclaringClass()->getName(),
                $reflectionProperty->getName()
            )
        );
    }
}
