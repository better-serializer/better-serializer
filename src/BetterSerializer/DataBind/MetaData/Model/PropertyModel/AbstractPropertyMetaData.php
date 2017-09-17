<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Annotations\Groups;
use BetterSerializer\DataBind\MetaData\Annotations\Property;
use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use LogicException;
use RuntimeException;

/**
 * ClassModel PropertyMetadata
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
     * @throws RuntimeException
     */
    public function __construct(array $annotations, TypeInterface $type)
    {
        $this->setAnnotations($annotations);
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
        if (!isset($this->annotations[Property::ANNOTATION_NAME])) {
            throw new LogicException('Property annotation missing.');
        }

        /* @var  $propertyAnnotation PropertyInterface */
        $propertyAnnotation = $this->annotations[Property::ANNOTATION_NAME];
        $outputKey = $propertyAnnotation->getName();

        if (!$outputKey) {
            throw new RuntimeException('Missing property name.');
        }

        return $outputKey;
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        /* @var $groupsAnnotation Groups */
        $groupsAnnotation = $this->annotations[Groups::ANNOTATION_NAME];

        return $groupsAnnotation->getGroups();
    }

    /**
     * @param array $annotations
     * @throws RuntimeException
     */
    private function setAnnotations(array $annotations): void
    {
        if (!isset($annotations[Groups::ANNOTATION_NAME])) {
            throw new RuntimeException('Groups annotation missing.');
        }

        $this->annotations = $annotations;
    }
}
