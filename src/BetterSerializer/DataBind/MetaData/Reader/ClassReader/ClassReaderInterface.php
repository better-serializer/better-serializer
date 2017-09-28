<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader\ClassReader;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;

/**
 * Class ClassReader
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
interface ClassReaderInterface
{
    /**
     * @param ReflectionClassInterface $reflectionClass
     * @return ClassMetaDataInterface
     */
    public function getClassMetadata(ReflectionClassInterface $reflectionClass): ClassMetaDataInterface;
}
