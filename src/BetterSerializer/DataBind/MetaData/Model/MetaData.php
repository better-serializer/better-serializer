<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Model;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
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
     * MetaData constructor.
     *
     * @param ClassMetaDataInterface      $classMetadata
     * @param PropertyMetaDataInterface[] $propertiesMetadata
     */
    public function __construct(ClassMetaDataInterface $classMetadata, array $propertiesMetadata)
    {
        $this->classMetadata = $classMetadata;
        $this->propertiesMetadata = $propertiesMetadata;
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
}
