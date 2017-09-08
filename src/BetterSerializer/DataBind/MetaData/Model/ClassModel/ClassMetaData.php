<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Model\ClassModel;

use BetterSerializer\Reflection\ReflectionClassInterface;

/**
 * Class ClassMetadata
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class ClassMetaData implements ClassMetaDataInterface
{

    /**
     * @var ReflectionClassInterface
     */
    private $reflectionClass;

    /**
     * @var array<ClassMetadata>
     */
    private $annotations;

    /**
     * ClassMetadata constructor.
     *
     * @param ReflectionClassInterface $reflectionClass
     * @param array $annotations
     */
    public function __construct(ReflectionClassInterface $reflectionClass, array $annotations)
    {
        $this->reflectionClass = $reflectionClass;
        $this->annotations = $annotations;
    }

    /**
     * @return ReflectionClassInterface
     */
    public function getReflectionClass(): ReflectionClassInterface
    {
        return $this->reflectionClass;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->reflectionClass->getName();
    }

    /**
     * @return array
     */
    public function getAnnotations(): array
    {
        return $this->annotations;
    }
}
