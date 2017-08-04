<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Chained;

use BetterSerializer\DataBind\MetaData\Annotations\BoundToPropertyInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;
use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionParameter;

/**
 * Class AnnotationCombiner
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\TypeReader\StringType
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
     */
    public function initialize(Context\InitializeContextInterface $context): void
    {
        $this->propertiesMetaData = $context->getPropertiesMetaData();
        $constructor = $context->getConstructor();

        $allAnnotations = $this->annotationReader->getMethodAnnotations($constructor);
        $this->annotations = [];

        foreach ($allAnnotations as $annotation) {
            if (!$annotation instanceof BoundToPropertyInterface) {
                continue;
            }

            $this->annotations[$annotation->getArgumentName()] = $annotation;
        }
    }

    /**
     * @param ReflectionParameter $parameter
     * @return bool
     */
    protected function isAbleToCombine(ReflectionParameter $parameter): bool
    {
        return isset($this->annotations[$parameter->getName()]);
    }

    /**
     * @param ReflectionParameter $parameter
     * @return Context\PropertyWithConstructorParamTupleInterface
     */
    protected function createCombinedTuple(
        ReflectionParameter $parameter
    ): Context\PropertyWithConstructorParamTupleInterface {
        $boundTuple = $this->annotations[$parameter->getName()];
        /* @var $propertyMetaData ReflectionPropertyMetaDataInterface */
        $propertyMetaData = $this->propertiesMetaData->shrinkBy($boundTuple->getPropertyName());

        return new Context\PropertyWithConstructorParamTuple($parameter, $propertyMetaData);
    }
}
