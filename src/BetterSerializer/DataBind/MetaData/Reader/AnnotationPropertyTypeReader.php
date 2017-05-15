<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Annotations\PropertyInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use RuntimeException;

/**
 * Class AnnotationPropertyTypeReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class AnnotationPropertyTypeReader implements AnnotationPropertyTypeReaderInterface
{

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * AnnotationPropertyTypeReader constructor.
     * @param TypeFactoryInterface $typeFactory
     */
    public function __construct(TypeFactoryInterface $typeFactory)
    {
        $this->typeFactory = $typeFactory;
    }

    /**
     * @param array $annotations
     * @return TypeInterface
     * @throws RuntimeException
     */
    public function getType(array $annotations): TypeInterface
    {
        $propertyAnnotation = null;

        foreach ($annotations as $annotation) {
            if ($annotation instanceof PropertyInterface) {
                $propertyAnnotation = $annotation;
                break;
            }
        }

        if ($propertyAnnotation === null) {
            throw new RuntimeException('Property annotation missing.');
        }

        $propertyType = $propertyAnnotation->getType();

        return $this->typeFactory->getType($propertyType);
    }
}
