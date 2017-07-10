<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ObjectPropertyMetaData;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetadata;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContext;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeReader\TypeReaderInterface;
use BetterSerializer\DataBind\MetaData\Reflection\ReflectionClassHelperInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use ReflectionClass;
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
     * @var ReflectionClassHelperInterface
     */
    private $reflClassHelper;

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
     * @param ReflectionClassHelperInterface $reflClassHelper
     * @param AnnotationReader $annotationReader
     * @param TypeFactoryInterface $typeFactory
     * @param TypeReaderInterface[] $typeReaders
     * @throws RuntimeException
     */
    public function __construct(
        ReflectionClassHelperInterface $reflClassHelper,
        AnnotationReader $annotationReader,
        TypeFactoryInterface $typeFactory,
        array $typeReaders
    ) {
        $this->reflClassHelper = $reflClassHelper;
        $this->annotationReader = $annotationReader;
        $this->typeFactory = $typeFactory;

        if (empty($typeReaders)) {
            throw new RuntimeException('Type readers missing.');
        }

        $this->typeReaders = $typeReaders;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return PropertyMetaDataInterface[]
     * @throws RuntimeException
     */
    public function getPropertiesMetadata(ReflectionClass $reflectionClass): array
    {
        $metaData = [];

        foreach ($this->reflClassHelper->getProperties($reflectionClass) as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            $annotations = $this->annotationReader->getPropertyAnnotations($reflectionProperty);
            $context = new PropertyContext($reflectionClass, $reflectionProperty, $annotations);
            $type = $this->getType($context);
            $propertyClassName = $type instanceof ObjectType ?
                                    ObjectPropertyMetaData::class : ReflectionPropertyMetadata::class;

            $metaData[$propertyName] = new $propertyClassName($reflectionProperty, $annotations, $type);
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
