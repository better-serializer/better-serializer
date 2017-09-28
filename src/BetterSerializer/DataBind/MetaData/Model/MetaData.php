<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Model;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTuple;
use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;

/**
 * Class MetaData
 *
 * @author  mfris
 * @package BetterSerializer\DataBind\MetaData
 */
final class MetaData implements MetaDataInterface
{

    /**
     * @var ClassMetaDataInterface
     */
    private $classMetadata;

    /**
     * @var PropertyMetaDataInterface[]
     */
    private $propertiesMetadata;

    /**
     * @var ConstructorParamMetaDataInterface[]
     */
    private $constructorParams;

    /**
     * @var PropertyWithConstructorParamTuple[]
     */
    private $propertyWithConstrParamTuples;

    /**
     * @var bool
     */
    private $isInstantiableByConstructor;

    /**
     * MetaData constructor.
     *
     * @param ClassMetaDataInterface      $classMetadata
     * @param PropertyMetaDataInterface[] $propertiesMetadata
     * @param ConstructorParamMetaDataInterface[] $constructorParams
     */
    public function __construct(
        ClassMetaDataInterface $classMetadata,
        array $propertiesMetadata,
        array $constructorParams = []
    ) {
        $this->classMetadata = $classMetadata;
        $this->propertiesMetadata = $propertiesMetadata;
        $this->constructorParams = $constructorParams;
    }

    /**
     * @return ClassMetaDataInterface
     */
    public function getClassMetadata(): ClassMetaDataInterface
    {
        return $this->classMetadata;
    }

    /**
     * @return PropertyMetaDataInterface[]
     */
    public function getPropertiesMetadata(): array
    {
        return $this->propertiesMetadata;
    }

    /**
     * @return ConstructorParamMetaDataInterface[]
     */
    public function getConstructorParamsMetaData(): array
    {
        return $this->constructorParams;
    }

    /**
     * @return PropertyWithConstructorParamTupleInterface[]
     */
    public function getPropertyWithConstructorParamTuples(): array
    {
        if (!$this->isInstantiableByConstructor()) {
            return [];
        }

        if ($this->propertyWithConstrParamTuples !== null) {
            return $this->propertyWithConstrParamTuples;
        }

        $this->propertyWithConstrParamTuples = [];

        foreach ($this->constructorParams as $paramName => $constrParamMetaData) {
            $this->propertyWithConstrParamTuples[$paramName] = new PropertyWithConstructorParamTuple(
                $this->propertiesMetadata[$paramName],
                $constrParamMetaData
            );
        }

        return $this->propertyWithConstrParamTuples;
    }

    /**
     * @return bool
     */
    public function isInstantiableByConstructor(): bool
    {
        if ($this->isInstantiableByConstructor !== null) {
            return $this->isInstantiableByConstructor;
        }

        if (count($this->propertiesMetadata) < count($this->constructorParams)) {
            $this->isInstantiableByConstructor = false;
            return false;
        }

        foreach (array_keys($this->constructorParams) as $paramName) {
            if (!isset($this->propertiesMetadata[$paramName])) {
                $this->isInstantiableByConstructor = false;
                return false;
            }
        }

        $this->isInstantiableByConstructor = true;
        return true;
    }
}
