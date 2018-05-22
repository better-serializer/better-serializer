<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained;

use BetterSerializer\DataBind\MetaData\Annotations\BoundToPropertyInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use BetterSerializer\Reflection\ReflectionParameterInterface;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 *
 */
final class AnnotationCombiner extends ChainedCombiner
{

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var ShrinkingPropertiesMetaDataInterface
     */
    private $propertiesMetaData;

    /**
     * @var BoundToPropertyInterface[]
     */
    private $annotations;

    /**
     * AnnotationCombiner constructor.
     * @param AnnotationReader $annotationReader
     */
    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param Context\InitializeContextInterface $context
     * @throws \RuntimeException
     */
    public function initialize(Context\InitializeContextInterface $context): void
    {
        $this->propertiesMetaData = $context->getPropertiesMetaData();
        $constructor = $context->getConstructor();

        $allAnnotations = $this->annotationReader->getMethodAnnotations($constructor->getNativeReflMethod());
        $this->annotations = [];

        foreach ($allAnnotations as $annotation) {
            if (!$annotation instanceof BoundToPropertyInterface) {
                continue;
            }

            $this->annotations[$annotation->getArgumentName()] = $annotation;
        }
    }

    /**
     * @param ReflectionParameterInterface $parameter
     * @return bool
     */
    protected function isAbleToCombine(ReflectionParameterInterface $parameter): bool
    {
        return isset($this->annotations[$parameter->getName()]);
    }

    /**
     * @param ReflectionParameterInterface $parameter
     * @return Context\PropertyWithConstructorParamTupleInterface
     */
    protected function createCombinedTuple(
        ReflectionParameterInterface $parameter
    ): Context\PropertyWithConstructorParamTupleInterface {
        $boundTuple = $this->annotations[$parameter->getName()];
        /* @var $propertyMetaData PropertyMetaDataInterface */
        $propertyMetaData = $this->propertiesMetaData->shrinkBy($boundTuple->getPropertyName());

        return new Context\PropertyWithConstructorParamTuple($parameter, $propertyMetaData);
    }
}
