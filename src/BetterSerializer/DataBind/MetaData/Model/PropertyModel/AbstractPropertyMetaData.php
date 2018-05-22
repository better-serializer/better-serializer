<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Annotations\Groups;
use BetterSerializer\DataBind\MetaData\Annotations\Property;
use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use LogicException;
use RuntimeException;

/**
 *
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
     * @var ReflectionPropertyInterface
     */
    private $reflectionProperty;

    /**
     * @param ReflectionPropertyInterface $reflectionProperty,
     * @param AnnotationInterface[] $annotations
     * @param TypeInterface         $type
     * @throws RuntimeException
     */
    public function __construct(
        ReflectionPropertyInterface $reflectionProperty,
        array $annotations,
        TypeInterface $type
    ) {
        $this->reflectionProperty = $reflectionProperty;
        $this->reflectionProperty->setAccessible(true);
        $this->setAnnotations($annotations);
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->reflectionProperty->getName();
    }

    /**
     * @return string
     */
    public function getSerializationName(): string
    {
        if (!isset($this->annotations[Property::ANNOTATION_NAME])) {
            return '';
        }

        /* @var $propertyAnnotation Property */
        $propertyAnnotation = $this->annotations[Property::ANNOTATION_NAME];

        return $propertyAnnotation->getName();
    }

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    /**
     * @return ReflectionPropertyInterface
     */
    public function getReflectionProperty(): ReflectionPropertyInterface
    {
        return $this->reflectionProperty;
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
