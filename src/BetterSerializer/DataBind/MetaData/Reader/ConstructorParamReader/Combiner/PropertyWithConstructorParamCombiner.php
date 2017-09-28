<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained\ChainedCombinerInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context\InitializeContext;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;
use RuntimeException;

/**
 * Class PropertyWithParamCombiner
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner
 */
final class PropertyWithConstructorParamCombiner implements PropertyWithConstructorParamCombinerInterface
{

    /**
     * @var ChainedCombinerInterface[]
     */
    private $chainedCombiners;

    /**
     * PropertyWithParamCombiner constructor.
     * @param ChainedCombinerInterface[] $chainedCombiners
     * @throws RuntimeException
     */
    public function __construct(array $chainedCombiners)
    {
        if (empty($chainedCombiners)) {
            throw new RuntimeException('Chained combiners missing.');
        }

        $this->chainedCombiners = $chainedCombiners;
    }

    /**
     * @param ReflectionMethodInterface $constructor
     * @param PropertyMetaDataInterface[] $propertiesMetaData
     * @return Context\PropertyWithConstructorParamTupleInterface[]
     */
    public function combine(ReflectionMethodInterface $constructor, array $propertiesMetaData): array
    {
        $this->initializeCombiners($constructor, $propertiesMetaData);
        $params = $constructor->getParameters();

        return $this->createCombinedTuples($params);
    }

    /**
     * @param ReflectionMethodInterface $constructor
     * @param array $propertiesMetaData
     */
    private function initializeCombiners(
        ReflectionMethodInterface $constructor,
        array $propertiesMetaData
    ): void {
        $context = new InitializeContext($constructor, new ShrinkingPropertiesMetaData($propertiesMetaData));

        foreach ($this->chainedCombiners as $combiner) {
            $combiner->initialize($context);
        }
    }

    /**
     * @param ReflectionParameterInterface[] $constructorParams
     * @return Context\PropertyWithConstructorParamTupleInterface[]
     */
    private function createCombinedTuples(array $constructorParams): array
    {
        $tuples = [];

        foreach ($constructorParams as $parameter) {
            $tuple = $this->createCombinedTupleForParameter($parameter);

            if ($tuple) {
                $tuples[] = $tuple;
            }
        }

        return $tuples;
    }

    /**
     * @param ReflectionParameterInterface $parameter
     * @return Context\PropertyWithConstructorParamTupleInterface|null
     */
    private function createCombinedTupleForParameter(
        ReflectionParameterInterface $parameter
    ): ?Context\PropertyWithConstructorParamTupleInterface {
        foreach ($this->chainedCombiners as $combiner) {
            $tuple = $combiner->combineWithParameter($parameter);

            if ($tuple) {
                return $tuple;
            }
        }

        return null;
    }
}
