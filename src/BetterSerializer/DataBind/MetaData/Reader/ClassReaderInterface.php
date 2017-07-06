<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use ReflectionClass;

/**
 * Class ClassReader
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
interface ClassReaderInterface
{
    /**
     * @param ReflectionClass $reflectionClass
     * @return ClassMetaDataInterface
     */
    public function getClassMetadata(ReflectionClass $reflectionClass): ClassMetaDataInterface;
}
