<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use LogicException;
use RuntimeException;

/**
 * Class PropertyMetadata
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData
 */
abstract class AbstractPropertyMetaData implements PropertyMetaDataInterface
{

    /**
     * @var AnnotationInterface[]
     */
    private $annotations;

    /**
     * @var TypeInterface
     */
    private $type;

    /**
     * PropertyMetadata constructor.
     *
     * @param AnnotationInterface[] $annotations
     * @param TypeInterface         $type
     */
    public function __construct(array $annotations, TypeInterface $type)
    {
        $this->annotations = $annotations;
        $this->type = $type;
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @return string
     * @throws LogicException
     * @throws RuntimeException
     */
    public function getOutputKey(): string
    {
        $propertyAnnotations = array_filter($this->annotations, function (AnnotationInterface $annotation) {
            return $annotation instanceof PropertyInterface;
        });

        if (count($propertyAnnotations) !== 1) {
            throw new LogicException(
                sprintf('Invalid property annotation count - %d.', count($propertyAnnotations))
            );
        }

        /* @var  $propertyAnnotation PropertyInterface */
        $propertyAnnotation = $propertyAnnotations[0];
        $outputKey = $propertyAnnotation->getName();

        if (!$outputKey) {
            throw new RuntimeException('Missing property name.');
        }

        return $outputKey;
    }
}
