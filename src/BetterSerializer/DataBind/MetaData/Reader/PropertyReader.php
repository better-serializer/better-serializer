<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Annotations\Property;
use BetterSerializer\DataBind\MetaData\PropertyMetadata;
use BetterSerializer\DataBind\MetaData\PropertyMetadataInterface;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use ReflectionClass;
use ReflectionProperty;

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
     * @var DocBlockFactoryInterface
     */
    private $docBlockFactory;

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * PropertyReader constructor.
     * @param AnnotationReader $annotationReader
     * @param DocBlockFactoryInterface $docBlockFactory
     * @param TypeFactoryInterface $typeFactory
     */
    public function __construct(
        AnnotationReader $annotationReader,
        DocBlockFactoryInterface $docBlockFactory,
        TypeFactoryInterface $typeFactory
    ) {
        $this->annotationReader = $annotationReader;
        $this->docBlockFactory = $docBlockFactory;
        $this->typeFactory = $typeFactory;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return PropertyMetadataInterface[]
     * @throws Exception
     */
    public function getPropertyMetadata(ReflectionClass $reflectionClass): array
    {
        $metaData = [];

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            $annotations = $this->annotationReader->getPropertyAnnotations($reflectionProperty);
            $type = $this->getType($reflectionProperty, $annotations);
            $metaData[$propertyName] = new PropertyMetadata($reflectionProperty, $annotations, $type);
        }

        return $metaData;
    }

    /**
     * @param ReflectionProperty $reflectionProperty
     * @param AnnotationInterface[] $annotations
     * @return TypeInterface
     * @throws Exception
     */
    private function getType(ReflectionProperty $reflectionProperty, array $annotations): TypeInterface
    {
        $type = $this->getTypeFromAnnotations($annotations);

        if (!$type instanceof  NullType) {
            return $type;
        }

        $type = $this->getTypeFromReflectionProperty($reflectionProperty);

        if ($type instanceof  NullType) {
            throw new Exception(
                sprintf(
                    'Type declaration missing in class: %s, property: %s',
                    $reflectionProperty->getDeclaringClass()->getName(),
                    $reflectionProperty->getName()
                )
            );
        }

        return $type;
    }

    /**
     * @param array $annotations
     * @return TypeInterface
     */
    private function getTypeFromAnnotations(array $annotations): TypeInterface
    {
        $propertyAnnotation = null;

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Property) {
                $propertyAnnotation = $annotation;
                break;
            }
        }

        $propertyType = $propertyAnnotation ? $propertyAnnotation->getType() : null;

        return $this->typeFactory->getType($propertyType);
    }

    /**
     * @param ReflectionProperty $reflectionProperty
     * @return TypeInterface
     * @throws Exception
     */
    private function getTypeFromReflectionProperty(ReflectionProperty $reflectionProperty): TypeInterface
    {
        $docComment = $reflectionProperty->getDocComment();
        if ($docComment === '') {
            throw new Exception(sprintf('You need to add a docblock to property "%s"', $reflectionProperty->getName()));
        }

        $docBlock = $this->docBlockFactory->create($docComment);
        $varTags = $docBlock->getTagsByName('var');

        if (empty($varTags)) {
            throw new Exception(
                sprintf(
                    'You need to add an @var annotation to property "%s"',
                    $reflectionProperty->getName()
                )
            );
        }

        /** @var Var_[] $varTags */
        $type = $varTags[0]->getType();

        return $this->typeFactory->getType((string) $type);
    }
}
