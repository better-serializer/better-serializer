<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\ClassMetadataInterface;
use ReflectionClass;

/**
 * Class ClassReader
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
interface ClassReaderInterface
{
    /**
     * @param ReflectionClass $reflectionClass
     * @return ClassMetadataInterface
     */
    public function getClassMetadata(ReflectionClass $reflectionClass): ClassMetadataInterface;
}
