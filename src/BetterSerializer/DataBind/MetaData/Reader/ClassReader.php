<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\ClassMetaData;
use BetterSerializer\DataBind\MetaData\ClassMetaDataInterface;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use ReflectionClass;
use ReflectionException;

/**
 * Class ClassReader
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class ClassReader implements ClassReaderInterface
{

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * PropertyReader constructor.
     *
     * @param AnnotationReader $annotationReader
     */
    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return ClassMetaDataInterface
     */
    public function getClassMetadata(ReflectionClass $reflectionClass): ClassMetaDataInterface
    {
        $classAnnotations = $this->annotationReader->getClassAnnotations($reflectionClass);

        return new ClassMetaData($reflectionClass->getName(), $classAnnotations);
    }
}
