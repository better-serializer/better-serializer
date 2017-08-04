<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use ReflectionMethod;

/**
 * Interface InitializeContextInterface
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context
 */
interface InitializeContextInterface
{

    /**
     * @return ReflectionMethod
     */
    public function getConstructor(): ReflectionMethod;

    /**
     * @return ShrinkingPropertiesMetaDataInterface
     */
    public function getPropertiesMetaData(): ShrinkingPropertiesMetaDataInterface;
}
