<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use RuntimeException;

/**
 *
 */
final class ClassPropertyMetaData extends AbstractReflectionPropertyMetaData implements ClassPropertyMetaDataInterface
{

    /**
     * PropertyMetadata constructor.
     *
     * @param ReflectionPropertyInterface $reflectionProperty
     * @param AnnotationInterface[] $annotations
     * @param ClassType $type
     * @throws RuntimeException
     */
    public function __construct(ReflectionPropertyInterface $reflectionProperty, array $annotations, ClassType $type)
    {
        parent::__construct($reflectionProperty, $annotations, $type);
    }

    /**
     * @return string
     */
    public function getObjectClass(): string
    {
        /* @var $type ClassType */
        $type = $this->getType();

        return $type->getClassName();
    }
}
