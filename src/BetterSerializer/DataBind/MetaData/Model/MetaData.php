<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Model;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;

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
}
