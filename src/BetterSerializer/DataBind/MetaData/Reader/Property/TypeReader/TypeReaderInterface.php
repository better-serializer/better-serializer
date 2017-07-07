<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\Property\TypeReader;

use BetterSerializer\DataBind\MetaData\Reader\Property\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Reader\Property\Context\StringTypedPropertyContextInterface;

/**
 * Class AnnotationPropertyTypeReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
interface TypeReaderInterface
{
    /**
     * @param PropertyContextInterface $context
     * @return StringTypedPropertyContextInterface|null
     */
    public function resolveType(PropertyContextInterface $context): ?StringTypedPropertyContextInterface;
}
