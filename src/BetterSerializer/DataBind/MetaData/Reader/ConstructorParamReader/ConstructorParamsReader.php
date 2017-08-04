<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader;

use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaData;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContext;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringFormTypedPropertyContext;
use BetterSerializer\DataBind\MetaData\Reflection\ReflectionClassHelperInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use ReflectionClass;
use ReflectionParameter;
use LogicException;

/**
 * Class ConsttuctorParamReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader
 */
final class ConstructorParamsReader implements ConstructorParamsReaderInterface
{

    /**
     * @var ReflectionClassHelperInterface
     */
    private $reflClassHelper;

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * ConsttuctorParamReader constructor.
     * @param ReflectionClassHelperInterface $reflClassHelper
     * @param TypeFactoryInterface $typeFactory
     */
    public function __construct(ReflectionClassHelperInterface $reflClassHelper, TypeFactoryInterface $typeFactory)
    {
        $this->reflClassHelper = $reflClassHelper;
        $this->typeFactory = $typeFactory;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param PropertyMetaDataInterface[] $propertiesMetaData
     * @return ConstructorParamMetaDataInterface[]
     * @throws LogicException
     */
    public function getConstructorParamsMetadata(ReflectionClass $reflectionClass, array $propertiesMetaData): array
    {
        $constructor = $reflectionClass->getConstructor();
        $args = $constructor->getParameters();
        $constructorParams = [];

        /* @var $argument ReflectionParameter */
        foreach ($args as $argument) {
            $argName = $argument->getName();

            if (!isset($propertiesMetaData[$argName])) {
                continue;
            }

            $reflectionProperty = $this->reflClassHelper->getProperty($reflectionClass, $argName);
            $context = new PropertyContext($reflectionClass, $reflectionProperty, []);
            $constructorParam = $this->createConstructorParam($argument, $propertiesMetaData[$argName], $context);

            if ($constructorParam) {
                $constructorParams[$argName] = $constructorParam;
            }
        }

        return $constructorParams;
    }

    /**
     * @param ReflectionParameter $arg
     * @param PropertyMetaDataInterface $property
     * @param PropertyContext $context
     * @return ConstructorParamMetaData
     * @throws LogicException
     */
    private function createConstructorParam(
        ReflectionParameter $arg,
        PropertyMetaDataInterface $property,
        PropertyContext $context
    ): ?ConstructorParamMetaData {
        $name = $arg->getName();
        $reflectionType = $arg->getType();

        if (!$reflectionType) {
            throw new LogicException(sprintf("Instantiator parameter type missing for '%s'.", $name));
        }

        $stringType = (string) $reflectionType;

        // temporary hack
        if ($stringType === 'array') {
            $propType = $property->getType();

            return new ConstructorParamMetaData($name, $propType, $property->getOutputKey());
        }

        $typedContext = new StringFormTypedPropertyContext($context, $stringType);
        $type = $this->typeFactory->getType($typedContext);

        if (!$type->equals($property->getType())) {
            throw new LogicException(
                sprintf(
                    "Types don't match for constructor argument '%s' and class parameter '%s'.",
                    $name,
                    $property->getOutputKey()
                )
            );
        }

        return new ConstructorParamMetaData($name, $type, $property->getOutputKey());
    }
}
