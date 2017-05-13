<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData;

use BetterSerializer\DataBind\MetaData\Annotations\AnnotationInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use ReflectionProperty;

/**
 * Class AbstractReflectionPropertyMetaData
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 */
abstract class AbstractReflectionPropertyMetaData extends AbstractPropertyMetaData implements
    ReflectionPropertyMetaDataInterface
{

    /**
     * @var ReflectionProperty
     */
    private $reflectionProperty;

    /**
     * PropertyMetadata constructor.
     *
     * @param ReflectionProperty    $reflectionProperty
     * @param AnnotationInterface[] $annotations
     * @param TypeInterface         $type
     */
    public function __construct(ReflectionProperty $reflectionProperty, array $annotations, TypeInterface $type)
    {
        $this->reflectionProperty = $reflectionProperty;
        parent::__construct($annotations, $type);
    }

    /**
     * @return ReflectionProperty
     */
    public function getReflectionProperty(): ReflectionProperty
    {
        return $this->reflectionProperty;
    }
}
