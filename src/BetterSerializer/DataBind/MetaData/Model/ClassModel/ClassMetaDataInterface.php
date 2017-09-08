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
interface ClassMetaDataInterface
{

    /**
     * @return ReflectionClassInterface
     */
    public function getReflectionClass(): ReflectionClassInterface;

    /**
     * @return string
     */
    public function getClassName(): string;

    /**
     * @return array
     */
    public function getAnnotations(): array;
}
