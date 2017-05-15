<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use RuntimeException;

/**
 * Class AnnotationPropertyTypeReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
interface AnnotationPropertyTypeReaderInterface
{
    /**
     * @param array $annotations
     * @return TypeInterface
     * @throws RuntimeException
     */
    public function getType(array $annotations): TypeInterface;
}
