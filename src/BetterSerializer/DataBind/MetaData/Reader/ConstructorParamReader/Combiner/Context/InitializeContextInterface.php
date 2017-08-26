<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;

/**
 * Interface InitializeContextInterface
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context
 */
interface InitializeContextInterface
{

    /**
     * @return ReflectionMethodInterface
     */
    public function getConstructor(): ReflectionMethodInterface;

    /**
     * @return ShrinkingPropertiesMetaDataInterface
     */
    public function getPropertiesMetaData(): ShrinkingPropertiesMetaDataInterface;
}
