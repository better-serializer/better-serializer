<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;

/**
 * Class InitializeContext
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context
 */
final class InitializeContext implements InitializeContextInterface
{

    /**
     * @var ReflectionMethodInterface
     */
    private $constructor;

    /**
     * @var ShrinkingPropertiesMetaDataInterface
     */
    private $propertiesMetaData;

    /**
     * InitializeContext constructor.
     * @param ReflectionMethodInterface $constructor
     * @param ShrinkingPropertiesMetaDataInterface $propertiesMetaData
     */
    public function __construct(
        ReflectionMethodInterface $constructor,
        ShrinkingPropertiesMetaDataInterface $propertiesMetaData
    ) {
        $this->constructor = $constructor;
        $this->propertiesMetaData = $propertiesMetaData;
    }

    /**
     * @return ReflectionMethodInterface
     */
    public function getConstructor(): ReflectionMethodInterface
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
