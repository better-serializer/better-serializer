<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaData;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;

/**
 * Class AbstractMetaDataDecorator
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Model
 */
abstract class AbstractMetaDataDecorator implements MetaDataInterface
{

    /**
     * @var MetaDataInterface
     */
    private $decorated;

    /**
     * AbstractMetaDataDecorator constructor.
     * @param MetaDataInterface $decorated
     */
    public function __construct(MetaDataInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @return ClassMetaDataInterface
     */
    public function getClassMetadata(): ClassMetaDataInterface
    {
        return $this->decorated->getClassMetadata();
    }

    /**
     * @return PropertyMetaDataInterface[]
     */
    public function getPropertiesMetadata(): array
    {
        return $this->decorated->getPropertiesMetadata();
    }

    /**
     * @return ConstructorParamMetaData[]
     */
    public function getConstructorParamsMetaData(): array
    {
        return $this->decorated->getConstructorParamsMetaData();
    }

    /**
     * @return PropertyWithConstructorParamTupleInterface[]
     */
    public function getPropertyWithConstructorParamTuples(): array
    {
        return $this->decorated->getPropertyWithConstructorParamTuples();
    }

    /**
     * @return bool
     */
    public function isInstantiableByConstructor(): bool
    {
        return $this->decorated->isInstantiableByConstructor();
    }
}
