<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ClassPropertyMetaData;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetadata;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContext;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver\TypeResolverChainInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use BetterSerializer\Reflection\ReflectionClassInterface;
use ReflectionException;
use RuntimeException;

/**
 *
 */
final class PropertiesReader implements PropertiesReaderInterface
{

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var TypeResolverChainInterface
     */
    private $typeResolverChain;

    /**
     * PropertyReader constructor.
     * @param AnnotationReader $annotationReader
     * @param TypeResolverChainInterface $typeResolverChain
     */
    public function __construct(AnnotationReader $annotationReader, TypeResolverChainInterface $typeResolverChain)
    {
        $this->annotationReader = $annotationReader;
        $this->typeResolverChain = $typeResolverChain;
    }

    /**
     * @param ReflectionClassInterface $reflectionClass
     * @return PropertyMetaDataInterface[]
     * @throws RuntimeException
     * @throws ReflectionException
     */
    public function getPropertiesMetadata(ReflectionClassInterface $reflectionClass): array
    {
        $metaData = [];

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $nativeReflProperty = $reflectionProperty->getNativeReflProperty();
            $propertyName = $nativeReflProperty->getName();
            $context = $this->createPropertyContext($reflectionClass, $reflectionProperty);
            $type = $this->typeResolverChain->resolveType($context);
            $metaData[$propertyName] = $this->createPropertyMetaData(
                $reflectionProperty,
                $context,
                $type
            );
        }

        return $metaData;
    }

    /**
     * @param ReflectionPropertyInterface $reflectionProperty
     * * @param PropertyContext $context
     * @param TypeInterface $type
     * @return PropertyMetaDataInterface
     * @throws RuntimeException
     */
    private function createPropertyMetaData(
        ReflectionPropertyInterface $reflectionProperty,
        PropertyContext $context,
        TypeInterface $type
    ): PropertyMetaDataInterface {
        if ($type instanceof ClassType) {
            return new ClassPropertyMetaData($reflectionProperty, $context->getAnnotations(), $type);
        }

        return new PropertyMetadata($reflectionProperty, $context->getAnnotations(), $type);
    }

    /**
     * @param ReflectionClassInterface $reflectionClass
     * @param ReflectionPropertyInterface $reflectionProperty
     * @return PropertyContext
     * @throws RuntimeException
     */
    private function createPropertyContext(
        ReflectionClassInterface $reflectionClass,
        ReflectionPropertyInterface $reflectionProperty
    ): PropertyContext {
        $annotations = $this->annotationReader->getPropertyAnnotations($reflectionProperty->getNativeReflProperty());

        return new PropertyContext($reflectionClass, $reflectionProperty, $annotations);
    }
}
