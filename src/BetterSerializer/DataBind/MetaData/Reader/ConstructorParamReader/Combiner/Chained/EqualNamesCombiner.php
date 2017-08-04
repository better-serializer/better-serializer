<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context\InitializeContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use ReflectionParameter;

/**
 * Class EqualNamesCombiner
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained
 */
final class EqualNamesCombiner extends ChainedCombiner
{

    /**
     * @var ShrinkingPropertiesMetaDataInterface
     */
    private $propertiesMetaData;

    /**
     * @param InitializeContextInterface $context
     */
    public function initialize(InitializeContextInterface $context): void
    {
        $this->propertiesMetaData = $context->getPropertiesMetaData();
    }

    /**
     * @param ReflectionParameter $parameter
     * @return bool
     */
    protected function isAbleToCombine(ReflectionParameter $parameter): bool
    {
        return $this->propertiesMetaData->hasProperty($parameter->getName());
    }

    /**
     * @param ReflectionParameter $parameter
     * @return Context\PropertyWithConstructorParamTupleInterface
     */
    protected function createCombinedTuple(
        ReflectionParameter $parameter
    ): Context\PropertyWithConstructorParamTupleInterface {
        $propertyMetaData = $this->propertiesMetaData->shrinkBy($parameter->getName());

        return new Context\PropertyWithConstructorParamTuple($parameter, $propertyMetaData);
    }
}
