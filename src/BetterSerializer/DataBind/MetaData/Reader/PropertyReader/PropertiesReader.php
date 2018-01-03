<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ClassPropertyMetaData;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetadata;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContext;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\TypeReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use BetterSerializer\Reflection\ReflectionClassInterface;
use ReflectionException;
use RuntimeException;

/**
 * Class PropertyReader
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class PropertiesReader implements PropertiesReaderInterface
{

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * @var TypeReaderInterface[]
     */
    private $typeReaders;

    /**
     * PropertyReader constructor.
     * @param AnnotationReader $annotationReader
     * @param TypeFactoryInterface $typeFactory
     * @param TypeReaderInterface[] $typeReaders
     * @throws RuntimeException
     */
    public function __construct(
        AnnotationReader $annotationReader,
        TypeFactoryInterface $typeFactory,
        array $typeReaders
    ) {
        $this->annotationReader = $annotationReader;
        $this->typeFactory = $typeFactory;

        if (empty($typeReaders)) {
            throw new RuntimeException('Type readers missing.');
        }

        $this->typeReaders = $typeReaders;
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
            $annotations = $this->annotationReader->getPropertyAnnotations($nativeReflProperty);
            $context = new PropertyContext($reflectionClass, $reflectionProperty, $annotations);
            $type = $this->getType($context);
            $propertyClassName = $type instanceof ClassType ?
                                    ClassPropertyMetaData::class : ReflectionPropertyMetadata::class;

            $metaData[$propertyName] = new $propertyClassName($reflectionProperty, $context->getAnnotations(), $type);
        }

        return $metaData;
    }

    /**
     * @param PropertyContext $context
     * @return TypeInterface
     * @throws RuntimeException
     */
    private function getType(PropertyContext $context): TypeInterface
    {
        foreach ($this->typeReaders as $typeReader) {
            $typedContext = $typeReader->resolveType($context);

            if ($typedContext) {
                return $this->typeFactory->getType($typedContext);
            }
        }

        $reflectionProperty = $context->getReflectionProperty();

        throw new RuntimeException(
            sprintf(
                'Type declaration missing in class: %s, property: %s.',
                $reflectionProperty->getDeclaringClass()->getName(),
                $reflectionProperty->getName()
            )
        );
    }
}
