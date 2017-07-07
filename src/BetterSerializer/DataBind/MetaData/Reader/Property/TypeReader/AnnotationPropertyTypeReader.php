<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\Property\TypeReader;

use BetterSerializer\DataBind\MetaData\Reader\Property\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\Property\Context\StringTypedPropertyContext;
use BetterSerializer\DataBind\MetaData\Reader\Property\Context\StringTypedPropertyContextInterface;

/**
 * Class AnnotationPropertyTypeReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class AnnotationPropertyTypeReader implements TypeReaderInterface
{

    /**
     * @param PropertyContextInterface $context
     * @return StringTypedPropertyContextInterface|null
     */
    public function resolveType(PropertyContextInterface $context): ?StringTypedPropertyContextInterface
    {
        $propertyAnnotation = $context->getPropertyAnnotation();

        if ($propertyAnnotation === null) {
            return null;
        }

        $propertyType = $propertyAnnotation->getType();

        return new StringTypedPropertyContext($context, $propertyType);
    }
}
