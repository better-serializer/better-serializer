<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaData;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;

/**
 * Class MetaData
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData
 */
interface MetaDataInterface
{
    /**
     * @return ClassMetaDataInterface
     */
    public function getClassMetadata(): ClassMetaDataInterface;

    /**
     * @return PropertyMetaDataInterface[]
     */
    public function getPropertiesMetadata(): array;

    /**
     * @return ConstructorParamMetaData[]
     */
    public function getConstructorParamsMetaData(): array;

    /**
     * @return PropertyWithConstructorParamTupleInterface[]
     */
    public function getPropertyWithConstructorParamTuples(): array;

    /**
     * @return bool
     */
    public function isInstantiableByConstructor(): bool;
}
