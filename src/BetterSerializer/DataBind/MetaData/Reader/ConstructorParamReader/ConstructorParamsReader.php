<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader;

use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaData;
use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\TypeReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use ReflectionClass;
use LogicException;
use RuntimeException;

/**
 * Class ConsttuctorParamReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader
 */
final class ConstructorParamsReader implements ConstructorParamsReaderInterface
{

    /**
     * @var Combiner\PropertyWithConstructorParamCombinerInterface
     */
    private $paramCombiner;

    /**
     * @var TypeReaderInterface
     */
    private $typeReader;

    /**
     * ConstructorParamsReader constructor.
     * @param Combiner\PropertyWithConstructorParamCombinerInterface $paramCombiner
     * @param TypeReaderInterface $typeReader
     */
    public function __construct(
        Combiner\PropertyWithConstructorParamCombinerInterface $paramCombiner,
        TypeReaderInterface $typeReader
    ) {
        $this->paramCombiner = $paramCombiner;
        $this->typeReader = $typeReader;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param array $propertiesMetaData
     * @return ConstructorParamMetaDataInterface[]
     * @throws RuntimeException
     */
    public function getConstructorParamsMetadata(ReflectionClass $reflectionClass, array $propertiesMetaData): array
    {
        $constructor = $reflectionClass->getConstructor();
        $tuples = $this->paramCombiner->combine($constructor, $propertiesMetaData);
        $types = $this->typeReader->getParameterTypes($constructor);

        return $this->createConstructorParamMetaDatas($tuples, $types);
    }

    /**
     * @param Combiner\Context\PropertyWithConstructorParamTupleInterface[] $tuples
     * @param TypeInterface[] $types
     * @return ConstructorParamMetaDataInterface[]
     * @throws RuntimeException
     */
    private function createConstructorParamMetaDatas(array $tuples, array $types): array
    {
        $constructorParams = [];

        foreach ($tuples as $tuple) {
            $paramName = $tuple->getParamName();

            if (!isset($types[$paramName])) {
                throw new RuntimeException(sprintf("Parameter type missing for '%s'", $paramName));
            }

            $type = $types[$paramName];

            try {
                $constructorParams[$tuple->getPropertyName()] = new ConstructorParamMetaData($tuple, $type);
            } catch (LogicException $e) {
            }
        }

        return $constructorParams;
    }
}
