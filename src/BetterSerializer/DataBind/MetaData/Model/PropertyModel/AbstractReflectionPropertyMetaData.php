<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyModel;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use LogicException;
use RuntimeException;

/**
 * ClassModel AbstractReflectionPropertyMetaData
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
abstract class AbstractReflectionPropertyMetaData extends AbstractPropertyMetaData implements
    ReflectionPropertyMetaDataInterface
{

    /**
     * @var ReflectionPropertyInterface
     */
    private $reflectionProperty;

    /**
     * PropertyMetadata constructor.
     *
     * @param ReflectionPropertyInterface $reflectionProperty
     * @param AnnotationInterface[] $annotations
     * @param TypeInterface         $type
     */
    public function __construct(
        ReflectionPropertyInterface $reflectionProperty,
        array $annotations,
        TypeInterface $type
    ) {
        $this->reflectionProperty = $reflectionProperty;
        $this->reflectionProperty->setAccessible(true);
        parent::__construct($annotations, $type);
    }

    /**
     * @return ReflectionPropertyInterface
     */
    public function getReflectionProperty(): ReflectionPropertyInterface
    {
        return $this->reflectionProperty;
    }

    /**
     * @return string
     * @SuppressWarnings(PHPMD)
     */
    public function getOutputKey(): string
    {
        try {
            return parent::getOutputKey();
        } catch (RuntimeException | LogicException $e) {
        }

        return $this->reflectionProperty->getName();
    }
}
