<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use RuntimeException;
use ReflectionProperty;

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
     * @param ReflectionProperty    $reflectionProperty
     * @param AnnotationInterface[] $annotations
     * @param ObjectType            $type
     */
    public function __construct(ReflectionProperty $reflectionProperty, array $annotations, ObjectType $type)
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
