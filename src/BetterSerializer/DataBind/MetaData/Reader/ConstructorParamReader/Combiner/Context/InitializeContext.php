<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use ReflectionMethod;

/**
 * Class InitializeContext
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context
 */
final class InitializeContext implements InitializeContextInterface
{

    /**
     * @var ReflectionMethod
     */
    private $constructor;

    /**
     * @var ShrinkingPropertiesMetaDataInterface
     */
    private $propertiesMetaData;

    /**
     * InitializeContext constructor.
     * @param ReflectionMethod $constructor
     * @param ShrinkingPropertiesMetaDataInterface $propertiesMetaData
     */
    public function __construct(ReflectionMethod $constructor, ShrinkingPropertiesMetaDataInterface $propertiesMetaData)
    {
        $this->constructor = $constructor;
        $this->propertiesMetaData = $propertiesMetaData;
    }

    /**
     * @return ReflectionMethod
     */
    public function getConstructor(): ReflectionMethod
    {
        return $this->constructor;
    }

    /**
     * @return ShrinkingPropertiesMetaDataInterface
     */
    public function getPropertiesMetaData(): ShrinkingPropertiesMetaDataInterface
    {
        return $this->propertiesMetaData;
    }
}
