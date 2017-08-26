<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use RuntimeException;

/**
 * ClassModel ObjectPropertyMetadata
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class ObjectPropertyMetaData extends AbstractReflectionPropertyMetaData implements ObjectPropertyMetaDataInterface
{

    /**
     * PropertyMetadata constructor.
     *
     * @param ReflectionPropertyInterface    $reflectionProperty
     * @param AnnotationInterface[] $annotations
     * @param ObjectType            $type
     */
    public function __construct(ReflectionPropertyInterface $reflectionProperty, array $annotations, ObjectType $type)
    {
        parent::__construct($reflectionProperty, $annotations, $type);
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    public function getObjectClass(): string
    {
        /* @var $type ObjectType */
        $type = $this->getType();

        return $type->getClassName();
    }
}
